<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Locations;
use App\Models\LocationServices;
use App\Models\LocationPesuboxs;
use App\Models\Orders;
use App\Models\Bookings;
use App\Models\Services;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookIdMail;
// use Illuminate\Support\Facades\Input;

class HomePageController extends Controller
{
    //
    public function index(Request $request) {
        if ($request->Bookings != NULL) {
            $timestamp = strtotime(substr($request->datetime, 0, 10));
            $day = date('D', $timestamp);
            $location = Locations::find($request->location_id);
            $location_date_end_time = $location[$day . "_end"];

            $booking = new Bookings();
            // check start time in database already.
            $datetime = substr($request['Bookings']['started_at'], 6, 4) . "-" . substr($request['Bookings']['started_at'], 3, 2) . "-" . substr($request['Bookings']['started_at'], 0, 2) . " " . substr($request['Bookings']['started_at'], 11, 5) . ":00";
            
            $order_already = Bookings::where('location_id', $request->location_id)->where('pesubox_id', $request['BookingResources']['resource_id'])->where("is_delete", "N")
            ->where(function($query1) use($datetime, $request) {
                $query1->where(function($query) use($datetime, $request)
                {
                    $query->where("started_at", "<=", $datetime);
                    $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration - 1 MINUTE)"), ">", $datetime);
                });
                $query1->orwhere(function($query) use($datetime, $request)
                {
                    $query->where("started_at", "<", date("Y-m-d H:i:s", strtotime($datetime. ' + ' . ($request['duration'] - 1) . ' minutes')));
                    $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration - 1 MINUTE)"), ">", date("Y-m-d H:i:s", strtotime($datetime. ' + ' . $request['duration'] . ' minutes')));
                });
                $query1->orwhere(function($query) use($datetime, $request)
                {
                    $query->where("started_at", ">", $datetime);
                    $query->where(DB::raw("DATE_ADD(started_at, INTERVAL duration MINUTE)"), "<", date("Y-m-d H:i:s", strtotime($datetime. ' + ' . $request['duration'] . ' minutes')));
                });
            })
            ->first();
            if ($order_already != null) {
                $end_time = date('Y-m-d H:i:s', strtotime($order_already->started_at. ' +' . $order_already->duration . ' minutes')); 
                if ($end_time <= $order_already->date . " " . $location_date_end_time) 
                    return redirect()->route('errorBooking', ["message" => "Your booking time is already booked"]);
            }
            $booking->location_id = $request->location_id;
            $booking->email = $request['Bookings']['email'];
            $booking->phone = $request['Bookings']['phone'];
            $booking->first_name = $request['Bookings']['first_name'];
            $booking->birth_date = $request['Bookings']['birthday_year'] . "-" . str_pad($request['Bookings']['birthday_month'], 2, '0', STR_PAD_LEFT) . "-" . str_pad($request['Bookings']['birthday_date'], 2, '0', STR_PAD_LEFT);
            $booking->last_name = $request['Bookings']['last_name'];
            $booking->message = $request['Bookings']['message'];
            $booking->is_delete = 'N';
            $booking->service_id = implode(",", $request->BookingService['service_id']);
            $booking->pesubox_id = $request['BookingResources']['resource_id'];
            $booking->date = substr($request['Bookings']['started_at'], 6, 4) . "-" . substr($request['Bookings']['started_at'], 3, 2) . "-" . substr($request['Bookings']['started_at'], 0, 2);
            $booking->time = substr($request['Bookings']['started_at'], 11, 5) . ":00";
            $booking->duration = $request['duration'];
            $booking->started_at = $booking->date . " " . $booking->time;
            $end_time = date('Y-m-d H:i:s', strtotime($booking->started_at. ' +' . $booking->duration . ' minutes')); 
            if ($end_time > $booking->date . " " . $location_date_end_time) 
                return redirect()->route('errorBooking', ["message" => "Your booking time is already booked"]);
            $booking->save();
            // send email
            $data = array(
                'name'=>$booking->first_name . " " . $booking->last_name,
                'time'=>$request['Bookings']['started_at'] . "~" . $request['Bookings']['ended_at'],
                'booking' => $booking
            );
            $email_data = [];
            $location = Locations::find($booking->location_id);
            $email_data['location_name'] = $location->name;
            $email_data['service_name'] = '';
            $email_data['e_post'] = $booking->email;
            $email_data['telephone'] = $booking->phone;
            $email_data['message'] = $booking->message;
            $arr_service = explode(",", $booking->service_id);
            foreach ($arr_service as $service_id) {
                $service = Services::find($service_id);
                $email_data['service_name'] .= $service->name . ", ";
            }
            $email_data['time'] = $request['Bookings']['started_at'];
            $email_data['book_id'] = $booking->id;
            
