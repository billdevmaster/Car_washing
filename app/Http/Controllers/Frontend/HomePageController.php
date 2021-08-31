<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\LocationServices;
use App\Models\LocationVehicles;

class HomePageController extends Controller
{
    //
    public function index() {
        $location = Locations::find(2);
        $location_services = LocationServices::leftJoin('services', 'services.id', '=', 'location_services.service_id')->where("location_id", $location->id)->get();
        $location_vehicles = LocationVehicles::leftJoin('vehicles', 'vehicles.id', '=', 'location_vehicles.vehicle_id')->where("location_id", $location->id)->get();
        return view("frontend.home", compact("location", "location_services", "location_vehicles"));
    }
}
