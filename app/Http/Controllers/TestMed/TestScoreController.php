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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TestScoreController extends Controller
{
    /**
     * Show the page for visualizing the score page.
     */
    public function create(): View
    {
        return view('testmed.creationcomponents.testscore');
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
        if($section->operationOnScore) {
            $status = 1;
        } else {
            $status = 0;
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
                $res = $this->createSecionNode($subsesction);

                $array["section".$section->progressive]['sections']['section'.$subsesction->progressive] = $res['section'.$subsesction->progressive];
            }
            return $array;

        } else {
            $questions = $section->questions;
            if($questions->count() != 0) {
                for($i=0; $i<$questions->count(); $i++) {
                    $question = $questions[$i];

                    if($question->questionable != null) {
                        if($question->operationOnScore) {
                            $status = 1;
                        } else {
                            if(get_class($question->questionable) == OpenQuestion::class) {
                                $status = 2;
                            } else {
                                $status = 0;
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
        $progressive = $request->session()->get('progressive');
        $progressive = explode('-', $progressive);
        // $progressive = [1,1,2];
        // $request->session()->put('progressive', implode("-", $progressive));
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
                ]);
            } elseif(get_class($questionrelated) == ValueQuestion::class) {
                return view('testmed.creationcomponents.scoreitempages.valuequestiondetail', [
                    'question' => $questionrelated,
                ]);
            } elseif(get_class($questionrelated) == MultipleSelectionQuestion::class) {
                return view('testmed.creationcomponents.scoreitempages.multipleselectionquestiondetail', [
                    'question' => $questionrelated,
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
     * Store a newly created score resource in storage.
     */
    public function storeScore(Request $request): JsonResponse
    {
        $request->validate([
            'enabler' => ['integer', 'in:1'],
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

                //validating response score
                if(get_class($element->questionable) != ValueQuestion::class) {
                    $rule = [];
                    if(get_class($element->questionable) == ImageQuestion::class) {
                        for($i=0; $i<count($element->questionable->images); $i++) {
                            $rule['selectvalue'.$i] = ['required', 'integer'];
                        }
                    } else {
                        for($i=0; $i<count($element->questionable->fields); $i++) {
                            $rule['selectvalue'.$i] = ['required', 'integer'];
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
                            'lenght' => ['required', 'integer'],
                        ]);
                        $rule = [];
                        for($i=1; $i<=$request->lenght; $i++) {
                            $rule['value-'.$i] = ['required', 'integer'];
                            $rule['converted-'.$i] = ['required', 'integer'];
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
                            'lenght' => ['required', 'integer'],
                        ]);
                        $rule = [];
                        for($i=1; $i<=$request->lenght; $i++) {
                            $rule['value-'.$i] = ['required', 'integer'];
                            $rule['converted-'.$i] = ['required', 'integer'];
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

        if(get_class($element) == Test::class) {
            $test->update([
                'status' => 1,
            ]);
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
                $section = $parent->sections[$element->progressive];
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

}
