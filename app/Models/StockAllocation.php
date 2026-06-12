<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAllocation extends Model
{
    use HasFactory;

    protected $table = 'stock_allocations';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'supervisor_id',
        'store_id',
        'product_id',
        'quantity',
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'person_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'item_id');
    }
}
