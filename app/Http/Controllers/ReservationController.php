<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Court;
use App\User;
use App\Reservation;
use App\Dummy_time_slot;
use DB;
use Auth;

class ReservationController extends Controller
{
    public function index()
    {
        if(request()->mode == null)
        {
            $mode = 'Booked';
        }
        else
        {     
            $mode =  request()->mode;
        }

        $reservation_list = 
        DB::table('reservations')->select('reservations.*','users.name')
        ->where('reservations.status','=',$mode)
        ->join('users','reservations.user_id','=','users.id')
        ->latest()->paginate(10);
        $editpath = '/reservation/index?mode=' . $mode;
        $reservation_list->withPath($editpath);
        $courtlist = Court::select('sport')->where('status', '=', 'Available')->distinct()->orderBy('sport')->get();
        return view('reservation.index', compact('reservation_list','courtlist'));
    }



    public function create()
    {
        //retrieve court data
        $courtlist = Court::select('sport')->where('status', '=', 'Available')->distinct('sport')->orderBy('sport')->get();
        return view('reservation.create', compact('courtlist'));
    }



    public function store()
    {
        $data = request()->validate(
            [
                'user_id' => 'required|exists:users,id',
                'sport' => 'required',
                'status' => 'required',
                'venue' => 'required',
                'reserve_at' => 'required|date_format:"Y-m-d H"|after:today',
                'reserve_until' => 'required|date_format:"Y-m-d H"|after:reserve_at',
            ]
        );

        request()->reserve_at = request()->reserve_at.':00:00';
        request()->reserve_until = request()->reserve_until.':00:00';

        //Check whether select timeslot is booked or not
        $current = Reservation::select('court_id','reserve_at','reserve_until','court_num')->where([['sport','=',request()->sport],['venue','=',request()->venue]])
        ->whereIn('status',['Booked','Ongoing'])->get()->all();
    
        $crashcourt = [];  //Flag booked court number

        $totalcourt = Court::where([['sport','=',request()->sport],['venue','=',request()->venue]])->count(); //Get total number of court of selected sport
        
        for($i=0;$i<count($current);$i++)
        {
            if(!(request()->reserve_at >= $current[$i]->reserve_until || request()->reserve_until <=  $current[$i]->reserve_at))  
            {
                // Run here if selected time crash with one of the reservation
                array_push($crashcourt, $current[$i]->court_num); // Flag the court number that crash
            }
            
        }

        $crashcourt = array_unique($crashcourt);  // Trim the array to remove duplicated value;
        
        if(count($crashcourt) == $totalcourt)
        {
            //Implies that all court of the selected sport and venue are not available for selected time
            return redirect()->back()->with('crash','This time slot has been fully booked.');
        }
        else if(count($crashcourt) < $totalcourt)
        {
            //at least 1 court is available
            $availableCourt = Court::select('court','id')
            ->where([
                ['sport','=',request()->sport],
                ['status','=','Available']
                ])
            ->whereNotin('court',$crashcourt)
                ->get()->all();
           
            //Randomly select a available court and append to reservation detail
            $randomIndex = array_rand($availableCourt);
            $data += array('court_num'=>$availableCourt[$randomIndex]['court']);
            $data += array('court_id'=>$availableCourt[$randomIndex]['id']);
    
        }
        else{
            dd('Error');
        }

        //Save reservation
        $reserve = new Reservation();
        $reserve->create($data);

        return redirect()->route('reservation.index')->with('ReserveSuccess','New reservation created!');
    }



    public function show(Reservation $reservation)
    {
       
        $courtlist =  Court::where('status', '=', 'Available')->get();

        $userDetail = User::select('name','matric','contact')->where('id','=',$reservation['user_id'])->get();
        // dd($userDetail);
    
        return view('reservation.show', compact('reservation', 'userDetail','courtlist'));
    }



