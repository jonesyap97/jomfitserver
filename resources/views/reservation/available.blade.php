@extends('layouts.app')
@section('content')

<div class='container'>

   
   
    <h2>Check Availability</h2>

    @if(Session::has('err_no_court'))
    <div class="alert alert-warning alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{Session::get('err_no_court')}}</strong>
    </div>
    @endif

    <form action="\reservation\check" method="get">
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date">
        </div>

        <div class="form-group">
            <label for="venue">Venue</label>
            <select class="form-control @error('venue') is-invalid @enderror" id="venue" name="venue">       
                <option value="Sport Hall 1" selected>Sport Hall 1</option>
                <option value="Sport Hall 2" selected>Sport Hall 2</option>
            </select>
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
        <button type="submit" class="btn btn-primary">Check</button>
    </form>

    @if($availableTime ?? '')
    <div class="col-6">
        <table class="table">
            <thead>
                <td>No.</td>
                <td>Start time</td>
                <td>End time</td>
            </thead>

            <tbody>
                @forelse($availableTime ?? '' as $slot)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$slot['start_time']}}</td>
                    <td>{{$slot['end_time']}}</td>
                </tr>

                @empty
                <tr>
                    <td>No free</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>



@endsection