<?php

namespace App\Http\Controllers\TestMed;

use App\Http\Controllers\Controller;
use App\Models\Questions\MultipleQuestion;
use App\Models\Questions\Question;
use App\Models\Questions\ValueQuestion;
use App\Models\Section;
use App\Models\Test;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CreateTestController extends Controller
{
    /**
     * Display the create test view.
     */
    public function create(): View
    {
        return view('testmed.createtest');
    }

    /**
     * Display the create test structure view.
     */
    public function createtest(Request $request): View
    {
        if($request->session()->get('status') == 'exit-status') {
            $status = $request->session()->get('status');
            return view('testmed.createteststructure', ['status' => $status]);
        } elseif($request->status == 'exit-status') {
            return view('testmed.createteststructure', ['status' => $request->status]);
        } else {
            return view('testmed.createteststructure');
        }

    }

    /**
     * Display the test's tree json.
     */
    public function createTree(Request $request): JsonResponse
    {
        //Test data
        $testid = $request->session()->get('testidcreation');
        $test = Test::where('id', $testid)->get()[0];

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

                    $array["section".$section->progressive]['questions']['question'.$question->progressive] = [
                        'id' => $question->id,
                        'title' => $question->questionable->title,
                    ];
                }

                return $array;

            } else {
                return $array;
            }
        }
    }

    /**
     * Display the add section button view.
     */
    public function createaddsectionbutton(): View
    {
        return view('testmed.creationcomponents.add-section-button');
    }

    /**
     * Display the add question button view.
     */
    public function createaddquestionbutton(): View
    {
        return view('testmed.creationcomponents.add-question-button');
    }

    /**
     * Display the add section button view.
     */
    public function createsection(): View
    {
        return view('testmed.creationcomponents.add-section');
    }

    /**
     * Display the add question button view.
     */
    public function createquestion(): View
    {
        return view('testmed.creationcomponents.add-question');
    }

    /**
     * Handle an incoming create test request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'testname' => ['required', 'string', 'max:255', 'unique:'.Test::class.",name"],
        ]);

        $test = Test::create([
            'name' => $request->testname,
        ]);

        $request->session()->put('testidcreation', $test->id);
        return Redirect::route('testmed.createteststructure');
    }

    /**
     * Handle an incoming create section request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storesection(Request $request): JsonResponse
    {

        $request->validate([
            'sectionname' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'id' => ['required', 'integer', 'max:255'],
        ]);

        //Create section object
        if($request->type == 'test') {
            $progressive = Test::where('id', $request->id)->get()[0]->sections->count() + 1;
            $type = Test::class;
        } elseif($request->type == 'section') {
            $progressive = Section::where('id', $request->id)->get()[0]->sections->count() + 1;
            $type = Section::class;
        }
        Section::create([
            'name' => $request->sectionname,
            'sectionable_id' => $request->id,
            'sectionable_type' => $type,
            'progressive' => $progressive,
        ]);

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * Handle an incoming create question request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storequestion(Request $request)
    {

        $request->validate([
            'id' => ['required', 'integer', 'max:255'],
            'radio' => ['required', 'integer', 'max:255'],
        ]);

        //Create question object
        $count = Section::where('id', $request->id)->get()[0]->questions()->count();
        if($request->radio == 1) {
            $class = MultipleQuestion::class;
        } elseif($request->radio == 2) {
            $class = ValueQuestion::class;
        } else {
            return response()->json([
                'status' => 400
            ]);
        }
        $question = Question::create([
            'section_id' => $request->id,
            'progressive' => $count + 1,
            'questionable_type' => $class,
        ]);

        if($request->radio == 1) {
            return view('testmed.creationcomponents.questions.multiple-question', ['questionid' => $question->id, 'questiontype' => 'multiple']);
        } elseif($request->radio == 2) {
            return view('testmed.creationcomponents.questions.value-question', ['questionid' => $question->id, 'questiontype' => 'value']);
        }
    }

    /**
     * Handle an incoming create multiple question request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storemultiplequestion(Request $request): JsonResponse
    {

        $request->validate([
            'questiontitle' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer', 'max:255'],
        ]);

        //Createmultiple question object
        $question = MultipleQuestion::create([
            'title' => $request->questiontitle,
        ]);
        Question::where('id', $request->questionid)->update(['questionable_id' => $question->id]);

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * Handle an incoming create value question request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storevaluequestion(Request $request): JsonResponse
    {

        $request->validate([
            'questiontitle' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer', 'max:255'],
        ]);

        //Createmultiple question object
        $question = ValueQuestion::create([
            'title' => $request->questiontitle,
        ]);
        Question::where('id', $request->questionid)->update(['questionable_id' => $question->id]);

        return response()->json([
            'status' => 200
        ]);
    }

    /**
     * Delete the creation test.
     */
    public function destroy(Request $request): RedirectResponse
    {
        //Procedura per cancelazione del test

        $request->session()->forget('testidcreation');

        return Redirect::route('testmed.createteststructure');
    }

    /**
     * Delete the creation test.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function cancelquestion(Request $request): JsonResponse
    {

        $request->validate([
            'questionid' => ['required', 'integer', 'max:255'],
        ]);

        Question::where('id', $request->questionid)->delete();

        return response()->json([
            'status' => 200
        ]);
    }
}
