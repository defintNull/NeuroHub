<?php

namespace App\Http\Controllers\TestMed;

use App\Http\Controllers\Controller;
use App\Models\Questions\ImageQuestion;
use App\Models\Questions\MultipleQuestion;
use App\Models\Questions\MultipleSelectionQuestion;
use App\Models\Questions\OpenQuestion;
use App\Models\Questions\Question;
use App\Models\Questions\ValueQuestion;
use App\Models\Section;
use App\Models\Test;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        if($request->input('search')) {
            $request->validate([
                'search' => ['max:255', 'string'],
            ]);
            $tests = Test::where('name', 'like', "%".$request->search."%")->paginate(5);
        } else {
            $tests = Test::paginate(5);
        }

        return view('testmed.listtest', ['tests' => $tests]);
    }

    /**
     * Show the page for visualizing the selected test.
     */
    public function create(string $testname): View
    {
        return view('testmed.testdetail', ['testname' => $testname]);
    }

    /**
     * Get the test name and pass it to the create view.
     */
    public function storeTestName(Request $request): RedirectResponse
    {
        $request->validate([
            'testname' => ['required', 'string'],
        ]);
        $test = Test::where('name', $request->testname)->get();
        if($test->count() != 0) {
            $test = $test[0];
        }
        if($test->status == 1) {
            return redirect(route('testmed.testdetail', ['testname' => $request->testname]));
        }
        return redirect(route('testmed.testlist'));

    }

    /**
     * Display the test's tree json.
     */
    public function createTree(Request $request): JsonResponse
    {
        $request->validate([
            'testname' => ['required', 'string', 'max:255'],
        ]);

        //Test data
        $test = Test::where('name', $request->testname)->get();
        if($test->count() != 0) {
            $test = $test[0];
        } else {
            return response()->json([
                'status' => 400,
            ]);
        }

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
                $res = $this->createSecionNode($sections[$i]);
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

    private function createSecionNode(Section $section): Array {
        $array = [
            "section".$section->progressive => [
                'id' => $section->id,
                'name' => $section->name,
            ]
        ];
        $subsections = $section->sections;
        if($subsections->count() != 0) {
            for($i=0; $i<$subsections->count(); $i++) {
                $subsesction = $subsections[$i];
                $res = $this->createSecionNode($subsesction);

                $array["section".$section->progressive]['sections']['section'.$subsesction->progressive] = $res['section'.$subsesction->progressive];
            }
            return $array;

        } else {
            $questions = $section->questions;
            if($questions->count() != 0) {
                for($i=0; $i<$questions->count(); $i++) {
                    $question = $questions[$i];

                    if($question->questionable == null) {
                        $question->delete();
                    } else {
                        $array["section".$section->progressive]['questions']['question'.$question->progressive] = [
                            'id' => $question->id,
                            'title' => $question->questionable->title,
                        ];
                    }

                }

                return $array;

            } else {
                return $array;
            }
        }
    }

    /**
     * Show the page for visualizing the chosen element.
     */
    public function createElementDetail(Request $request)
    {
        if($request['testid']) {
            $request->validate([
                'testid' => ['required', 'integer'],
            ]);
            $test = Test::where('id', $request->testid)->get();
            if($test->count() != 0) {
                $test = $test[0];
                return view('testmed.detailcomponents.testdetail', ['test' => $test]);
            }
        } elseif($request['sectionid']) {
            $request->validate([
                'sectionid' => ['required', 'integer'],
            ]);
            $section = Section::where('id', $request->sectionid)->get();
            if($section->count() != 0) {
                $section = $section[0];
                return view('testmed.detailcomponents.sectiondetail', ['section' => $section]);
            }
        } elseif($request['questionid']) {
            $request->validate([
                'questionid' => ['required', 'integer'],
            ]);
            $question = Question::where('id', $request->questionid)->get();
            if($question->count() != 0) {
                $question = $question[0];
                $questionrelated = $question->questionable;
                if(get_class($questionrelated) == MultipleQuestion::class) {
                    return view('testmed.detailcomponents.multiplequestiondetail', ['question' => $questionrelated]);
                } elseif(get_class($questionrelated) == ValueQuestion::class) {
                    return view('testmed.detailcomponents.valuequestiondetail', ['question' => $questionrelated]);
                } elseif(get_class($questionrelated) == OpenQuestion::class) {
                    return view('testmed.detailcomponents.openquestiondetail', ['question' => $questionrelated]);
                } elseif(get_class($questionrelated) == MultipleSelectionQuestion::class) {
                    return view('testmed.detailcomponents.multipleselectionquestiondetail', ['question' => $questionrelated]);
                } elseif(get_class($questionrelated) == ImageQuestion::class) {
                    $images = [];
                    $files = $questionrelated->images;
                    for($i=0; $i<count($files); $i++) {
                        $imageContent = Storage::disk('test')->get($files[$i][0]);
                        $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                        $images[] = $base64Image;
                    }
                    return view('testmed.detailcomponents.imagequestion', [
                        'question' => $questionrelated,
                        'images' => $images,
                    ]);
                }
            }
        }
        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Test $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Test $test)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test)
    {
        //
    }
}
