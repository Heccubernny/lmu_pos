<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $table = 'pos_store_requisition';
    protected $primaryKey = 'item_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'category',
        'quantity',
        'collectedby',
        'department',
        'ty',
        'staff_id',
        'manager_approved',
        'status',
        'branch',
    ];
}
