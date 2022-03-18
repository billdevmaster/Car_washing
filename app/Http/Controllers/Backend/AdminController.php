<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Locations;
use App\Models\LocationServices;
use App\Models\LocationPesuboxs;
use App\Models\Orders;
use App\Models\Services;
use App\Models\Bookings;
use Carbon\Carbon;

class AdminController extends Controller
{
    
    //
    public function index(Request $request) {
        $menu = "home";
        $locations = Locations::where("is_delete", 'N')->get();
        $current_location_id = $request->location_id ? $request->location_id : (count($locations) > 0 != null ? $locations[0]->id : 0);
        $search_input = $request->search_input ? $request->search_input : "";
        return view('backend.home.index', compact("menu", "locations", "current_location_id", "search_input"));
    }
    
    public function getCalendar(Request $request) {
        $colors = [
            "green" => '#008000',
            "yellow" => '#FFFF00',
            "red" => '#FF0000',
            "blue" => '#0000FF',
        ];
        $start_date = $request->start_date ? $request->start_date : date("Y-m-d");
        $year = date("M Y", strtotime($start_date));
        $end_date = date("Y-m-d", strtotime($start_date. ' + 3 days'));
        $pesuboxs = LocationPesuboxs::where("location_id", $request->current_location_id)->where("is_delete", 'N')->get();
        if ($request->search_input && $request->search_input != "") {
            $orders = Bookings::where("location_id", $request->current_location_id)->where(function($query1) use($request) {
                $query1->where("first_name", "like", $request->search_input);
                $query1->orwhere("last_name", "like", $request->search_input);
            })->whereBetween("date", [$start_date, $end_date])->where("is_delete", 'N')->get();
        } else {
            $orders = Bookings::where("location_id", $request->current_location_id)->whereBetween("date", [$start_date, $end_date])->where("is_delete", 'N')->get();
        }
        $data = [];
        foreach($orders as $order) {
            $item = [];
            $item['uid'] = $order->id;
            $item['begins'] = $order->date . ' ' . $order->time;
            $endTime = strtotime("+" . $order->duration . " minutes", strtotime($item['begins']));
            $item['ends'] = $order->date . ' ' . date('H:i:s', $endTime);
            $item['color'] = $colors[$order->type];
            $item['resource'] = $order->pesubox_id;
            $item['title'] = substr($order->time, 0, 5) . " " . $order->first_name . " " . $order->last_name;
            $item['notes'] = "name: " . $order->first_name . " " . $order->last_name . "\n" . "birth_date: " . $order->birth_date . "\n" . "phone: " . $order->phone . "\n" . "email: " . $order->email . "\n" . "message: " . $order->message;
            $item['notes'] .= "\n" . "services: ";
            $arr_service = explode(",", $order->service_id);
            foreach($arr_service as $service_id) {
                if ($service_id != null) {
                    $service = Services::find($service_id);
                    $item['notes'] .= $service->name . ", ";
                }
            }
            // $item['notes'] = "test";
            $data[] = (object)$item;
        }
        return view('backend.home.components.calendar', compact("start_date", "pesuboxs", "data", "year"))->render();
    }

    public function editOrder(Request $request) {
        $id = $request->id;
        $location_id = $request->location_id;
        $order = Bookings::find($id);

        $location_services = LocationServices::leftJoin('services', 'services.id', '=', 'location_services.service_id')->where("location_id", $request->location_id)->where("services.is_delete", "N")->get();
        $location_pesuboxs = LocationPesuboxs::where("location_id", $request->location_id)->where("is_delete", 'N')->get();
        $end_time = "";
        if ($order != null) {
            $time = Carbon::parse($order->date . " " . $order->time);
            $end_time = $time->addMinutes($order->duration)->format('Y-m-d H:i:s');
        }

        // $end_time = $time->format('Y-m-d H:i');
        $location = Locations::find($location_id);
        $location_lasttimes = json_encode([
            "1" => $location->Mon_end,
            "2" => $location->Tue_end,
            "3" => $location->Wed_end,
            "4" => $location->Thu_end,
            "5" => $location->Fri_end,
            "6" => $location->Sat_end,
            "0" => $location->Sun_end,
        ]);
        $order_services = [];
        if ($order) {
            $order_service_ids = explode(",", $order->service_id);
            if ($order_service_ids[0] != '') {
                foreach($order_service_ids as $service_id) {
                    $service = Services::find($service_id);
                    array_push($order_services, $service);
                }
            }
        }
        return view('backend.home.components.modal', compact("order", "id", "location_lasttimes", "location_id", "location_services", "location_pesuboxs", "end_time", "order_services"))->render();
    }

