@extends('layouts.app')
@section('content')

<div class="container">
    <h1>Contact Us</h1>

    <div class="py-4">
        <form action="#">
            <div class="form-group row ">
                <label for="name">Name</label>
                <div class="col-md-6"></div>
                <input type="text" name="name" id="name" class="form-control">
            </div>

            <div class="form-group row">
                <label for="contact">Contact</label>
                <div class="col-md-6"></div>
                <input type="text" name="contact" id="contact" class="form-control">
            </div>

            <div class="form-group row">
                <label for="description">Description</label>
                <div class="col-md-6"></div>
                <textarea name="description" id="description" cols="30" rows="10" class="form-control"></textarea>
            </div>

            <div><button type="submit" class="btn btn-primary">Sumbit</button></div>
        </form>
    </div>

</div>


@endsection