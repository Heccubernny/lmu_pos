<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'pos_sessions';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'ip_address',
        'user_agent',
        'recipt_number',
        'itemname',
        'qty',
        'last_activity',
        'date',
    ];
}
