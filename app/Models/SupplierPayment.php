<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
    use HasFactory;

    protected $table = 'tconnpos_suppliers_payment';
    protected $primaryKey = 'person_id';
    public $timestamps = true;

    protected $fillable = [
        'quantity_goods',
        'amount_topay',
        'amount_paid',
        'email',
        'date',
        'staff_id',
    ];
}
