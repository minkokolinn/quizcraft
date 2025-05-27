<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;

    protected $with = ["type"];

    protected $guarded = ["id"];

    public function type(){
        return $this->belongsTo(Type::class);
    }

    public function scopeFilter($query, $request){
        $query->when($request["type"] ?? false, function($query,$type){ //$type is selected id of type
            $query->whereHas("type", function($query) use ($type){
                $query->where("id",$type);
            });
        });
        $query->when($request["grade"] ?? false, function($query,$grade){
            $query->where("grade",$grade);
        });
        $query->when($request["chapter"] ?? false, function($query,$chapter){
            $query->where("chapter",$chapter);
        });
        $query->when($request["search"] ?? false, function($query,$search){
            $query->where("body","like","%$search%");
        });
    }
}
