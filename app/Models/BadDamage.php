<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BadDamage extends Model
{
    use HasFactory;

    protected $table = 'tconnpos_b_d';
    protected $primaryKey = 'item_id';
    public $timestamps = true;

    protected $fillable = [
        'name',
        'qty',
        'from_dept',
        'description',
        'staff_id',
        'date',
    ];
}
