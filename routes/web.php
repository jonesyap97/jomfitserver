<?php
use App\Newsfeed;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/userpage', 'HomeController@userpage')->name('userpage');

Route::get('/', function () {
    $newslist = DB::table('newsfeeds')->latest()->get();
   
    return view('welcome',compact('newslist'));
});



Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/editprofile', 'Auth\RegisterController@updatetest');

Route::group(['middleware' => ['auth','isAdmin']], function () {
    // Court Handler
    Route::get('/court/index', 'CourtController@index')->name('court.index');
    Route::get('/court/create', 'CourtController@create');
    Route::post('/court', 'CourtController@store');
    Route::get('/court/{court}', 'CourtController@show');
    Route::get('court/{court}/edit', 'CourtController@edit');
    Route::patch('/court/{court}', 'CourtController@update');
    Route::delete('/court/{court}', 'CourtController@destroy');


    //Reservation
    Route::get('/reservation/test', 'ReservationController@displayAvailabilityPage');
    Route::get('/reservation/check', 'ReservationController@checkAvailability');
    Route::get('reservation/index', 'ReservationController@index')->name('reservation.index');
    Route::get('reservation/create', 'ReservationController@create')->name('reservation.create');
    Route::post('/reservation', 'ReservationController@store');
    Route::get('/reservation/{reservation}', 'ReservationController@show');
    Route::patch('/reservation/{reservation}', 'ReservationController@update');
    Route::delete('/reservation/{reservation}', 'ReservationController@destroy');
    Route::get('/reservation/bookedtoongoing/{reservation}', 'ReservationController@setStatusBookedtoOngoing');
    Route::get('/reservation/ongoingtofinish/{reservation}', 'ReservationController@setStatusOngoingtoFinish');

    //AJAX
    Route::get('/ajax/reservation/index','ReservationController@ajaxindex');
});



//NewsHandler
Route::get('news/index', 'NewsfeedController@index');
Route::get('news/create', 'NewsfeedController@create');
Route::post('/news', 'NewsfeedController@store');
Route::get('/news/{news}', 'NewsfeedController@show');
Route::delete('/news/{news}', 'NewsfeedController@destroy');
Route::get('news/{news}/edit', 'NewsfeedController@edit');
Route::patch('/news/{news}', 'NewsfeedController@update');
