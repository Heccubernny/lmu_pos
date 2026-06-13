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
        'product_id',
        'store_id',
    ];

    /**
     * Relationship to Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'item_id');
    }

    /**
     * Relationship to Store.
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    /**
     * Relationship to cashier/requesting staff.
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'staff_id', 'staff_id');
    }
}
