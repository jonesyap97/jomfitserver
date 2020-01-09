@extends('layouts.app')
@section('content')


<div class="container py-3">
    
<h1>Latest Event</h1>
    <div class="py-3">  <a href="/news/create" class="btn btn-primary">Add New Event</a></div>
  
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Organiser</th>
                <th>Date</th>
            </tr>
        </thead>
        @forelse($newslist as $news)
        <tbody>
            <tr>
                <!-- TODO : fixed text overflow and fixed column width -->
                <td><a href="/news/{{$news->id}}">{{$news->title}}</a></td>
                <td>{{$news->description}}</td>
                <td>{{$news->organiser}}</td>
                <td>{{$news->created_at}}</td>
                
            </tr>    
        @empty
        <h2>No event</h2>
        @endforelse
        </tbody>
    </table>

    <!--  -->
</div>


@endsection