@extends('layouts.app2')

@section('content')


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">

<h1>New Reservation</h1>

@if(Session::has('crash'))
<div class="alert alert-danger ">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{Session::get('crash')}}</strong>
</div>
@endif



<div class="w-50 mx-auto">
    <form action="/reservation" method="post">
        @csrf
        <div class="form-group">
            <label for="user_id">User</label>
            <input type="text" name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
            @error('user_id') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class="form-group">
            <label for="sport">Court </label>
            <select class="form-control @error('sport') is-invalid @enderror" id="sport" name="sport">
                @forelse($courtlist as $court)
                <option value="{{$court->sport}}" selected>{{$court->sport}}</option>
                @empty
                <option value="" disabled selected>No court available</option>
                @endforelse
            </select>
        </div>

        <div class="form-group">
            <label for="venue">Venue</label>
            <select class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue">       
                <option value="Sport Hall 1" selected>Sport Hall 1</option>
                <option value="Sport Hall 2" selected>Sport Hall 2</option>
            </select>
            
        </div>


        <div class="form-group" style="position: relative">
            <label for="reserve_at">Reserve at</label>
            <input type="text" name="reserve_at" id="reserve_at" class="form-control @error('reserve_at') is-invalid @enderror">
            @error('reserve_at') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <input type="text" name="status" id="status" value="Booked" hidden>
        <div class="form-group" style="position: relative">
            <label for="reserve_until">Reserve until</label>
            <input type="text" name="reserve_until" id="reserve_until" class="form-control @error('reserve_until') is-invalid @enderror">
            @error('reserve_until') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>




        <button type="submit" class="btn btn-dark">Reserve</button>
    </form>




         <script>
        $('#reserve_at').datetimepicker({
            format: 'YYYY-MM-DD HH'
        });
        $('#reserve_until').datetimepicker({
            format: 'YYYY-MM-DD HH'
        });
    </script>

</div>





@endsection