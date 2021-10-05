<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\LocationServices;
use App\Models\LocationVehicles;
use App\Models\LocationPesuboxs;
use App\Models\Orders;

class AdminController extends Controller
{
    //
    public function index(Request $request) {
        $menu = "home";
        $locations = Locations::where("is_delete", 'N')->get();
        $current_location_id = $request->location_id ? $request->location_id : (count($locations) > 0 != null ? $locations[0]->id : 0);
        
        return view('backend.home.index', compact("menu", "locations", "current_location_id"));
    }
    
    public function getCalendar(Request $request) {
        $start_date = $request->start_date ? $request->start_date : date("Y-m-d");
        $year = date("M Y", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($start_date. ' + 3 days'));
        $pesuboxs = LocationPesuboxs::where("location_id", $request->current_location_id)->where("is_delete", 'N')->get();
        $orders = Orders::where("location_id", $request->current_location_id)->whereBetween("date", [$start_date, $end_date])->where("is_delete", 'N')->get();
        $data = [];
        foreach($orders as $order) {
            $item = [];
            $item['uid'] = $order->id;
            $item['begins'] = $order->date . ' ' . $order->time;
            $endTime = strtotime("+" . $order->duration . " minutes", strtotime($item['begins']));
            $item['ends'] = $order->date . ' ' . date('H:i:s', $endTime);
            $item['color'] = "#dddddd";
            $item['resource'] = $order->pesubox_id;
            $item['title'] = "";
            $data[] = (object)$item;
        }
        return view('backend.home.components.calendar', compact("start_date", "pesuboxs", "data", "year"))->render();
    }

    public function editOrder(Request $request) {
        $id = $request->id;
        $location_id = $request->location_id;
        $order = Orders::find($id);
        
        $location_vehicles = LocationVehicles::leftJoin('vehicles', 'vehicles.id', '=', 'location_vehicles.vehicle_id')->where("location_id", $request->location_id)->get();
        $location_services = LocationServices::leftJoin('services', 'services.id', '=', 'location_services.service_id')->where("location_id", $request->location_id)->get();
        $location_pesuboxs = LocationPesuboxs::where("location_id", $request->location_id)->where("is_delete", 'N')->where("status", 1)->get();
        return view('backend.home.components.modal', compact("order", "id", "location_id", "location_vehicles", "location_services", "location_pesuboxs"))->render();
    }

    public function updateOrder(Request $request) {
        // $service_id = []
        // $request->service_id
        $order = Orders::find($request->id);
        if ($order == null) {
            $order = new Orders();
        }
        $order->location_id = $request->location_id;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->company_name = $request->company_name;
        $order->email = $request->email;
        $order->phone = $request->phone;
        $order->model = $request->model;
        $order->message = $request->message;
        $order->is_delete = 'N';
        $order->service_id = implode(",", $request->service_id);
        $order->pesubox_id = $request->pesubox_id;
        $order->vehicle_id = $request->vehicle_id;
        $order->duration = $request->duration;
        $order->date = substr($request->datetime, 0, 10);
        $order->time = substr($request->datetime, 10, 5) . ":00";
        $order->save();
        return response(json_encode(['success' => true]));
    }
}
