<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index() {
        $menu = "home";
        return view('backend.home.index', compact("menu"));
    }
}
