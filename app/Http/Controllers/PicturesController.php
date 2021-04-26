<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\like;
use App\Models\Picture;
use App\Models\PicturesReport;
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
        $pictures = Picture::where('accept', 1)->latest()->paginate(8);

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

            $picture->user_id = Auth::id();
            $picture->user = Auth::user()->name;
            $picture->name = $request->name;
            $picture->file_path = $path;
            $picture->accept = 1;
            $picture->visible = $request->visible;
            $picture->comment = $request->comment;
            $picture->likes = 0;
            $picture->views = 0;
            $picture->allow_comments = $request->allowcomments;
            $picture->album = "main";
            $picture->ip = $request->ip();

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
    public function show(Request $request, $id)
    {
        $pictures = Picture::find($id);
        $follow = Follower::where('user_id', $pictures->user_id)->where('follow_id', Auth::id())->first();

        $comments = $pictures->comments()->where('picture_id', $id)->latest()->paginate(20);

        if(!$request->session()->has('visit' . $id)) {
            $request->session()->put('visit' . $id, 1);
            $pictures->increment('views');
        }

        if (Auth::check()){
            if ($pictures->visible == 1 && $pictures->accept == 1){
                return view('pictures.show')->with(['pictures' => $pictures, 'comments' => $comments]);
            } elseif ($pictures->visible == 0 && $pictures->user == Auth::user()->name){
                return view('pictures.show')->with(['pictures' => $pictures, 'comments' => $comments]);
            } elseif ($pictures->visible == 0 && $follow->rights == 1 && $pictures->accept == 1) {
                return view('pictures.show')->with(['pictures' => $pictures, 'comments' => $comments]);
            } else {
                return redirect()->back();
            }
        } else {
            if ($pictures->visible == 1 && $pictures->accept == 1){
                return view('unloged.show')->with(['pictures' => $pictures, 'comments' => $comments]);
            } else {
                return redirect()->back();
            }
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

        if (Auth::user()->name == $pictures->user && $pictures->accept == 1){
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
        $pictures = Picture::find($id);

        $pictures->name = $request->name;
        $pictures->comment = $request->comment;
        $pictures->visible = $request->visible;
        $pictures->allow_comments = $request->allowcomments;

        $pictures->save();

        return redirect()->route('pictures.show', ['picture' => $pictures->id])->with('message', 'Poprawnie zapisano zmiany');
    }

    public function report($id)
    {
        $pictures = Picture::find($id);
        if (Auth::check()){
            return view('pictures.report', compact('pictures'));
        } else {
            return view('unloged.report', compact('pictures'));
        }
    }

    public function top()
    {
        $pictures = Picture::where('accept', 1)->where('visible', 1)->orderByDesc('likes')->take(10)->get();

        if (Auth::check()) {
            return view('pictures.top10', compact('pictures'));
        } else {
            return view('unloged.top10', compact('pictures'));
        }
    }

    public function topviews()
    {
        $pictures = Picture::where('accept', 1)->where('visible', 1)->orderByDesc('views')->take(10)->get();

        if (Auth::check()) {
            return view('pictures.topviews', compact('pictures'));
        } else {
            return view('unloged.topviews', compact('pictures'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function SendReport(Request $request, $id)
    {
        $report = new PicturesReport();
        $pictures = Picture::find($id);

        if (Auth::check()){
            $report->user_id = Auth::id();
            $report->ip_address = $request->ip();
            $report->picture_id = $id;
            $report->reason = $request->reason;

            $report->save();
            return redirect()->route('pictures.show', ['picture' => $pictures->id])
                ->with('message', __('Image has been submitted for moderation'));

        } else {
            $report->ip_address = $request->ip();
            $report->picture_id = $id;
            $report->reason = $request->reason;
            $pictures->message = 1;

            $report->save();
            return view('unloged.show', compact('pictures'))
                ->with('message', __('Image has been submitted for moderation'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */
    public function destroy($id)
    {
        $picture = Picture::find($id);

        if ( Storage::disk('public')->delete($picture->file_path) ) {
            Picture::destroy($id);
            like::where('picture_id', $id)->delete();
            Comment::where('picture_id', $id)->delete();
            return response()->json([
               'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode();
        }
    }
}
