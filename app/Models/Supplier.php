<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'tconnpos_suppliers';
    protected $primaryKey = 'person_id';
    public $timestamps = true;

    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'branch',
        'status',
    ];
}
