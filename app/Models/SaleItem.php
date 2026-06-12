<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $table = 'pos_sales_items';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'recipt_number',
        'store_id',
        'item_id',
        'category',
        'supplier',
        'quantity_purchased',
        'quantity_left',
        'item_cost_price',
        'item_unit_price',
        'total_amount',
        'amount_paid',
        'mode_payment',
        'description',
        'discount_percent',
        'date',
        'staff_id',
        'status',
        'status_location',
        'status_secound',
        'customer',
    ];

    /**
     * Relationship to Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id', 'item_id');
    }

    /**
     * Relationship to parent Sale model transaction.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'recipt_number', 'recipt_number');
    }
}
