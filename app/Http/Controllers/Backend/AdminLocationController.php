<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLocationController extends Controller
{
    //
    public function index()
    {
        // $services = Service::all();
        return view('backend.locations.index');
    }
}
