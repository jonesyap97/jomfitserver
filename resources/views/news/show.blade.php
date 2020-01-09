@extends('layouts.app')

@section('content')

<div class="container">
<div>
    <h1>{{$news->title}}</h1>
</div>


<div>
    
    <h4>Organiser</h4>
    <p>{{$news->organiser}}</p>
    <br>
    <h4>Event Date</h4>
    <p>{{$news->event_date}}</p>
    <br>
    <h4>Event Time</h4>
    <p>{{$news->event_time}}</p>
    <br>
    <h4>Venue</h4>
    <p>{{$news->venue}}</p>
    <br>
 
    @if($news->filename)
    @php
    $file_path = "/storage/uploads/".$news->filename
    @endphp
    <div class="row">
        <div class="col-md-6"><img src="{{$file_path}}" alt="{{$news->filename}}" class="img-fluid"></div>
        <div class="col-md-6">
            <h2>Event Description</h2>
            {{$news->description}}
        </div>
    </div>
    @endif
</div>

<div>
    <form action="/news/{{$news->id}}" method="post">
        @method('DELETE')
        @csrf
        <button type="submit" class="btn btn-danger">Delete Event</button>
    </form>
</div>

<div><a href="/news/{{$news->id}}/edit" class="btn btn-warning"> Edit</a></div>



</div>



@endsection