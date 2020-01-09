@extends('layouts.app')
@section('content')
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>


<div class="container">

<h1>Reservation detail</h1>

<p>Last Update    : {{$reservation->updated_at}}</p>
<p>Reservation ID : {{$reservation->id}}</p>
<p>Name : {{$userDetail[0]->name}}</p>
<p>Matric No. : {{$userDetail[0]->matric}}</p>
<p>Contact No. : {{$userDetail[0]->contact}}</p>

<div class="col-md-12">
    <form action="">
    <div class="form-group col-form-label">
        
            <label for="staticid">Reservation ID</label>
            <input type="text" readonly class="form-control-plaintext" id="staticid" value="{{$reservation->id}}">
       
    </div>

    </form>
</div>



<div class="w-50 mx-auto">
    <form action="/reservation/{{$reservation->id}}" method="post">
        @method('PATCH')

        @csrf
        <div class="form-group">
            <label for="user_id">User</label>
            <input type="text" name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" value="{{$reservation->user_id}}">
            @error('user_id') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class="form-group">
            <label for="court">Court </label>
            <select class="form-control-lg form-control @error('court') is-invalid @enderror" id="court" name="court">
                @forelse($courtlist as $court)
                <option value="{{$court->id}}" selected>{{$court->name}} - {{$court->type}}</option>
                @empty
                <option value="" disabled selected>No court available</option>
                @endforelse
            </select>
        </div>


        <div class="form-group" style="position: relative">
            <label for="reserve_at">Reserve at</label>
            <input type="text" name="reserve_at" id="reserve_at" value="{{$reservation->reserve_at}}" class="form-control @error('reserve_at') is-invalid @enderror" >
            @error('reserve_at') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <input type="text" name="status" id="status" value="Booked" hidden>

        <div class="form-group" style="position: relative">
            <label for="reserve_until">Reserve until</label>
            <input type="text" name="reserve_until" id="reserve_until"  value="{{$reservation->reserve_until}}" class="form-control @error('reserve_until') is-invalid @enderror" >
            @error('reserve_until') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>




        <button type="submit" class="btn btn-dark">Update</button>
    </form>
</div>


    <script type="text/javascript">
        $('#reserve_at').datetimepicker({
            format: 'YYYY-MM-DD HH'
        });
        $('#reserve_until').datetimepicker({
            format: 'YYYY-MM-DD HH'
        });
    </script>

    @endsection