<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Session;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(){
        $credentials = request()->only('email','password');
        request()->session()->regenerate();
        if(Auth::attempt($credentials)){
            Session::create([
                'email'=>$credentials['email'],
                'active' => True,
                'date' => date('Y-m-d H:i:s')

            ]);

            return redirect('home');
        }
        return redirect('login');
    }

    public function logout(){
        Session::create([
            'email'=>auth()->user()->email,
            'active' => False,
            'date' => date('Y-m-d H:i:s')
        ]);
        Auth::logout();

    }


}