    public function update(Reservation $reservation)
    {
     
        $data = request()->validate(
            [
                'user_id' => 'required|exists:users,id',
                'court' => 'required',
                'status' => 'required',
                'reserve_at' => 'required|date_format:"Y-m-d H"|after:today',
                'reserve_until' => 'required|date_format:"Y-m-d H"|after:reserve_at',
            ]
        );

        $reservation->update($data);
      
        return redirect()->route('reservation.index');
    }




    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservation.index');
    }


    public function checkAvailability()
    {
        $date = request()->date;
        $sport = request()->sport;
        $venue = request()->venue;
     
        $isExist = Court::where([['venue','=',$venue],['sport','=',$sport]])->get();

         if($isExist->isEmpty())
         {
             return redirect()->back()->with('err_no_court','This court does not exist.');
         }

        $current =  Reservation::whereRaw("DATE(reserve_at) = '$date' AND status IN ('Booked','Ongoing') AND sport = '$sport' AND venue = '$venue'")->get();
        $totalcourt = Court::where([['sport','=',$sport],['venue','=',$venue]])->count(); //Get total number of court of selected sport
        $availableTime = [];
        
        $timeslot = DB::table('dummy_time_slot')->select('start_time','end_time')->get()->toArray();
        

        for($x=0;$x<count($timeslot);$x++)
        {
            $crashcounter = 0;
            $start_time = $date.' '.$timeslot[$x]->start_time;
            $end_time =   $date.' '.$timeslot[$x]->end_time;

            for($i=0;$i<count($current);$i++)
            {
                if(!($start_time >= $current[$i]->reserve_until || $end_time <=  $current[$i]->reserve_at))
                {
                    $crashcounter += 1 ;
                }
            }

            if($crashcounter < $totalcourt)
            {   
                $temp = ['start_time' =>$timeslot[$x]->start_time, 'end_time' =>$timeslot[$x]->end_time];
                array_push($availableTime,  $temp);
            }

            
        }
        
            $courtlist = Court::select('sport')->where('status', '=', 'Available')->distinct()->orderBy('sport')->get();
            // dd($availableTime[0]['start_time']);
            return  view('reservation.available', compact('availableTime','courtlist'));

       
    }

    public function displayAvailabilityPage()
    {
        $courtlist = Court::select('sport')->where('status', '=', 'Available')->distinct()->orderBy('sport')->get();
        return view('reservation.available',compact('courtlist'));
    }



    public function setStatusBookedtoOngoing(Reservation $reservation)
    {
        $status = ['status'=>'Ongoing'];
        $reservation->update($status);
        return back();
    }



    public function setStatusOngoingtoFinish(Reservation $reservation)
    {
        $status = ['status'=>'Finish'];
        $reservation->update($status);
        return back();
    }

    // *****************************************************************************************************************************************8
    //AJAX

    public function ajaxindex()
    {
        if(request()->mode == null)
        {
            $mode = 'Booked';
        }
        else
        {     
            $mode =  request()->mode;
        }

        $reservation_list = 
        DB::table('reservations')->select('reservations.*','users.name')
        ->where('reservations.status','=',$mode)
        ->join('users','reservations.user_id','=','users.id')
        ->latest()->paginate(10);

        $editpath = '/reservation/index?mode=' . $mode;
        $reservation_list->withPath($editpath);
        $courtlist = Court::select('sport')->where('status', '=', 'Available')->distinct()->orderBy('sport')->get();

      
        $str ="<table>";
      
        for($i=0;$i<count($reservation_list);$i++)
        {
           
            $str .="
            <tr>
                <td class='px-4'>". $reservation_list[$i]->id."</td>
                <td class='px-4'>". $reservation_list[$i]->name."</td>
                <td class='px-4'>". $reservation_list[$i]->sport."</td>
                <td class='px-4'>". $reservation_list[$i]->venue."</td>
                <td class='px-4'>". $reservation_list[$i]->court_num."</td>
                <td class='px-4'>". $reservation_list[$i]->reserve_at."</td>
                <td class='px-4'>". $reservation_list[$i]->reserve_until."</td>
                <td class='px-4'>". $reservation_list[$i]->status."</td>
                <td>
                    <div class='row px-3'>
                    <a href='/reservation/'".$reservation_list[$i]->id." class='btn btn-info btn-fsm mx-2 px-4'>Detail</a>
        
            " ;

            if($reservation_list[$i]->status == "Booked")
            {
                $str.="<a href='/reservation/bookedtoongoing/'".$reservation_list[$i]->id ." class='btn btn-success btn-sm mx-2 px-4'>Active</a>";
            }
            elseif($reservation_list[$i]->status == "Ongoing")
            {
                $str.="<a href='/reservation/ongoingtofinish/'".$reservation_list[$i]->id ." class='btn btn-dark  btn-sm mx-2 px-4'>Terminate</a>";
            }
            else{
                $str.=" <button class='btn btn-primary btn-sm mx-2 px-4' disabled>Done</button>";
            }

            $str.="</div>
            </td>
        </tr>";
        }

     


        $str.="</table>";
       echo($str);
       
     
    }
}
