<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'item_id',
        'quantity',
        'quantity_left',
        'selling_price',
        'cost_price',
        'staff_id',
    ];
}
