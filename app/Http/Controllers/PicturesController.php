<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PicturesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get data with newest date
        $pictures = Picture::all()->sortByDesc('created_at');
        //dd($pictures);

        if (!Auth::check()) {
            return view('unloged.gallery', compact('pictures'));
        }
        return view('pictures.index', compact('pictures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            return view('pictures.create');
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Validate the inputs
        $request->validate([
            'name' => 'required'
        ]);

        // ensure the request has a file before we attempt anything else.
        if ($request->hasFile('file')) {

            $request->validate([
                'file' => 'mimes:jpeg,bmp,png,jpg'
            ]);

            $path =  $request->file('file')->store('gallery', 'public');

            $picture = new Picture();

            $picture->user = Auth::user()->name;
            $picture->name = $request->name;
            $picture->file_path = $path;
            $picture->accept = 1;
            $picture->visible = $request->visible;
            $picture->comment = $request->comment;
            $picture->likes = 0;
            $picture->album = 'nowy';

            $picture->save();
            return redirect()->route('pictures.create')->with('message', 'Poprawnie zapisano zdjęcie');
        } else {
            return redirect()->route('pictures.create')->with('message2', 'Nie wybrano pliku');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pictures = Picture::find($id);

        if (Auth::check()){
            return view('pictures.show', compact('pictures'));
        } else {
            return view('unloged.show', compact('pictures'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pictures = Picture::find($id);

        if (Auth::user()->name == $pictures->user){
            if (Auth::check()){
                return view('pictures.edit', compact('pictures'));
            } else {
                return view('pictures.index');
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function like($id)
    {
       // dd($id);
        $like = Picture::find($id);

        $like->likes = $like->likes + 1;
        $like->save();

        return redirect()->back();
    }

    public function report($id)
    {
        //...
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $picture = Picture::find($id);

        if ( Storage::disk('public')->delete($picture->file_path) ){
            Picture::destroy($id);
            return redirect()->route('pictures.index')->with('message', 'Praca ' . $picture->name . ' została usunięta.');
        } else {
            return redirect()->route('pictures.index')->with('message2', 'Praca nie została usunięta.');
        }
    }
}
