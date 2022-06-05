<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Follower;
use App\Models\like;
use App\Models\Picture;
use App\Models\PicturesReport;
use App\Models\User;
use Illuminate\Support\Arr;
use Intervention\Image\Facades\Image;
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

        /*
         *  W galerii nie wyswietlaja sie ukryte zdjecia uzytkownika
         */

        if (!Auth::check()) {
            $pictures = Picture::where('accept', 1)->latest()->paginate(8);
            return view('unloged.gallery', compact('pictures'));
        } else {
            // collecting id's of users that are blocked.
            $blocks_ids = BlockedUser::where('user_id', Auth::id())->pluck('blocks_user');

            // collecting my hidden pictures.
            $user_hidden_pics = Picture::where('user_id', Auth::id())->where('visible', 0)->pluck('id');

            // get all visible pictures id and merge them with my hidden pictures.
            $pass_ids2 = Picture::where('visible', 1)
                ->pluck('id');
            $pass_ids = $pass_ids2->merge($user_hidden_pics);

            // collecting id's of users who allow me to view hidden pictures
            $rigts= Follower::where('follow_id', Auth::id())
                ->where('rights', 1)
                ->pluck('user_id');

            $except = Picture::whereIn('user_id', $rigts)->where('visible', 0)->pluck('id');

            $x = count($pass_ids);
            $y = count($except);

            // join all id's into one array
            for ($i=0; $i<=$y-1; $i++) {
                $pass_ids[$x] = $except[$i];
                $x++;
            }

            $pictures = Picture::where('accept', 1)->whereIn('id', $pass_ids)
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
         *  Merging ID's
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
     * Display favorites pictures
     *
     * @return \Illuminate\Http\Response
     */
    public function favorites()
    {
        $collection = [];
        $collection2 = [];
        $i = 0;
        $my_favorites = Favorite::where('user_id', Auth::id())->pluck('picture_id');

        // I check who's giving me hidden pictures
        $rigts= Follower::where('follow_id', Auth::id())
            ->where('rights', 1)
            ->pluck('user_id')->toArray();

        $pre_pics = Picture::whereIn('id', $my_favorites)->where('accept', 1)->get();

        // I check which of my favorite pictures are public,
        // those that are not I check if I have permissions - if yes I add them to the collection
        foreach ($pre_pics as $value) {
            if ( $value->visible == 1 ) {
                $collection[$i] = $value->id;
            } elseif ($value->visible == 0) {
                if ( array_search($value->user_id, $rigts) ) {
                    $collection[$i] = $value->id;
                }
            }
            $i++;
        }
        // I collect the id of my hidden pictures
        $my_hiddens = Picture::where('user_id', Auth::id())->where('visible', 0)->pluck('id');

        $hidden_count = count($my_hiddens);

        // I check which hidden pictures are added to my favorites,
        // and if the hidden picture is in favorites it adds to the collection2.
        for ($i=0; $i<=$hidden_count-1; $i++) {
            if ( Favorite::where('picture_id', $my_hiddens[$i])->where('user_id', Auth::id())->count() == 1 ) {
                $collection2[$i] = $my_hiddens[$i];
            }
        }

        // merge both arrays into one and download the correct pictures.
        $final_collection = array_merge($collection, $collection2);
        $pictures = Picture::whereIn('id', $final_collection)->latest()->paginate(8);

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
                'file' => 'required|mimes:jpeg,bmp,png,jpg,gif|max:550000'
            ]);

            $path =  $request->file('file')->store('gallery', 'public');

            $name = str_replace('gallery/', '', $path);

//            if (! file_exists('/app/public/thumbs')) {
//                mkdir('/app/public/thumbs/', 0755, true);
//            }
//            dd('/gallery/' . $name);
//            $thumb = Image::make(public_path('gallery/') . $name)
//                ->resize(240, 160)
//                ->save('/app/public/thumbs/' . $name, 60);

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

        if ($pictures->isTopLikes($id) == 1){
            $place_likes = $pictures->witchPlaceInLikess($id);
            $top = 1;
        } else {
            $place_likes = 0;
            $top = 0;
        }
        if ($pictures->isTopViews($id) == 1){
            $place_view = $pictures->witchPlaceInViews($id);
            $top_views = 1;
        } else {
            $place_view = 0;
            $top_views = 0;
        }

        if (Auth::check()){
            if ($pictures->visible == 1 && $pictures->accept == 1){
                return view('pictures.show')
                    ->with(['pictures' => $pictures,
                        'comments' => $comments,
                        'top' => $top,
                        'top_views' => $top_views,
                        'place_views' => $place_view,
                        'place_likes' => $place_likes
                    ]);
            } elseif ($pictures->visible == 0 && $pictures->user == Auth::user()->name){
                return view('pictures.show')
                    ->with(['pictures' => $pictures,
                        'comments' => $comments,
                        'top' => $top,
                        'top_views' => $top_views,
                        'place_views' => $place_view,
                        'place_likes' => $place_likes
                    ]);
            } elseif ($pictures->visible == 0 && $follow->rights == 1 && $pictures->accept == 1) {
                return view('pictures.show')
                    ->with(['pictures' => $pictures,
                        'comments' => $comments,
                        'top' => $top,
                        'top_views' => $top_views,
                        'place_views' => $place_view,
                        'place_likes' => $place_likes
                        ]);
            } else {
                return redirect()->back();
            }
        } else {
            if ($pictures->visible == 1 && $pictures->accept == 1){
                return view('unloged.show')
                    ->with(['pictures' => $pictures,
                        'comments' => $comments,
                        'top' => $top,
                        'top_views' => $top_views,
                        'place_views' => $place_view,
                        'place_likes' => $place_likes
                    ]);
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

        if (!Auth::check()) {
            $pictures = Picture::where('name', 'LIKE', "%$request->search%")
                ->where('visible', 1)
                ->where('accept', 1)->latest()->paginate(8);

            return view('unloged.gallery')->with(['pictures' => $pictures, 'search' => $request->search]);
        } else {
            /*
             * Collecting pictures i have right to watch.
             */
            $user_pics = Picture::where('user_id', Auth::id())->where('visible', 0)->pluck('id');
            $allow = Auth::user()->followers()->where('rights', 1)->pluck('user_id');
            $allow_hidden = Picture::where('visible', 0)->whereIn('user_id', $allow)->pluck('id');
            $stack = Picture::where('visible', 1)->where('accept', 1)->pluck('id');
            $merge1 = $stack->merge($allow_hidden);
            $result = $merge1->merge($user_pics);

            $pictures = Picture::where('name', 'LIKE', "%$request->search%")->whereIn('id', $result)->latest()->paginate(8);

            return view('pictures.index')->with(['pictures' => $pictures, 'search' => $request->search]);
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
            Favorite::where('picture_id', $id)->delete();
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
