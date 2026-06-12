<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerBank extends Model
{
    use HasFactory;

    protected $table = 'tconnpos_customers_bank';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'amount_deposited',
        'amount_left',
        'date',
        'staff_id',
    ];
}
