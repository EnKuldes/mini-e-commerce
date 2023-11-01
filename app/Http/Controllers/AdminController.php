<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        can_access(\Request::path());
    }

    // Halaman
    public function pageHome()
    {
        return view('admin.home');
    }
}
