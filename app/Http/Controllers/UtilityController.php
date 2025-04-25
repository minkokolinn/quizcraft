<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UtilityController extends Controller
{
    public function dashboardIndex(){
        return Inertia::render("DashboardView",[
            "questionTotalCount" => Question::count()
        ]);
    }

}
