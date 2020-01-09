@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>Hi, {{Auth::user()->name}}</h2></div>

            
            </div>

            <div class="card mb-3">
                <img src="storage/image/google-play-badge.png" class="card-img-top" alt="...">
                <div class="card-body">
                    <h3 class="card-title text-center">Download our App in Google Play Store to access all the feature.</h3>
                
                </div>
            </div>
    </div>
</div>

@endsection
