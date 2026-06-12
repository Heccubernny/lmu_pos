<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosReceipt extends Model
{
    use HasFactory;

    protected $table = 'pos_receipts';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'receipt_number',
        'receipt_uid',
        'barcode_identifier',
        'cashier_name',
        'store_name',
        'total_amount',
        'payment_method',
        'moniepoint_ref',
        'terminal_id',
        'receipt_data',
    ];

    protected $casts = [
        'receipt_data' => 'array',
    ];
}
