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
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CreateTestController extends Controller
{
    /**
     * Display the create test view.
     */
    public function create(Request $request): View
    {
        if($request->get('status')) {
            return view('testmed.createtest', ['status' => $request->get('status')]);
        }

        $user = $request->user();
        $testmed = $user->userable;
        $opentest = $testmed->tests()->where('status', 0)->get();
        if($opentest->count() == 0) {
            return view('testmed.createtest');
        } else {
            $request->session()->put('testidcreation', $opentest[0]->id);
            return view('testmed.createteststructure');
        }

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
     * Display the add section and question button view.
     */
    public function createAddSectionQuestionButton(): View
    {
        return view('testmed.creationcomponents.add-section-question-button');
    }

    /**
     * Display the add section view.
     */
    public function createsection(): View
    {
        return view('testmed.creationcomponents.add-section');
    }

    /**
     * Display the add question view.
     */
    public function createquestion(): View
    {
        return view('testmed.creationcomponents.add-question');
    }

    /**
     * Display the modify and delete button view.
     */
    public function createDeleteModifyButton(): View
    {
        return view('testmed.creationcomponents.delete-modify-button');
    }

    /**
     * Display the modify element view.
     */
    public function createElementModify(Request $request)
    {
        $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'id' => ['required', 'integer'],
        ]);

        if($request->type == 'test') {
            if($request->id == $request->session()->get('testidcreation')) {
                $test = Test::where('id', $request->id)->get()[0];
                return view('testmed.creationcomponents.update-test', [
                    'update' => true,
                    'name' => $test->name,
                    'testid' => $test->id
                ]);
            }

        } elseif($request->type == 'section') {
            $section = Section::where('id', $request->id)->get();
            if($section != []) {
                $section = $section[0];
                $parent = $section;
                //Looping for subsections
                do {
                    $status = false;
                    $parent = $parent->sectionable;
                    if(get_class($parent) == Test::class) {
                        $status = true;
                    }
                } while($status == false);
                if($parent->id = $request->session()->get('testidcreation')) {
                    return view('testmed.creationcomponents.add-section', [
                        'update' => true,
                        'name' => $section->name,
                        'sectionid' => $section->id
                    ]);
                }
            }

        } elseif($request->type == 'question') {
            $question = Question::where('id', $request->id)->get();
            if($question != []) {
                $question = $question[0];
                $section = $question->section;
                //Looping for subsections
                do {
                    $status = false;
                    $section = $section->sectionable;
                    if(get_class($section) == Test::class) {
                        $status = true;
                    }
                } while($status == false);
                if($section->id = $request->session()->get('testidcreation')) {
                    $questionrelated = $question->questionable;
                    if(get_class($questionrelated) == MultipleQuestion::class) {
                        return view('testmed.creationcomponents.questions.multiple-question',[
                            'questionid' => $questionrelated->id,
                            'update' => true,
                            'title' => $questionrelated->title,
                            'text' => $questionrelated->text,
                            'fields' => $questionrelated->fields,
                        ]);
                    } elseif(get_class($questionrelated) == ValueQuestion::class) {
                        return view('testmed.creationcomponents.questions.value-question', [
                            'questionid' => $questionrelated->id,
                            'update' => true,
                            'title' => $questionrelated->title,
                            'text' => $questionrelated->text,
                            'fields' => $questionrelated->fields,
                        ]);
                    } elseif(get_class($questionrelated) == OpenQuestion::class) {
                        return view('testmed.creationcomponents.questions.open-question', [
                            'questionid' => $questionrelated->id,
                            'update' => true,
                            'title' => $questionrelated->title,
                            'text' => $questionrelated->text,
                        ]);
                    } elseif(get_class($questionrelated) == MultipleSelectionQuestion::class) {
                        return view('testmed.creationcomponents.questions.multiple-selection-question', [
                            'questionid' => $questionrelated->id,
                            'update' => true,
                            'title' => $questionrelated->title,
                            'text' => $questionrelated->text,
                            'fields' => $questionrelated->fields,
                        ]);
                    } elseif(get_class($questionrelated) == ImageQuestion::class) {
                        $images = [];
                        $files = $questionrelated->images;
                        for($i=0; $i<count($files); $i++) {
                            $imageContent = Storage::disk('test')->get($files[$i][0]);
                            $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                            $images[] = $files[$i];
                            $images[$i][] = $base64Image;
                        }
                        return view('testmed.creationcomponents.questions.image-question', [
                            'questionid' => $questionrelated->id,
                            'update' => true,
                            'title' => $questionrelated->title,
                            'text' => $questionrelated->text,
                            'images' => $images,
                        ]);
                    }
                }
            }
        }
        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Handle an incoming create multiple question item request.
     */
    public function createMultipleQuestionItem(): View {
        return view('testmed.creationcomponents.items.multiple-question-item');
    }

    /**
     * Handle an incoming create value question item request.
     */
    public function createValueQuestionItem(): View {
        return view('testmed.creationcomponents.items.value-question-item');
    }

    /**
     * Handle an incoming create multiple selection question item request.
     */
    public function createMultipleSelectionQuestionItem(): View {
        return view('testmed.creationcomponents.items.multiple-selection-question-item');
    }

    /**
     * Handle an incoming create image question item request.
     */
    public function createImageQuestionItem(): View {
        return view('testmed.creationcomponents.items.image-question-item');
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
            'test_med_id' => $request->user()->userable->id,
        ]);

        //Creating filesystem folder
        Storage::disk('test')->makeDirectory($request->testname);

        $request->session()->put('testidcreation', $test->id);
        return Redirect::route('testmed.createteststructure');
    }

    /**
     * Handle an incoming create section request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storesection(Request $request)//: JsonResponse
    {

        $request->validate([
            'sectionname' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'id' => ['required', 'integer'],
            'testid' => ['required', 'integer'],
        ]);

        //Create section object
        if($request->testid == $request->session()->get('testidcreation')) {
            if($request->type == 'test') {
                $test = Test::where('id', $request->id)->get();
                if($test != []) {
                    $progressive = $test[0]->sections->count() + 1;
                    $type = Test::class;
                    Section::create([
                        'name' => $request->sectionname,
                        'sectionable_id' => $request->id,
                        'sectionable_type' => $type,
                        'progressive' => $progressive,
                    ]);

                    return response()->json([
                        'status' => 200
                    ]);
                } else {
                    return response()->json([
                        'status' => 400
                    ]);
                }

            } elseif($request->type == 'section') {
                //Finding section
                $section = Section::where('id', $request->id)->get();
                if($section == []) {
                    return response()->json([
                        'status' => 400
                    ]);
                }
                $section = $section[0];
                //Ensure it doesn't have questions
                if($section->questions->count() != 0) {
                    return response()->json([
                        'status' => 400,
                    ]);
                }
                //Looping for subsections
                do {
                    $status = false;
                    $section = $section->sectionable;
                    if(get_class($section) == Test::class) {
                        $status = true;
                    }
                } while($status == false);

                if($section->id == $request->session()->get('testidcreation')) {

                    $type = Section::class;
                    $progressive = Section::where('id', $request->id)->get()[0]->sections->count() + 1;
                    Section::create([
                        'name' => $request->sectionname,
                        'sectionable_id' => $request->id,
                        'sectionable_type' => $type,
                        'progressive' => $progressive,
                    ]);

                    return response()->json([
                        'status' => 200
                    ]);
                } else {
                    return response()->json([
                        'status' => 400
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400
                ]);
            }
        } else {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * Handle an incoming create question request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storequestion(Request $request)
    {

        $request->validate([
            'id' => ['required', 'integer'],
            'radio' => ['required', 'integer'],
            'testid' => ['required', 'integer'],
        ]);

        //Create question object
        if($request->testid == $request->session()->get('testidcreation')) {
            $section = Section::where('id', $request->id)->get();
            if($section != []) {
                $parent = $section[0];

                //Looping for subsections
                do {
                    $status = false;
                    $parent = $parent->sectionable;
                    if(get_class($parent) == Test::class) {
                        $status = true;
                    }
                } while($status == false);

                if($parent->id == $request->session()->get('testidcreation')) {

                    $count = $section[0]->questions->count();
                    if($request->radio == 1) {
                        $class = MultipleQuestion::class;
                    } elseif($request->radio == 2) {
                        $class = ValueQuestion::class;
                    } elseif($request->radio == 3) {
                        $class = OpenQuestion::class;
                    } elseif($request->radio == 4) {
                        $class = MultipleSelectionQuestion::class;
                    } elseif($request->radio == 5) {
                        $class = ImageQuestion::class;
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
                        return view('testmed.creationcomponents.questions.multiple-question', ['questionid' => $question->id]);
                    } elseif($request->radio == 2) {
                        return view('testmed.creationcomponents.questions.value-question', ['questionid' => $question->id]);
                    } elseif($request->radio == 3) {
                        return view('testmed.creationcomponents.questions.open-question', ['questionid' => $question->id]);
                    } elseif($request->radio == 4) {
                        return view('testmed.creationcomponents.questions.multiple-selection-question', ['questionid' => $question->id]);
                    } elseif($request->radio == 5) {
                        return view('testmed.creationcomponents.questions.image-question', ['questionid' => $question->id]);
                    }

                } else {
                    return response()->json([
                        'status' => 400
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 400
                ]);
            }
        } else {
            return response()->json([
                'status' => 400
            ]);
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
            'radiolenght' => ['required', 'integer'],
        ]);

        $rule = [
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
            'testid' => ['required', 'integer'],
        ];

        if($request->radiolenght == 0) {
            $rule['radiosection'] = ['required'];
        } else {
            for($i=0; $i<$request->radiolenght; $i++) {
                $rule['radioinput'.$i] = ['required', 'string', 'max:255'];
            }
        }

        $request->validate($rule);

        //Createmultiple question object
        if($request->testid == $request->session()->get('testidcreation')) {

            $question = Question::where('id', $request->questionid)->get();
            if($question != []) {
                $question = $question[0];
                if($question->questionable_id == null) {
                    $section = $question->section;
                    //Looping for subsections
                    do {
                        $status = false;
                        $section = $section->sectionable;
                        if(get_class($section) == Test::class) {
                            $status = true;
                        }
                    } while($status == false);
                    if ($section->id == $request->session()->get('testidcreation')) {

                        $fields = [];
                        for($i=0; $i<$request->radiolenght; $i++) {
                            $fields[] = $request['radioinput'.$i];
                        }

                        $multiplequestion = MultipleQuestion::create([
                            'title' => $request->questiontitle,
                            'text' => $request->questiontext,
                            'fields' => $fields,
                        ]);
                        $question->update(['questionable_id' => $multiplequestion->id]);

                        return response()->json([
                            'status' => 200
                        ]);

                    } else {
                        return response()->json([
                            'status' => 400
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => 400
                ]);
            }
        } else {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * Handle an incoming create value question request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storevaluequestion(Request $request): JsonResponse
    {
        $rule = [
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
            'testid' => ['required', 'integer'],
        ];

        //Check value of personal fields
        $i = 1;
        while(isset($request["checkboxpersonal".$i])) {
            $request->validate([
                'checkboxpersonal'.$i => ['integer'],
            ]);
            if($request["checkboxpersonal".$i] < 100) {
                $rule['values'] = ['required'];
            }
            $i++;
        }

        //Ensure require value fields
        $check = true;
        while(true) {
            if($request["checkboxpersonal1"]) {
                $check = false;
                break;
            }
            for($i=0; $i<101; $i++) {
                if(isset($request["checkboxsingle".$i])) {
                    $check = false;
                    break 2;
                }
            }
            break;
        }

        if($check) {
            $rule['values'] = ['required'];
        }

        $request->validate($rule);

        //Createmultiple question object
        if($request->testid == $request->session()->get('testidcreation')) {

            $question = Question::where('id', $request->questionid)->get();
            if($question != []) {
                $question = $question[0];
                if($question->questionable_id == null) {
                    $section = $question->section;
                    //Looping for subsections
                    do {
                        $status = false;
                        $section = $section->sectionable;
                        if(get_class($section) == Test::class) {
                            $status = true;
                        }
                    } while($status == false);
                    if ($section->id == $request->session()->get('testidcreation')) {

                        //Setting fields
                        $fields = [
                            'singular' => [],
                            'personal' => [],
                        ];
                        for($i=0; $i<101; $i++) {
                            if(isset($request["checkboxsingle".$i])) {
                                $fields['singular'][] = $request["checkboxsingle".$i];
                            }
                        }
                        $i = 1;
                        while(isset($request["checkboxpersonal".$i])) {
                            $fields['personal'][] = $request["checkboxpersonal".$i];
                            $i++;
                        }

                        $valuequestion = ValueQuestion::create([
                            'title' => $request->questiontitle,
                            'text' => $request->questiontext,
                            'fields' => $fields,
                        ]);
                        $question->update(['questionable_id' => $valuequestion->id]);

                        return response()->json([
                            'status' => 200
                        ]);

                    } else {
                        return response()->json([
                            'status' => 400
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => 400
                ]);
            }
        } else {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * Handle an incoming create open question request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeOpenQuestion(Request $request): JsonResponse
    {

        $request->validate([
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
            'testid' => ['required', 'integer'],
        ]);

        //Createmultiple question object
        if($request->testid == $request->session()->get('testidcreation')) {

            $question = Question::where('id', $request->questionid)->get();
            if($question != []) {
                $question = $question[0];
                if($question->questionable_id == null) {
                    $section = $question->section;
                    //Looping for subsections
                    do {
                        $status = false;
                        $section = $section->sectionable;
                        if(get_class($section) == Test::class) {
                            $status = true;
                        }
                    } while($status == false);
                    if ($section->id == $request->session()->get('testidcreation')) {

                        $openquestion = OpenQuestion::create([
                            'title' => $request->questiontitle,
                            'text' => $request->questiontext,
                        ]);
                        $question->update(['questionable_id' => $openquestion->id]);

                        return response()->json([
                            'status' => 200
                        ]);

                    } else {
                        return response()->json([
                            'status' => 400
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => 400
                ]);
            }
        } else {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * Handle an incoming create multiple selection question request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeMultipleSelectionQuestion(Request $request): JsonResponse
    {
        $request->validate([
            'radiolenght' => ['required', 'integer'],
        ]);

        $rule = [
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
            'testid' => ['required', 'integer'],
        ];

        //Ensure require value fields
        if($request->radiolenght == 0) {
            $rule['checkboxsection'] = ['required'];
        } else {
            for($i=1; $i<=$request->radiolenght; $i++) {
                $rule['checkbox'.$i] = ['required', 'string', 'max:255'];
            }
        }

        $request->validate($rule);

        //Createmultiple question object
        if($request->testid == $request->session()->get('testidcreation')) {

            $question = Question::where('id', $request->questionid)->get();
            if($question != []) {
                $question = $question[0];
                if($question->questionable_id == null) {
                    $section = $question->section;
                    //Looping for subsections
                    do {
                        $status = false;
                        $section = $section->sectionable;
                        if(get_class($section) == Test::class) {
                            $status = true;
                        }
                    } while($status == false);
                    if ($section->id == $request->session()->get('testidcreation')) {

                        //Setting fields
                        $fields = [];
                        $i = 1;
                        while(isset($request["checkbox".$i])) {
                            $fields[] = $request["checkbox".$i];
                            $i++;
                        }

                        $valuequestion = MultipleSelectionQuestion::create([
                            'title' => $request->questiontitle,
                            'text' => $request->questiontext,
                            'fields' => $fields,
                        ]);
                        $question->update(['questionable_id' => $valuequestion->id]);

                        return response()->json([
                            'status' => 200
                        ]);

                    } else {
                        return response()->json([
                            'status' => 400
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => 400
                ]);
            }
        } else {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * Handle an incoming create image question request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeImageQuestion(Request $request): JsonResponse
    {

        $request->validate([
            'radiolenght' => ['required', 'integer'],
        ]);

        $rule = [
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
            'testid' => ['required', 'integer'],
        ];
        if($request->radiolenght == 0) {
            $rule['imagefield'] = ['required'];
        } else {
            for($i=0; $i<$request->radiolenght; $i++) {
                $rule['imageinput'.$i] = ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'];
            }
        }

        $request->validate($rule);

        //Createmultiple question object
        if($request->testid == $request->session()->get('testidcreation')) {

            $question = Question::where('id', $request->questionid)->get();
            if($question != []) {
                $question = $question[0];
                if($question->questionable_id == null) {
                    $section = $question->section;
                    //Looping for subsections
                    do {
                        $status = false;
                        $section = $section->sectionable;
                        if(get_class($section) == Test::class) {
                            $status = true;
                        }
                    } while($status == false);
                    if ($section->id == $request->session()->get('testidcreation')) {

                        //Saving Image on filesystem and path on db
                        $test = Test::where('id', "=", $request->session()->get('testidcreation'))->get()[0];
                        $images = [];
                        for($i=0; $i<$request->radiolenght; $i++) {
                            if($request->hasFile('imageinput'.$i)) {
                                $path = $request->file('imageinput'.$i)->store($test->name, 'test');
                                $filename = basename($path);
                                $images[] = [$path, $filename];
                            } else {
                                return response()->json([
                                    'status' => 400
                                ]);
                            }
                        }

                        $multiplequestion = ImageQuestion::create([
                            'title' => $request->questiontitle,
                            'text' => $request->questiontext,
                            'images' => $images,
                        ]);
                        $question->update(['questionable_id' => $multiplequestion->id]);

                        return response()->json([
                            'status' => 200
                        ]);

                    } else {
                        return response()->json([
                            'status' => 400
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => 400
                ]);
            }
        } else {
            return response()->json([
                'status' => 400
            ]);
        }
    }

    /**
     * Confirm the test creation.
     */
    public function storeTest(Request $request) {

        $testid = $request->session()->get('testidcreation');
        $test = Test::where('id', $testid)->get()[0];
        $test->update([
            'status' => 1,
        ]);

        $request->session()->forget('testidcreation');

        return Redirect::route('testmed.createtest', ['status' => true]);
    }

    /**
     * Delete the creation test.
     */
    public function destroy(Request $request): RedirectResponse
    {
        //Deleting test code
        $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];

        //declaration recursive anonymous function
        $destroy = function($test) use (&$destroy) {
            $sections = $test->sections;
            if($sections->count() != 0) {
                foreach($sections as $section) {
                    if($section->sections->count() != 0) {
                        $destroy($section);
                    } else {
                        $questions = $section->questions;
                        if($questions->count() != 0) {
                            foreach($questions as $question) {
                                $question->questionable->delete();
                                $question->delete();
                            }
                        }
                        $section->delete();
                    }
                }
            }
            $test->delete();

        };

        //Remove filesystem folder
        Storage::disk('test')->deleteDirectory($test->name);

        $destroy($test);

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
            'questionid' => ['required', 'integer'],
        ]);

        $question = Question::where('id', $request->questionid)->get();
        if($question != []) {
            $question = $question[0];
            $question->delete();

            return response()->json([
                'status' => 200
            ]);
        }

        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Delete the elements of the test tree.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deleteElement(Request $request): JsonResponse
    {

        $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'id' => ['required', 'integer'],
        ]);

        if($request->type == 'question') {
            $question = Question::where('id', $request->id)->get();

            if($question != []) {
                $question = $question[0];
                $section = $question->section;
                //Looping for subsections
                do {
                    $status = false;
                    $section = $section->sectionable;
                    if(get_class($section) == Test::class) {
                        $status = true;
                    }
                } while($status == false);

                if($section->id == $request->session()->get('testidcreation')) {
                    $section = $question->section;
                    $progressive = $question->progressive;

                    //Removing images if needed
                    if(get_class($question->questionable) == ImageQuestion::class) {
                        $images = $question->questionable->images;
                        for($i=0; $i<count($images); $i++) {
                            Storage::disk('test')->delete($images[$i][0]);
                        }
                    }

                    //Deleting question
                    $question->questionable->delete();
                    $question->delete();

                    //Updating progressives
                    $questions = $section->questions()->where('progressive', '>', $progressive)->get();
                    for($i=0; $i<$questions->count(); $i++) {
                        $questions[$i]->update(['progressive' => $questions[$i]->progressive-1]);
                    }

                    return response()->json([
                        'status' => 200
                    ]);
                }
            }
        } elseif($request->type == 'section') {
            $section = Section::where('id', $request->id)->get();

            if($section != []) {
                $section = $section[0];
                $element = $section;

                //Looping for subsections
                do {
                    $status = false;
                    $element = $element->sectionable;
                    if(get_class($element) == Test::class) {
                        $status = true;
                    }

                } while($status == false);

                if($element->id == $request->session()->get('testidcreation')) {
                    $progressive = $section->progressive;
                    $element = $section->sectionable;

                    //Deleting section and related questions
                    $section->questions()->delete();
                    $section->delete();

                    //Updating progressive
                    $sections = $element->sections()->where('progressive', '>', $progressive)->get();
                    for($i=0; $i<$sections->count(); $i++) {
                        $sections[$i]->update(['progressive' => $sections[$i]->progressive-1]);
                    }

                    return response()->json([
                        'status' => 200
                    ]);
                }
            }

        } elseif($request->type == 'test') {
            if($request->id == $request->session()->get('testidcreation')) {
                return response()->json([
                    'status' => 200,
                    'redirect' => true
                ]);
            }
        }

        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Update the test of the test tree.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateTest(Request $request): JsonResponse
    {

        $request->validate([
            'testname' => ['required', 'string', 'max:255'],
            'testid' => ['required', 'integer'],
        ]);

        if($request->testid == $request->session()->get('testidcreation')) {
            $test = Test::where('id', $request->testid)->get()[0];
            $test->update([
                'name' => $request->testname,
            ]);

            return response()->json([
                'status' => 200
            ]);
        }

        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Update the section of the test tree.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateSection(Request $request): JsonResponse
    {

        $request->validate([
            'sectionname' => ['required', 'string', 'max:255'],
            'sectionid' => ['required', 'integer'],
        ]);

        $section = Section::where('id', $request->sectionid)->get();
        if($section != []) {
            $section = $section[0];
            $parent = $section;
            //Looping for subsections
            do {
                $status = false;
                $parent = $parent->sectionable;
                if(get_class($parent) == Test::class) {
                    $status = true;
                }
            } while($status == false);

            if($parent->id == $request->session()->get('testidcreation')) {
                $section->update([
                    'name' => $request->sectionname
                ]);
                return response()->json([
                    'status' => 200
                ]);
            }
        }

        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Update the value question of the test tree.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateValueQuestion(Request $request): JsonResponse
    {
        $rule = [
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
        ];

        //Check value of personal fields
        $i = 1;
        while(isset($request["checkboxpersonal".$i])) {
            if($request["checkboxpersonal".$i] < 100) {
                $rule['values'] = ['required'];
            }
            $i++;
        }

        //Ensure require value fields
        $check = true;
        while(true) {
            if($request["checkboxpersonal1"]) {
                $check = false;
                break;
            }
            for($i=0; $i<101; $i++) {
                if(isset($request["checkboxsingle".$i])) {
                    $check = false;
                    break 2;
                }
            }
            break;
        }

        if($check) {
            $rule['values'] = ['required'];
        }

        $request->validate($rule);

        $valuequestion = ValueQuestion::where('id', $request->questionid)->get();
        if($valuequestion != []) {
            $valuequestion = $valuequestion[0];
            $question = $valuequestion->question;
            $section = $question->section;
            //Looping for subsections
            do {
                $status = false;
                $section = $section->sectionable;
                if(get_class($section) == Test::class) {
                    $status = true;
                }
            } while($status == false);

            if($section->id == $request->session()->get('testidcreation')) {

                //Setting fields
                $fields = [
                    'singular' => [],
                    'personal' => [],
                ];
                for($i=0; $i<101; $i++) {
                    if(isset($request["checkboxsingle".$i])) {
                        $fields['singular'][] = $request["checkboxsingle".$i];
                    }
                }
                $i = 1;
                while(isset($request["checkboxpersonal".$i])) {
                    $fields['personal'][] = $request["checkboxpersonal".$i];
                    $i++;
                }

                $valuequestion->update([
                    'title' => $request->questiontitle,
                    'text' => $request->questiontext,
                    'fields' => $fields,
                ]);

                return response()->json([
                    'status' => 200
                ]);
            }
        }
        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Update the multiple question of the test tree.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateMultipleQuestion(Request $request): JsonResponse
    {

        $request->validate([
            'radiolenght' => ['required', 'integer'],
        ]);

        $rule = [
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
        ];
        if($request->radiolenght == 0) {
            $rule['radiosection'] = ['required'];
        } else {
            for($i=0; $i<$request->radiolenght; $i++) {
                $rule['radioinput'.$i] = ['required', 'string', 'max:255'];
            }
        }
        $request->validate($rule);

        $multiplequestion = MultipleQuestion::where('id', $request->questionid)->get();
        if($multiplequestion != []) {
            $multiplequestion = $multiplequestion[0];
            $question = $multiplequestion->question;
            $section = $question->section;
            //Looping for subsections
            do {
                $status = false;
                $section = $section->sectionable;
                if(get_class($section) == Test::class) {
                    $status = true;
                }
            } while($status == false);

            if($section->id == $request->session()->get('testidcreation')) {
                $fields = [];
                for($i=0; $i<$request->radiolenght; $i++) {
                    $fields[] = $request['radioinput'.$i];
                }

                $multiplequestion->update([
                    'title' => $request->questiontitle,
                    'text' => $request->questiontext,
                    'fields' => $fields,
                ]);

                return response()->json([
                    'status' => 200
                ]);
            }
        }
        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Update the open question of the test tree.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateOpenQuestion(Request $request): JsonResponse
    {

        $request->validate([
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
        ]);

        $openquestion = OpenQuestion::where('id', $request->questionid)->get();
        if($openquestion != []) {
            $openquestion = $openquestion[0];
            $question = $openquestion->question;
            $section = $question->section;
            //Looping for subsections
            do {
                $status = false;
                $section = $section->sectionable;
                if(get_class($section) == Test::class) {
                    $status = true;
                }
            } while($status == false);

            if($section->id == $request->session()->get('testidcreation')) {

                $openquestion->update([
                    'title' => $request->questiontitle,
                    'text' => $request->questiontext,
                ]);

                return response()->json([
                    'status' => 200
                ]);
            }
        }
        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Update the multiple selection question of the test tree.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateMultipleSelectionQuestion(Request $request): JsonResponse
    {
        $request->validate([
            'radiolenght' => ['required', 'integer'],
        ]);

        $rule = [
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
        ];

        //Ensure require value fields
        if($request->radiolenght == 0) {
            $rule['checkboxsection'] = ['required'];
        } else {
            for($i=1; $i<=$request->radiolenght; $i++) {
                $rule['checkbox'.$i] = ['required', 'string', 'max:255'];
            }
        }

        $request->validate($rule);

        $multipleselectionquestion = MultipleSelectionQuestion::where('id', $request->questionid)->get();
        if($multipleselectionquestion != []) {
            $multipleselectionquestion = $multipleselectionquestion[0];
            $question = $multipleselectionquestion->question;
            $section = $question->section;
            //Looping for subsections
            do {
                $status = false;
                $section = $section->sectionable;
                if(get_class($section) == Test::class) {
                    $status = true;
                }
            } while($status == false);

            if($section->id == $request->session()->get('testidcreation')) {

                //Setting fields
                $fields = [];
                $i = 1;
                while(isset($request["checkbox".$i])) {
                    $fields[] = $request["checkbox".$i];
                    $i++;
                }

                $multipleselectionquestion->update([
                    'title' => $request->questiontitle,
                    'text' => $request->questiontext,
                    'fields' => $fields,
                ]);

                return response()->json([
                    'status' => 200
                ]);
            }
        }
        return response()->json([
            'status' => 400
        ]);
    }

    /**
     * Update the image question of the test tree.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateImageQuestion(Request $request): JsonResponse
    {

        $request->validate([
            'radiolenght' => ['required', 'integer'],
        ]);

        $rule = [
            'questiontitle' => ['required', 'string', 'max:24'],
            'questiontext' => ['required', 'string', 'max:255'],
            'questionid' => ['required', 'integer'],
        ];
        if($request->radiolenght == 0) {
            $rule['imagefield'] = ['required'];
        } else {
            $pattern = '/^old-\d+$/';
            for($i=0; $i<$request->radiolenght; $i++) {
                if(!preg_match($pattern, $request['imageinput'.$i])) {
                    $rule['imageinput'.$i] = ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'];
                }
            }
        }

        $request->validate($rule);

        $imagequestion = ImageQuestion::where('id', $request->questionid)->get();
        if($imagequestion != []) {
            $imagequestion = $imagequestion[0];
            $question = $imagequestion->question;
            $section = $question->section;
            //Looping for subsections
            do {
                $status = false;
                $section = $section->sectionable;
                if(get_class($section) == Test::class) {
                    $status = true;
                }
            } while($status == false);

            if($section->id == $request->session()->get('testidcreation')) {
                $images = [];
                $old = [];

                //Saving Image on filesystem and path on db
                $test = Test::where('id', "=", $request->session()->get('testidcreation'))->get()[0];
                $pattern = '/^old-\d+$/';
                for($i=0; $i<$request->radiolenght; $i++) {
                    if(preg_match($pattern, $request['imageinput'.$i])) {
                        $idold = explode("-",$request['imageinput'.$i])[1];
                        $old[] = $idold;
                        $images[] = $imagequestion->images[$idold];
                    } else {
                        if($request->hasFile('imageinput'.$i)) {
                            $path = $request->file('imageinput'.$i)->store($test->name, 'test');
                            $filename = basename($path);
                            $images[] = [$path, $filename];
                        } else {
                            return response()->json([
                                'status' => 400
                            ]);
                        }
                    }

                }

                //Looping to delete old images
                $pointer = 0;
                for($i=0;$i<count($imagequestion->images); $i++) {
                    if($old[$pointer] == $i) {
                        $pointer++;
                    } else {
                        Storage::disk('test')->delete($imagequestion->images[$i][0]);
                    }
                }

                $imagequestion->update([
                    'title' => $request->questiontitle,
                    'text' => $request->questiontext,
                    'images' => $images,
                ]);

                return response()->json([
                    'status' => 200
                ]);
            }
        }
        return response()->json([
            'status' => 400
        ]);
    }
}
