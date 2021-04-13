<?php

namespace App\Http\Controllers;

use App\Models\Picture;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user  = User::find(Auth::id());
        $pictures = $user->pictures()->where('accept', 1)->latest()->paginate(10);

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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

            if ($oldfilename != 'avatar/avatar.png' and  $oldfilename != 'avatar/avatar2.png'){
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $path;
            $user->save();

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
    public function defaultAvatar(Request $request, $id, $x)
    {

        $user = User::find($id);
        $oldfilename = $user->avatar;

        $avatars = [
            '1' => 'avatar/avatar.png',
            '2' => 'avatar/avatar2.png',
            '3' => 'avatar/avatar3.png'
        ];

        if ( !in_array($oldfilename, $avatars) ){
            Storage::disk('public')->delete($user->avatar);
        }

        if ($x == 1){
            $user->avatar = 'avatar/avatar.png';
        } else {
            $user->avatar = 'avatar/avatar' . $x . '.png';
        }

        $user->save();

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
