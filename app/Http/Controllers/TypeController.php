<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TypeController extends Controller
{
    public function index(){
        return Inertia::render("Type/IndexView");
    }

    public function create(){
        return Inertia::render("Type/FormView");
    }

    public function store(Request $request){
        $request->validate([
            "name"=>["required","min:4"],
            "mark"=>["required","numeric"],
            "header"=>["required"]
        ]);
        
        Type::create($request->all());
    }

    public function delete(Request $request){
        $request->validate([
            "ids"=>["required"]
        ]);
        $ids = $request->input("ids");

        if(count($ids)==0){
            return back()->withErrors(["error"=>"No Ids found to delete!"]);
        }

        Type::whereIn("id",$ids)->delete();
    }

    public function edit($id){
        $type = Type::findOrFail($id);
        return Inertia::render("Type/FormView",[
            "type" => $type
        ]);
    }

    public function update($id, Request $request){
        $request->validate([
            "name"=>["required","min:4"],
            "mark"=>["required","numeric"],
            "header"=>["required"]
        ]);
        
        try {
            $type = Type::findOrFail($id);
            $type->update($request->all());
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(["error"=>"No Type found to update!"]);
        } catch (Exception $e){
            return back()->withErrors(["error"=>"Failed To update!"]);
        }
    }
}
