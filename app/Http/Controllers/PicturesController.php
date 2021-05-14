<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\like;
use App\Models\Picture;
use App\Models\PicturesReport;
use App\Models\User;
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
        if (!Auth::check()) {
            $pictures = Picture::where('accept', 1)->latest()->paginate(8);
            return view('unloged.gallery', compact('pictures'));
        } else {
            $blocks_ids = BlockedUser::where('user_id', Auth::id())->pluck('blocks_user');

            $pass_ids2 = Picture::where('visible', 1)
                ->pluck('id');

            $rigts= Follower::where('follow_id', Auth::id())
                ->where('rights', 1)
                ->pluck('user_id');

            $except = Picture::whereIn('user_id', $rigts)->where('visible', 0)->pluck('id');

            $x = count($pass_ids2);
            $y = count($except);

            for ($i=0; $i<=$y-1; $i++) {
                $pass_ids2[$x] = $except[$i];
                $x++;
            }
            $pictures = Picture::where('accept', 1)->whereIn('id', $pass_ids2)
                ->whereNotIn('user_id', $blocks_ids)
                ->latest()
                ->paginate(8);

            return view('pictures.index', compact('pictures'));
        }
    }

    /**
     * Display pictures only from follow by you.
     * And hidden pictures from user who gives you
     * rights to watch that pictures.
     *
     * @return \Illuminate\Http\Response
     */
    public function yoursgallery()
    {
        $user = User::where('id', Auth::id())->first();
        $usersIds = $user->following()->pluck('follow_id')->all();

        /*
         *  Collecting the IDs of users I am following,
         *  and gives me right to watch hidden pictures.
        */
        $rights = $user->followers()->where('follow_id', $user->id)
            ->whereIn('user_id', $usersIds)
            ->where('rights', 1)
            ->pluck('user_id')->all();

        $pics_set1 = Picture::whereIn('user_id', $usersIds)->where('visible', 1)->pluck('id');

        // collecting pictures id's of users who let me see their hidden pictures
        $pics_set2 = Picture::where('visible', 0)->whereIn('user_id', $rights)->pluck('id');

        /*
         * Merging ID's
         */
        $set1_count = count($pics_set1);
        $set2_count = count($pics_set2);

        for ($i=0; $i<=$set2_count-1; $i++) {
            $pics_set1[$set1_count] = $pics_set2[$i];
            $set1_count++;
        }
        // Getting images from id's acquired earlier
        $pictures = Picture::whereIn('id', $pics_set1)->where('accept', 1)->latest()->paginate(8);

        return view('pictures.yoursgallery', compact('pictures'));
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
            $picture->moderator_review = 1;
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
        $blocked_comments = BlockedUser::where('user_id', Auth::id())->pluck('blocks_user');
        $comments = $pictures->comments()->where('picture_id', $id)->whereNotIn('user_id', $blocked_comments)->latest()->paginate(20);

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


    public function search(Request $request)
    {
        $pictures = Picture::where('name', 'LIKE', "%$request->search%")->latest()->paginate(8);

        if (!Auth::check()) {
            return view('unloged.gallery', compact('pictures'));
        }
        return view('pictures.index', compact('pictures'));
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
            $report->user_name = Auth::user()->name;
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
            return redirect()->route('unloged.show', ['picture' => $pictures->id])
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
            ])->setStatusCode(200);
        }
    }
}
