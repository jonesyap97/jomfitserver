@extends('layouts.app')
@section('content')
<h1>Add New Event</h1>

<div class="w-75 m-auto py-3">
    <form action="/news" method="post" enctype="multipart/form-data">
        @csrf
        <div class="py-2 form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror">
            @error('title') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class="py-2 form-group">
            <label for="organiser">Organiser</label>
            <input type="text" name="organiser" id="organiser" class="form-control @error('organiser') is-invalid @enderror">
            @error('organiser') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class="py-2 form-group">
            <label for="venue">Venue</label>
            <input type="text" name="venue" id="venue" class="form-control @error('venue') is-invalid @enderror">
            @error('venue') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class='col-md-6'>
            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" name="event_date" id="event_date" class="form-control @error('event_date') is-invalid @enderror">
            </div>
            @error('event_date') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class='col-md-6'>
            <div class="form-group">
                <label for="event_time">Event Time</label>
                <input type="time" name="event_time" id="event_time" class="form-control @error('event_time') is-invalid @enderror">
            </div>
            @error('event_time') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class="py-2 form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" cols="30" rows="7" class="form-control @error('description') is-invalid @enderror"></textarea>
            @error('description') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

        <div class="py-2 form-group">
            <label for="url">URL</label>
            <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror">
            @error('url') <span class="invalid-feedback">{{$message}}</span>@enderror
        </div>

     

        <div class="py-2 form-group" class="form-control @error('filename') is-invalid @enderror">
            <label for="filename">Image</label>
            <div>
                <input type="file" name="filename" id="image" onchange="loadFile(event)">
                @error('filename') <span class="invalid-feedback">{{$message}}</span>@enderror
            </div>
            
        </div>

       

        <div>
            <h2>Preview</h2>
            <img id="preview" width="300" />
        </div>


        <script type="application/javascript">
            var loadFile = function(event) {
                var image = document.getElementById('preview');
                image.src = URL.createObjectURL(event.target.files[0]);
            }
        </script>

        <button type="submit" class="btn btn-primary">Sumbit</button>
    </form>

    

</div>

@endsection