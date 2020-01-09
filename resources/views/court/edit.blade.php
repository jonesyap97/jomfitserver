@extends('layouts.app')
@section('content')

<h1>Edit court Info</h1>

<div class="container w-50">

    <form action="/court/{{$court->id}}" method="post">
        @method('PATCH')
        <div class="form-group">
            <label for="name">Court Name</label>
            <input type="text" name="name" id="name" value="{{$court->name}}" class="form-control @error('name') is-invalid @enderror">
            @error('name') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class="form-group">
        <label for="venue">Venue</label>
        <select class="form-control" id="venue" name="venue">
        @php
            if($court->venue == 'Sport Hall 1')
            {
                $pointer1 = 'selected';
                $pointer2 = '';
            }
            else
            {
                $pointer1 = '';
                $pointer2 = 'selected';
            }
        @endphp
            <option {{$pointer1}}>Sport Hall 1</option>
            <option {{$pointer2}}>Sport Hall 2</option>
        </select>
    </div>

        <div class="form-group">
            <label for="sport">Court Type (select one):</label>
            <select class="form-control" id="sport" name="sport">
                <option>Badminton </option>
                <option>Futsal</option>
                <option>Squash</option>
                <option>Gym</option>
            </select>
        </div>

        <div class="form-group">
            <label for="court">Court Number</label>
            <input type="text" name="court" id="court" value="{{$court->court}}"  class="form-control @error('court') is-invalid @enderror">
            @error('court') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>
        @csrf

        <button type="submit" class="btn btn-primary">Update</button>

    </form>
</div>


@endsection