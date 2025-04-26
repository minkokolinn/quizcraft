<?php

use App\Http\Controllers\PaperController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UtilityController;
use Illuminate\Support\Facades\Route;

Route::get("/",[UtilityController::class,"dashboardIndex"]);

Route::get("/type",[TypeController::class,"index"]);
Route::get("/type/create", [TypeController::class,"create"]);
Route::post("/type/create",[TypeController::class,"store"]);
Route::delete("/type/delete",[TypeController::class,"delete"]);
Route::get("/type/{id}/edit",[TypeController::class,"edit"]);
Route::put("/type/{id}/edit", [TypeController::class,"update"]);

Route::get("/question",[QuestionController::class,"index"]);
Route::get("/question/create", [QuestionController::class,"create"]);
Route::post("/question/create", [QuestionController::class,"store"]);
Route::get("/question/create/checkNumberExist",[QuestionController::class,"checkNumberExist"]);
Route::get("/question/create/getLastNumber",[QuestionController::class,"getLastNumber"]);
Route::get("/question/{id}/edit",[QuestionController::class,"edit"]);
Route::post("/question/{id}/edit",[QuestionController::class,"update"]);
Route::delete("/question/delete",[QuestionController::class,"delete"]);
Route::get("/paper/configure",[PaperController::class,"configure"]);