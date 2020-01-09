<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Validator;

use App\Http\Resources\Reservations;
use App\Http\Resources\SendCourtInfoApi;
use App\Http\Resources\SendEventFeedApi;


use App\Newsfeed;
use App\Court;
use App\User;
use App\Favourite;
use App\Reservation;
use App\Dummy_time_slot;
use DB;

class ApiController extends Controller
{
    public $successStatus = 200;


    public function reservation()
    {
        $data = request()->validate(
            [
                'sport' => 'required',
                'venue' => 'required',
                'reserve_at' => 'required|date_format:"d-m-Y H:i:s"|after:today',
                'reserve_until' => 'required|date_format:"d-m-Y H:i:s"|after:reserve_at',
            ]
        );
       
        //Modify Date Format
        $data['reserve_at']  = $this->changeDateFormat(request()->reserve_at,'Y-m-d H:i:s');
        $data['reserve_until'] = $this->changeDateFormat(request()->reserve_until,'Y-m-d H:i:s');
        request()->reserve_at  = $this->changeDateFormat(request()->reserve_at,'Y-m-d H:i:s');
        request()->reserve_until = $this->changeDateFormat(request()->reserve_until,'Y-m-d H:i:s');


        //Check whether select timeslot is booked or not
        $current = Reservation::select('court_id', 'reserve_at', 'reserve_until', 'court_num')->where([['sport','=',request()->sport],['venue','=',request()->venue]])
        ->whereIn('status', ['Booked','Ongoing'])->get()->all();
    
        $crashcourt = [];  //Flag booked court number
    
        $totalcourt = Court::where([['sport','=',request()->sport],['venue','=',request()->venue]])->count(); //Get total number of court of selected sport
        
        for ($i=0;$i<count($current);$i++) {
            if (!(request()->reserve_at >= $current[$i]->reserve_until || request()->reserve_until <=  $current[$i]->reserve_at)) {
                // Run here if selected time crash with one of the reservation
                array_push($crashcourt, $current[$i]->court_num); // Flag the court number that crash
            }
        }
    
        $crashcourt = array_unique($crashcourt);  // Trim the array to remove duplicated value;
        
        if (count($crashcourt) == $totalcourt) {
            //Implies that all court of the selected sport and venue are not available for selected time
            return response(['error'=>'This court has been fully booked. Try another court or date'], 400);
        } elseif (count($crashcourt) < $totalcourt) {
            //at least 1 court is available
            $availableCourt = Court::select('court', 'id')
            ->where([
                ['sport','=',request()->sport],
                ['status','=','Available']
                ])
            ->whereNotin('court', $crashcourt)
                ->get()->all();
           
            //Randomly select a available court and append to reservation detail
            $randomIndex = array_rand($availableCourt);
            $data += array('court_num'=>$availableCourt[$randomIndex]['court']);
            $data += array('court_id'=>$availableCourt[$randomIndex]['id']);
        } else {
            return response(['error'=>'Unknown error. Please try again.'], 400);
        }
    
        //Save reservation
        $data['user_id'] = Auth::id();
        $data['status'] = 'Booked';
        $reserve = new Reservation();
        $reserve->create($data);
        
        return response(['data'=>$data], $this->successStatus);
    }

    public function cancelReservation()
    {
        if(request()->book_id == null)
        {
            return response(['error'=>'Please provide the reservation ID'],400);
        }

      
        $reservation = Reservation::where([['user_id','=',Auth::id()],['id','=',(int)(request()->book_id)]])->first();
      
        if($reservation == null)
        {
            return response(['error'=>'Reservation not exist'],400);
        }
        $reservation->delete();

        return response(['success'=>'Reservation removed'],$this->successStatus);
    }


    public function court()
    {
        if (request()->date ==null ||request()->sport == null|| request()->venue ==null) {
            return response(['error'=>'Invalid parameter'], 400);
        }
        

        $isExist = Court::where([['venue','=',request()->venue],['sport','=',request()->sport]])->get();
    
        if ($isExist->isEmpty()) {
            return response(['error'=>'This court does not exist'], 400);
        }
    
        $date = $this->changeDateFormat(request()->date,'Y-m-d');
        $sport = request()->sport;
        $venue = request()->venue;
    
        $current =  Reservation::whereRaw("DATE(reserve_at) = '$date' AND status IN ('Booked','Ongoing') AND sport = '$sport' AND venue = '$venue'")->get();
        $totalcourt = Court::where([['sport','=',$sport],['venue','=',$venue]])->count(); //Get total number of court of selected sport
        $availableTime = [];
            
        $timeslot =  DB::table('dummy_time_slot')->select('start_time', 'end_time')->get()->toArray();
            
    
        for ($x=0; $x<count($timeslot); $x++) {
            $crashcounter = 0;
            $start_time = $date.' '.$timeslot[$x]->start_time;
            $end_time =   $date.' '.$timeslot[$x]->end_time;
    
            for ($i=0;$i<count($current);$i++) {
                if (!($start_time >= $current[$i]->reserve_until || $end_time <=  $current[$i]->reserve_at)) {
                    $crashcounter += 1 ;
                }
            }
    
            if ($crashcounter < $totalcourt) {
                $temp = ['start_time' =>$timeslot[$x]->start_time, 'end_time' =>$timeslot[$x]->end_time];
                array_push($availableTime, $temp);
            }
        }
            
        return  response(['data'=>$availableTime], $this->successStatus);
    }


    public function getUserProfile()
    {
        $user = Auth::user();
        return response(['data' => $user], $this->successStatus);
    }


    public function getUserReservation()
    {
        return new Reservations(Reservation::where([['user_id', '=', Auth::id()]])->whereIn('status',['Booked','Ongoing'])->get());
    }

   

    public function logoutApi()
    {
        if (Auth::check()) {
            $user = Auth::user()->token();
            $user->revoke();
            return response('logout',200);
        }
        return response('error',200);
    }


    public function editProfile()
    {
        $user = Auth::user();
        
        $editData = $this->validate(request(),[
            'name' => [Rule::unique('users')->ignore(Auth::id())],
            'email' => ['email',Rule::unique('users')->ignore(Auth::id())],
            'matric' => [Rule::unique('users')->ignore(Auth::id())],
            'contact'=>'numeric',
            'password' => ['string', 'min:8', 'confirmed'],
        ]);
        
        if(request()->password)
        {
            $editData['password'] = bcrypt($editData['password']);
        }
       

        $user->update($editData);
   


        return response(['success'=>"Profile updated"],$this->successStatus);

    }
    

    public function changeDateFormat($original, $format)
    {
        return date($format, strtotime($original));
    }

}
