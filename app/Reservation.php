<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['user_id','court_id','sport','venue','court_num','status','reserve_at','reserve_until'];
    // protected $guarded =[];
  
}
