<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locations;
use DataTables;

class AdminLocationController extends Controller
{
    //
    public function index(Request $request)
    {
        // $services = Service::all();
        return view('backend.locations.index');
    }

    public function save(Request $request) {
        $location = new Locations();
        $location->name = $request->name;
        $location->save();
        return back()->with("added", "Service is successfully added");
    }

    public function get_list (Request $request) {
        $location_list = Locations::get();
        return Datatables::of($location_list)
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request) {
        $location = Locations::find($request->id);
        return view('backend.locations.edit', compact("location"));
    }

    public function save_general(Request $request) {
        $location = Locations::find($request->id);
    }
}
