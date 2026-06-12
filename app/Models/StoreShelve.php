<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreShelve extends Model
{
    use HasFactory;

    protected $table = 'pos_store_shelve';
    protected $primaryKey = 'item_id';
    public $timestamps = true;

    protected $fillable = [
        'supplier',
        'name',
        'category',
        'cost_price',
        'quantity',
        'ty',
        'status',
        'status_location',
        'staff_id',
    ];
}
