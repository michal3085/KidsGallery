<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UsersData;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:30', 'confirmed'],
            'terms' => 'required',
            'g-recaptcha-response' => function ($attribute, $value, $fail) {
                $secret = env('RECAPTCHA_SECRET');
                $response = $value;
                $userIP = $_SERVER['REMOTE_ADDR'];
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $response . "&remoteip=" . $userIP ;
                $response = \file_get_contents($url);
                $response = json_decode($response);

                if (! $response->success) {
                    Session::flash('g-recaptcha-response', 'Please resolve reCaptcha');
                    Session::flash('alert-class', 'alert-danger');
                    $fail($attribute.'Google ReCaptcha Error');
                }
            }
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'active' => 1,
            'avatar' => 'avatar/avatar1.png',
        ]);

        $user_id = $user->id;
        $data = new UsersData();
        $data->user_id = $user_id;
        $data->about = NULL;
        $data->city = NULL;
        $data->birthdate = NULL;
        $data->unfollowing_msg = 1;
        $data->save();

        return $user;
    }
}
