<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierReceipt extends Model
{
    use HasFactory;

    protected $table = 'supplier_receipts';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'supervisor_id',
        'supplier_name',
        'product_id',
        'unit_cost',
        'quantity',
        'total_cost',
        'payment_status',
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'person_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'item_id');
    }
}
