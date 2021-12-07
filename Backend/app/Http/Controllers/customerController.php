<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class customerController extends Controller
{
    //
    function user(Request $req){
        $user = new Customer;
        $user -> name = $req -> input('name');
        $user -> email = $req -> input('email');
        $user -> password = Hash::make($req -> input('password'));
        $user -> save();
        return $user;
    }

    function message(){
        return 'This is Hello world api';
    }

    // Ching Method
    // install JWT token first
}
