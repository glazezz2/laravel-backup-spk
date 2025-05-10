<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    // Halaman Home
    public function home()
    {
        return view('home'); // Pastikan ada file view home.blade.php
    }
}