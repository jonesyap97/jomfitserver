@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Welcome back, {{Auth::user()->name}}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div>
                        <button class="btn btn-primary" onclick="location.href='/editprofile'">Edit Profile</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection