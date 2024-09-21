<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Questions\ImageQuestion;
use App\Models\Questions\MultipleQuestion;
use App\Models\Questions\MultipleSelectionQuestion;
use App\Models\Questions\OpenQuestion;
use App\Models\Questions\ValueQuestion;
use App\Models\Results\QuestionResult;
use App\Models\Results\SectionResult;
use App\Models\Results\TestResult;
use App\Models\Section;
use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InterviewController extends Controller
{
    /**
     * Display a list of .
     */
    public function index(Visit $visit)
    {
        $interviews = $visit->interviews;
        return view('med.interviewlist', ['interviews' => $interviews]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeInterview(Request $request): RedirectResponse
    {
        $request->validate([
            'interview' => ['required', 'integer', 'min:0'],
        ]);
        $interview = Interview::where('id', $request->interview)->get();
        if($interview->count() != 0) {
            $interview = $interview[0];
            return redirect(route('med.visits.interviewdetail.interview.show', ['visit'=> $interview->visit->id, 'interview' => $interview->id]));
        }
        return back();

    }

    /**
     * Display the specified resource.
     */
    public function showInterview(int $visit, int $interview): View
    {
        $interview = Visit::where('status', '1')->findorfail($visit)->interviews()->findorfail($interview);
        return view('med.interviewdetail.interviewdetail', ['interview' => $interview]);
    }

   /**
     * Show the page for visualizing the chosen element.
     */
    public function createElementDetail(Request $request, int $visit, int $interview)
    {

        $interview = Visit::where('status', '1')->findorfail($visit)->interviews()->findorfail($interview);

        if($request['testid']) {
            $request->validate([
                'testid' => ['required', 'integer', 'min:0'],
            ]);

            $testresult = $interview->testresult;
            return view('med.interviewdetail.testdetail', [
                'test' => $testresult->test,
                'testresult' => $testresult
            ]);

        } elseif($request['sectionid']) {
            $request->validate([
                'sectionid' => ['required', 'integer', 'min:0'],
            ]);
            $sectionresult = SectionResult::findorfail($request->sectionid);
            $parentsection = $sectionresult;
            $check = false;
            while($check == false) {
                $parentsection = $parentsection->sectionable;
                if(get_class($parentsection) == TestResult::class) {
                    $check = true;
                }
            }
            if($parentsection->interview == $interview) {
                $section = $sectionresult->section;
                if($sectionresult->sections->count() != 0) {
                    return view('med.interviewdetail.parentsectiondetail', [
                        'section' => $section,
                        'sectionresult' => $sectionresult
                    ]);
                } else {
                    $questiontypes = [];
                    $images = [];
                    if($sectionresult->jump == 0) {
                        for($i=0; $i<$section->questions->count(); $i++) {
                            $path = explode("\\", $section->questions[$i]->questionable_type);
                            $questiontypes[] = end($path);
                            if(end($path) == 'ImageQuestion') {
                                $imageContent = Storage::disk('test')->get($sectionresult->questionresults[$i]->questionable->value[0]);
                                $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                                $images[$i] = $base64Image;
                            }
                        }
                    } else {
                        for($i=0; $i<$sectionresult->questionresults->count(); $i++) {
                            $questiontypes[] = 'jump';
                        }
                    }
                    return view('med.interviewdetail.sectiondetail', [
                        'section' => $section,
                        'sectionresult' => $sectionresult,
                        'questiontypes' => $questiontypes,
                        'images' => $images,
                    ]);
                }
            }

        } elseif($request['questionid']) {
            $request->validate([
                'questionid' => ['required', 'integer', 'min:0'],
            ]);

            $questionresult = QuestionResult::findorfail($request->questionid);
            $sectionresult = $questionresult->sectionresult;
            $check = false;
            while($check == false) {
                $sectionresult = $sectionresult->sectionable;
                if(get_class($sectionresult) == TestResult::class) {
                    $check = true;
                }
            }
            if($sectionresult->interview == $interview) {
                $questionrelated = $questionresult->question->questionable;
                if($questionresult->jump == 1) {
                    $questionresult = 'jump';
                } else {
                    $questionresult = $questionresult->questionable;
                }
                if(get_class($questionrelated) == MultipleQuestion::class) {
                    return view('med.interviewdetail.question.multiplequestiondetail', [
                        'question' => $questionrelated,
                        'questionresult' => $questionresult,
                    ]);
                } elseif(get_class($questionrelated) == ValueQuestion::class) {
                    return view('med.interviewdetail.question.valuequestiondetail', [
                        'question' => $questionrelated,
                        'questionresult' => $questionresult,
                    ]);
                } elseif(get_class($questionrelated) == OpenQuestion::class) {
                    return view('med.interviewdetail.question.openquestiondetail', [
                        'question' => $questionrelated,
                        'questionresult' => $questionresult,
                    ]);
                } elseif(get_class($questionrelated) == MultipleSelectionQuestion::class) {
                    return view('med.interviewdetail.question.multipleselectionquestiondetail', [
                        'question' => $questionrelated,
                        'questionresult' => $questionresult,
                    ]);
                } elseif(get_class($questionrelated) == ImageQuestion::class) {
                    $images = [];
                    $position = 0;
                    $files = $questionrelated->images;
                    for($i=0; $i<count($files); $i++) {
                        if($questionresult != "jump") {
                            if($files[$i][0] == $questionresult->questionable->value[0]) {
                                $position = $i;
                            }
                        }
                        $imageContent = Storage::disk('test')->get($files[$i][0]);
                        $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                        $images[] = $base64Image;
                    }
                    return view('med.interviewdetail.question.imagequestiondetail', [
                        'question' => $questionrelated,
                        'questionresult' => $questionresult,
                        'images' => $images,
                        'position' => $position,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Display the test's tree json.
     */
    public function createTree(int $visit, int $interview): JsonResponse
    {
        $interview = Visit::where('status', '1')->findorfail($visit)->interviews()->findorfail($interview);
        //Test data
        $test = $interview->testresult->test;
        $testresult = $interview->testresult;

        $array = [
            'test' => [
                'id' => $test->id,
                'name' => $test->name,
            ]
        ];
        //Section data
        $sections = $test->sections;
        $count = $sections->count();
        if($count != 0) {
            for($i=0; $i<$count; $i++) {
                $res = $this->createSecionNode($sections[$i], $testresult->sectionresults[$i]);
                $array['test']['sections'][array_keys($res)[0]] = $res[array_keys($res)[0]];
            }
            return response()->json($array);
        } else {
            return response()->json([
                'test' => [
                'id' => $test->id,
                'name' => $test->name,
                ]
            ]);
        }
    }

    private function createSecionNode(Section $section, SectionResult $sectionresult): Array {
        $array = [
            "section".$section->progressive => [
                'id' => $sectionresult->id,
                'name' => $section->name,
            ]
        ];
        $subsections = $section->sections;
        $subsectionresults = $sectionresult->sections;
        if($subsections->count() != 0) {
            for($i=0; $i<$subsections->count(); $i++) {
                $subsesction = $subsections[$i];
                $subsectionresult = $subsectionresults[$i];
                $res = $this->createSecionNode($subsesction, $subsectionresult);

                $array["section".$section->progressive]['sections']['section'.$subsesction->progressive] = $res['section'.$subsesction->progressive];
            }
            return $array;

        } else {
            $questions = $section->questions;
            $questionresults = $sectionresult->questionresults;
            if($questions->count() != 0) {
                for($i=0; $i<$questions->count(); $i++) {
                    $question = $questions[$i];
                    $questionresult = $questionresults[$i];

                    $array["section".$section->progressive]['questions']['question'.$question->progressive] = [
                        'id' => $questionresult->id,
                        'title' => $question->questionable->title,
                    ];

                }

                return $array;

            } else {
                return $array;
            }
        }
    }
}
