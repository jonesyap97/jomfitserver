@extends('layouts.app')
@section('content')

<h1>Insert new court</h1>

<div class="container">
<form action="/court" method="post">
    
    <div class="form-group">
        <label for="name">Court Name</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
        @error('name') <span class="invalid-feedback">{{$message}}</span>@enderror
    </div>

    <div class="form-group">
        <label for="venue">Venue</label>
        <select class="form-control" id="venue" name="venue">
            <option>Sport Hall 1</option>
            <option>Sport Hall 2</option>
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
        <input type="text" name="court" id="court" class="form-control @error('court') is-invalid @enderror">
        @error('court') <span class="invalid-feedback">{{$message}}</span>@enderror
    </div>
    @csrf

    <button type="submit" class="btn btn-primary">Create</button>

</form>

</div>


@endsection