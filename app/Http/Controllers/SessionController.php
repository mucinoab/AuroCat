<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function create(){

    }

    public function store(Request $request){
        return Session::create([
            'email'=>request('email'),
            'active'=>request('active')
        ]);
    }
}
