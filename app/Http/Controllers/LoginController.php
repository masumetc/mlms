<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RegisterUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Countries\Package\Countries;

class LoginController extends Controller
{
    public function login(){
        return view('frontend.login.login');
    }

    public function register($username){
        $countries = new Countries();
        $countries = $countries->all();

        $user = User::where('username', $username)->first();
        if(isset($user)){
            return view('frontend.login.register', compact('user', 'countries'));
        }else{

            return 'The refer url not valid';
            return redirect()->with('error', 'The refer url not valid');
        }

    }


    public function login_store(Request $request){
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $users = User::where('username', $request->username)->get();

        if(!$users->isEmpty()){
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                if(Auth::user()->id != '1'){
                    return redirect('user/dashboard');
                }else{
                    return redirect('admin/dashboard');
                }
            }else{
                return redirect()->back()->with('error', 'Email password not match...');
            }
        }else{
            return redirect()->back()->with('error', 'Username not exist');
        }

    }




    public function register_store(Request $request){

        $this->validate($request, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'username' => 'required|alpha|max:255|unique:users',
            'email' => 'required|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'required_with:password|same:password|min:8',
        ]);
        $registerUsers = RegisterUser::where('email', $request->email)->get();
        foreach($registerUsers as $registerUserr){
            $ruf = RegisterUser::find($registerUserr->id);
            $ruf->delete();
        }


        $recovery_code = rand(100000, 999999);
        $registerUser = new RegisterUser();
        $registerUser->name = $request->first_name.' '.$request->last_name;
        $registerUser->username = $request->username;
        $registerUser->email = $request->email;
        $registerUser->password = $request->password;
        $registerUser->code = $recovery_code;
        $registerUser->save();

        Session::put('email', $request->email);

        $email = $request->email;
        $name = $registerUser->name;
        Mail::send('mail.login.register', [
            'email' => $email,
            'recovery_code' => $recovery_code,
            'name' => $name
        ], function ($m) use ($email, $recovery_code, $name) {
            $m->from('masumetc5@gmail.com', env('MAIL_FROM_NAME'));

            $m->to($email)->subject('Your mlms register verification mail');
        });

        return redirect('code');
    }


    public function code(){
        if(Session::get('email') == null){
            return redirect('login');
        }
        return view('frontend.login.code');
    }


    public function logout(){
        Auth::logout();
        return redirect('/');
    }


    public function code_verify(Request $request){
        $this->validate($request, [
            'code' =>'required|min:6|integer'
        ]);

        if(Session::get('email') == null){
            return redirect('login');
        }
        $email = Session::get('email');
        $registerUser = RegisterUser::where('email', $email)->first();
        if($request->code != $registerUser->code){
            return redirect()->back()->with('error', 'Verification code not match');
        }
        Session::put('email', null);
        if(isset($registerUser)){
            $user = new User();
            $user->name = $registerUser->name;
            $user->username = $registerUser->username;
            $user->email = $registerUser->email;
            $user->password = bcrypt($registerUser->password);
            $user->save();

            Auth::login($user);
            return redirect('user/dashboard');
        }else{
            return redirect('login');
        }
    }



    public function password(){
        return view('frontend.login.password');
    }


    public function recovery_mail(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if(isset($user)){
            $recovery_code = rand(100000, 999999);
            $userf = User::find($user->id);
            $userf->code = $recovery_code;
            $userf->save();

            $email = $request->email;
            Session::put('recovery_email', $email);
            $name = $user->name;
            Mail::send('mail.login.register', [
                'email' => $email,
                'recovery_code' => $recovery_code,
                'name' => $name
            ], function ($m) use ($email, $recovery_code, $name) {
                $m->from('masumetc5@gmail.com', env('MAIL_FROM_NAME'));

                $m->to($email)->subject('mlms passowrd recovery mail');
            });

            return redirect('password/change');
        }else{
            return redirect()->back()->with('error', 'Email not exist...');
        }

        return $request;
    }



    public function passwordChnage(){
        if(Session::get('recovery_email') == null){
            return redirect('login');
        }
        return view('frontend.login.password_change');
    }


    public function password_store(Request $request){
        $this->validate($request, [
            'code' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'required_with:password|same:password|min:8',
        ]);

        $email = Session::get('recovery_email');
        $user = User::where('email', $email)->first();

        if(isset($registerUser)){
            $user = User::where('email', $email)->first();
            $user->password = bcrypt($request->password);
            $user->save();

            Auth::login($user);
            if(Auth::user()->id == '1'){
                return redirect('admin/dashboard');
            }
            return redirect('user/dashboard');
        }else{
            return redirect('login');
        }
    }
}
