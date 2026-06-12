<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'pos_people';
    protected $primaryKey = 'person_id';
    public $timestamps = true;

    protected $fillable = [
        'staff_id',
        'title',
        'first_name',
        'last_name',
        'sex',
        'dob',
        'mstatus',
        'religion',
        'phone_number',
        'email',
        'address',
        'state',
        'country',
        'nok',
        'nok_address',
        'nok_contact',
        'nok_email',
        'nok_rela',
        'comments'
    ];
}
