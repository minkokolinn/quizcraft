<?php

use App\Http\Controllers\PaperController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UtilityController;
use App\Mail\DatabaseBackupMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Process\Process;

Route::get("/", [UtilityController::class, "dashboardIndex"]);
Route::get("/grade-portal", [UtilityController::class, "gradePortal"]);
Route::post("/grade-portal-process", [UtilityController::class, "gradePortalProcess"]);

Route::get("/type", [TypeController::class, "index"]);
Route::get("/type/create", [TypeController::class, "create"]);
Route::post("/type/create", [TypeController::class, "store"]);
Route::post("/type/delete", [TypeController::class, "delete"]);
Route::get("/type/{id}/edit", [TypeController::class, "edit"]);
Route::post("/type/{id}/edit", [TypeController::class, "update"]);

Route::get("/question", [QuestionController::class, "index"]);
Route::get("/question/create", [QuestionController::class, "create"]);
Route::post("/question/create", [QuestionController::class, "store"]);
Route::get("/question/create/checkNumberExist", [QuestionController::class, "checkNumberExist"]);
Route::get("/question/create/getLastNumber", [QuestionController::class, "getLastNumber"]);
Route::get("/question/{id}/edit", [QuestionController::class, "edit"]);
Route::post("/question/{id}/edit", [QuestionController::class, "update"]);
Route::post("/question/delete", [QuestionController::class, "delete"]);

Route::get("/paper/create/step/{step}", [PaperController::class, "stepShift"]);
Route::post("/paper/create/step/{step}", [PaperController::class, "stepShift"]);
Route::get("/question/fetchQuestions", [PaperController::class, "fetchQuestions"]);

Route::get("/profile", [UtilityController::class, "profile"]);
Route::post("/profile/update-grade", [UtilityController::class, "updateUserGrade"]);
Route::post("/profile/update-chapter", [UtilityController::class, "updateUserChapter"]);


Route::post('/run-backup', [UtilityController::class,"runBackup"]);
