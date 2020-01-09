<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    //

    //Option 1
    //protected $fillable =['name'];

    //Option 2
    protected $guarded=[];
}
