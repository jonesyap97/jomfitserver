@extends('layouts.app')
@section('content')
<!-- <meta http-equiv="refresh" content="30"> -->
<div style="margin-left:50px;">

    <h1>Reservation list</h1>

    @if(Session::has('ReserveSuccess'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{Session::get('ReserveSuccess')}}</strong>
    </div>
    @endif
    <div class="row">
        <div class="col-sm">
        <a href="{{route('reservation.create')}}" class="btn btn-primary">New Reservation</a>
        <a href="/reservation/index?mode=Booked" class="btn btn-primary">Show Booked</a>
        <a href="/reservation/index?mode=Ongoing" class="btn btn-primary">Show Ongoing</a>
        <a href="/reservation/index?mode=Finish" class="btn btn-primary">Show Finish</a>
        <a href="/reservation/test" class="btn btn-primary">Check Availablility</a>
        </div>
    </div>


    <table class='table-sm text-center table-hover'>
        @if(count($reservation_list)!= 0)
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Type</th>
                <th>Venue</th>
                <th>Court</th>
                <th>Reserve at</th>
                <th>Reserve until</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="table-body">
            @foreach($reservation_list as $reservation)
            <tr>
                <td class="px-4">{{$reservation->id}}</td>
                <td class="px-4">{{$reservation->name}}</td>
                <td class="px-4">{{$reservation->sport}}</td>
                <td class="px-4">{{$reservation->venue}}</td>
                <td class="px-4">{{$reservation->court_num}}</td>
                <td class="px-4">{{$reservation->reserve_at}}</td>
                <td class="px-4">{{$reservation->reserve_until}}</td>
                <td class="px-4">{{$reservation->status}}</td>
                <td > 
                    <div class="row px-3">
                            <a href="/reservation/{{$reservation->id}}" class='btn btn-info btn-fsm mx-2 px-4'>Detail</a>
        
                            @if($reservation->status == 'Booked')
                            <a href="/reservation/bookedtoongoing/{{$reservation->id}}" class='btn btn-success btn-sm mx-2 px-4'>Active</a>
                            @elseif($reservation->status == 'Ongoing')
                            <a href="/reservation/ongoingtofinish/{{$reservation->id}}" class='btn btn-dark  btn-sm mx-2 px-4'>Terminate</a>
                            @else
                            <button class='btn btn-primary btn-sm mx-2 px-4' disabled>Done</button>
                            @endif
                
                            <form action="/reservation/{{$reservation->id}}" method="post">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" >Delete</button>
                            </form>
                        </div>
                    
                </td>
            </tr>
            @endforeach
            @else
            <h3>No records</h3>
            @endif
        </tbody>
    </table>
    <div class="py-3">

    {{$reservation_list->links()}}
    </div>

    <button onclick="test()">Test</button>
    <div id="test-field"></div>
</div>

<script>
    function test()
    {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
               
                document.getElementById('table-body').innerHTML = this.responseText;
                console.log(this.responseText);
            }
            else
            {
                console.log(this.readyState);
            }

            
        
        };


        xhttp.open("GET", "/ajax/reservation/index", true);
        xhttp.send();
    }

  
</script>

@endsection