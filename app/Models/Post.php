<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'description' , 'user_id'];


    public function user_creator(){
        return $this->belongsTo(User::class , 'user_id');
    }
}


