<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        $grade = $request->input("grade");
        $chapter = $request->input("chapter");
        $noToCheck = $request->input("noToCheck");

        $noExists = Question::where("type_id", $typeId)
            ->where("grade", $grade)
            ->where("chapter", $chapter)
            ->where("no", $noToCheck)
            ->exists();

        return response()->json([
            'noExists' => $noExists
        ]);
    }

    public function getLastNumber(Request $request)
    {
        $typeId = $request->input("type");
        $grade = $request->input("grade");
        $chapter = $request->input("chapter");

        $lastNo = Question::where("type_id", $typeId)
            ->where("grade", $grade)
            ->where("chapter", $chapter)
            ->max("no");

        return response()->json([
            'lastNo' => $lastNo ?? 0
        ]);
    }

    public function store(Request $request)
    {
        $formData = $request->validate([
            "no" => ["required", "numeric", "min:1"],
            "body" => ["required"],
            "image" => ["file", "nullable", "max:20480"],
            "grade" => ["required", "numeric"],
            "chapter" => ["required", "numeric"],
            "type_id" => ["required", "exists:types,id"],
        ]);

        if ($request->hasFile("image")) {
            $filename = uniqid() . '.' . $request->file("image")->getClientOriginalExtension();

            // Move the file to public/uploads
            $request->file("image")->move(public_path("uploads"), $filename);

            // Store only the relative path in the database
            $formData["image"] = "/uploads/" . $filename;
        }

        try {
            // Update number if question is added with already existed number
            $noExists = Question::where("type_id", $formData["type_id"])
                ->where("grade", $formData["grade"])
                ->where("chapter", $formData["chapter"])
                ->where("no", $formData["no"])
                ->exists();
            if ($noExists) {
                Question::where('type_id', $formData['type_id'])
                    ->where("grade", $formData["grade"])
                    ->where("chapter", $formData["chapter"])
                    ->where('no', '>=', $formData['no'])
                    ->increment('no');
            }

            $question = Question::create($formData);
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Failed to create question!!"]);
        }
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        return Inertia::render("Question/FormView", [
            "type" => $question->type,
            "user" => User::first(),
            "question" => $question
        ]);
    }

    public function update($id, Request $request)
    {
        $updatedData = $request->validate([
            "no" => ["required", "numeric", "min:1"],
            "body" => ["required"],
            "image" => ["file", "nullable", "max:20480"],
            "grade" => ["required", "numeric"],
            "chapter" => ["required", "numeric"],
            "type_id" => ["required", "exists:types,id"],
        ]);

        if ($request->hasFile("image")) {
            $filename = uniqid() . '.' . $request->file("image")->getClientOriginalExtension();

            // Move the file to public/uploads
            $request->file("image")->move(public_path("uploads"), $filename);

            // Store only the relative path in the database
            $formData["image"] = "/uploads/" . $filename;
        }
        try {
            $question = Question::findOrFail($id);

            // if no image in file input and this question has already image
            if ($updatedData["image"] == null && $question->image != null) {
                $updatedData["image"] = $question->image;
            }

            // Update number if question is added with already existed number
            $noExists = Question::where("type_id", $updatedData["type_id"])
                ->where("no", $updatedData["no"])
                ->where("grade", $updatedData["grade"])
                ->where("chapter", $updatedData["chapter"])
                ->exists();
            if ($noExists && $question->no != $updatedData["no"]) {
                Question::where('type_id', $updatedData['type_id'])
                    ->where("grade", $updatedData["grade"])
                    ->where("chapter", $updatedData["chapter"])
                    ->where('no', '>=', $updatedData['no'])
                    ->increment('no');
            }

            $question->update($updatedData);
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(["error" => "No question found to update!"]);
        } catch (Exception $e) {
            return back()->withErrors(["error" => "Failed To update!"]);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            "ids" => ["required"]
        ]);
        $ids = $request->input("ids");

        if (count($ids) == 0) {
            return back()->withErrors(["error" => "No Ids found to delete!"]);
        }

        DB::beginTransaction();

        try {
            foreach ($ids as $id) {
                $question = Question::find($id);
                if (!$question) {
                    continue;
                }

                if ($question->image) {
                    $relativePath = $question->image; // remove wrong prefix
                    $fullPath = public_path($relativePath); // get actual full path
                    if (File::exists($fullPath)) {
                        File::delete($fullPath);
                    }
                }

                $typeId = $question->type_id;
                $grade = $question->grade;
                $chapter = $question->chapter;
                $no = $question->no;

                $question->delete();

                Question::where("type_id", $typeId)
                    ->where("grade", $grade)
                    ->where("chapter", $chapter)
                    ->where("no", ">", $no)
                    ->decrement("no");
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(["error" => "Failed to delete questions."]);
        }
    }
}
