<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'tconnpos_customers';
    protected $primaryKey = 'person_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];

    /**
     * Relationship to Sale model.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer', 'name');
    }
}
