<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallStock extends Model
{
    use HasFactory;

    protected $table = 'hall_stocks';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'store_id',
        'product_id',
        'quantity',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'item_id');
    }
}
