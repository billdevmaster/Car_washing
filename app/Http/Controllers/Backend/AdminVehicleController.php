<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Vehicle_types;

class AdminVehicleController extends Controller
{
    //
    public function index(Request $request)
    {
        $vehicle_list = Vehicle_types::all();
        return view('backend.vehicles.index', compact("vehicle_list"));
    }

    public function save(Request $request) {
        $input = $request->all();
        if ($input['id'] == 0) {
            Vehicle_types::create($input);
            return back()->with("added", "Vehicle type is successfully added");
        } else {
            $vehicle = Vehicle_types::find($input['id']);
            $vehicle->name = $input['name'];
            $vehicle->icon = $input['icon'];
            $vehicle->save();
            return back()->with("updated", "Vehicle type is successfully udpated");
        }
    }

    public function get_list (Request $request) {
        $vehicle_list = Vehicle_types::get();
        return Datatables::of($vehicle_list)
                ->addIndexColumn()
                ->make(true);
    }
}
