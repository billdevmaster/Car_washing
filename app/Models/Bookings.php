<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    use HasFactory;
    protected $fillable = [
        'location_id',
        'email',
        "first_name",
        "last_name",
        "birth_date",
        'phone',
        "message",
        "started_at",
        "date",
        "time",
        "duration",
        "is_delete",
        "service_id",
        "type",
        "pesubox_id"
    ];
}
