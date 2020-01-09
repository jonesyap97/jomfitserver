@extends('layouts.app')
@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<h1>Courts</h1>

<div>
<a href="/court/create" class="btn btn-primary">Add new court</a>
</div>

@if(Session::has('msg'))
<div class="alert alert-primary alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{Session::get('msg')}}</strong>
</div>
@endif

@if(Session::has('msg2'))
<div class="alert alert-primary alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{Session::get('msg2')}}</strong>
</div>
@endif


<table class="table">
    <thead>
        <tr>
            <td>No.</td>
            <td>Name</td>
            <td>Venue</td>
            <td>Type</td>
            <td>Number</td>
            <td>Status</td>
        </tr>

    </thead>
    <tbody>
        @forelse($courts as $court)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="/court/{{$court->id}}">{{$court->name}}</a></td>
            <td>{{$court->venue}}</a></td>
            <td>{{$court->sport}}</a></td>
            <td>{{$court->court}}</a></td>
            <td>
                @php
                if($court->status == 'Available' )
                {
                    $status = "Available";
                }else
                {
                    $status = "Occupied";
                }
                @endphp
                {{$status}}
            </td>
        </tr>

        @empty
        <p>No courts</p>
        @endforelse    
    </tbody>
</table>

@endsection