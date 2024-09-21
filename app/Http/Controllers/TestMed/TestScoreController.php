<?php

namespace App\Http\Controllers\TestMed;

use App\Http\Controllers\Controller;
use App\Models\Questions\ImageQuestion;
use App\Models\Questions\MultipleQuestion;
use App\Models\Questions\MultipleSelectionQuestion;
use App\Models\Questions\OpenQuestion;
use App\Models\Questions\Question;
use App\Models\Questions\ValueQuestion;
use App\Models\Scores\OperationOnScore;
use App\Models\Section;
use App\Models\Test;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;


class TestScoreController extends Controller
{
    /**
     * Show the page for visualizing the score page.
     */
    public function create(Request $request): View
    {
        if($request->session()->get('status') == 'exit-status') {
            $status = $request->session()->get('status');
            return view('testmed.creationcomponents.testscore', ['status' => $status]);
        } elseif($request->status == 'exit-status') {
            return view('testmed.creationcomponents.testscore', ['status' => $request->status]);
        } else {
            if($request->session()->get('status') == 'exit-status') {
                $request->session()->forget(('status'));
            }
            if($request->session()->get('error')) {
                $error = $request->session()->get('error');
                $request->session()->forget('error');
                return view('testmed.creationcomponents.testscore', ['error' => $error]);
            } else {
                return view('testmed.creationcomponents.testscore');
            }
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
            $progressive = explode("-", $request->session()->get('progressive'));
            for($i=0; $i<$count; $i++) {
                $res = $this->createSecionNode($sections[$i], $progressive);
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

    private function createSecionNode(Section $section, array $progressive): Array {
        //DA CAMBIARE logica con non usabile da fare fatto
        //Recoursive code that check if there is at least a question non open
        $recoursive = function($section) use (&$recoursive) {
            if($section->sections->count() != 0) {
                //Subsections
                for($i=0; $i<$section->sections->count(); $i++) {
                    $res = $recoursive($section->sections[$i]);
                    if($res) {
                        return true;
                    }
                }
                return false;
            } else {
                //Questions
                for($i=0; $i<$section->questions->count(); $i++) {
                    if(get_class($section->questions[$i]->questionable) != OpenQuestion::class) {
                        return true;
                    }
                }
                return false;
            }
        };
        $res = $recoursive($section);

        $status = 0;
        if($res) {

            //Loop to generate progressive of the section
            $sectionprogressive = [];
            $sectionprogressive[] = $section->progressive;
            $parent = $section;
            while (get_class($parent->sectionable) != Test::class) {
                $parent = $parent->sectionable;
                array_unshift($sectionprogressive, $parent->progressive);
            }

            //Check if question is done or not
            $check = false;
            if($progressive[0] != 'test') {
                $loop = min(count($sectionprogressive), count($progressive));
                for($i=0; $i<$loop; $i++) {
                    if($i != $loop-1) {
                        if($sectionprogressive[$i] < $progressive[$i]) {
                            $check = true;
                            break;
                        } elseif($sectionprogressive[$i] > $progressive[$i]) {
                            break;
                        }
                    } else {
                        if(count($sectionprogressive) > count($progressive)) {
                            if($sectionprogressive[$i] <= $progressive[$i]) {
                                $check = true;
                                break;
                            } elseif($sectionprogressive[$i] > $progressive[$i]) {
                                break;
                            }
                        } else {
                            if($sectionprogressive[$i] < $progressive[$i]) {
                                $check = true;
                                break;
                            } elseif($sectionprogressive[$i] >= $progressive[$i]) {
                                break;
                            }
                        }
                    }
                }
            } else {
                $check = true;
            }

            if($check) {
                $status = 0;
            } else {
                $status = 1;
            }

        } else {
            $status = 2;
        }

        $array = [
            "section".$section->progressive => [
                'id' => $section->id,
                'name' => $section->name,
                'status' => $status,
            ]
        ];

        $subsections = $section->sections;
        if($subsections->count() != 0) {
            for($i=0; $i<$subsections->count(); $i++) {
                $subsesction = $subsections[$i];
                $res = $this->createSecionNode($subsesction, $progressive);

                $array["section".$section->progressive]['sections']['section'.$subsesction->progressive] = $res['section'.$subsesction->progressive];
            }
            return $array;

        } else {
            $questions = $section->questions;
            if($questions->count() != 0) {
                for($i=0; $i<$questions->count(); $i++) {
                    $question = $questions[$i];

                    if($question->questionable != null) {
                        if(get_class($question->questionable) == OpenQuestion::class) {
                            $status = 2;
                        } else {
                            //Loop to generate progressive of the question
                            $questionprogressive = [];
                            $questionprogressive[] = $question->progressive;
                            $parent = $question->section;
                            while (get_class($parent) != Test::class) {
                                array_unshift($questionprogressive, $parent->progressive);
                                $parent = $parent->sectionable;
                            }

                            //Check if question is done or not
                            $check = false;
                            if($progressive[0] != 'test') {
                                $loop = min(count($questionprogressive), count($progressive));
                                for($n=0; $n<$loop; $n++) {
                                    if($n != $loop-1) {
                                        if($questionprogressive[$n] < $progressive[$n]) {
                                            $check = true;
                                            break;
                                        } elseif($questionprogressive[$n] > $progressive[$n]) {
                                            break;
                                        }
                                    } else {
                                        if(count($questionprogressive) > count($progressive)) {
                                            if($questionprogressive[$n] <= $progressive[$n]) {
                                                $check = true;
                                                break;
                                            } elseif($questionprogressive[$n] > $progressive[$n]) {
                                                break;
                                            }
                                        } else {
                                            if($questionprogressive[$n] < $progressive[$n]) {
                                                $check = true;
                                                break;
                                            } elseif($questionprogressive[$n] >= $progressive[$n]) {
                                                break;
                                            }
                                        }
                                    }
                                }
                            } else {
                                $check = true;
                            }

                            if($check) {
                                $status = 0;
                            } else {
                                $status = 1;
                            }
                        }

                        $array["section".$section->progressive]['questions']['question'.$question->progressive] = [
                            'id' => $question->id,
                            'title' => $question->questionable->title,
                            'status' => $status,
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
     * Show the page for visualizing the node score view.
     */
    public function createNodeScore(Request $request): View
    {
        //Retriving data from session
        //$request->session()->put('progressive', '3');
        $progressive = $request->session()->get('progressive');
        $progressive = explode('-', $progressive);
        $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];

        $parent = $test;
        if($progressive[0] != 'test') {
            for($i=0; $i<count($progressive); $i++) {
                if(get_class($parent) == Test::class) {
                    $element = $parent->sections[$progressive[$i]-1];
                } elseif(get_class($parent) == Section::class) {
                    if($parent->sections->count() != 0) {
                        $element = $parent->sections[$progressive[$i]-1];
                    } else {
                        $element = $parent->questions[$progressive[$i]-1];
                    }
                }
                $parent = $element;
            }
        }

        //Sectionlist
        $sectionlist = [];
        $rec = function($section) use (&$rec, &$sectionlist) {
            if($section->sections->count() != 0) {
                for($i=0; $i<$section->sections->count(); $i++) {
                    $rec($section->sections[$i]);
                }
            }
            $sectionlist[] = [$section->id, $section->name];
        };
        for($i=0; $i<$test->sections->count(); $i++) {
            $rec($test->sections[$i]);
        }
        //Generating array of parents
        $parents = [];
        $recprogressive = function($section) use (&$recprogressive, &$parents) {
            if($section->sections->count() != 0) {
                for($i=0; $i<$section->sections->count(); $i++) {
                    $recprogressive($section->sections[$i]);
                }
                $parents[] = $section->id;
            } else {
                $parents[] = $section->id;
            }
        };
        if(get_class($parent) == Section::class) {
            if(get_class($parent->sectionable) == Test::class) {
                $previoussection = $parent->sectionable->sections()->where('progressive', '<', $parent->progressive)->get();
                for($i=0; $i<$previoussection->count(); $i++) {
                    $recprogressive($previoussection[$i]);
                }
                $parents[] = $parent->id;
                //Array diff
                for($x=0; $x<count($parents); $x++) {
                    for($i=0; $i<count($sectionlist); $i++) {
                        if($sectionlist[$i][0] == $parents[$x]) {
                            array_splice($sectionlist,$i,1);
                            break;
                        }
                    }
                }
            } else {
                $section = $parent;
                do{
                    $previoussection = $section->sectionable->sections()->where('progressive', '<', $parent->progressive)->get();
                    for($i=0; $i<$previoussection->count(); $i++) {
                        $recprogressive($previoussection[$i]);
                    }
                    $parents[] = $section->id;
                    //Array diff
                    for($x=0; $x<count($parents); $x++) {
                        for($i=0; $i<count($sectionlist); $i++) {
                            if($sectionlist[$i][0] == $parents[$x]) {
                                array_splice($sectionlist,$i,1);
                                break;
                            }
                        }
                    }
                    $parents = [];
                    $section = $section->sectionable;
                } while(get_class($section) != Test::class);
            }

        } elseif(get_class($parent) == Test::class) {

        } else {
            $section = $parent->section;
            do{
                $previoussection = $section->sectionable->sections()->where('progressive', '<', $section->progressive)->get();
                for($i=0; $i<$previoussection->count(); $i++) {
                    $recprogressive($previoussection[$i]);
                }
                $parents[] = $section->id;
                //Array diff
                for($x=0; $x<count($parents); $x++) {
                    for($i=0; $i<count($sectionlist); $i++) {
                        if($sectionlist[$i][0] == $parents[$x]) {
                            array_splice($sectionlist,$i,1);
                            break;
                        }
                    }
                }
                $parents = [];
                $section = $section->sectionable;
            } while(get_class($section) != Test::class);
        }

        if(get_class($parent) == Section::class) {
            //Checking if at least one subelement has score logic
            $check = false;
            if($parent->sections->count() != 0) {
                for($i=0; $i<$parent->sections->count(); $i++) {
                    if($parent->sections[$i]->operationOnScore) {
                        $check = true;
                        break;
                    }
                }
            } else {
                for($i=0; $i<$parent->questions->count(); $i++) {
                    if($parent->questions[$i]->questionable->scores) {
                        $check = true;
                        break;
                    }
                }
            }
            return view('testmed.creationcomponents.scoreitempages.sectiondetail', [
                'section' => $parent,
                'enabler' => $check,
                'sectionlist' => $sectionlist,
            ]);
        } elseif(get_class($parent) == Test::class) {
            //Checking if at least one subelement has score logic
            $check = false;
            if($parent->sections->count() != 0) {
                for($i=0; $i<$parent->sections->count(); $i++) {
                    if($parent->sections[$i]->operationOnScore) {
                        $check = true;
                        break;
                    }
                }
            }
            return view('testmed.creationcomponents.scoreitempages.testdetail', [
                'test' => $parent,
                'enabler' => $check,
            ]);
        } else {
            $questionrelated = $parent->questionable;
            if(get_class($questionrelated) == MultipleQuestion::class) {
                return view('testmed.creationcomponents.scoreitempages.multiplequestiondetail', [
                    'question' => $questionrelated,
                    'sectionlist' => $sectionlist,
                ]);
            } elseif(get_class($questionrelated) == ValueQuestion::class) {
                return view('testmed.creationcomponents.scoreitempages.valuequestiondetail', [
                    'question' => $questionrelated,
                    'sectionlist' => $sectionlist,
                ]);
            } elseif(get_class($questionrelated) == MultipleSelectionQuestion::class) {
                return view('testmed.creationcomponents.scoreitempages.multipleselectionquestiondetail', [
                    'question' => $questionrelated,
                    'sectionlist' => $sectionlist,
                ]);
            } elseif(get_class($questionrelated) == ImageQuestion::class) {
                $images = [];
                $files = $questionrelated->images;
                for($i=0; $i<count($files); $i++) {
                    $imageContent = Storage::disk('test')->get($files[$i][0]);
                    $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                    $images[] = $base64Image;
                }
                return view('testmed.creationcomponents.scoreitempages.imagequestion', [
                    'question' => $questionrelated,
                    'images' => $images,
                    'sectionlist' => $sectionlist,
                ]);
            }
        }
    }

    /**
     * Show the page for visualizing the score module.
     */
    public function createScoreItem(Request $request): View
    {
        $request->validate([
            'type' => ['required', 'integer', 'in:1,2,3']
        ]);

        if($request->type == 1) {
            return view('testmed.creationcomponents.scoreitempages.scoreitems.formulaitem');
        } elseif($request->type == 2) {
            return view('testmed.creationcomponents.scoreitempages.scoreitems.conversiontableitem');
        } else {
            return view('testmed.creationcomponents.scoreitempages.scoreitems.formulaconversionitem');
        }
    }

    /**
     * Show the page for visualizing the update score module.
     */
    public function createUpdateScore(Request $request)
    {
        $request->validate([
            'update' => ['required', 'regex:/^(section-\d+|question-\d+)$/'],
        ]);

        //Sectionlist
        $sectionlist = [];
        $rec = function($section) use (&$rec, &$sectionlist) {
            if($section->sections->count() != 0) {
                for($i=0; $i<$section->sections->count(); $i++) {
                    $rec($section->sections[$i]);
                }
            }
            $sectionlist[] = [$section->id, $section->name];
        };
        $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];
        for($i=0; $i<$test->sections->count(); $i++) {
            $rec($test->sections[$i]);
        }
        //Generating array of parents
        $parents = [];
        $recprogressive = function($section) use (&$recprogressive, &$parents) {
            if($section->sections->count() != 0) {
                for($i=0; $i<$section->sections->count(); $i++) {
                    $recprogressive($section->sections[$i]);
                }
                $parents[] = $section->id;
            } else {
                $parents[] = $section->id;
            }
        };

        if(preg_match('/^section-\d+$/', $request->update)) {

            $section = Section::where('id', explode("-", $request->update)[1])->get();
            if($section->count() != 0) {
                $section = $section[0];

                //Array jump
                if(get_class($section->sectionable) == Test::class) {
                    $previoussection = $section->sectionable->sections()->where('progressive', '<', $section->progressive)->get();
                    for($i=0; $i<$previoussection->count(); $i++) {
                        $recprogressive($previoussection[$i]);
                    }
                    $parents[] = $section->id;
                    //Array diff
                    for($x=0; $x<count($parents); $x++) {
                        for($i=0; $i<count($sectionlist); $i++) {
                            if($sectionlist[$i][0] == $parents[$x]) {
                                array_splice($sectionlist,$i,1);
                                break;
                            }
                        }
                    }
                } else {
                    $parents[] = $section->id;
                    $cicle = $section;
                    do{
                        $previoussection = $cicle->sectionable->sections()->where('progressive', '<', $cicle->progressive)->get();
                        for($i=0; $i<$previoussection->count(); $i++) {
                            $recprogressive($previoussection[$i]);
                        }
                        $parents[] = $cicle->id;
                        //Array diff
                        for($x=0; $x<count($parents); $x++) {
                            for($i=0; $i<count($sectionlist); $i++) {
                                if($sectionlist[$i][0] == $parents[$x]) {
                                    array_splice($sectionlist,$i,1);
                                    break;
                                }
                            }
                        }
                        $cicle = $cicle->sectionable;
                    } while(get_class($cicle) != Test::class);
                }

                //Loop to generate progressive of the section
                $elementprogressive[] = $section->progressive;
                $parent = $section;
                while (get_class($parent->sectionable) != Test::class) {
                    $parent = $parent->sectionable;
                    array_unshift($elementprogressive, $parent->progressive);
                }

                //Check if the section is updateable
                $progressive = $request->session()->get('progressive');
                $progressive = explode("-", $progressive);
                $check = false;
                if($progressive[0] != 'test') {
                    $loop = min(count($elementprogressive), count($progressive));
                    for($i=0; $i<$loop; $i++) {
                        if($i != $loop-1) {
                            if($elementprogressive[$i] < $progressive[$i]) {
                                $check = true;
                                break;
                            } elseif($elementprogressive[$i] > $progressive[$i]) {
                                break;
                            }
                        } else {
                            if(count($elementprogressive) > count($progressive)) {
                                if($elementprogressive[$i] <= $progressive[$i]) {
                                    $check = true;
                                    break;
                                } elseif($elementprogressive[$i] > $progressive[$i]) {
                                    break;
                                }
                            } else {
                                if($elementprogressive[$i] < $progressive[$i]) {
                                    $check = true;
                                    break;
                                } elseif($elementprogressive[$i] >= $progressive[$i]) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $check = true;
                }

                if($check) {
                    //Checking if at least one subelement has score logic
                    $check = false;
                    if($section->sections->count() != 0) {
                        for($i=0; $i<$section->sections->count(); $i++) {
                            if($section->sections[$i]->operationOnScore) {
                                $check = true;
                                break;
                            }
                        }
                    } else {
                        for($i=0; $i<$section->questions->count(); $i++) {
                            if($section->questions[$i]->questionable->scores) {
                                $check = true;
                                break;
                            }
                        }
                    }
                    if($check) {
                        if($section->operationOnScore) {
                            $type = "";
                            if($section->operationOnScore->formula) {
                                $type .= 'formula';
                            }
                            if($section->operationOnScore->conversion) {
                                $type .= 'conversion';
                            }
                            $jump = 0;
                            if($section->jump != null) {
                                $jump = 1;
                            }
                            return view('testmed.creationcomponents.scoreitempages.sectiondetail', [
                                'section' => $section,
                                'enabler' => 1,
                                'update' => 1,
                                'data' => 1,
                                'scoretype' => $type,
                                'formula' => $section->operationOnScore->formula,
                                'conversion' => $section->operationOnScore->conversion,
                                'jump' => $jump,
                                'sectionlist' => $sectionlist,
                            ]);
                        } else {
                            return view('testmed.creationcomponents.scoreitempages.sectiondetail', [
                                'section' => $section,
                                'enabler' => 1,
                                'update' => 1,
                                'sectionlist' => $sectionlist,
                            ]);
                        }
                    } else {
                        //Check if at least one question is't open
                        $rec = function($section) use (&$rec) {
                            if($section->sections->count() != 0) {
                                for($i=0; $i<$section->sections->count(); $i++) {
                                    if(!$rec($section->sections[$i])) {
                                        return false;
                                    }
                                }
                                return true;
                            } else {
                                for($i=0; $i<$section->questions->count(); $i++) {
                                    if(get_class($section->questions[$i]->questionable) == OpenQuestion::class) {
                                        return false;
                                    }
                                }
                                return true;
                            }
                        };
                        if($rec($section)) {
                            return view('testmed.creationcomponents.scoreitempages.sectiondetail', [
                                'section' => $section,
                                'enabler' => 0,
                                'update' => 1,
                            ]);
                        }
                    }
                }
            }

        } elseif(preg_match('/^question-\d+$/', $request->update)) {
            //Question
            $question = Question::where('id', explode("-", $request->update)[1])->get();
            if($question->count() != 0) {
                $question = $question[0];

                //Array jump
                $section = $question->section;
                do{
                    $previoussection = $section->sectionable->sections()->where('progressive', '<', $section->progressive)->get();
                    for($i=0; $i<$previoussection->count(); $i++) {
                        $recprogressive($previoussection[$i]);
                    }
                    $parents[] = $section->id;
                    //Array diff
                    for($x=0; $x<count($parents); $x++) {
                        for($i=0; $i<count($sectionlist); $i++) {
                            if($sectionlist[$i][0] == $parents[$x]) {
                                array_splice($sectionlist,$i,1);
                                break;
                            }
                        }
                    }
                    $parents = [];
                    $section = $section->sectionable;
                } while(get_class($section) != Test::class);

                //Loop to generate progressive of the question
                $elementprogressive[] = $question->progressive;
                $parent = $question->section;
                while (get_class($parent) != Test::class) {
                    array_unshift($elementprogressive, $parent->progressive);
                    $parent = $parent->sectionable;
                }

                //Check if the question is updateable
                $progressive = $request->session()->get('progressive');
                $progressive = explode("-", $progressive);
                $check = false;
                if($progressive[0] != 'test') {
                    $loop = min(count($elementprogressive), count($progressive));
                    for($i=0; $i<$loop; $i++) {
                        if($i != $loop-1) {
                            if($elementprogressive[$i] < $progressive[$i]) {
                                $check = true;
                                break;
                            } elseif($elementprogressive[$i] > $progressive[$i]) {
                                break;
                            }
                        } else {
                            if(count($elementprogressive) > count($progressive)) {
                                if($elementprogressive[$i] <= $progressive[$i]) {
                                    $check = true;
                                    break;
                                } elseif($elementprogressive[$i] > $progressive[$i]) {
                                    break;
                                }
                            } else {
                                if($elementprogressive[$i] < $progressive[$i]) {
                                    $check = true;
                                    break;
                                } elseif($elementprogressive[$i] >= $progressive[$i]) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $check = true;
                }

                if($check) {
                    //Check for open question
                    if(get_class($question->questionable) != OpenQuestion::class) {
                        //Valid question
                        $questionrelated = $question->questionable;
                        $data = 0;
                        if($questionrelated->scores) {
                            $data = 1;
                        }
                        $jump = 0;
                        if($questionrelated->jump != null) {
                            $jump = 1;
                        }

                        if(get_class($questionrelated) == MultipleQuestion::class) {
                            return view('testmed.creationcomponents.scoreitempages.multiplequestiondetail', [
                                'question' => $questionrelated,
                                'update' => 1,
                                'data' => $data,
                                'scores' => $questionrelated->scores,
                                'jump' => $jump,
                                'sectionlist' => $sectionlist,
                            ]);
                        } elseif(get_class($questionrelated) == ValueQuestion::class) {
                            return view('testmed.creationcomponents.scoreitempages.valuequestiondetail', [
                                'question' => $questionrelated,
                                'update' => 1,
                                'data' => $data,
                                'jump' => $jump,
                                'sectionlist' => $sectionlist,
                            ]);
                        } elseif(get_class($questionrelated) == MultipleSelectionQuestion::class) {
                            return view('testmed.creationcomponents.scoreitempages.multipleselectionquestiondetail', [
                                'question' => $questionrelated,
                                'update' => 1,
                                'data' => $data,
                                'scores' => $questionrelated->scores,
                                'jump' => $jump,
                                'sectionlist' => $sectionlist,
                            ]);
                        } elseif(get_class($questionrelated) == ImageQuestion::class) {
                            $images = [];
                            $files = $questionrelated->images;
                            for($i=0; $i<count($files); $i++) {
                                $imageContent = Storage::disk('test')->get($files[$i][0]);
                                $base64Image = 'data:image/jpeg;base64,' . base64_encode($imageContent);
                                $images[] = $base64Image;
                            }
                            return view('testmed.creationcomponents.scoreitempages.imagequestion', [
                                'question' => $questionrelated,
                                'images' => $images,
                                'update' => 1,
                                'data' => $data,
                                'scores' => $questionrelated->scores,
                                'jump' => $jump,
                                'sectionlist' => $sectionlist,
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json([
            'status' => 400,
        ]);
    }

    /**
     * Store a newly created score resource in storage.
     */
    public function storeScore(Request $request): JsonResponse
    {
        $request->validate([
            'enabler' => ['integer', 'in:1'],
            'jump' => ['integer', 'in:1'],
        ]);

        //Retriving data from session
        $progressive = $request->session()->get('progressive');
        $progressive = explode('-', $progressive);
        $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];

        $parent = $test;
        if($progressive[0] != 'test') {
            for($i=0; $i<count($progressive); $i++) {
                if(get_class($parent) == Test::class) {
                    $element = $parent->sections[$progressive[$i]-1];
                } elseif(get_class($parent) == Section::class) {
                    if($parent->sections->count() != 0) {
                        $element = $parent->sections[$progressive[$i]-1];
                    } else {
                        $element = $parent->questions[$progressive[$i]-1];
                    }
                }
                $parent = $element;
            }
        }
        $element = $parent;

        if($request->enabler) {

            if(get_class($element) == Question::class) {
                //Question Pages
                if(get_class($element->questionable) == OpenQuestion::class) {
                    return response()->json([
                        'status' => 400,
                    ]);
                }

                //validating response score
                if(get_class($element->questionable) != ValueQuestion::class) {
                    $rule = [];
                    if(get_class($element->questionable) == ImageQuestion::class) {
                        for($i=0; $i<count($element->questionable->images); $i++) {
                            $rule['selectvalue'.$i] = ['required', 'integer', 'min:0'];
                        }
                    } else {
                        for($i=0; $i<count($element->questionable->fields); $i++) {
                            $rule['selectvalue'.$i] = ['required', 'integer', 'min:0'];
                        }
                    }

                    $request->validate($rule);
                }

                //Saving values of question
                $points = [];
                if(get_class($element->questionable) == ValueQuestion::class) {
                    $points = array_merge($points, $element->questionable->fields['singular']);
                    $points = array_merge($points, $element->questionable->fields['personal']);
                } else {
                    if(get_class($element->questionable) == ImageQuestion::class) {
                        for($i=0; $i<count($element->questionable->images); $i++) {
                            $points[] = $request['selectvalue'.$i];
                        }
                    } else {
                        for($i=0; $i<count($element->questionable->fields); $i++) {
                            $points[] = $request['selectvalue'.$i];
                        }
                    }
                }
                $element->questionable->update([
                    'scores' => $points,
                ]);

            } else {
                //Score Pages
                //Checking if at least one subelement has score logic
                $check = false;
                if(get_class($element) == Test::class) {
                    if($element->sections->count() != 0) {
                        for($i=0; $i<$element->sections->count(); $i++) {
                            if($element->sections[$i]->operationOnScore) {
                                $check = true;
                                break;
                            }
                        }
                    }
                } else {
                    if($element->sections->count() != 0) {
                        for($i=0; $i<$element->sections->count(); $i++) {
                            if($element->sections[$i]->operationOnScore) {
                                $check = true;
                                break;
                            }
                        }
                    } else {
                        for($i=0; $i<$element->questions->count(); $i++) {
                            if($element->questions[$i]->questionable->scores) {
                                $check = true;
                                break;
                            }
                        }
                    }
                }

                if($check) {
                    $request->validate([
                        'scoreoperation' => ['required', 'integer', 'in:1,2,3']
                    ]);
                    //Score operation
                    if($request->scoreoperation == 1) {
                        //Validation logic for formula field
                        if(get_class($element) == Test::class) {
                            $regex = 'regex:/^(S\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                        } else {
                            if($element->sections->count() == 0) {
                                $regex = 'regex:/^(Q\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                            } else {
                                $regex = 'regex:/^(S\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                            }
                        }
                        $request->validate([
                            'formula' => [
                                'required',
                                $regex,
                                //Check for unbalanced parentheses
                                function ($attribute, $value, $fail) {
                                    $open = 0;
                                    foreach (str_split($value) as $char) {
                                        if ($char == '(') {
                                            $open++;
                                        } elseif ($char == ')') {
                                            $open--;
                                            if ($open < 0) {
                                                $fail('Unbalanced parentheses.');
                                            }
                                        }
                                    }

                                    if ($open != 0) {
                                        $fail('Unbalanced parentheses.');
                                    }
                                },
                                function ($attribute, $value, $fail) use ($element) {
                                    if(get_class($element) == Test::class) {
                                        $letter = 'S';
                                    } else {
                                        if($element->sections->count() == 0) {
                                            $letter = 'Q';
                                        } else {
                                            $letter = 'S';
                                        }
                                    }
                                    for ($i = 0; $i < strlen($value); $i++) {
                                        // Current character
                                        $char = $value[$i];
                                        if ($char == $letter) {
                                            $selector = $char.$value[$i + 1];
                                            if($selector[0] == 'Q') {
                                                if($element->questions->count() < $selector[1] || $selector[1] == 0 || !$element->questions[$selector[1]-1]->questionable->scores) {
                                                    $fail('Wrong question identifier');
                                                }
                                            } elseif($selector[0] == 'S') {
                                                if($element->sections->count() < $selector[1] || $selector[1] == 0 || !$element->sections[$selector[1]-1]->operationOnScore) {
                                                    $fail('Wrong section identifier');
                                                }
                                            }
                                        }
                                    }
                                },
                            ],
                        ]);

                        //Saving operation on score
                        if(get_class($element) == Test::class) {
                            $element->operationOnScore->update([
                                'scorable_id' => $element->id,
                                'scorable_type' => get_class($element),
                                'formula' => $request->formula,
                            ]);
                        } else {
                            OperationOnScore::create([
                                'scorable_id' => $element->id,
                                'scorable_type' => get_class($element),
                                'formula' => $request->formula,
                            ]);
                        }

                    } elseif($request->scoreoperation == 2) {
                        $request->validate([
                            'lenght' => ['required', 'integer', 'min:0'],
                        ]);
                        $rule = [];
                        for($i=1; $i<=$request->lenght; $i++) {
                            $rule['value-'.$i] = ['required', 'integer', 'min:0'];
                            $rule['converted-'.$i] = ['required', 'integer', 'min:0'];
                        }
                        $request->validate($rule);
                        //Validation for value field that must be not equal
                        $errors = [];
                        $value = [];
                        for($i=1; $i<=$request->lenght; $i++) {
                            if(!in_array($request['value-'.$i], $value)) {
                                $value[] = $request['value-'.$i];
                            } else {
                                $errors[] = $i;
                            }
                        }
                        $rule = [];
                        for($i=0; $i<count($errors); $i++) {
                            $rule['value-'.$errors[$i]] = [
                                function ($attribute, $value, $fail) {
                                    $fail("Value fields cannot be equal");
                                },
                            ];
                        }
                        $request->validate($rule);

                        //Creating array of conversion
                        $conversion = [];
                        for($i=1; $i<=$request->lenght; $i++) {
                            $conversion[$request['value-'.$i]] = $request['converted-'.$i];
                        }

                        //Saving operation on score
                        if(get_class($element) == Test::class) {
                            $element->operationOnScore->update([
                                'scorable_id' => $element->id,
                                'scorable_type' => get_class($element),
                                'conversion' => $conversion,
                            ]);
                        } else {
                            OperationOnScore::create([
                                'scorable_id' => $element->id,
                                'scorable_type' => get_class($element),
                                'conversion' => $conversion,
                            ]);
                        }

                    } elseif($request->scoreoperation == 3) {
                        //Validation logic for formula field
                        if(get_class($element) == Test::class) {
                            $regex = 'regex:/^(S\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                        } else {
                            if($element->sections->count() == 0) {
                                $regex = 'regex:/^(Q\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                            } else {
                                $regex = 'regex:/^(S\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                            }
                        }
                        $request->validate([
                            'formula' => [
                                'required',
                                $regex,
                                //Check for unbalanced parentheses
                                function ($attribute, $value, $fail) {
                                    $open = 0;
                                    foreach (str_split($value) as $char) {
                                        if ($char == '(') {
                                            $open++;
                                        } elseif ($char == ')') {
                                            $open--;
                                            if ($open < 0) {
                                                $fail('Unbalanced parentheses.');
                                            }
                                        }
                                    }

                                    if ($open != 0) {
                                        $fail('Unbalanced parentheses.');
                                    }
                                },
                                function ($attribute, $value, $fail) use ($element) {
                                    if(get_class($element) == Test::class) {
                                        $letter = 'S';
                                    } else {
                                        if($element->sections->count() == 0) {
                                            $letter = 'Q';
                                        } else {
                                            $letter = 'S';
                                        }
                                    }
                                    for ($i = 0; $i < strlen($value); $i++) {
                                        // Current character
                                        $char = $value[$i];
                                        if ($char == $letter) {
                                            $selector = $char.$value[$i + 1];
                                            if($selector[0] == 'Q') {
                                                if($element->questions->count() < $selector[1] || $selector[1] == 0 || !$element->questions[$selector[1]-1]->questionable->scores) {
                                                    $fail('Wrong question identifier');
                                                }
                                            } elseif($selector[0] == 'S') {
                                                if($element->sections->count() < $selector[1] || $selector[1] == 0 || !$element->sections[$selector[1]-1]->operationOnScore) {
                                                    $fail('Wrong section identifier');
                                                }
                                            }
                                        }
                                    }
                                },
                            ],
                        ]);

                        $request->validate([
                            'lenght' => ['required', 'integer', 'min:0'],
                        ]);
                        $rule = [];
                        for($i=1; $i<=$request->lenght; $i++) {
                            $rule['value-'.$i] = ['required', 'integer', 'min:0'];
                            $rule['converted-'.$i] = ['required', 'integer', 'min:0'];
                        }
                        $request->validate($rule);
                        //Validation for value field that must be not equal
                        $errors = [];
                        $value = [];
                        for($i=1; $i<=$request->lenght; $i++) {
                            if(!in_array($request['value-'.$i], $value)) {
                                $value[] = $request['value-'.$i];
                            } else {
                                $errors[] = $i;
                            }
                        }
                        $rule = [];
                        for($i=0; $i<count($errors); $i++) {
                            $rule['value-'.$errors[$i]] = [
                                function ($attribute, $value, $fail) {
                                    $fail("Value fields cannot be equal");
                                },
                            ];
                        }
                        $request->validate($rule);

                        //Creating array of conversion
                        $conversion = [];
                        for($i=1; $i<=$request->lenght; $i++) {
                            $conversion[$request['value-'.$i]] = $request['converted-'.$i];
                        }

                        //Saving operation on score
                        if(get_class($element) == Test::class) {
                            $element->operationOnScore->update([
                                'scorable_id' => $element->id,
                                'scorable_type' => get_class($element),
                                'formula' => $request->formula,
                                'conversion' => $conversion,
                            ]);
                        } else {
                            OperationOnScore::create([
                                'scorable_id' => $element->id,
                                'scorable_type' => get_class($element),
                                'formula' => $request->formula,
                                'conversion' => $conversion,
                            ]);
                        }
                    }


                }
            }

        }

        if($request->jump) {
            if(get_class($element) == Question::class) {
                //Avoiding open question
                if(get_class($element->questionable) == OpenQuestion::class) {
                    return response()->json([
                        'status' => 400,
                    ]);
                }

                //Validating response jump
                $rangelist = [];
                if(get_class($element->questionable) == MultipleQuestion::class || get_class($element->questionable) == ImageQuestion::class) {
                    $rule = [];
                    if(get_class($element->questionable) == ImageQuestion::class) {
                        for($i=0; $i<count($element->questionable->images); $i++) {
                            $rule['jumpselect'.$i] = ['required',
                            'integer',
                            'min:0',
                            function($attribute, $value, $fail) use ($request, $element) {
                                $section = Section::where('id', $value)->get();
                                if($section->count() != 0) {
                                    $section = $section[0];
                                    $cicle = $section;
                                    while(get_class($cicle) != Test::class) {
                                        $cicle = $section->sectionable;
                                    }
                                    if($cicle->id != $request->session()->get('testidcreation')) {
                                        $fail('Invalid Section');
                                    } else {
                                        $parent = $element->section;
                                        while(get_class($parent) != Test::class) {
                                            if($parent->id == $section->id) {
                                                $fail("No parent section");
                                                break;
                                            }
                                            $parent = $parent->sectionable;
                                        }
                                    }

                                } else {
                                    $fail('Invalid Section');
                                }
                            },
                        ];
                        }
                    } else {
                        for($i=0; $i<count($element->questionable->fields); $i++) {
                            $rule['jumpselect'.$i] = [
                                'required',
                                'integer',
                                'min:0',
                                function($attribute, $value, $fail) use ($request, $element) {
                                    $section = Section::where('id', $value)->get();
                                    if($section->count() != 0) {
                                        $section = $section[0];
                                        $cicle = $section;
                                        while(get_class($cicle) != Test::class) {
                                            $cicle = $cicle->sectionable;
                                        }
                                        if($cicle->id != $request->session()->get('testidcreation')) {
                                            $fail('Invalid Section');
                                        } else {
                                            $parent = $element->section;
                                            while(get_class($parent) != Test::class) {
                                                if($parent->id == $section->id) {
                                                    $fail("No parent section");
                                                    break;
                                                }
                                                $parent = $parent->sectionable;
                                            }
                                        }
                                    } else {
                                        $fail('Invalid Section');
                                    }
                                },
                            ];
                        }
                    }
                    $request->validate($rule);
                } else {
                    if($request->enabler) {
                        $request->validate([
                            'jumplenght' => ['required', 'integer', 'min:1'],
                        ]);

                        for($i=1; $i<=$request->jumplenght; $i++) {
                            $request->validate([
                                'jumpinterval'.$i => [
                                    'required',
                                    'integer',
                                    'min:0',
                                    function($attribute, $value, $fail) use ($request, $element) {
                                        $section = Section::where('id', $value)->get();
                                        if($section->count() != 0) {
                                            $section = $section[0];
                                            $cicle = $section;
                                            while(get_class($cicle) != Test::class) {
                                                $cicle = $cicle->sectionable;
                                            }
                                            if($cicle->id != $request->session()->get('testidcreation')) {
                                                $fail('Invalid Section');
                                            } else {
                                                $parent = $element->section;
                                                while(get_class($parent) != Test::class) {
                                                    if($parent->id == $section->id) {
                                                        $fail("No parent section");
                                                        break;
                                                    }
                                                    $parent = $parent->sectionable;
                                                }
                                            }
                                        } else {
                                            $fail('Invalid Section');
                                        }
                                    },
                                ],
                                'from-'.$i => [
                                    'required',
                                    'integer',
                                    'min:0',
                                    function($attribute, $value, $fail) use (&$rangelist) {
                                        $check = true;
                                        for($i=0; $i<count($rangelist); $i++) {
                                            if($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) {
                                                $fail("Ranges cannot overap");
                                                $check = false;
                                                break;
                                            }
                                        }
                                        if($check) {
                                            $rangelist[] = [$value];
                                        }
                                    }
                                ],
                                'to-'.$i => [
                                    'required',
                                    'integer',
                                    'min:0',
                                    function($attribute, $value, $fail) use (&$rangelist) {
                                        $check = true;
                                        for($i=0; $i<count($rangelist)-1; $i++) {
                                            if(($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) || ($value >= $rangelist[$i][1] && $rangelist[array_key_last($rangelist)][0] <= $rangelist[$i][0])) {
                                                $fail("Ranges cannot overap");
                                                $check = false;
                                                break;
                                            }
                                        }
                                        if($check) {
                                            $rangelist[array_key_last($rangelist)][1] = $value;
                                        }
                                    }
                                ]
                            ]);
                        }

                        //Adding jump pointer to rangelist
                        for($i=0; $i<count($rangelist); $i++) {
                            $rangelist[$i][2] = $request['jumpinterval'.($i+1)];
                        }
                    } else {
                        return response()->json([
                            'status' => 400,
                        ]);
                    }
                }

                //Check if jump is possible
                if($element->section->jump == null) {
                    $parent = $element->section;
                    for($i=0; $i<$parent->questions->count(); $i++) {
                        if($parent->questions[$i]->questionable->jump != null) {
                            if($parent->questions[$i]->id != $element->id) {
                                return response()->json([
                                    'status' => 400,
                                ]);
                            }
                        }
                    }

                    //Uppertree analysis
                    //Sectionlist
                    $sectionlist = [];
                    $rec = function($section) use (&$rec, &$sectionlist) {
                        if($section->sections->count() != 0) {
                            for($i=0; $i<$section->sections->count(); $i++) {
                                $rec($section->sections[$i]);
                            }
                        }
                        $sectionlist[] = [$section->id, $section->name];
                    };
                    $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];
                    for($i=0; $i<$test->sections->count(); $i++) {
                        $rec($test->sections[$i]);
                    }
                    //Generating array of parents
                    $parents = [];
                    $recprogressive = function($section) use (&$recprogressive, &$parents) {
                        if($section->sections->count() != 0) {
                            for($i=0; $i<$section->sections->count(); $i++) {
                                $recprogressive($section->sections[$i]);
                            }
                            $parents[] = $section->id;
                        } else {
                            $parents[] = $section->id;
                        }
                    };
                    //Array jump
                    $section = $element->section;
                    if(get_class($section->sectionable) == Test::class) {
                        $previoussection = $section->sectionable->sections()->where('progressive', '<', $section->progressive)->get();
                        for($i=0; $i<$previoussection->count(); $i++) {
                            $recprogressive($previoussection[$i]);
                        }
                        $parents[] = $section->id;
                        //Array diff
                        for($x=0; $x<count($parents); $x++) {
                            for($i=0; $i<count($sectionlist); $i++) {
                                if($sectionlist[$i][0] == $parents[$x]) {
                                    array_splice($sectionlist,$i,1);
                                    break;
                                }
                            }
                        }
                    } else {
                        $parents[] = $section->id;
                        $cicle = $section;
                        do{
                            $previoussection = $cicle->sectionable->sections()->where('progressive', '<', $cicle->progressive)->get();
                            for($i=0; $i<$previoussection->count(); $i++) {
                                $recprogressive($previoussection[$i]);
                            }
                            $parents[] = $cicle->id;
                            //Array diff
                            for($x=0; $x<count($parents); $x++) {
                                for($i=0; $i<count($sectionlist); $i++) {
                                    if($sectionlist[$i][0] == $parents[$x]) {
                                        array_splice($sectionlist,$i,1);
                                        break;
                                    }
                                }
                            }
                            $cicle = $cicle->sectionable;
                        } while(get_class($cicle) != Test::class);
                    }

                    //Check if section isn't empty
                    if(count($sectionlist) == 0) {
                        return response()->json([
                            'status' => 400,
                        ]);
                    }

                } else {
                    return response()->json([
                        'status' => 400,
                    ]);
                }

                $jumplist = [];
                if(get_class($element->questionable) == MultipleQuestion::class) {
                    for($i=0; $i<count($element->questionable->fields); $i++) {
                        $jumplist[] = $request['jumpselect'.$i];
                    }
                } elseif(get_class($element->questionable) == ImageQuestion::class) {
                    for($i=0; $i<count($element->questionable->images); $i++) {
                        $jumplist[] = $request['jumpselect'.$i];
                    }
                } else {
                    $jumplist = $rangelist;
                }
                $element->questionable->update([
                    'jump' => $jumplist,
                ]);

            } else {
                if($request->enabler) {
                    $request->validate([
                        'jumplenght' => ['required', 'integer', 'min:1'],
                    ]);

                    $rangelist = [];
                    for($i=1; $i<=$request->jumplenght; $i++) {
                        $request->validate([
                            'jumpinterval'.$i => [
                                'required',
                                'integer',
                                'min:0',
                                function($attribute, $value, $fail) use ($request, $element) {
                                    $section = Section::where('id', $value)->get();
                                    if($section->count() != 0) {
                                        $section = $section[0];
                                        $cicle = $section;
                                        while(get_class($cicle) != Test::class) {
                                            $cicle = $cicle->sectionable;
                                        }
                                        if($cicle->id != $request->session()->get('testidcreation')) {
                                            $fail('Invalid Section');
                                        } else {
                                            $parent = $element->sectionable;
                                            while(get_class($parent) != Test::class) {
                                                if($parent->id == $section->id) {
                                                    $fail("No parent section");
                                                    break;
                                                }
                                                $parent = $parent->sectionable;
                                            }
                                        }
                                    } else {
                                        $fail('Invalid Section');
                                    }
                                },
                            ],
                            'from-'.$i => [
                                'required',
                                'integer',
                                'min:0',
                                function($attribute, $value, $fail) use (&$rangelist) {
                                    $check = true;
                                    for($i=0; $i<count($rangelist); $i++) {
                                        if($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) {
                                            $fail("Ranges cannot overap");
                                            $check = false;
                                            break;
                                        }
                                    }
                                    if($check) {
                                        $rangelist[] = [$value];
                                    }
                                }
                            ],
                            'to-'.$i => [
                                'required',
                                'integer',
                                'min:0',
                                function($attribute, $value, $fail) use (&$rangelist) {
                                    $check = true;
                                    for($i=0; $i<count($rangelist)-1; $i++) {
                                        if(($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) || ($value >= $rangelist[$i][1] && $rangelist[array_key_last($rangelist)][0] <= $rangelist[$i][0])) {
                                            $fail("Ranges cannot overap");
                                            $check = false;
                                            break;
                                        }
                                    }
                                    if($check) {
                                        $rangelist[array_key_last($rangelist)][1] = $value;
                                    }
                                }
                            ]
                        ]);
                    }

                    //Adding jump pointer to rangelist
                    for($i=0; $i<count($rangelist); $i++) {
                        $rangelist[$i][2] = $request['jumpinterval'.($i+1)];
                    }

                } else {
                    return response()->json([
                        'status' => 400,
                    ]);
                }

                //Check if jump is possible
                //Subtree analysis
                $rec = function($section) use (&$rec) {
                    if($section->sections->count() == 0) {
                        for($i=0; $i<$section->questions->count(); $i++) {
                            if($section->questions[$i]->questionable->jump != null) {
                                return false;
                            }
                        }
                    } else {
                        for($i=0; $i<$section->sections->count(); $i++) {
                            if($section->sections[$i]->jump == null) {
                                if(!$rec($section->sections[$i])) {
                                    return false;
                                }
                            } else {
                                return false;
                            }
                        }
                    }
                    return true;
                };
                if(!$rec($element)) {
                    return response()->json([
                        'check' => false,
                    ]);
                }

                //Sectionlist
                $sectionlist = [];
                $rec = function($section) use (&$rec, &$sectionlist) {
                    if($section->sections->count() != 0) {
                        for($i=0; $i<$section->sections->count(); $i++) {
                            $rec($section->sections[$i]);
                        }
                    }
                    $sectionlist[] = [$section->id, $section->name];
                };
                $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];
                for($i=0; $i<$test->sections->count(); $i++) {
                    $rec($test->sections[$i]);
                }
                //Generating array of parents
                $parents = [];
                $recprogressive = function($section) use (&$recprogressive, &$parents) {
                    if($section->sections->count() != 0) {
                        for($i=0; $i<$section->sections->count(); $i++) {
                            $recprogressive($section->sections[$i]);
                        }
                        $parents[] = $section->id;
                    } else {
                        $parents[] = $section->id;
                    }
                };

                //Uppertree analysis
                //Array jump
                if(get_class($element->sectionable) == Test::class) {
                    $previoussection = $element->sectionable->sections()->where('progressive', '<', $element->progressive)->get();
                    for($i=0; $i<$previoussection->count(); $i++) {
                        $recprogressive($previoussection[$i]);
                    }
                    $parents[] = $element->id;
                    //Array diff
                    for($x=0; $x<count($parents); $x++) {
                        for($i=0; $i<count($sectionlist); $i++) {
                            if($sectionlist[$i][0] == $parents[$x]) {
                                array_splice($sectionlist,$i,1);
                                break;
                            }
                        }
                    }
                } else {
                    $parents[] = $element->id;
                    $cicle = $element;
                    do{
                        $previoussection = $cicle->sectionable->sections()->where('progressive', '<', $cicle->progressive)->get();
                        for($i=0; $i<$previoussection->count(); $i++) {
                            $recprogressive($previoussection[$i]);
                        }
                        $parents[] = $cicle->id;
                        //Array diff
                        for($x=0; $x<count($parents); $x++) {
                            for($i=0; $i<count($sectionlist); $i++) {
                                if($sectionlist[$i][0] == $parents[$x]) {
                                    array_splice($sectionlist,$i,1);
                                    break;
                                }
                            }
                        }
                        $cicle = $cicle->sectionable;
                    } while(get_class($cicle) != Test::class);
                }

                //Check if section isn't empty
                if(count($sectionlist) == 0) {
                    return response()->json([
                        'status' => 400,
                    ]);
                }

                $element->update([
                    'jump' => $rangelist,
                ]);
            }
        }

        if(get_class($element) == Test::class) {
            //Label system
            $request->validate([
                'rangeenabler' => ['integer', 'in:1'],
            ]);
            if($request->rangeenabler) {
                $request->validate([
                    'rangelenght' => ['required', 'integer', 'min:1'],
                ]);
                //Validation
                $rangelist = [];
                for($i=1; $i<=$request->rangelenght; $i++) {
                    $request->validate([
                        'label-'.$i => ['required', 'string'],
                        'from-'.$i => [
                            'required',
                            'integer',
                            'min:0',
                            function($attribute, $value, $fail) use (&$rangelist) {
                                $check = true;
                                for($i=0; $i<count($rangelist); $i++) {
                                    if($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) {
                                        $fail("Ranges cannot overap");
                                        $check = false;
                                        break;
                                    }
                                }
                                if($check) {
                                    $rangelist[] = [$value];
                                }
                            }
                        ],
                        'to-'.$i => [
                            'required',
                            'integer',
                            'min:0',
                            function($attribute, $value, $fail) use (&$rangelist) {
                                $check = true;
                                for($i=0; $i<count($rangelist)-1; $i++) {
                                    if(($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) || ($value >= $rangelist[$i][1] && $rangelist[array_key_last($rangelist)][0] <= $rangelist[$i][0])) {
                                        $fail("Ranges cannot overap");
                                        $check = false;
                                        break;
                                    }
                                }
                                if($check) {
                                    $rangelist[array_key_last($rangelist)][1] = $value;
                                }
                            }
                        ]
                    ]);
                }

                //Adding label to rangelist
                for($i=0; $i<count($rangelist); $i++) {
                    $rangelist[$i][2] = $request['label-'.($i+1)];
                }

                $test->update([
                    'status' => 1,
                    'labels' => $rangelist,
                ]);
            } else {
                $test->update([
                    'status' => 1,
                ]);
            }

            $request->session()->forget(['testidcreation', 'progressive']);
            return response()->json([
                'status' => 300,
            ]);
        }

        //Shift progressive
        if(get_class($element) == Question::class) {
            //Progressive when the saved element is a question
            $parent = $element->section;
            $question = $element;
            $check = false;
            while($parent->questions->count() >= $question->progressive + 1 && $check == false) {
                $question = $parent->questions[$question->progressive];
                if(get_class($question->questionable) != OpenQuestion::class) {
                    array_pop($progressive);
                    $progressive[] = $question->progressive;
                    $check = true;
                }
            }
            if($check == false) {
                array_pop($progressive);
            }
            $request->session()->put('progressive', implode("-", $progressive));

        } else {
            //Progressive when the saved element is a section
            //Recoursive code that check id the input section has nested non open question and update progressive
            $rec = function ($section) use (&$rec, &$progressive) {
                if($section->sections->count() != 0) {
                    //Recoursive code
                    for($i=0; $i<$section->sections->count(); $i++) {
                        $subsection = $section->sections[$i];
                        $progressive[] = $subsection->progressive;
                        $check = $rec($subsection);
                        if($check) {
                            return true;
                        } else {
                            array_pop($progressive);
                        }
                    }
                    return false;
                } else {
                    for($i=0; $i<$section->questions->count(); $i++) {
                        $question = $section->questions[0];
                        if(get_class($question->questionable) != OpenQuestion::class) {
                            array_pop($progressive);
                            $progressive[] = $section->progressive;
                            $progressive[] = $question->progressive;
                            return true;
                        }
                    }
                    return false;
                }
            };

            $section = $element;
            $parent = $element->sectionable;
            array_pop($progressive);
            $check = false;
            while($parent->sections->count() >= $section->progressive + 1) {
                $section = $parent->sections[$section->progressive];
                $progressive[] = $section->progressive;
                $res = $rec($section);
                if($res == true) {
                    $check = true;
                    break;
                } else {
                    array_pop($progressive);
                }
            }
            if(!$check) {
                if(get_class($parent) == Test::class) {
                    $progressive[] = 'test';
                }
            }

            $request->session()->put('progressive', implode("-", $progressive));
        }

        return response()->json([
            'status' => 200,
        ]);
    }

    /**
     * Update a score resource in storage.
     */
    public function updateScore(Request $request): JsonResponse
    {
        $request->validate([
            'enabler' => ['integer', 'in:1'],
            'jump' => ['integer', 'in:1'],
            'identifier' => ['required', 'regex:/^(section-\d+|question-\d+)$/'],
        ]);

        //Retriving data from session
        $progressive = $request->session()->get('progressive');
        $progressive = explode('-', $progressive);

        $identifier = explode("-", $request->identifier);
        if($identifier[0] == 'section') {
            $section = Section::where('id', $identifier[1])->get();
            if($section->count() != 0) {
                $section = $section[0];

                //Loop to generate progressive of the section
                $elementprogressive[] = $section->progressive;
                $parent = $section;
                while (get_class($parent->sectionable) != Test::class) {
                    $parent = $parent->sectionable;
                    array_unshift($elementprogressive, $parent->progressive);
                }

                //Check if the section is updateable
                $progressive = $request->session()->get('progressive');
                $progressive = explode("-", $progressive);
                $check = false;
                if($progressive[0] != 'test') {
                    $loop = min(count($elementprogressive), count($progressive));
                    for($i=0; $i<$loop; $i++) {
                        if($i != $loop-1) {
                            if($elementprogressive[$i] < $progressive[$i]) {
                                $check = true;
                                break;
                            } elseif($elementprogressive[$i] > $progressive[$i]) {
                                break;
                            }
                        } else {
                            if(count($elementprogressive) > count($progressive)) {
                                if($elementprogressive[$i] <= $progressive[$i]) {
                                    $check = true;
                                    break;
                                } elseif($elementprogressive[$i] > $progressive[$i]) {
                                    break;
                                }
                            } else {
                                if($elementprogressive[$i] < $progressive[$i]) {
                                    $check = true;
                                    break;
                                } elseif($elementprogressive[$i] >= $progressive[$i]) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $check = true;
                }

                if($check) {
                    //Checking if at least one subelement has score logic
                    $check = false;
                    if($section->sections->count() != 0) {
                        for($i=0; $i<$section->sections->count(); $i++) {
                            if($section->sections[$i]->operationOnScore) {
                                $check = true;
                                break;
                            }
                        }
                    } else {
                        for($i=0; $i<$section->questions->count(); $i++) {
                            if($section->questions[$i]->questionable->scores) {
                                $check = true;
                                break;
                            }
                        }
                    }
                    if($check) {

                        if($request->enabler) {
                            $request->validate([
                                'scoreoperation' => ['required', 'integer', 'in:1,2,3']
                            ]);
                            //Score operation
                            if($request->scoreoperation == 1) {
                                //Validation logic for formula field
                                if($section->sections->count() == 0) {
                                    $regex = 'regex:/^(Q\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                                } else {
                                    $regex = 'regex:/^(S\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                                }
                                $request->validate([
                                    'formula' => [
                                        'required',
                                        $regex,
                                        //Check for unbalanced parentheses
                                        function ($attribute, $value, $fail) {
                                            $open = 0;
                                            foreach (str_split($value) as $char) {
                                                if ($char == '(') {
                                                    $open++;
                                                } elseif ($char == ')') {
                                                    $open--;
                                                    if ($open < 0) {
                                                        $fail('Unbalanced parentheses.');
                                                    }
                                                }
                                            }

                                            if ($open != 0) {
                                                $fail('Unbalanced parentheses.');
                                            }
                                        },
                                        function ($attribute, $value, $fail) use ($section) {
                                            if($section->sections->count() == 0) {
                                                $letter = 'Q';
                                            } else {
                                                $letter = 'S';
                                            }
                                            for ($i = 0; $i < strlen($value); $i++) {
                                                // Current character
                                                $char = $value[$i];
                                                if ($char == $letter) {
                                                    $selector = $char.$value[$i + 1];
                                                    if($selector[0] == 'Q') {
                                                        if($section->questions->count() < $selector[1] || $selector[1] == 0 || !$section->questions[$selector[1]-1]->questionable->scores) {
                                                            $fail('Wrong question identifier');
                                                        }
                                                    } elseif($selector[0] == 'S') {
                                                        if($section->sections->count() < $selector[1] || $selector[1] == 0 || !$section->sections[$selector[1]-1]->operationOnScore) {
                                                            $fail('Wrong section identifier');
                                                        }
                                                    }
                                                }
                                            }
                                        },
                                    ],
                                ]);

                                //Saving operation on score
                                if($section->operationOnScore) {
                                    $section->operationOnScore->update([
                                        'scorable_id' => $section->id,
                                        'scorable_type' => get_class($section),
                                        'formula' => $request->formula,
                                    ]);
                                } else {
                                    OperationOnScore::create([
                                        'scorable_id' => $section->id,
                                        'scorable_type' => get_class($section),
                                        'formula' => $request->formula,
                                    ]);
                                }

                            } elseif($request->scoreoperation == 2) {
                                $request->validate([
                                    'lenght' => ['required', 'integer', 'min:0'],
                                ]);
                                $rule = [];
                                for($i=1; $i<=$request->lenght; $i++) {
                                    $rule['value-'.$i] = ['required', 'integer', 'min:0'];
                                    $rule['converted-'.$i] = ['required', 'integer', 'min:0'];
                                }
                                $request->validate($rule);
                                //Validation for value field that must be not equal
                                $errors = [];
                                $value = [];
                                for($i=1; $i<=$request->lenght; $i++) {
                                    if(!in_array($request['value-'.$i], $value)) {
                                        $value[] = $request['value-'.$i];
                                    } else {
                                        $errors[] = $i;
                                    }
                                }
                                $rule = [];
                                for($i=0; $i<count($errors); $i++) {
                                    $rule['value-'.$errors[$i]] = [
                                        function ($attribute, $value, $fail) {
                                            $fail("Value fields cannot be equal");
                                        },
                                    ];
                                }
                                $request->validate($rule);
                                //Creating array of conversion
                                $conversion = [];
                                for($i=1; $i<=$request->lenght; $i++) {
                                    $conversion[$request['value-'.$i]] = $request['converted-'.$i];
                                }

                                //Saving operation on score
                                if($section->operationOnScore) {
                                    $section->operationOnScore->update([
                                        'scorable_id' => $section->id,
                                        'scorable_type' => get_class($section),
                                        'conversion' => $conversion,
                                    ]);
                                } else {
                                    OperationOnScore::create([
                                        'scorable_id' => $section->id,
                                        'scorable_type' => get_class($section),
                                        'conversion' => $conversion,
                                    ]);
                                }

                            } elseif($request->scoreoperation == 3) {
                                //Validation logic for formula field
                                if($section->sections->count() == 0) {
                                    $regex = 'regex:/^(Q\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                                } else {
                                    $regex = 'regex:/^(S\d+|\d+(\.\d+)?|[+\-*\/()]|\((?=.*\)))+$/';
                                }
                                $request->validate([
                                    'formula' => [
                                        'required',
                                        $regex,
                                        //Check for unbalanced parentheses
                                        function ($attribute, $value, $fail) {
                                            $open = 0;
                                            foreach (str_split($value) as $char) {
                                                if ($char == '(') {
                                                    $open++;
                                                } elseif ($char == ')') {
                                                    $open--;
                                                    if ($open < 0) {
                                                        $fail('Unbalanced parentheses.');
                                                    }
                                                }
                                            }

                                            if ($open != 0) {
                                                $fail('Unbalanced parentheses.');
                                            }
                                        },
                                        function ($attribute, $value, $fail) use ($section) {
                                            if($section->sections->count() == 0) {
                                                $letter = 'Q';
                                            } else {
                                                $letter = 'S';
                                            }
                                            for ($i = 0; $i < strlen($value); $i++) {
                                                // Current character
                                                $char = $value[$i];
                                                if ($char == $letter) {
                                                    $selector = $char.$value[$i + 1];
                                                    if($selector[0] == 'Q') {
                                                        if($section->questions->count() < $selector[1] || $selector[1] == 0 || !$section->questions[$selector[1]-1]->questionable->scores) {
                                                            $fail('Wrong question identifier');
                                                        }
                                                    } elseif($selector[0] == 'S') {
                                                        if($section->sections->count() < $selector[1] || $selector[1] == 0 || !$section->sections[$selector[1]-1]->operationOnScore) {
                                                            $fail('Wrong section identifier');
                                                        }
                                                    }
                                                }
                                            }
                                        },
                                    ],
                                ]);

                                $request->validate([
                                    'lenght' => ['required', 'integer', 'min:0'],
                                ]);
                                $rule = [];
                                for($i=1; $i<=$request->lenght; $i++) {
                                    $rule['value-'.$i] = ['required', 'integer', 'min:0'];
                                    $rule['converted-'.$i] = ['required', 'integer', 'min:0'];
                                }
                                $request->validate($rule);
                                //Validation for value field that must be not equal
                                $errors = [];
                                $value = [];
                                for($i=1; $i<=$request->lenght; $i++) {
                                    if(!in_array($request['value-'.$i], $value)) {
                                        $value[] = $request['value-'.$i];
                                    } else {
                                        $errors[] = $i;
                                    }
                                }
                                $rule = [];
                                for($i=0; $i<count($errors); $i++) {
                                    $rule['value-'.$errors[$i]] = [
                                        function ($attribute, $value, $fail) {
                                            $fail("Value fields cannot be equal");
                                        },
                                    ];
                                }
                                $request->validate($rule);

                                //Creating array of conversion
                                $conversion = [];
                                for($i=1; $i<=$request->lenght; $i++) {
                                    $conversion[$request['value-'.$i]] = $request['converted-'.$i];
                                }

                                //Saving operation on score
                                if($section->operationOnScore) {
                                    $section->operationOnScore->update([
                                        'scorable_id' => $section->id,
                                        'scorable_type' => get_class($section),
                                        'formula' => $request->formula,
                                        'conversion' => $conversion,
                                    ]);
                                } else {
                                    OperationOnScore::create([
                                        'scorable_id' => $section->id,
                                        'scorable_type' => get_class($section),
                                        'formula' => $request->formula,
                                        'conversion' => $conversion,
                                    ]);
                                }
                            }

                            if($request->jump) {
                                $request->validate([
                                    'jumplenght' => ['required', 'integer', 'min:1'],
                                ]);

                                $rangelist = [];
                                for($i=1; $i<=$request->jumplenght; $i++) {
                                    $request->validate([
                                        'jumpinterval'.$i => [
                                            'required',
                                            'integer',
                                            'min:0',
                                            function($attribute, $value, $fail) use ($request) {
                                                $section = Section::where('id', $value)->get();
                                                if($section->count() != 0) {
                                                    $section = $section[0];
                                                    while(get_class($section) != Test::class) {
                                                        $section = $section->sectionable;
                                                    }
                                                    if($section->id != $request->session()->get('testidcreation')) {
                                                        $fail('Invalid Section');
                                                    }
                                                } else {
                                                    $fail('Invalid Section');
                                                }
                                            },
                                        ],
                                        'from-'.$i => [
                                            'required',
                                            'integer',
                                            'min:0',
                                            function($attribute, $value, $fail) use (&$rangelist) {
                                                $check = true;
                                                for($i=0; $i<count($rangelist); $i++) {
                                                    if($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) {
                                                        $fail("Ranges cannot overap");
                                                        $check = false;
                                                        break;
                                                    }
                                                }
                                                if($check) {
                                                    $rangelist[] = [$value];
                                                }
                                            }
                                        ],
                                        'to-'.$i => [
                                            'required',
                                            'integer',
                                            'min:0',
                                            function($attribute, $value, $fail) use (&$rangelist) {
                                                $check = true;
                                                for($i=0; $i<count($rangelist)-1; $i++) {
                                                    if(($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) || ($value >= $rangelist[$i][1] && $rangelist[array_key_last($rangelist)][0] <= $rangelist[$i][0])) {
                                                        $fail("Ranges cannot overap");
                                                        $check = false;
                                                        break;
                                                    }
                                                }
                                                if($check) {
                                                    $rangelist[array_key_last($rangelist)][1] = $value;
                                                }
                                            }
                                        ]
                                    ]);
                                }

                                //Adding jump pointer to rangelist
                                for($i=0; $i<count($rangelist); $i++) {
                                    $rangelist[$i][2] = $request['jumpinterval'.($i+1)];
                                }

                                //Check if jump is possible
                                //Subtree analysis
                                $rec = function($section) use (&$rec) {
                                    if($section->sections->count() == 0) {
                                        for($i=0; $i<$section->questions->count(); $i++) {
                                            if($section->questions[$i]->questionable->jump != null) {
                                                return false;
                                            }
                                        }
                                    } else {
                                        for($i=0; $i<$section->sections->count(); $i++) {
                                            if($section->sections[$i]->jump == null) {
                                                if(!$rec($section->sections[$i])) {
                                                    return false;
                                                }
                                            } else {
                                                return false;
                                            }
                                        }
                                    }
                                    return true;
                                };
                                if(!$rec($section)) {
                                    return response()->json([
                                        'status' => 400,
                                    ]);
                                }

                                //Sectionlist
                                $sectionlist = [];
                                $rec = function($section) use (&$rec, &$sectionlist) {
                                    if($section->sections->count() != 0) {
                                        for($i=0; $i<$section->sections->count(); $i++) {
                                            $rec($section->sections[$i]);
                                        }
                                    }
                                    $sectionlist[] = [$section->id, $section->name];
                                };
                                $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];
                                for($i=0; $i<$test->sections->count(); $i++) {
                                    $rec($test->sections[$i]);
                                }
                                //Generating array of parents
                                $parents = [];
                                $recprogressive = function($section) use (&$recprogressive, &$parents) {
                                    if($section->sections->count() != 0) {
                                        for($i=0; $i<$section->sections->count(); $i++) {
                                            $recprogressive($section->sections[$i]);
                                        }
                                        $parents[] = $section->id;
                                    } else {
                                        $parents[] = $section->id;
                                    }
                                };
                                ///Uppertree analysis
                                //Array jump
                                if(get_class($section->sectionable) == Test::class) {
                                    $previoussection = $section->sectionable->sections()->where('progressive', '<', $section->progressive)->get();
                                    for($i=0; $i<$previoussection->count(); $i++) {
                                        $recprogressive($previoussection[$i]);
                                    }
                                    $parents[] = $section->id;
                                    //Array diff
                                    for($x=0; $x<count($parents); $x++) {
                                        for($i=0; $i<count($sectionlist); $i++) {
                                            if($sectionlist[$i][0] == $parents[$x]) {
                                                array_splice($sectionlist,$i,1);
                                                break;
                                            }
                                        }
                                    }
                                } else {
                                    $parents[] = $section->id;
                                    $cicle = $section;
                                    do{
                                        $previoussection = $cicle->sectionable->sections()->where('progressive', '<', $cicle->progressive)->get();
                                        for($i=0; $i<$previoussection->count(); $i++) {
                                            $recprogressive($previoussection[$i]);
                                        }
                                        $parents[] = $cicle->id;
                                        //Array diff
                                        for($x=0; $x<count($parents); $x++) {
                                            for($i=0; $i<count($sectionlist); $i++) {
                                                if($sectionlist[$i][0] == $parents[$x]) {
                                                    array_splice($sectionlist,$i,1);
                                                    break;
                                                }
                                            }
                                        }
                                        $cicle = $cicle->sectionable;
                                    } while(get_class($cicle) != Test::class);
                                }

                                //Check if section isn't empty
                                if(count($sectionlist) == 0) {
                                    return response()->json([
                                        'status' => 400,
                                    ]);
                                }

                                $section->update([
                                    'jump' => $rangelist,
                                ]);
                            } else {
                                $section->update([
                                    'jump' => null,
                                ]);
                            }

                        } else {
                            //Code for section with no score checkbox
                            if($section->operationOnScore) {
                                $section->operationOnScore->delete();
                            }
                            if($section->jump != null) {
                                $section->update([
                                    'jump' => null,
                                ]);
                            }

                            //Check if all siblings don't have score operations
                            $check = true;
                            $parent = $section->sectionable;
                            for($i=0; $i<$parent->sections->count(); $i++) {
                                if($parent->sections[$i]->operationOnScore) {
                                    $check = false;
                                    break;
                                }
                            }
                            if($check) {
                                $parent->operationOnScore->delete();
                                if(get_class($parent) == Section::class) {
                                    $parent->update([
                                        'jump' => null,
                                    ]);
                                }
                            }
                        }
                        return response()->json([
                            'status' => 200,
                        ]);
                    }
                }
            }

        } else if($identifier[0] == 'question') {
            $question = Question::where('id', $identifier[1])->get();
            if($question->count() != 0) {
                $question = $question[0];

                //Loop to generate progressive of the question
                $elementprogressive[] = $question->progressive;
                $parent = $question->section;
                while (get_class($parent) != Test::class) {
                    array_unshift($elementprogressive, $parent->progressive);
                    $parent = $parent->sectionable;
                }

                //Check if the question is updateable
                $progressive = $request->session()->get('progressive');
                $progressive = explode("-", $progressive);
                $check = true;
                $check = false;
                if($progressive[0] != 'test') {
                    $loop = min(count($elementprogressive), count($progressive));
                    for($i=0; $i<$loop; $i++) {
                        if($i != $loop-1) {
                            if($elementprogressive[$i] < $progressive[$i]) {
                                $check = true;
                                break;
                            } elseif($elementprogressive[$i] > $progressive[$i]) {
                                break;
                            }
                        } else {
                            if(count($elementprogressive) > count($progressive)) {
                                if($elementprogressive[$i] <= $progressive[$i]) {
                                    $check = true;
                                    break;
                                } elseif($elementprogressive[$i] > $progressive[$i]) {
                                    break;
                                }
                            } else {
                                if($elementprogressive[$i] < $progressive[$i]) {
                                    $check = true;
                                    break;
                                } elseif($elementprogressive[$i] >= $progressive[$i]) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $check = true;
                }

                if($check) {
                    //Question Pages

                    if($request->enabler) {
                        //validating response score
                        if(get_class($question->questionable) != ValueQuestion::class) {
                            $rule = [];
                            if(get_class($question->questionable) == ImageQuestion::class) {
                                for($i=0; $i<count($question->questionable->images); $i++) {
                                    $rule['selectvalue'.$i] = ['required', 'integer', 'min:0'];
                                }
                            } else {
                                for($i=0; $i<count($question->questionable->fields); $i++) {
                                    $rule['selectvalue'.$i] = ['required', 'integer', 'min:0'];
                                }
                            }

                            $request->validate($rule);
                        }

                        //Saving values of question
                        $points = [];
                        if(get_class($question->questionable) == ValueQuestion::class) {
                            $points = array_merge($points, $question->questionable->fields['singular']);
                            $points = array_merge($points, $question->questionable->fields['personal']);
                        } else {
                            if(get_class($question->questionable) == ImageQuestion::class) {
                                for($i=0; $i<count($question->questionable->images); $i++) {
                                    $points[] = $request['selectvalue'.$i];
                                }
                            } else {
                                for($i=0; $i<count($question->questionable->fields); $i++) {
                                    $points[] = $request['selectvalue'.$i];
                                }
                            }
                        }

                        $question->questionable->update([
                            'scores' => $points,
                        ]);
                    } else {
                        $question->questionable->update([
                            'scores' => null,
                        ]);

                        //Check if all the siblings have disabled score sistem
                        $check = true;
                        for($i=0; $i<$question->section->questions->count(); $i++) {
                            if($question->section->questions[$i]->scores != null) {
                                $check = false;
                                break;
                            }
                        }
                        if($check) {
                            $question->section->operationOnScore->delete();
                            $question->section->update([
                                'jump' => null,
                            ]);
                        }
                    }

                    if($request->jump) {
                        //Avoiding open question
                        if(get_class($question->questionable) == OpenQuestion::class) {
                            return response()->json([
                                'status' => 400,
                            ]);
                        }

                        //Validating response jump
                        $rangelist = [];
                        if(get_class($question->questionable) == MultipleQuestion::class || get_class($question->questionable) == ImageQuestion::class) {
                            $rule = [];
                            if(get_class($question->questionable) == ImageQuestion::class) {
                                for($i=0; $i<count($question->questionable->images); $i++) {
                                    $rule['jumpselect'.$i] = ['required',
                                    'integer',
                                    'min:0',
                                    function($attribute, $value, $fail) use ($request) {
                                        $section = Section::where('id', $value)->get();
                                        if($section->count() != 0) {
                                            $section = $section[0];
                                            while(get_class($section) != Test::class) {
                                                $section = $section->sectionable;
                                            }
                                            if($section->id != $request->session()->get('testidcreation')) {
                                                $fail('Invalid Section');
                                            }
                                        } else {
                                            $fail('Invalid Section');
                                        }
                                    },
                                ];
                                }
                            } else {
                                for($i=0; $i<count($question->questionable->fields); $i++) {
                                    $rule['jumpselect'.$i] = [
                                        'required',
                                        'integer',
                                        'min:0',
                                        function($attribute, $value, $fail) use ($request) {
                                            $section = Section::where('id', $value)->get();
                                            if($section->count() != 0) {
                                                $section = $section[0];
                                                while(get_class($section) != Test::class) {
                                                    $section = $section->sectionable;
                                                }
                                                if($section->id != $request->session()->get('testidcreation')) {
                                                    $fail('Invalid Section');
                                                }
                                            } else {
                                                $fail('Invalid Section');
                                            }
                                        },
                                    ];
                                }
                            }
                            $request->validate($rule);
                        } else {
                            if($request->enabler) {
                                $request->validate([
                                    'jumplenght' => ['required', 'integer', 'min:1'],
                                ]);

                                for($i=1; $i<=$request->jumplenght; $i++) {
                                    $request->validate([
                                        'jumpinterval'.$i => [
                                            'required',
                                            'integer',
                                            'min:0',
                                            function($attribute, $value, $fail) use ($request) {
                                                $section = Section::where('id', $value)->get();
                                                if($section->count() != 0) {
                                                    $section = $section[0];
                                                    while(get_class($section) != Test::class) {
                                                        $section = $section->sectionable;
                                                    }
                                                    if($section->id != $request->session()->get('testidcreation')) {
                                                        $fail('Invalid Section');
                                                    }
                                                } else {
                                                    $fail('Invalid Section');
                                                }
                                            },
                                        ],
                                        'from-'.$i => [
                                            'required',
                                            'integer',
                                            'min:0',
                                            function($attribute, $value, $fail) use (&$rangelist) {
                                                $check = true;
                                                for($i=0; $i<count($rangelist); $i++) {
                                                    if($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) {
                                                        $fail("Ranges cannot overap");
                                                        $check = false;
                                                        break;
                                                    }
                                                }
                                                if($check) {
                                                    $rangelist[] = [$value];
                                                }
                                            }
                                        ],
                                        'to-'.$i => [
                                            'required',
                                            'integer',
                                            'min:0',
                                            function($attribute, $value, $fail) use (&$rangelist) {
                                                $check = true;
                                                for($i=0; $i<count($rangelist)-1; $i++) {
                                                    if(($value >= $rangelist[$i][0] && $value <= $rangelist[$i][1]) || ($value >= $rangelist[$i][1] && $rangelist[array_key_last($rangelist)][0] <= $rangelist[$i][0])) {
                                                        $fail("Ranges cannot overap");
                                                        $check = false;
                                                        break;
                                                    }
                                                }
                                                if($check) {
                                                    $rangelist[array_key_last($rangelist)][1] = $value;
                                                }
                                            }
                                        ]
                                    ]);
                                }

                                //Adding jump pointer to rangelist
                                for($i=0; $i<count($rangelist); $i++) {
                                    $rangelist[$i][2] = $request['jumpinterval'.($i+1)];
                                }
                            } else {
                                return response()->json([
                                    'status' => 400,
                                ]);
                            }
                        }

                        //Check if jump is possible
                        if($question->section->jump == null) {
                            $parent = $question->section;
                            for($i=0; $i<$parent->questions->count(); $i++) {
                                if($parent->questions[$i]->questionable->jump != null) {
                                    if($parent->questions[$i]->id != $question->id) {
                                        return response()->json([
                                            'status' => 400,
                                        ]);
                                    }
                                }
                            }

                            //Sectionlist
                            $sectionlist = [];
                            $rec = function($section) use (&$rec, &$sectionlist) {
                                if($section->sections->count() != 0) {
                                    for($i=0; $i<$section->sections->count(); $i++) {
                                        $rec($section->sections[$i]);
                                    }
                                }
                                $sectionlist[] = [$section->id, $section->name];
                            };
                            $test = Test::where('id', $request->session()->get('testidcreation'))->get()[0];
                            for($i=0; $i<$test->sections->count(); $i++) {
                                $rec($test->sections[$i]);
                            }
                            //Generating array of parents
                            $parents = [];
                            $recprogressive = function($section) use (&$recprogressive, &$parents) {
                                if($section->sections->count() != 0) {
                                    for($i=0; $i<$section->sections->count(); $i++) {
                                        $recprogressive($section->sections[$i]);
                                    }
                                    $parents[] = $section->id;
                                } else {
                                    $parents[] = $section->id;
                                }
                            };
                            //Uppertree analysis
                            //Array jump
                            $section = $question->section;
                            do{
                                $previoussection = $section->sectionable->sections()->where('progressive', '<', $section->progressive)->get();
                                for($i=0; $i<$previoussection->count(); $i++) {
                                    $recprogressive($previoussection[$i]);
                                }
                                $parents[] = $section->id;
                                //Array diff
                                for($x=0; $x<count($parents); $x++) {
                                    for($i=0; $i<count($sectionlist); $i++) {
                                        if($sectionlist[$i][0] == $parents[$x]) {
                                            array_splice($sectionlist,$i,1);
                                            break;
                                        }
                                    }
                                }
                                $parents = [];
                                $section = $section->sectionable;
                            } while(get_class($section) != Test::class);

                            //Check if section isn't empty
                            if(count($sectionlist) == 0) {
                                return response()->json([
                                    'status' => 400,
                                ]);
                            }

                        } else {
                            return response()->json([
                                'status' => 400,
                            ]);
                        }

                        $jumplist = [];
                        if(get_class($question->questionable) == MultipleQuestion::class) {
                            for($i=0; $i<count($question->questionable->fields); $i++) {
                                $jumplist[] = $request['jumpselect'.$i];
                            }
                        } elseif(get_class($question->questionable) == ImageQuestion::class) {
                            for($i=0; $i<count($question->questionable->images); $i++) {
                                $jumplist[] = $request['jumpselect'.$i];
                            }
                        } else {
                            $jumplist = $rangelist;
                        }
                        $question->questionable->update([
                            'jump' => $jumplist,
                        ]);

                    } else {
                        $question->questionable->update([
                            'jump' => null,
                        ]);
                    }

                    return response()->json([
                        'status' => 200,
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 400,
        ]);

    }

    /**
     * Delete the creation test and scores.
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
                        if($section->operationOnScore) {
                            $section->operationOnScore->delete();
                        }
                        $section->delete();
                    }
                }
            }
            if($test->operationOnScore) {
                $test->operationOnScore->delete();
            }
            $test->delete();

        };

        //Remove filesystem folder
        Storage::disk('test')->deleteDirectory($test->name);

        $destroy($test);

        $request->session()->forget('testidcreation');
        $request->session()->forget('progressive');
        $request->session()->forget('status');

        return Redirect::route('testmed.createteststructure');
    }

}