            Mail::to($booking->email)->send(new BookIdMail($email_data));
            return redirect()->route('index', ["office" => $request->location_id]);
        }
        $location_list = Locations::where("is_delete", "N")->get();
        $locations = Locations::first();
        $location_id = $request->office ? $request->office : $locations->id;
        $location = $this->getCurrentLocation($location_id);
        $location_services = $this->getLocationServices($location_id);
        $location_pesuboxs = LocationPesuboxs::where("location_id", $location_id)->where("status", 1)->get();
        $slots_data = $this->getTimeSlots(date("d"), date("m"), date("Y"), 0, 7, $location_id);
        $usedtime = "none";
        $office = $location_id;

        // $date_range = array(
        //     'start' => strtotime("today"),
        //     'end' => strtotime("+6 day", strtotime("today")),
        // );

        return view("frontend.dashboard", compact("office", "location", "location_services", "location_pesuboxs", "location_id", "location_list"));
    }

    public function storeBooking(Request $request) {
        
        return response(json_encode(['success' => true]));
    }

    public function getCurrentLocation($location_id) {
        return Locations::find($location_id);
    }

    public function getLocationServices($location_id) {
        $location = $this->getCurrentLocation($location_id);
        if ($location == null) {
            return null;
        }
        return LocationServices::leftJoin('services', 'services.id', '=', 'location_services.service_id')->where("location_id", $location->id)->where("services.is_delete", 'N')->get();
    }

    public function getLocationVehicles($location_id) {
        $location = $this->getCurrentLocation($location_id);
        if ($location == null) {
            return null;
        }
        return LocationVehicles::leftJoin('vehicles', 'vehicles.id', '=', 'location_vehicles.vehicle_id')->where("location_id", $location->id)->get();
    }

    public function getLocationPesuboxs($location_id, $date) {
        $timestamp = strtotime($date);
        $day = date('D', $timestamp);
        $location = Locations::find($location_id);
        $location_date_end_time = $location[$day . "_end"];

        $data = [];
        $location = $this->getCurrentLocation($location_id);
        if ($location == null) {
            return null;
        }
        $pesuboxes = LocationPesuboxs::where("location_id", $location->id)->where("is_delete", 'N')->where("status", 1)->get();
        foreach($pesuboxes as $box) {
            $info = [];
            $info['name'] = $box->name;
            $info['id'] = (string) $box->id;
            $orders = Bookings::where('location_id', $location_id)->where("pesubox_id", $box->id)->where("date", $date)->where("is_delete", "N")->get();
            if (count($orders) > 0) {
                $info['bookings_slots'] = [];
                foreach($orders as $order) {
                    $end_time = date('Y-m-d H:i:s', strtotime($order->started_at. ' +' . $order->duration . ' minutes')); 
                    if ($end_time > $order->date . " " . $location_date_end_time) 
                        continue;
                    $order_info = [];
                    $order_info['id'] = (string) $order->id;
                    $order_info['slot_duration'] = $order->duration * 1 / $location->interval;
                    $time_start = explode(':', $order->time);
                    $order_info['slot_start'] = ($time_start[0] * 1 * (60 / $location->interval)) + ($time_start[1] * 1 / $location->interval);
                    $order_info['slot_end'] = $order_info['slot_start'] + ($order->duration / $location->interval);
                    $info['bookings_slots'][] = $order_info;
                }
            }
            $data[] = $info;
        }
        return $data;
    }

    public function getTimeSlots($day_first, $month, $year, $step, $length, $location_id) {
        $location = $this->getCurrentLocation($location_id);
        if ($location == null) {
            return null;
        }
        
        for ($x = $step; $x < $step + 7; $x++)
        {
            $next_day = mktime(0, 0, 0, $month, $day_first + $x, $year);
            $time_start = explode(':', $location[date("D", $next_day) . '_start']);
            $time_end = explode(':', $location[date("D", $next_day) . '_end']);
            if (count($time_start) == 1 || count($time_end) == 1) {
                return null;
            }
            $slot_start = mktime($time_start[0], intval($time_start[1]), $time_start[2], date("m"), date("d"), date("y"));
            $slot_end = mktime($time_end[0], intval($time_end[1]), $time_end[2], date("m"), date("d"), date("y"));

            $slots = [];

            for ($i = $slot_start; $i <= $slot_end; $i += $location->interval * 60) {
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
        $orders = Bookings::whereBetween("date", [$start_date , $end_date])->where("pesubox_id", $request->pesubox_id)->where("is_delete", "N")->get();
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
                    if (($time >= $range[0] && $time < $range[1]) || ($time + $request->service_duration * 60 > $range[0] && $time + $request->service_duration * 60 < $range[1]) || $time + $request->service_duration * 60 > $last_time + $location->interval * 60 || ($time <= $range[0]) && $time + $request->service_duration * 60 >= $range[1]) {
                        $usedtime[] = $time;
                    }
                }
            }
        }
        return view('frontend.partials.calendar', compact("slots_data", "orders", "usedtime"))->render();
    }

    public function booking(Request $request) {
        $location = Locations::find($request['office']);
        $ret_data = [];
        $date1 = date_create($request['start_date']);
        $ret_data['office'] = [];
        $ret_data['days'] = [];
        $ret_data['office']['allow_brn_max_time'] = '0';
        $ret_data['office']['allow_brn_min_time'] = '1';
        $ret_data['office']['brn_min_time'] = '60';
        $ret_data['office']['slot_length'] = $location->interval;
        $day = [];
        $day['date'] = strtotime($request['start_date']) * 1 - 3600;
        if ($date1 > date_create("2022-10-30")) {
            $day['date'] = strtotime($request['start_date']) * 1 - 7200;
        }
        $day['openTimes'] = $this->getLocationOpenTimes($request['office'], $request['start_date']);
        $day['resources'] = $this->getLocationPesuboxs($request['office'], $request['start_date']);
        $ret_data['days'][0] = $day;
        return response(json_encode($ret_data));
    }

    public function getLocationOpenTimes($location_id, $date) {
        $location = Locations::find($location_id);
        $day = mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));
        $time_start = explode(':', $location[date("D", $day) . '_start']);
        $time_end = explode(':', $location[date("D", $day) . '_end']);
        if (count($time_start) == 1 || count($time_end) == 1) {
            return null;
        }
        $open_time = [];
        $open_time['id'] = (string) $location['id'];
        $open_time['slot_start'] = (string) (($time_start[0] * 1 * 60 / $location->interval) + ($time_start[1] * 1 / $location->interval));
        $open_time['slot_end'] = (string) (($time_end[0] * 1 * 60 / $location->interval) + ($time_end[1] * 1 / $location->interval));
        return $open_time;
    }

    public function models (Request $request) {
        $models = MarkModel::where("mark_id", $request->id)->get();
        return response(json_encode($models));
    }

    public function errorBooking(Request $request) {
        $message = $request['message'];
        return view("frontend.errorBooking", compact("message"));
    }

    public function cancelBookingView(Request $request) {
        
        $book_id = $request->id;
        return view("frontend.cancelBooking", compact("book_id"));
    }

    public function cancelBooking(Request $request) {
        if (!$request->input('agree')) {
            return back()->withErrors(["message" => "Please Check Agree"]);
        } else {
            $booking = Bookings::find($request->input("id"));
            $booking->is_delete = 'Y';
            $booking->save();
            return redirect("/");
        }
    }
}
