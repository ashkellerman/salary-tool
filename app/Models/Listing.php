<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor',
        'vendor_id',
        'title',
        'short_description',
        'location',
        'salary',
        'hours',
        'company_name',
        'listing_date',
    ];

    protected $guarded = [];

    protected $casts = [
        'listing_date' => 'datetime',
    ];


}
