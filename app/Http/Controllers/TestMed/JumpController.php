<?php

namespace App\Http\Controllers\TestMed;

use App\Http\Controllers\Controller;
use App\Models\Questions\Question;
use App\Models\Section;
use App\Models\Test;
use Illuminate\Http\Request;

class JumpController extends Controller
{
    /**
     * Check if an element can enable jump functionality
     */
    public function createJumpCheck(Request $request)
    {
        $request->validate([
            'element' => ['required', 'in:section,question'],
            'id' => ['required', 'integer', 'min:0'],
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

        if($request->element == 'section') {
            $section = Section::where('id', $request->id)->get();
            if($section->count() != 0) {
                $section = $section[0];
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
                        'check' => false,
                    ]);
                }

                //Uppertree analysis
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
                if(count($sectionlist) != 0) {
                    return response()->json([
                        'check' => true,
                    ]);
                }
            }

        } else {
            $question = Question::where('id', $request->id)->get();
            if($question->count() != 0) {
                $question = $question[0];
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
                    if(count($sectionlist) != 0) {
                        return response()->json([
                            'check' => true,
                        ]);
                    }

                }
            }
        }
        return response()->json([
            'check' => false,
        ]);
    }
}
