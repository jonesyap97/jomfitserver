<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Favourite;

class FavouriteController extends Controller
{
    public function index()
    {
        $list = Favourite::where('user_id','=',request()->user)->get();
        dd($list);
    }

    public function store()
    {
        $data = request()->validate([
            'user_id'=>'required|exists:users,id',
            'event_id'=>'required|exists:newsfeed,id',
        ]);

        Favourite::create($data);

        dd('success');
    }
}
