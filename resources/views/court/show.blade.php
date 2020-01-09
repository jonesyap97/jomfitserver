@extends('layouts.app')
@section('content')

<h1>Court Details</h1>

<strong>Name</strong>
<p>{{$court->name}}</p>
<strong>Type</strong>
<p>{{$court->type}}</p>

<div class="row" >
    <div>
        <a href="/court/{{$court->id}}/edit" class="btn btn-success">Edit court detail</a>
    </div>
</div>

<div >
    @if($list)
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Booked at</th>
                <th>Booked until</th>
            </tr>
        </thead>
        <tbody>
           @foreach($list as $item)
           <td>{{$loop->iteration}}</td>
           <td>{{$item->user_id}}</td>
           <td>{{$item->reserve_at}}</td>
           <td>{{$item->reserve_until}}</td>
        </tbody>
            @endforeach
    </table>
    @else
    <h2>No booking</h2>
    @endif
</div>

<div>
    <form action="/court/{{$court->id}}" method="post">
        @method('DELETE')
        @csrf
        <button type="submit " class="btn btn-danger">Delete</button>
    </form>

</div>



@endsection