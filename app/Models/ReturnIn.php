<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnIn extends Model
{
    use HasFactory;

    protected $table = 'sales_return_in';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'itemname',
        'quantity',
        'date',
        'staff_id',
    ];

    /**
     * Relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'staff_id', 'staff_id');
    }

    /**
     * Accessor for cashier/staff name.
     */
    public function getStaffNameAttribute()
    {
        return $this->user->name ?? $this->staff_id ?? 'Unknown';
    }
}
