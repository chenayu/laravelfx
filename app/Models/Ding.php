<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ding extends Model
{
    public $fillable = ['user_id','blog_id'];

    // 因为表中没有时间字段，所以必须设置为False
    public $timestamps = false;
}
