<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /** @use HasFactory<\Database\Factories\OptionFactory> */
    use HasFactory;

    protected $guarded = ["id"];

    public $timestamps = false;


    public function question(){
        return $this->belongsTo(Question::class);
    }
}
