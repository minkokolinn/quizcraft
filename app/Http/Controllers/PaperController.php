<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaperController extends Controller
{
    public function configure(){
        return Inertia::render("Paper/ConfigureView",[
            "user"=>User::first()
        ]);
    }
}
