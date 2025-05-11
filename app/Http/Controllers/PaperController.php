<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class PaperController extends Controller
{
    public function configure()
    {
        return Inertia::render("Paper/ConfigureView", [
            "user" => User::first()
        ]);
    }

    public function fetchQuestions(Request $request)
    {
        return response()->json([
            "questions" => Question::filter($request->only("type", "grade", "chapter", "search"))
                ->orderBy("type_id")
                ->orderBy("no")
                ->get()
        ]);
    }

    public function preview(Request $request)
    {
        return Inertia::render("Paper/PreviewView", [
            "sections" => $request->sections
        ]);
    }

    public function stepShift($step, Request $request)
    {
        if($request->isMethod("post")){
            if ($step === "1") {
                return Inertia::render("Paper/ConfigureView", [
                    "user" => User::first(),
                    "info" => $request->info ?? null,
                    "sections" => $request->sections ?? null
                ]);
            } else if ($step === "2") {
                return Inertia::render("Paper/InsertQuestionView", [
                    "user" => User::first(),
                    "info" => $request->info ?? null,
                    "sections" => $request->sections ?? null
                ]);
            } else if ($step === "3") {
                return Inertia::render("Paper/PreviewView", [
                    "user" => User::first(),
                    "info" => $request->info ?? null,
                    "sections" => $request->sections ?? null
                ]);
            }
        }else{
            return Inertia::render("Paper/AutoPostRedirect", [
                "url" => "/paper/create/step/1",
            ]);
        }
    }
}
