<?php

namespace App\Http\Controllers;

use App\Models\User;

class MyBiachController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('mybiach', compact('users'));
    }
}
