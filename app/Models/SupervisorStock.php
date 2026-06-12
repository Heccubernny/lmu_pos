<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorStock extends Model
{
    use HasFactory;

    protected $table = 'supervisor_stocks';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'supervisor_id',
        'product_id',
        'quantity',
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
