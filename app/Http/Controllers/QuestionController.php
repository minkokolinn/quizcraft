<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Type;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    public function getLastNumber(Request $request)
    {
        $typeId = $request->input("type");

        $lastNo = Question::where("type_id", $typeId)
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
            "A" => ["nullable", "string"],
            "B" => ["nullable", "string"],
            "C" => ["nullable", "string"],
            "D" => ["nullable", "string"],
        ]);

        if ($request->hasFile("image")) {
            $formData["image"] = Storage::disk("public")->put("uploads", $request->file("image"));
        }

        try {
            // Update number if question is added with already existed number
            $noExists = Question::where("type_id", $formData["type_id"])
                ->where("no", $formData["no"])
                ->exists();
            if ($noExists) {
                Question::where('type_id', $formData['type_id'])
                    ->where('no', '>=', $formData['no'])
                    ->increment('no');
            }

            $optionsData = collect(['A', 'B', 'C', 'D'])->map(function ($label) use ($request) {
                $content = $request->input($label);
                return $content ? ['label' => $label, 'content' => $content] : null;
            })->filter()->values()->all();
            unset($formData['A'], $formData['B'], $formData['C'], $formData['D']);

            $question = Question::create($formData);
            if ($formData["type_id"] == 3 && !empty($optionsData)) {
                $question->options()->createMany($optionsData);
            }
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
            "A" => ["nullable", "string"],
            "B" => ["nullable", "string"],
            "C" => ["nullable", "string"],
            "D" => ["nullable", "string"],
        ]);

        if ($request->hasFile("image")) {
            $updatedData["image"] = Storage::disk("public")->put("uploads", $request->file("image"));
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
                ->exists();
            if ($noExists && $question->no != $updatedData["no"]) {
                Question::where('type_id', $updatedData['type_id'])
                    ->where('no', '>=', $updatedData['no'])
                    ->increment('no');
            }

            $optionsData = collect(['A', 'B', 'C', 'D'])->map(function ($label) use ($request) {
                $content = $request->input($label);
                return $content ? ['label' => $label, 'content' => $content] : null;
            })->filter()->values()->all();
            unset($updatedData['A'], $updatedData['B'], $updatedData['C'], $updatedData['D']);

            $question->update($updatedData);
            if ($updatedData["type_id"] == 3 && !empty($optionsData)) {
                $question->options()->delete();
                $question->options()->createMany($optionsData);
            }
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

        $images = Question::whereIn("id", $ids)->pluck("image")->filter();

        foreach ($images as $imagePath) {
            Storage::disk("public")->delete($imagePath);
        }

        Question::whereIn("id", $ids)->delete();
    }
}
