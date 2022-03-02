<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Bookings;

class AdminClientsController extends Controller
{
    //
    public function index(Request $request)
    {
        $clients_list = Bookings::all();
        $menu = "clients";
        return view('backend.clients.index', compact("clients_list", "menu"));
    }

    public function get_list() {
        $clients_list = Bookings::select(['email', 'driver', 'phone'])->where("is_delete", 'N')->where("driver", "!=", "")->groupBy("email", 'driver', 'phone')->get();
        return Datatables::of($clients_list)
            ->addIndexColumn()
            ->make(true);
    }
}