<?php

namespace App\Http\Controllers\Med;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Questions\ImageQuestion;
use App\Models\Questions\MultipleQuestion;
use App\Models\Questions\MultipleSelectionQuestion;
use App\Models\Questions\OpenQuestion;
use App\Models\Questions\ValueQuestion;
use App\Models\Results\ImageQuestionResult;
use App\Models\Results\MultipleQuestionResult;
use App\Models\Results\MultipleSelectionQuestionResult;
use App\Models\Results\OpenQuestionResult;
use App\Models\Results\QuestionResult;
use App\Models\Results\SectionResult;
use App\Models\Results\TestResult;
use App\Models\Results\ValueQuestionResult;
use App\Models\Section;
use App\Models\Test;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VisitAdministrationController extends Controller {

    /**
     * Show the page for visualizing the visit control panel.
     */
    public function create(Request $request): View
    {
        if($request->session()->get('status') == 'exit-status') {
            $status = $request->session()->get('status');
            return view('med.testadministration.visitadministration', [
                'visit' => Visit::where('id', $request->session()->get('activevisit'))->get()[0],
                'status' => $status,
            ]);
        } elseif($request->status == 'exit-status') {
            return view('med.testadministration.visitadministration', [
                'visit' => Visit::where('id', $request->session()->get('activevisit'))->get()[0],
                'status' => $request->status,
            ]);
        } else {
            if($request->session()->get('status') == 'exit-status') {
                $request->session()->forget(('status'));
            }
            return view('med.testadministration.visitadministration', [
                'visit' => Visit::where('id', $request->session()->get('activevisit'))->get()[0],
            ]);
        }

    }

    /**
     * Show the page for visualizing the test selector view.
     */
    public function createTestSelector(): View
    {
        $tests = Test::where('status', '1')->orderBy('name', 'asc')->get();
        return view('med.testadministration.testselection', ['tests' => $tests]);
    }

    /**
     * Redirect to the test selector page
     */
    public function createNewInterview(): RedirectResponse
    {
        return redirect(route('med.visitadministration.testselector'));
    }

    /**
     * Show the page for visualizing the test compilation view.
     */
    public function createTestCompilation(Request $request): View
    {
        if($request->session()->get('status') == 'exit-status') {
            $status = $request->session()->get('status');
            return view('med.testadministration.testcompilation', ['status' => $status]);
        } elseif($request->status == 'exit-status') {
            return view('med.testadministration.testcompilation', ['status' => $request->status]);
        } else {
            if($request->session()->get('status') == 'exit-status') {
                $request->session()->forget(('status'));
            }
            return view('med.testadministration.testcompilation');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeTestSelector(Request $request): RedirectResponse
    {
        $request->validate([
            'test_id' => ['required', 'integer'],
        ]);

        $test = Test::where('id', $request->test_id)->get();
        if($test->count() != 0) {
            $test = $test[0];

            //Create interview
            $interview = Interview::create([
                'visit_id' => $request->session()->get('activevisit'),
            ]);

            //Putting on session interview
            $request->session()->put('activeinterview', $interview->id);

            //Generating Result tree
            $testresult = TestResult::create([
                'test_id' => $test->id,
                'interview_id' => $interview->id,
                'status' => 0
            ]);

            //Putting on session progressive for compiling
            $section = $test->sections[0];
            $progressive = $section->progressive."-";
            while($section->sections->count() != 0) {
                $section = $section->sections[0];
                $progressive = $progressive.$section->progressive."-";
            }
            $question = $section->questions[0];
            $progressive = $progressive.$question->progressive;
            $request->session()->put('progressive', $progressive);

            if($test->sections->count() != 0) {
                $this->createTestResultNode($test, $testresult);
            }
            return redirect(route('med.visitadministration.testcompilation'));
        }
    }

    private function createTestResultNode($node, $noderesult) {
        $sections = $node->sections;
        for($i=0; $i<$sections->count(); $i++) {
            $section = $sections[$i];

            $sectionresult = SectionResult::create([
                'section_id' => $section->id,
                'sectionable_id' => $noderesult->id,
                'sectionable_type' => get_class($noderesult),
                'progressive' => $section->progressive,
            ]);

            if($section->sections->count() != 0) {
                $this->createTestResultNode($section, $sectionresult);
            } elseif($section->questions->count() != 0) {
                $questions = $section->questions;
                for($x=0; $x<$questions->count(); $x++) {
                    $question = $questions[$x];
                    QuestionResult::create([
                        'question_id' => $question->id,
                        'section_result_id' => $sectionresult->id,
                        'progressive' => $question->progressive,
                        'questionable_type' => str_replace('Questions', 'Results', $question->questionable_type)."Result",
                    ]);
                }
            }
        }
    }

    /**
     * Display the test's tree json.
     */
    public function createTree(Request $request): JsonResponse
    {
        //Test data
        $test = Interview::where('id', $request->session()->get('activeinterview'))->get()[0]->testresult->test;
        $testresult = Interview::where('id', $request->session()->get('activeinterview'))->get()[0]->testresult;

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
                'status' => $sectionresult->status,
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
                    $status = 0;
                    if($questionresult->questionable_id != 0) {
                        $status = 1;
                    }

                    $array["section".$section->progressive]['questions']['question'.$question->progressive] = [
                        'id' => $questionresult->id,
                        'title' => $question->questionable->title,
                        'status' => $status,
                    ];

                }

                return $array;

            } else {
                return $array;
            }
        }
    }

    /**
     * Show the page for visualizing the node compilation view.
     */
    public function createNodeCompilation(Request $request)
    {
        $progressive = $request->session()->get('progressive');
        $progressive = explode('-', $progressive);

        $test = Interview::where('id', $request->session()->get('activeinterview'))->get()[0]->testresult->test;
        $sections = $test->sections;
        $testresult = Interview::where('id', $request->session()->get('activeinterview'))->get()[0]->testresult;
        $sectionresults = $testresult->sectionresults;

        if($progressive[0] <= $sections->count()) {
            $section = $sections[$progressive[0]-1];
            $sectionresult = $sectionresults[$progressive[0]-1];
            for($i=1; $i<count($progressive)-1; $i++) {
                if($progressive[$i]-1 >= $section->sections->count()) {
                    //View parent section
                    return view('med.testadministration.parentsectionrecap', [
                        'section' => $section,
                        'sectionresult' => $sectionresult,
                    ]);
                }
                $section = $section->sections[$progressive[$i]-1];
                $sectionresult = $sectionresult->sections[$progressive[$i]-1];
            }

            $questions = $section->questions;
            if(end($progressive) <= $questions->count()) {
                $question = $questions[end($progressive)-1];
                $questionrelated = $question->questionable;

                if(get_class($questionrelated) == MultipleQuestion::class) {
                    return view('med.testadministration.question.multiplequestion', ['question' => $questionrelated]);
                } elseif(get_class($questionrelated) == ValueQuestion::class) {
                    return view('med.testadministration.question.valuequestion', ['question' => $questionrelated]);
                } elseif(get_class($questionrelated) == OpenQuestion::class) {
                    return view('med.testadministration.question.openquestion', ['question' => $questionrelated]);
                } elseif(get_class($questionrelated) == MultipleSelectionQuestion::class) {
                    return view('med.testadministration.question.multipleselectionquestion', ['question' => $questionrelated]);
                } elseif(get_class($questionrelated) == ImageQuestion::class) {
                    $images = [];
                    $files = $questionrelated->images;
                    for($i=0; $i<count($files); $i++) {
                        $imageContent = Storage::disk('test')->get($files[$i][0]);
                        $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                        $images[] = $base64Image;
                    }
                    return view('med.testadministration.question.imagequestion', [
                        'question' => $questionrelated,
                        'images' => $images,
                    ]);
                }
            } else {
                //End Section Page
                $questiontypes = [];
                $images = [];
                for($i=0; $i<$section->questions->count(); $i++) {
                    $path = explode("\\", $section->questions[$i]->questionable_type);
                    $questiontypes[] = end($path);
                    if(end($path) == 'ImageQuestion') {
                        $imageContent = Storage::disk('test')->get($sectionresult->questionresults[$i]->questionable->value[0]);
                        $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                        $images[$i] = $base64Image;
                    }
                }
                return view('med.testadministration.sectionrecap', [
                    'section' => $section,
                    'sectionresult' => $sectionresult,
                    'questiontypes' => $questiontypes,
                    'images' => $images,
                ]);
            }
        } else {
            //END TEST PAGE
            return view('med.testadministration.testrecap', [
                'test' => $test,
                'testresult' => $testresult,
            ]);
        }

        return response()->json([
            'status' => 400,
        ]);
    }

    /**
     * Show the page for visualizing the end interview compilation view.
     */
    public function createInterviewEndPage(Request $request)
    {
        $interview = Interview::where('id', $request->session()->get('activeinterview'))->get()[0];
        $testresult = $interview->testresult;

        if($testresult->status == 1) {
            return view('med.testadministration.endinterview', ['interview' => $interview]);
        } else {
            return back();
        }
    }

    /**
     * Update interview in storage.
     */
    public function updateInterview(Request $request)
    {
        $diagnosis = null;
        if($request->diagnosis) {
            $diagnosis = $request->diagnosis;
        }

        $interview = Interview::where('id', $request->session()->get('activeinterview'))->get()[0];
        $sectionresults = $interview->testresult->sectionresults;
        $check = true;
        for($i=0; $i<$sectionresults->count(); $i++) {
            $sectionresult = $sectionresults[$i];
            if($sectionresult->status == 0) {
                $check = false;
                break;
            }
        }

        if($check == true) {
            $interview->update([
                'status' => 1,
                'diagnosis' => $diagnosis,
            ]);

            //Removing interview from session
            $request->session()->forget('activeinterview');

            return redirect(route('med.visitadministration.controlpanel'));
        } else {
            return back();
        }
    }

    /**
     * Update visit in storage.
     */
    public function updateVisit(Request $request)
    {
        $diagnosis = null;
        $treatment = null;
        if($request->diagnosis) {
            $diagnosis = $request->diagnosis;
        }
        if($request->treatment) {
            $treatment = $request->treatment;
        }

        $visit = Visit::where('id', $request->session()->get('activevisit'))->get()[0];
        $interviews = $visit->interviews;
        $check = true;
        for($i=0; $i<$interviews->count(); $i++) {
            $interview = $interviews[$i];
            if($interview->status == 0) {
                $check = false;
                break;
            }
        }

        if($check == true) {
            $visit->update([
                'status' => 1,
                'diagnosis' => $diagnosis,
                'treatment' => $treatment,
            ]);

            //Removing interview from session
            $request->session()->forget('activevisit');

            return redirect(route('med.dashboard'));
        } else {
            return back();
        }
    }

    /**
     * Store a newly created node in storage.
     */
    public function storeNode(Request $request)
    {
        $progressive = $request->session()->get('progressive');
        $progressive = explode('-', $progressive);

        $testresult = Interview::where('id', $request->session()->get('activeinterview'))->get()[0]->testresult;
        $sectionresults = $testresult->sectionresults;
        if($progressive[0] <= $sectionresults->count()) {
            $sectionresult = $sectionresults[$progressive[0]-1];
            for($i=1; $i<count($progressive)-1; $i++) {
                if($progressive[$i]-1 >= $sectionresult->sections->count()) {
                    //Store section with subsection
                    if($request->sectiontext) {
                        $sectionresult->update([
                            'status' => 1,
                            'result' => $request->sectiontext,
                        ]);
                    }
                    //Shifting progressive
                    array_pop($progressive);
                    $progressive[count($progressive) - 1] = 1;
                    $progressive[count($progressive) - 2] = $progressive[count($progressive) - 2] + 1;
                    $section = $sectionresult->sectionable;
                    if(get_class($section) == SectionResult::class) {
                        $section = $section->section;
                    } elseif(get_class($section) == TestResult::class) {
                        $section = $section->test;
                    }
                    if($progressive[count($progressive) - 2]-1 < $section->sections->count()) {
                        $section = $section->sections[$progressive[count($progressive) - 2]-1];
                        while($section->sections->count() != 0) {
                            $section = $section->sections[0];
                            $progressive[] = 1;
                        }
                    }
                    $request->session()->put('progressive', implode("-", $progressive));
                    return response()->json([
                        'status' => 200,
                        'id' => 'section-'.$sectionresult->id,
                    ]);

                }
                $sectionresult = $sectionresult->sections[$progressive[$i]-1];
            }

            $questionresults = $sectionresult->questionresults;
            if(end($progressive) <= $questionresults->count()) {
                $questionresult = $questionresults[end($progressive)-1];

                //Store Question
                $request->validate([
                    'type' => ['required', 'string'],
                ]);

                if($request->type == 'multiple') {
                    $result = $this->storeMultipleResult($request, $questionresult);
                } elseif($request->type == 'value') {
                    $result = $this->storeValueResult($request, $questionresult);
                } elseif($request->type == 'open') {
                    $result = $this->storeOpenResult($request, $questionresult);
                } elseif($request->type == 'multipleselection') {
                    $result = $this->storeMultipleSelectionResult($request, $questionresult);
                } elseif($request->type == 'image') {
                    $result = $this->storeImageResult($request, $questionresult);
                }

                $status = $result['status'];
                if($status == 200) {
                    //Shift progressive
                    $progressive[count($progressive) - 1] = $progressive[count($progressive) - 1] + 1;
                    $request->session()->put('progressive', implode("-", $progressive));
                    return response()->json([
                        'status' => 200,
                        'id' => 'question-'.$questionresult->id,
                    ]);
                }
                return $result;

            } else {
                //Storing section result
                if($request->sectiontext) {
                    $sectionresult->update([
                        'status' => 1,
                        'result' => $request->sectiontext,
                    ]);
                }
                //Shifting progressive
                $progressive[count($progressive) - 1] = 1;
                $progressive[count($progressive) - 2] = $progressive[count($progressive) - 2] + 1;
                $section = $sectionresult->sectionable;
                if(get_class($section) == SectionResult::class) {
                    $section = $section->section;
                } elseif(get_class($section) == TestResult::class) {
                    $section = $section->test;
                }
                if($progressive[count($progressive) - 2]-1 < $section->sections->count()) {
                    $section = $section->sections[$progressive[count($progressive) - 2]-1];
                    while($section->sections->count() != 0) {
                        $section = $section->sections[0];
                        $progressive[] = 1;
                    }
                }
                $request->session()->put('progressive', implode("-", $progressive));
                return response()->json([
                    'status' => 200,
                    'id' => 'section-'.$sectionresult->id,
                ]);
            }
        } else {
            //Store TEST
            $check = true;
            for($i=0; $i<$sectionresults->count(); $i++) {
                if($sectionresults[$i]->status == 0) {
                    $check = false;
                    break;
                }
            }

            if($check == true) {

                $result = null;
                if($request->testtext) {
                    $result = $request->testtext;
                }

                $testresult->update([
                    'status' => 1,
                    'result' => $result,
                ]);

                //Removing progressive from session
                $request->session()->forget('progressive');

                return response()->json([
                    'status' => 200,
                ]);

            }
        }

        return response()->json([
            'status' => 400,
        ]);
    }

    /**
     * Store a newly created mutiple question result in storage.
     */
    private function storeMultipleResult(Request $request, QuestionResult $questionresult) {
        $request->validate([
            'radioinput' => ['required', 'integer'],
        ]);

        $multiplequestion = $questionresult->question->questionable;
        if($request->radioinput < count($multiplequestion->fields)) {

            $value = $multiplequestion->fields[$request->radioinput];

            //Saving Score
            $score = 0;
            if($multiplequestion->scores) {
                $score = $multiplequestion->scores[$request->radioinput];
            }

            //Store multiple result
            $multiple = MultipleQuestionResult::create([
                'value' => $value,
                'multiple_question_id' => $multiplequestion->id,
                'score' => $score,
            ]);
            $questionresult->update(['questionable_id' => $multiple->id]);

            return [
                'status' => 200,
            ];

        }

        return [
            'status' => 422,
            'responseJSON' => [
                'errors' => [
                    'radioinput' => ['The radioinput field is required']
                ]
            ]
        ];
    }

    /**
     * Store a newly created value question result in storage.
     */
    private function storeValueResult(Request $request, QuestionResult $questionresult) {
        $request->validate([
            'valueinput' => ['required', 'string'],
        ]);

        $valuequestion = $questionresult->question->questionable;
        $valueinput = explode('-', $request->valueinput);
        if($valueinput[0] == 'singular') {
            if($valueinput[1] < count($valuequestion->fields['singular'])) {
                $value = $valuequestion->fields['singular'][$valueinput[1]];

                //Saving Score
                $score = 0;
                if($valuequestion->scores) {
                    $score = $valuequestion->scores[$valueinput[1]];
                }

                //Store value result
                $multiple = ValueQuestionResult::create([
                    'value' => $value,
                    'value_question_id' => $valuequestion->id,
                    'score' => $score,
                ]);
                $questionresult->update(['questionable_id' => $multiple->id]);

                return [
                    'status' => 200,
                ];
            }
        } elseif($valueinput[0] == 'personal') {
            if($valueinput[1] < count($valuequestion->fields['personal'])) {
                $value = $valuequestion->fields['personal'][$valueinput[1]];

                //Saving Score
                $score = 0;
                if($valuequestion->scores) {
                    $score = $valuequestion->scores[count($valuequestion->fields['singular']) + $valueinput[1]];
                }

                //Store value result
                $multiple = ValueQuestionResult::create([
                    'value' => $value,
                    'value_question_id' => $valuequestion->id,
                    'score' => $score,
                ]);
                $questionresult->update(['questionable_id' => $multiple->id]);

                return [
                    'status' => 200,
                ];
            }
        }

        return [
            'status' => 422,
            'responseJSON' => [
                'errors' => [
                    'valueinput' => ['The valueinput field is required']
                ]
            ]
        ];
    }

    /**
     * Store a newly created open question result in storage.
     */
    private function storeOpenResult(Request $request, QuestionResult $questionresult) {
        $request->validate([
            'openinput' => ['required', 'string'],
        ]);

        //Store open result
        $open = OpenQuestionResult::create([
            'value' => $request->openinput,
            'open_question_id' => $questionresult->question->questionable->id,
        ]);
        $questionresult->update(['questionable_id' => $open->id]);

        return [
            'status' => 200,
        ];
    }

    /**
     * Store a newly created mutiple question result in storage.
     */
    private function storeMultipleSelectionResult(Request $request, QuestionResult $questionresult) {
        $request->validate([
            'checkbox' => ['required', 'array'],
            'checkbox.*' => ['integer'],
        ]);

        $multipleselectionquestion = $questionresult->question->questionable;
        if(count($request->checkbox) < count($multipleselectionquestion->fields)) {

            //Saving respones and generating score
            $value = [];
            $score = 0;
            if($multipleselectionquestion->scores) {
                for($i=0; $i<count($request->checkbox); $i++) {
                    if($request->checkbox[$i] <= count($multipleselectionquestion->fields)) {
                        $value[] = $multipleselectionquestion->fields[$request->checkbox[$i]];
                        $score += $multipleselectionquestion->scores[$request->checkbox[$i]];
                    }
                }
            } else {
                for($i=0; $i<count($request->checkbox); $i++) {
                    if($request->checkbox[$i] <= count($multipleselectionquestion->fields)) {
                        $value[] = $multipleselectionquestion->fields[$request->checkbox[$i]];
                    }
                }
            }


            //Store multiple selection result
            $multiple = MultipleSelectionQuestionResult::create([
                'value' => $value,
                'multiple_selection_question_id' => $multipleselectionquestion->id,
                'score' => $score,
            ]);
            $questionresult->update(['questionable_id' => $multiple->id]);

            return [
                'status' => 200,
            ];

        }

        return [
            'status' => 422,
            'responseJSON' => [
                'errors' => [
                    'checkbox' => ['The field is required']
                ]
            ]
        ];
    }

    /**
     * Store a newly created mutiple question result in storage.
     */
    private function storeImageResult(Request $request, QuestionResult $questionresult) {
        $request->validate([
            'imageradio' => ['required', 'integer'],
        ]);

        $imagequestion = $questionresult->question->questionable;
        if($request->imageradio < count($imagequestion->images)) {

            //Saving Score
            $score = 0;
            if($imagequestion->scores) {
                $score = $imagequestion->scores[$request->imageradio];
            }

            $value = $imagequestion->images[$request->imageradio];

            //Store image result
            $image = ImageQuestionResult::create([
                'value' => $value,
                'image_question_id' => $imagequestion->id,
                'score' => $score,
            ]);
            $questionresult->update(['questionable_id' => $image->id]);

            return [
                'status' => 200,
            ];

        }

        return [
            'status' => 422,
            'responseJSON' => [
                'errors' => [
                    'imageradio' => ['The image field is required']
                ]
            ]
        ];
    }

    /**
     * Create the view for update node resources.
     */
    public function createUpdateNode(Request $request)
    {
        $request->validate([
            'update' => ['required', 'string'],
        ]);

        $update = explode('-', $request->update);
        if($update[0] == 'section') {
            $sectionresult = SectionResult::where('id', $update[1])->get();
            if($sectionresult->count() != 0) {
                $sectionresult = $sectionresult[0];
                if($sectionresult->status == 1) {
                    if($sectionresult->sections->count() != 0) {
                        return view('med.testadministration.parentsectionrecap', [
                            'update' => 1,
                            'section' => $sectionresult->section,
                            'sectionresult' => $sectionresult,
                        ]);
                    } else {
                        $questiontypes = [];
                        $images = [];
                        for($i=0; $i<$sectionresult->section->questions->count(); $i++) {
                            $path = explode("\\", $sectionresult->section->questions[$i]->questionable_type);
                            $questiontypes[] = end($path);
                            if(end($path) == 'ImageQuestion') {
                                $imageContent = Storage::disk('test')->get($sectionresult->questionresults[$i]->questionable->value[0]);
                                $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                                $images[$i] = $base64Image;
                            }
                        }
                        return view('med.testadministration.sectionrecap', [
                            'update' => 1,
                            'section' => $sectionresult->section,
                            'sectionresult' => $sectionresult,
                            'questiontypes' => $questiontypes,
                            'images' => $images,
                        ]);
                    }
                }
            }
        } elseif($update[0] == 'question') {
            $questionresult = QuestionResult::where('id', $update[1])->get();
            if($questionresult->count() != 0) {
                $questionresult = $questionresult[0];
                if($questionresult->questionable_id != null ) {
                    $questionrelated = $questionresult->questionable;
                    if(get_class($questionrelated) == MultipleQuestionResult::class) {
                        return view('med.testadministration.question.multiplequestion', [
                            'update' => 1,
                            'question' => $questionresult->question->questionable,
                            'questionresult' => $questionrelated,
                        ]);
                    } elseif(get_class($questionrelated) == ValueQuestionResult::class) {
                        return view('med.testadministration.question.valuequestion', [
                            'update' => 1,
                            'question' => $questionresult->question->questionable,
                            'questionresult' => $questionrelated,
                        ]);
                    } elseif(get_class($questionrelated) == OpenQuestionResult::class) {
                        return view('med.testadministration.question.openquestion', [
                            'update' => 1,
                            'question' => $questionresult->question->questionable,
                            'questionresult' => $questionrelated,
                        ]);
                    } elseif(get_class($questionrelated) == MultipleSelectionQuestionResult::class) {
                        return view('med.testadministration.question.multipleselectionquestion', [
                            'update' => 1,
                            'question' => $questionresult->question->questionable,
                            'questionresult' => $questionrelated,
                        ]);
                    } elseif(get_class($questionrelated) == ImageQuestionResult::class) {
                        $images = [];
                        $position = 0;
                        $files = $questionrelated->imagequestion->images;
                        for($i=0; $i<count($files); $i++) {
                            if($files[$i][0] == $questionrelated->value[0]) {
                                $position = $i;
                            }
                            $imageContent = Storage::disk('test')->get($files[$i][0]);
                            $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                            $images[] = $base64Image;
                        }
                        return view('med.testadministration.question.imagequestion', [
                            'update' => 1,
                            'question' => $questionresult->question->questionable,
                            'questionresult' => $questionrelated,
                            'images' => $images,
                            'position' => $position,
                        ]);
                    }
                }
            }
        }
        return response()->json([
            'status' => 400,
        ]);
    }

    /**
     * Update the specified node resource in storage.
     */
    public function updateNode(Request $request)
    {
        $request->validate([
            'update' => ['required', 'integer'],
            'type' => ['required', 'string'],
        ]);

        if($request->type == 'section') {
            $section = SectionResult::where('id', $request->update)->get();
            if($section->count() != 0) {
                $section = $section[0];
                if($section->status == 1) {

                    if($request->sectiontext) {
                        $section->update([
                            'result' => $request->sectiontext,
                        ]);
                    } else {
                        $section->update([
                            'result' => null,
                        ]);
                    }

                    return [
                        'status' => 200,
                    ];
                }

            }

        } elseif($request->type == 'multiple') {
            $multiple = MultipleQuestionResult::where('id', $request->update)->get();
            if($multiple->count() != 0) {
                $multiple = $multiple[0];

                $request->validate([
                    'radioinput' => ['required', 'integer'],
                ]);

                $multiplequestion = $multiple->multiplequestion;
                if($request->radioinput < count($multiplequestion->fields)) {

                    //Saving Score
                    $score = 0;
                    if($multiplequestion->scores) {
                        $score = $multiplequestion->scores[$request->radioinput];
                    }

                    $value = $multiplequestion->fields[$request->radioinput];

                    //Update multiple result
                    $multiple->update([
                        'value' => $value,
                        'score' => $score,
                    ]);

                    return [
                        'status' => 200,
                    ];

                }

            }

            return [
                'status' => 422,
                'responseJSON' => [
                    'errors' => [
                        'radioinput' => ['The radioinput field is required']
                    ]
                ]
            ];

        } elseif($request->type == 'value') {
            $value = ValueQuestionResult::where('id', $request->update)->get();
            if($value->count() != 0) {
                $value = $value[0];

                $request->validate([
                    'valueinput' => ['required', 'string'],
                ]);

                $valuequestion = $value->valuequestion;
                $valueinput = explode('-', $request->valueinput);
                if($valueinput[0] == 'singular') {
                    if($valueinput[1] < count($valuequestion->fields['singular'])) {
                        $res = $valuequestion->fields['singular'][$valueinput[1]];

                        //Saving Score
                        $score = 0;
                        if($valuequestion->scores) {
                            $score = $valuequestion->scores[$valueinput[1]];
                        }

                        //Update value result
                        $value->update([
                            'value' => $res,
                            'score' => $score,
                        ]);

                        return [
                            'status' => 200,
                        ];
                    }
                } elseif($valueinput[0] == 'personal') {
                    if($valueinput[1] < count($valuequestion->fields['personal'])) {
                        $res = $valuequestion->fields['personal'][$valueinput[1]];

                        //Saving Score
                        $score = 0;
                        if($valuequestion->scores) {
                            $score = $valuequestion->scores[count($valuequestion->fields['singular']) + $valueinput[1]];
                        }

                        //Update value result
                        $value->update([
                            'value' => $res,
                            'score' => $score,
                        ]);

                        return [
                            'status' => 200,
                        ];
                    }
                }
            }

            return [
                'status' => 422,
                'responseJSON' => [
                    'errors' => [
                        'valueinput' => ['The valueinput field is required']
                    ]
                ]
            ];

        } elseif($request->type == 'open') {
            $open = OpenQuestionResult::where('id', $request->update)->get();
            if($open->count() != 0) {
                $open = $open[0];
                $request->validate([
                    'openinput' => ['required', 'string'],
                ]);

                //Update open result
                $open->update([
                    'value' => $request->openinput,
                ]);

                return [
                    'status' => 200,
                ];
            }

        } elseif($request->type == 'multipleselection') {
            $multiple = MultipleSelectionQuestionResult::where('id', $request->update)->get();
            if($multiple->count() != 0) {
                $multiple = $multiple[0];

                $request->validate([
                    'checkbox' => ['required', 'array'],
                    'checkbox.*' => ['integer'],
                ]);

                $multipleselectionquestion = $multiple->multipleselectionquestion;
                if(count($request->checkbox) < count($multipleselectionquestion->fields)) {

                    //Saving respones and generating score
                    $value = [];
                    $score = 0;
                    if($multipleselectionquestion->scores) {
                        for($i=0; $i<count($request->checkbox); $i++) {
                            if($request->checkbox[$i] <= count($multipleselectionquestion->fields)) {
                                $value[] = $multipleselectionquestion->fields[$request->checkbox[$i]];
                                $score += $multipleselectionquestion->scores[$request->checkbox[$i]];
                            }
                        }
                    } else {
                        for($i=0; $i<count($request->checkbox); $i++) {
                            if($request->checkbox[$i] <= count($multipleselectionquestion->fields)) {
                                $value[] = $multipleselectionquestion->fields[$request->checkbox[$i]];
                            }
                        }
                    }

                    //Update multiple selection result
                    $multiple->update([
                        'value' => $value,
                        'score' => $score,
                    ]);

                    return [
                        'status' => 200,
                    ];

                }
            }

            return [
                'status' => 422,
                'responseJSON' => [
                    'errors' => [
                        'checkbox' => ['The field is required']
                    ]
                ]
            ];

        } elseif($request->type == 'image') {
            $image = ImageQuestionResult::where('id', $request->update)->get();
            if($image->count() != 0) {
                $image = $image[0];

                $request->validate([
                    'imageradio' => ['required', 'integer'],
                ]);

                $imagequestion = $image->imagequestion;
                if($request->imageradio < count($imagequestion->images)) {

                    $value = $imagequestion->images[$request->imageradio];

                    //Saving Score
                    $score = 0;
                    if($imagequestion->scores) {
                        $score = $imagequestion->scores[$request->imageradio];
                    }

                    //Update image result
                    $image->update([
                        'value' => $value,
                        'score' => $score,
                    ]);

                    return [
                        'status' => 200,
                    ];

                }
            }

            return [
                'status' => 422,
                'responseJSON' => [
                    'errors' => [
                        'imageradio' => ['The image field is required']
                    ]
                ]
            ];

        }

        return $request->json([
            'status' => 400,
        ]);

    }

    /**
     * Delete the active interview.
     */
    public function destroyInterview(Request $request): RedirectResponse
    {
        //Deleting test code
        $interview = Interview::where('id', $request->session()->get('activeinterview'))->get()[0];
        $testresult = $interview->testresult;

        //declaration recursive anonymous function
        $destroy = function($sectionresult) use (&$destroy) {
            if($sectionresult->sections->count() != 0) {
                $sectionresults = $sectionresult->sections;
                foreach($sectionresults as $sectionresult) {
                    $destroy($sectionresult);
                }
            } else {
                $questionresults = $sectionresult->questionresults;
                foreach($questionresults as $questionresult) {
                    if($questionresult->questionable) {
                        $questionresult->questionable->delete();
                    }
                    $questionresult->delete();
                }
            }
            $sectionresult->delete();
        };

        $sectionresults = $testresult->sectionresults;
        for($i=0; $i<$sectionresults->count(); $i++) {
            $destroy($sectionresults[$i]);
        }

        $testresult->delete();
        $interview->delete();

        $request->session()->forget('activeinterview');
        $request->session()->forget('status');

        return Redirect::route('med.visitadministration.controlpanel');
    }

    /**
     * Delete the active visit.
     */
    public function destroyVisit(Request $request): RedirectResponse
    {
        $visit = Visit::where('id', $request->session()->get('activevisit'))->get()[0];

        //Deleting linked interviews
        $interviews = $visit->interviews;
        if($interviews->count() != 0) {

            //declaration recursive anonymous function
            $destroy = function($sectionresult) use (&$destroy) {
                if($sectionresult->sections->count() != 0) {
                    $sectionresults = $sectionresult->sections;
                    foreach($sectionresults as $sectionresult) {
                        $destroy($sectionresult);
                    }
                } else {
                    $questionresults = $sectionresult->questionresults;
                    foreach($questionresults as $questionresult) {
                        if($questionresult->questionable) {
                            $questionresult->questionable->delete();
                        }
                        $questionresult->delete();
                    }
                }
                $sectionresult->delete();
            };

            for($i=0; $i<$interviews->count(); $i++) {
                $interview = $interviews[$i];

                //Deleting test result of interview
                $testresult = $interview->testresult;
                $sectionresults = $testresult->sectionresults;
                for($i=0; $i<$sectionresults->count(); $i++) {
                    $destroy($sectionresults[$i]);
                }
                $testresult->delete();

                //Deleting interview after deleting its test result
                $interview->delete();
            }
        }

        //Deleting visit
        $visit->delete();

        //Remove data from session
        $request->session()->forget('activevisit');
        $request->session()->forget('status');

        return Redirect::route('med.dashboard');
    }
}
