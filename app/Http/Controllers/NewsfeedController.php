<?php

namespace App\Http\Controllers;

use App\Newsfeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\File;

class NewsfeedController extends Controller
{
    public function index()
    {
        $newslist = Newsfeed::latest()->get();
        // dd($newslist);
        return view('news.index', compact('newslist'));
    }

    public function create()
    {
        return view('news.create');
    }

    public function store()
    {
        $news = new Newsfeed;
        // dd(request()->filename);
        if (request()->filename) {
           
            $data = request()->validate(
                [
                    'title' => 'required',
                    'organiser' => 'required',
                    'description' => 'required',
                    'event_date' => 'required',
                    'event_time' => 'required',
                    'venue'=>'required',
                    'url' => 'required',
                    'filename' => 'required|image',
                ]
            );

            //get the hashed filename by Laravel
            $imagename = request()->filename->hashName();
            //Populate item
            $news->title = request()->title;
            $news->organiser = request()->organiser;
            $news->description = request()->description;
            $news->filename = $imagename;
            $news->event_date = request()->event_date;
            $news->event_time = request()->event_time;
            $news->url = request()->url;
            $news->venue = request()->venue;

            //store image to public disk
            $this->storeImage($data);

            $news->save();
        } 
        else
        {
            $data =  request()->validate(
                [
                    'title' => 'required',
                    'organiser' => 'required',
                    'description' => 'required',
                    'event_date' => 'required',
                    'venue'=>'required',
                    'event_time' => 'required',
                    'url' => 'required',
                ]
            );

            Newsfeed::create($data);
        }

        return redirect('/news/index');
    }

    public function show(Newsfeed $news)
    {
        return view('news.show', compact('news'));
    }

    public function storeImage($data)
    {
        if (request()->has('filename')) {
            request()->filename->store('uploads', 'public');
        }
    }

    public function edit(Newsfeed $news)
    {
        return view('news.edit', compact('news'));
    }

    public function update(Newsfeed $news)
    {
        if (request()->filename) {
            //delete old file if new file is uploaded
            $file_to_be_deleted = $news->filename;

            // dd(request()->filename->hashName());

            //update entry
            $newdata =  request()->validate(
                [
                    'title' => 'required',
                    'organiser' => 'required',
                    'description' => 'required',
                    'event_date' => 'required',
                    'event_time' => 'required',
                    'venue'=>'required',
                    'url' => 'required',
                    'filename' => 'required|image',
                ]
            );

            $news->title = request()->title;
            $news->organiser = request()->organiser;
            $news->description = request()->description;
            $news->filename = request()->filename->hashName();  //replace filename in database to laravel hash filename convention

            $this->storeImage($newdata);  //store new image to local disk

            $news->update();      //update entry

            unlink(public_path("\storage\uploads\\" . $file_to_be_deleted));
        } else {
            $newdata =  request()->validate(
                [
                    'title' => 'required',
                    'organiser' => 'required',
                    'description' => 'required',
                    'event_date' => 'required',
                    'event_time' => 'required',
                    'venue'=>'required',
                    'url' => 'required',
                ]
            );
            $news->update($newdata);
        }

        return redirect('news/index');
    }

    public function destroy(Newsfeed $news)
    {

        $file_to_be_deleted = $news->filename;
        // dd(app_path().'\storage\uploads\\'.$file_to_be_deleted);
        // File::delete(app_path().'/public/uploads//'.$file_to_be_deleted);


        //Only delete file in local storage if exist
        if($file_to_be_deleted)
        {
            unlink(public_path("\storage\uploads\\" . $file_to_be_deleted));
        }
        
        $news->delete();

        return redirect('news/index');
    }
}
