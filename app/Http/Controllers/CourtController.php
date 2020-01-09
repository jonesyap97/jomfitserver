<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Court;
use App\Reservation;

class CourtController extends Controller
{

  

    public function index()
    {
        $courts = Court::all();

        return view('court.index',compact('courts'));

    }

    public function create()
    {
        return view('court.create');
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'venue'=>'required',
            'sport'=>'required',
            'court'=>'required',
        ]);
        
        //Check court detail exist in database

        $selected =  Court::where([['venue','=',request()->venue],['sport','=',request()->sport]])->get();

        for($i=0;$i<count($selected);$i++)
        {
            
            if(request()->court == $selected[$i]->court || request()->name == $selected[$i]->name)
            {
                return redirect()->route('court.index')->with('msg','This court info existed. Please try other detail.');
            }
        }

        Court::create($data);

        return redirect()->route('court.index')->with('msg','New Court Created!');
    }

    public function show(Court $court)
    {
    
        $list =  Reservation::where('court_id','=',$court->id)->get();
        return view('court.show',compact('court','list'));


    }

    public function edit(Court $court)
    {
        return view('court.edit',compact('court'));
    }

    public function update(Court $court)
    {
        
        $data = request()->validate([
            'name' => 'required',
            'venue'=>'required',
            'sport'=>'required',
            'court'=>'required',
        ]); 
        
    
        $court->update($data);

        return redirect()->route('court.index')->with('msg2','Court detail edited!');
    }

    public function destroy(Court $court)
    {
            $court->delete();
            return redirect('court/index');
    }
}
