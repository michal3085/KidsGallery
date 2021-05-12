<?php

namespace App\Http\Controllers;

use App\Models\BlockedUser;
use App\Models\Follower;
use App\Models\Picture;
use App\Models\User;
use App\Models\UsersData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{

    private $avatars = [
                '1' => 'avatar/avatar1.png',
                '2' => 'avatar/avatar2.png',
                '3' => 'avatar/avatar3.png',
                '4' => 'avatar/avatar4.png',
                '5' => 'avatar/avatar5.png',
                '6' => 'avatar/avatar6.png',
                '7' => 'avatar/avatar7.png',
                '8' => 'avatar/avatar8.png',
                '9' => 'avatar/avatar9.png',
                '10' => 'avatar/avatar10.png',
                '11' => 'avatar/avatar11.png'
            ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('id', Auth::id())->first();
        $usersIds = $user->following()->pluck('follow_id')->all();
        //$usersIds[] = $user->id;
        $pictures = Picture::whereIn('user_id', $usersIds)->where('accept', 1)->latest()->paginate(10);


        return view('users.index', compact('pictures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function block($id)
    {
      $blocks = new BlockedUser();

      $blocks->user_id = Auth::id();
      $blocks->blocks_user = $id;

        if ($blocks->save()) {
            Follower::where('user_id', Auth::id())->where('follow_id', $id)->delete();
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unblock($id)
    {
        if ( BlockedUser::where('user_id', Auth::id())->where('blocks_user', $id)->delete() ) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ])->setStatusCode(200);
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
        if ( Auth::id() == $id ){
            return view('users.setup');
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
        if ($request->hasFile('avatar')) {

            $request->validate([
                'avatar' => 'mimes:jpeg,bmp,png,jpg'
            ]);
            $path = $request->file('avatar')->store('avatar', 'public');

            $user = User::find($id);
            $oldfilename = $user->avatar;

            if (! in_array($oldfilename, $this->avatars) ){
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $path;
            $user->save();

            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function defaultAvatar($id, $x)
    {

        $user = User::find($id);
        $oldfilename = $user->avatar;


        if (! in_array($oldfilename, $this->avatars) ){
            Storage::disk('public')->delete($user->avatar);
        }
        $user->avatar = 'avatar/avatar' . $x . '.png';

        $user->save();

        return redirect()->back();

    }

    public function search(Request $request, $name)
    {
        $other_user = User::where('name', $name)->first();
        $users = User::where('name', 'LIKE', "%$request->search%")->latest()->paginate(8);

        return view('profiles.usersearch')->with(['other_user' => $other_user, 'followers' => $users]);
    }

    public function userinfo($id)
    {
        $user = User::find($id);

        if ( $user->usersdata()->where('user_id', $id)->count() == 0 ) {
            $userdata_create = new UsersData();
            $userdata_create->user_id = $user->id;
            $userdata_create->save();
        }

        $userdata = UsersData::where('user_id', $id)->first();

        return view('users.info', compact('userdata'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function aboutsave(Request $request, $id)
    {
        $userdata = UsersData::where('user_id', $id)->first();

        $userdata->about = $request->about;
        $userdata->city = $request->city;
        $userdata->birthdate = $request->birthdate;

        $userdata->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
