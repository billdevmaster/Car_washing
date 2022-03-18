<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bookings;
use App\Models\Services;
use App\Models\Locations;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookIdMail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email for reminding people';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $bookings_today = Bookings::where("date", date("Y-m-d"))->get();
        foreach($bookings_today as $booking) {
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
                if ($service) {
                    $email_data['service_name'] .= $service->name . ", ";
                }
            }
            $email_data['time'] = $booking->date . " " . $booking->time;
            $email_data['book_id'] = $booking->id;
            
            Mail::to($booking->email)->send(new BookIdMail($email_data));
        }
        $this->info('Successfully sent daily quote to everyone.');
    }
}