    public function updateOrder(Request $request) {
        $timestamp = strtotime(substr($request->datetime, 0, 10));
        $day = date('D', $timestamp);
        $location = Locations::find($request->location_id);
        $location_date_end_time = $location[$day . "_end"];

        $order = Bookings::find($request->id);
        if ($order == null) {
            $order = new Bookings();
            // check start time in database already.
            $order_already = Bookings::where("location_id", $request->location_id)->where('pesubox_id', $request->pesubox_id)->where("is_delete", "N")
                ->where(function($query1) use($request) {
                    $query1->where(function($query) use($request)
                    {
                        $query->where("started_at", "<=", $request->datetime);
                        $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration - 1 MINUTE)"), ">", $request->datetime);
                    });
                    $query1->orwhere(function($query) use($request)
                    {
                        $query->where("started_at", "<", date("Y-m-d H:i:s", strtotime($request->datetime. ' + ' . ($request->duration - 1) . ' minutes')));
                        $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration - 1 MINUTE)"), ">", date("Y-m-d H:i:s", strtotime($request->datetime. ' + ' . $request->duration . ' minutes')));
                    });
                    $query1->orwhere(function($query) use($request)
                    {
                        $query->where("started_at", ">", $request->datetime);
                        $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration MINUTE)"), "<", date("Y-m-d H:i:s", strtotime($request->datetime. ' + ' . $request->duration . ' minutes')));
                    });
                })
                ->first();
        } else {
            $order_already = Bookings::where("location_id", $request->location_id)->where('pesubox_id', $request->pesubox_id)->where("is_delete", "N")
                ->where(function($query1) use($request) {
                    $query1->where(function($query) use($request)
                    {
                        $query->where("started_at", "<=", $request->datetime);
                        $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration - 1 MINUTE)"), ">", $request->datetime);
                    });
                    $query1->orwhere(function($query) use($request)
                    {
                        $query->where("started_at", "<", date("Y-m-d H:i:s", strtotime($request->datetime. ' + ' . ($request->duration - 1) . ' minutes')));
                        $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration - 1 MINUTE)"), ">", date("Y-m-d H:i:s", strtotime($request->datetime. ' + ' . $request->duration . ' minutes')));
                    });
                    $query1->orwhere(function($query) use($request)
                    {
                        $query->where("started_at", ">", $request->datetime);
                        $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration MINUTE)"), "<", date("Y-m-d H:i:s", strtotime($request->datetime. ' + ' . $request->duration . ' minutes')));
                    });
                })
                ->where("id", "!=", $request->id)->first();
        }

        // var_dump($request->datetime);
        // var_dump(date("Y-m-d H:i:s", strtotime($request->datetime. ' + ' . $request->duration . ' minutes')));
        // var_dump($order_already);
        // return;
        if ($order_already != null) {
            // check that order_already exceed the range of working time.
            $end_time = date('Y-m-d H:i:s', strtotime($order_already->started_at. ' +' . $order_already->duration . ' minutes')); 
            
            if ($end_time <= $order_already->date . " " . $location_date_end_time) 
                return response(json_encode(['success' => false, "message" => "Your booking time was already booked"]));
        } 
        $order->location_id = $request->location_id;
        if ($request->first_name != null) 
            $order->first_name = $request->first_name;
        else
            $order->first_name = '';

        if ($request->last_name != null) 
            $order->last_name = $request->last_name;
        else
            $order->last_name = '';

        if ($request->email != null) 
            $order->email = $request->email;
        else
            $order->email = '';

        if ($request->phone != null) 
            $order->phone = $request->phone;
        else
            $order->phone = '';

        if ($request->message != null) 
            $order->message = $request->message;
        else
            $order->message = '';
        
        $order->pesubox_id = $request->pesubox_id;
        $order->service_id = $request->service_id;
        $order->duration = $request->duration;
        $order->type = $request->type;
        $order->birth_date = $request->birth_year . "-" . str_pad($request->birth_month, 2, '0', STR_PAD_LEFT) . "-" . str_pad($request->birth_date, 2, '0', STR_PAD_LEFT);
        $order->started_at = $request->datetime;
        $order->date = substr($request->datetime, 0, 10);
        $order->time = substr($request->datetime, 11, 5) . ":00";
        $end_time = date('Y-m-d H:i:s', strtotime($order->started_at. ' +' . $order->duration . ' minutes'));
        if ($end_time > $order->date . " " . $location_date_end_time) 
            return response(json_encode(['success' => false, "message" => "Your booking time is over the day"]));
        $order->save();
        return response(json_encode(['success' => true]));
    }

    public function deleteOrder(Request $request) {
        $order = Bookings::find($request->id);
        $order->is_delete = 'Y';
        $order->save();
        return response(json_encode(['success' => true]));
    }
 
    public function getModel(Request $request) {
        $location_mark_models = MarkModel::where("mark_id", $request['mark_id'])->get();
        return view('backend.home.components.model', compact("location_mark_models"))->render();
    }

    public function getDayEndTime(Request $request) {
        $day = mktime(0, 0, 0, substr($request->date, 5, 2), substr($request->date, 8, 2), substr($request->date, 0, 4));
        $location = Locations::find($request->location_id);
        $bookings = Bookings::where("date", substr($request->date, 0, 10))->where("started_at", ">", $request->date)->where("pesubox_id", $request->pesubox_id)->where('is_delete', 'N')->orderBy('time', 'asc')->first();

        if ($bookings != null) {
            $time_end = $bookings['time'];
        } else {
            $time_end = $location[date("D", $day) . '_end'];
        }
        $from_time = strtotime($request->date);
        $to_time = strtotime(substr($request->date, 0, 10) . " " . $time_end);
        $difference = round(abs($to_time - $from_time) / 60,2);
        
        return response(json_encode(['difference' => $difference, "end_time" => substr($request->date, 0, 10) . " " . $time_end]));
    }
}
