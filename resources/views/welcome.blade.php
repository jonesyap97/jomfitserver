<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <!-- Styles -->
        <style>
             .news-section {
                font-family :'Verdana, Geneva, sans-serif';
                font-size: 84px;
            }
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                scroll-behavior: smooth;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #000;
                padding: 10px;
                font-size: 28px;
                margin :0px 30px 0px;
                width: 300px;
                font-weight: 500;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .links2 > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            

           
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links2">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
            <div>
                <img src="/storage/image/running.png" alt="Running" style="width:300px;height:300px;" >
            </div>
                <div class="title m-b-md">
                    Jomfit
                </div>
           
                <div class="links" >

                @if(Route::has('login'))
                    @if( Auth::user()['isAdmin']==1)
                    @auth
                    <a href="/reservation/index"  class="btn btn-light">Reservation</a>
                    <a href="/court/index" class="btn btn-light" >Court</a>
                    @endauth
                    @endif
                @endif
                    <a href="#news-section" class="btn btn-light">Current Event</a>
                </div>    
                
        </div>
        </div>        
        <div id='news-section'>
        <div>
            <h1 class="text-lg-center py-4" style="font-size:56px;">Current Event</h1>
        </div>
          
            <br>
        <div class="container">
            

             @foreach($newslist as $news)
            <div class="card mb-3" style="max-width: 100%">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <a href='/storage/uploads/{{$news->filename}}'>
                        <img src="/storage/uploads/{{$news->filename}}" class="card-img" alt="..." style="max-height:400px;max-width:300px;">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h3 class="card-title">{{$news->title}}</h3>
                            <p class="card-text">{{$news->description}}</p>
                            <p>Organiser : {{$news->organiser}}</p>
                            <p>Venue : {{$news->venue}}</p>
                            <p>Date  : {{$news->event_date}}  {{$news->event_time}}</p>
                            <p>Link : <a href='http://{{$news->url}}'>{{$news->url}}</a></p>

                        </div>
                    </div>
                </div>
            </div>

            
            @endforeach
         
    </body>
</html>
