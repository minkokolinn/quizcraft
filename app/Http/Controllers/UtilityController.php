<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Type;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UtilityController extends Controller
{
    public function dashboardIndex()
    {
        return Inertia::render("DashboardView");
    }

    public function gradePortal()
    {
        return Inertia::render("GradePortalView", [
            "user" => User::first()
        ]);
    }

    public function gradePortalProcess(Request $request)
    {
        session(['gradePortal' => $request->grade]);
        return redirect("/");
    }

    public function profile()
    {
        return Inertia::render("ProfileView", [
            "user" => User::first()
        ]);
    }

    public function updateUserGrade(Request $request)
    {
        $request->validate([
            "grade" => ["required"]
        ]);
        $user = User::first();
        $user->update([
            "grade" => $request->grade
        ]);
    }

    public function updateUserChapter(Request $request)
    {
        $request->validate([
            "chapter" => ["required"]
        ]);
        $user = User::first();
        $user->update([
            "chapter" => $request->chapter
        ]);
    }

    public function runBackup(Request $request)
    {
        if (!$request->input('key') || $request->input('key') !== "0WJHQ6DlhBquuA6DQke34pEe5TBFrT") {
            abort(403, 'Unauthorized: Invalid or missing key');
        }

        try {
            $database = config('database.connections.mysql.database');
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $database;

            $tableOrder = [
                'types',
                'questions',
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

            // Prepare download response
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';

            return response($sqlDump)
                ->header('Content-Type', 'application/sql')
                ->header('Content-Disposition', "attachment; filename=\"$filename\"");
        } catch (\Throwable $e) {
            Log::error('Database backup failed: ' . $e->getMessage());

            return response()->json(['error' => 'Backup failed. Please try again later.']);
        }
    }
}
