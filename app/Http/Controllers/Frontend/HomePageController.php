<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\LocationServices;
use App\Models\LocationVehicles;
use App\Models\LocationPesuboxs;
use App\Models\Orders;

class HomePageController extends Controller
{
    //
    public function index() {
        $location = $this->getCurrentLocation();
        $location_services = $this->getLocationServices();
        $location_vehicles = $this->getLocationVehicles();
        $location_pesuboxs = $this->getLocationPesuboxs();

        $slots_data = $this->getTimeSlots(date("d"), date("m"), date("Y"), 0, 7);

        // $date_range = array(
        //     'start' => strtotime("today"),
        //     'end' => strtotime("+6 day", strtotime("today")),
        // );

        return view("frontend.home", compact("location", "location_services", "location_vehicles", "slots_data", "location_pesuboxs"));
    }

    public function storeBooking(Request $request) {
        $validated = $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'company_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phonenumber' => 'required|max:255',
            'vehicle_make_model' => 'required|max:255',
            'message' => 'required|max:255',
            'vehicle_id' => 'required',
            'service_id' => 'required',
            'pesubox_id' => 'required',
            'date' => 'required',
        ]);
        
        $order = new Orders();
        $order->location_id = $request->location_id;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->company_name = $request->company_name;
        $order->email = $request->email;
        $order->phone = $request->phonenumber;
        $order->model = $request->vehicle_make_model;
        $order->message = $request->message;
        $order->is_delete = 'N';
        $order->service_id = implode(",", $request->service_id);
        $order->pesubox_id = $request->pesubox_id;
        $order->vehicle_id = $request->vehicle_id;
        $order->duration = $request->duration;
        $order->date = substr($request->date, 4, 4) . "-" . substr($request->date, 2, 2) . "-" . substr($request->date, 0, 2);
        $order->time = substr($request->date, 8, 8);
        $order->save();
        return response(json_encode(['success' => true]));
    }

    public function getCurrentLocation() {
        return Locations::find(2);
    }

    public function getLocationServices() {
        $location = $this->getCurrentLocation();
        return LocationServices::leftJoin('services', 'services.id', '=', 'location_services.service_id')->where("location_id", $location->id)->get();
    }

    public function getLocationVehicles() {
        $location = $this->getCurrentLocation();
        return LocationVehicles::leftJoin('vehicles', 'vehicles.id', '=', 'location_vehicles.vehicle_id')->where("location_id", $location->id)->get();
    }

    public function getLocationPesuboxs() {
        $location = $this->getCurrentLocation();
        return LocationPesuboxs::where("location_id", $location->id)->where("is_delete", 'N')->where("status", 1)->get();
    }

    public function getTimeSlots($day_first, $month, $year, $step, $length) {
        $location = $this->getCurrentLocation();
        
        for ($x = $step; $x < $step + 7; $x++)
        {
            $next_day = mktime(0, 0, 0, $month, $day_first + $x, $year);
            $time_start = explode(':', $location[date("D", $next_day) . '_start']);
            $time_end = explode(':', $location[date("D", $next_day) . '_end']);
            
            $slot_start = mktime($time_start[0], intval($time_start[1]), $time_start[2], date("m"), date("d"), date("y"));
            $slot_end = mktime($time_end[0], intval($time_end[1]), $time_end[2], date("m"), date("d"), date("y"));

            $slots = [];

            for ($i = $slot_start; $i <= $slot_end; $i += 30 * 60) {
                $slots[] = date("H:i:s", $i);
            }

            $slots_data[] = array(
                "strdate" => date("d M Y", $next_day),
                "fulldate" => date('dmY',$next_day),
                "day" => date('D',$next_day),
                "date" => date('d',$next_day),
                "slots" => $slots
            );
        }
        return $slots_data;
    }

    public function getCalendar(Request $request) {
        $step = $request->step;
        $start_date = $request->startDate;
        $slots_data = $this->getTimeSlots(substr($start_date, 0, 2), substr($start_date, 2, 2), substr($start_date, 4, 4), $step, $step);
        $end_date = date("Y-m-d", strtotime($start_date. ' + ' . $step . ' days'));
        $orders = Orders::whereBetween("date", [$start_date, $end_date])->get();
        return view('frontend.partials.calendar', compact("slots_data", "orders"))->render();
    }
}
