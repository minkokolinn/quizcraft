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


Route::get('/run-backup', function (Request $request) {
    if (!$request->query('key') || $request->query('key') !== "0WJHQ6DlhBquuA6DQke34pEe5TBFrT") {
        abort(403, 'Unauthorized: Invalid or missing key');
    }

    try {
        $database = config('database.connections.mysql.database');
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $database;

        $tableOrder = [
            'types',
            'questions',
            'options',
        ];

        $systemTables = ['migrations', 'jobs', 'failed_jobs', 'cache', 'sessions', 'users', 'password_reset_tokens', 'job_batches', 'cache_locks'];

        $nonSystemTables = array_filter($tables, function ($table) use ($tableKey, $systemTables, $tableOrder) {
            return !in_array($table->$tableKey, array_merge($systemTables, $tableOrder));
        });

        $finalTables = array_merge($tableOrder, array_column($nonSystemTables, $tableKey), $systemTables);

        $sqlDump = "";

        foreach ($finalTables as $tableName) {
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0]->{'Create Table'};
            $sqlDump .= "\n\n-- ----------------------------\n";
            $sqlDump .= "-- Table structure for `$tableName`\n";
            $sqlDump .= "-- ----------------------------\n";
            $sqlDump .= $createTable . ";\n";

            $rows = DB::table($tableName)->get();
            if (!$rows->isEmpty()) {
                $sqlDump .= "\n-- ----------------------------\n";
                $sqlDump .= "-- Records of `$tableName`\n";
                $sqlDump .= "-- ----------------------------\n";

                foreach ($rows as $row) {
                    $columns = array_map(fn($v) => "`$v`", array_keys((array)$row));
                    $values = array_map(function ($v) {
                        return is_null($v) ? 'NULL' : DB::getPdo()->quote($v);
                    }, array_values((array)$row));

                    $sqlDump .= "INSERT INTO `$tableName` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ");\n";
                }
            }
        }

        Mail::to('hmtmmmmkklmthn@gmail.com')->send(new DatabaseBackupMail($sqlDump));

        return response()->json(['message' => 'Backup completed and email sent successfully.']);
    } catch (\Throwable $e) {
        Log::error('Database backup failed: ' . $e->getMessage());

        return response()->json(['error' => 'Backup failed. Please try again later.']);
    }
});