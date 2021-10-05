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
        $usedtime = "none";

        // $date_range = array(
        //     'start' => strtotime("today"),
        //     'end' => strtotime("+6 day", strtotime("today")),
        // );

        return view("frontend.home", compact("location", "location_services", "location_vehicles", "slots_data", "usedtime", "location_pesuboxs"));
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
        return Locations::find(1);
    }

    public function getLocationServices() {
        $location = $this->getCurrentLocation();
        if ($location == null) {
            return null;
        }
        return LocationServices::leftJoin('services', 'services.id', '=', 'location_services.service_id')->where("location_id", $location->id)->get();
    }

    public function getLocationVehicles() {
        $location = $this->getCurrentLocation();
        if ($location == null) {
            return null;
        }
        return LocationVehicles::leftJoin('vehicles', 'vehicles.id', '=', 'location_vehicles.vehicle_id')->where("location_id", $location->id)->get();
    }

    public function getLocationPesuboxs() {
        $location = $this->getCurrentLocation();
        if ($location == null) {
            return null;
        }
        return LocationPesuboxs::where("location_id", $location->id)->where("is_delete", 'N')->where("status", 1)->get();
    }

    public function getTimeSlots($day_first, $month, $year, $step, $length) {
        $location = $this->getCurrentLocation();
        if ($location == null) {
            return null;
        }
        
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
        $start_date = substr($request->startDate, 4, 4) . "-" . substr($request->startDate, 2, 2) . "-" . substr($request->startDate, 0, 2);
        
        $slots_data = $this->getTimeSlots(substr($request->startDate, 0, 2), substr($request->startDate, 2, 2), substr($request->startDate, 4, 4), $step, $step);
        if ($step > 0) {
            $start_date = date("Y-m-d", strtotime($start_date . "+" . $step . ' days'));
        } else {
            $start_date = date("Y-m-d", strtotime($start_date . "-" . -1 * $step . ' days'));
        }
        $end_date = date("Y-m-d", strtotime($start_date. ' + ' . 7 . ' days'));
        $orders = Orders::whereBetween("date", [$start_date , $end_date])->where("pesubox_id", $request->pesubox_id)->get();
        $date_ranges = [];
        foreach($orders as $order) {
            $start_time = strtotime($order->date . " " . $order->time);
            $end_time = strtotime($order->date . " " . $order->time) + $order->duration * 60;
            $date_ranges[] = [$start_time, $end_time];
        }
        $usedtime = [];
        foreach($slots_data as $data) {
            $last_time = strtotime(substr($data['fulldate'], 4, 4) . "-" . substr($data['fulldate'], 2, 2) . "-" . substr($data['fulldate'], 0, 2) . " " . $data['slots'][count($data['slots']) - 1]);
            foreach($data['slots'] as $slot) {
                $time = strtotime(substr($data['fulldate'], 4, 4) . "-" . substr($data['fulldate'], 2, 2) . "-" . substr($data['fulldate'], 0, 2) . " " . $slot);
                foreach($date_ranges as $range) {
                    if (($time >= $range[0] && $time < $range[1]) || ($time + $request->service_duration * 60 > $range[0] && $time + $request->service_duration * 60 < $range[1]) || $time + $request->service_duration * 60 > $last_time + 30 * 60) {
                        $usedtime[] = $time;
                    }
                }
            }
        }
        return view('frontend.partials.calendar', compact("slots_data", "orders", "usedtime"))->render();
    }
}
