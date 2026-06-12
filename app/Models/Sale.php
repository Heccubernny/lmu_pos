<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sale extends Model
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
     * Relationship to other items sold under the same receipt.
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class, 'recipt_number', 'recipt_number');
    }

    /**
     * Relationship to Product model.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'item_id', 'item_id');
    }

    /**
     * Accessor for receipt_number (backwards compatibility).
     */
    public function getReceiptNumberAttribute()
    {
        return $this->recipt_number;
    }

    /**
     * Mutator for receipt_number (backwards compatibility).
     */
    public function setReceiptNumberAttribute($value)
    {
        $this->attributes['recipt_number'] = $value;
    }

    /**
     * Accessor for created_at (backwards compatibility).
     */
    public function getCreatedAtAttribute()
    {
        return $this->date ? Carbon::parse($this->date) : null;
    }

    /**
     * Accessor for customer (backwards compatibility).
     */
    public function getCustomerAttribute()
    {
        $name = $this->attributes['customer'] ?? 'Walk-in';
        $c = Customer::where('name', $name)->first();
        if ($c) {
            return $c;
        }

        // Return a virtual object to maintain template layout compatibility
        return (object)[
            'name' => $name,
            'phone' => 'N/A',
            'email' => 'N/A',
        ];
    }

    /**
     * Accessor for customer_id (backwards compatibility).
     */
    public function getCustomerIdAttribute()
    {
        $name = $this->attributes['customer'] ?? '';
        $c = Customer::where('name', $name)->first();
        return $c ? $c->person_id : null;
    }

    /**
     * Mutator for customer_id (backwards compatibility).
     */
    public function setCustomerIdAttribute($value)
    {
        $c = Customer::find($value);
        $this->attributes['customer'] = $c ? $c->name : 'Walk-in';
    }

    /**
     * Relationship to Customer model (customer name stored on sales).
     */
    public function customer()
    {
        // sale.customer is a name string; map it to customers.name
        return $this->belongsTo(\App\Models\Customer::class, 'customer', 'name');
    }

    /**
     * Accessor to get the cashier's full name from their staff_id.
     */
    public function getCashierNameAttribute()
    {
        $user = User::where('staff_id', $this->staff_id)->first();
        return $user ? $user->name : ($this->staff_id ?? 'Unknown Cashier');
    }

    /**
     * Relationship to Store model.
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
