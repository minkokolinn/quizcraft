<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $user = User::first();

        $perPage = $request->input("perPage", 10);

        return Inertia::render("Question/IndexView", [
            "questions" => Question::filter($request->only("type", "grade", "chapter", "search"))
                ->orderBy("type_id")
                ->orderBy("no")
                ->paginate($perPage)
                ->withQueryString(),
            "type" => $request->input("type") ? Type::find($request->input("type")) : null,
            "user" => $user,
            "grade" => $request->input("grade") ?? null,
            "chapter" => $request->input("chapter") ?? null,
            "perPage" => (int)$perPage,
            "search" => $request->input("search") ?? null
        ]);
    }

    public function create(Request $request)
    {
        if (!$request->input("type")) {
            abort(404);
        }
        return Inertia::render("Question/FormView", [
            "type" => Type::findOrFail($request->input("type")),
            "user" => User::first()
        ]);
    }

    public function checkNumberExist(Request $request)
    {
        $typeId = $request->input("type");
        $noToCheck = $request->input("noToCheck");

        $noExists = Question::where("type_id", $typeId)
            ->where("no", $noToCheck)
            ->exists();
        
        return response()->json([
            'noExists' => $noExists
        ]);
    }

    public function getLastNumber(Request $request){
        $typeId = $request->input("type");

        $lastNo = Question::where("type_id", $typeId)
            ->max("no");

        return response()->json([
            'lastNo' => $lastNo ?? 0
        ]);
    }

    public function store(Request $request){
        $formData=$request->validate([
            "no"=>["required","numeric","min:1"],
            "body"=>["required"],
            "image"=>["file","nullable","max:20480"],
            "grade"=>["required","numeric"],
            "chapter"=>["required","numeric"],
            "type_id"=>["required","exists:types,id"]
        ]);

        if($request->hasFile("image")){
            $formData["image"] = Storage::disk("public")->put("uploads",$request->file("image"));
        }
        
        try {
            Question::create($formData);
        } catch (Exception $e) {
            return back()->withErrors(["error"=>"Failed to create question!!"]);
        }
    }
}
