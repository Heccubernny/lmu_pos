<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamagedExpiredItem extends Model
{
    use HasFactory;

    protected $table = 'damaged_expired_items';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'product_id',
        'store_id',
        'quantity',
        'type',
        'status',
        'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'person_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'item_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'person_id');
    }
}
