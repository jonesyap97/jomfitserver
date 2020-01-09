<?php

use App\Http\Resources\SendCourtInfoApi;
use App\Http\Resources\SendEventFeedApi;
use App\Newsfeed;
use App\Court;
use App\User;
use App\Favourite;
use App\Dummy_time_slot;
use App\Http\Resources\Reservations;
use Illuminate\Http\Request;
use App\Reservation;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/news', function () {
     return SendEventFeedApi::collection(Newsfeed::all());
   
   
});

Route::post('login', 'Api\ApiAuthController@login');
Route::post('register', 'Api\ApiAuthController@register');


Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/reservation', 'Api\ApiController@reservation');
    Route::get('/court','Api\ApiController@court');
    Route::get('/user/reservation','Api\ApiController@getUserReservation');
    Route::get('/cancelreservation','Api\ApiController@cancelReservation');
    Route::get('/user/profile','Api\ApiController@getUserProfile');
    Route::post('/user/edit','Api\ApiController@editProfile');
    Route::get('user/logout','Api\ApiController@logoutApi');
   




    Route::get('/favourite/list/', function () {

  
        $list = DB::table('favourites')->select('newsfeeds.*')
        ->where('user_id', '=', Auth::id())
        ->join('newsfeeds','favourites.event_id','=','newsfeeds.id')
        ->get();
 
  
        return response(['data'=>$list],200);
    });

    Route::get('/favourite/store', function () {
        $data = request()->validate([
            'event_id'=>'required|exists:newsfeeds,id',
        ]);
        $data['user_id'] = Auth::id();
        Favourite::create($data);
    
        return response('Favourite added', 200);
    });
    
    //Remove a user favourite
    Route::get('/favourite/unfavourite', function () {
        $data = request()->validate([
            'event_id'=>'required|exists:newsfeeds,id',
        ]);
        $data['user_id'] = Auth::id();
        // Favourite::destroy($data);
        Favourite::where([['user_id','=',Auth::id()],['event_id','=',$data['event_id']]])->delete();

        return response('Favourite removed', 200);
    });

    Route::get('/user/news',function(){
        $newslist =DB::table('newsfeeds')->get();
        $list = DB::table('favourites')->select('event_id')
        ->where('user_id', '=', Auth::id())
        ->get();


        
    
        for($i=0;$i<count($newslist);$i++)
        {
            for($j=0;$j<count($list);$j++)
            {
                if($newslist[$i]->id == $list[$j]->event_id)
                {
                    $newslist[$i]->isFavourite = 1;
                    break;
                }
                else
                {
                    $newslist[$i]->isFavourite = 0;
                }
            }
        }
      
        // $news = json_encode($news);
        dd($newslist);
        return response(['data'=>$newslist],200);
    });
});





