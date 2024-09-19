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
                $parent = $section->sectionable;
                while(get_class($parent) != Test::class) {
                    if($parent->jump != null) {
                        return response()->json([
                            'check' => false,
                        ]);
                    }
                    $parent = $parent->sectionable;
                }

                return response()->json([
                    'check' => true,
                ]);
            }

        } else {
            $question = Question::where('id', $request->id)->get();
            if($question->count() != 0) {
                $question = $question[0];
                if($question->section->jump == null) {
                    $parent = $question->section;
                    for($i=0; $i<$parent->questions->count(); $i++) {
                        if($parent->questions[$i]->questionable->jump != null) {
                            return response()->json([
                                'check' => false,
                            ]);
                        }
                    }

                    //Uppertree analysis
                    while(get_class($parent) != Test::class) {
                        if($parent->jump != null) {
                            return response()->json([
                                'check' => false,
                            ]);
                        }
                        $parent = $parent->sectionable;
                    }

                    return response()->json([
                        'check' => true,
                    ]);

                }
            }
        }
        return response()->json([
            'check' => false,
        ]);
    }
}
