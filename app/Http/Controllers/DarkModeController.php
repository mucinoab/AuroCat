<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DarkModeController extends Controller
{
    public function index(Request $request){
        $session = $request->session()->get('darkMode');
        return response()->json(['darkMode' => $session]);
    }

    public function changeColor(Request $request){
        if($request->session()->has('darkMode')){
            $value = $request->session()->get('darkMode');
            $request->session()->put('darkMode',!$value);
        }else{
            $request->session()->put('darkMode',false);
        }
        $session = $request->session()->get('darkMode');
        return response()->json(['darkMode' => $session]);
    }



}
