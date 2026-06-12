<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pos_users';
    protected $primaryKey = 'person_id';
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'person_id',
        'staff_id',
        'password',
        'position',
        'creator',
        'store_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship to the personal information details.
     */
    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'person_id');
    }

    /**
     * Accessor to get name dynamically from linked Person model.
     */
    public function getNameAttribute()
    {
        return $this->person ? ($this->person->first_name . ' ' . $this->person->last_name) : 'User';
    }

    /**
     * Accessor to get email dynamically from linked Person model.
     */
    public function getEmailAttribute()
    {
        return $this->person ? $this->person->email : '';
    }

    /**
     * Accessor to map 'role' property to 'position' column.
     */
    public function getRoleAttribute()
    {
        return $this->position;
    }

    /**
     * Mutator to set 'position' column when setting 'role' property.
     */
    public function setRoleAttribute($value)
    {
        $this->attributes['position'] = $value;
    }

    /**
     * Relationship to the Store model.
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    /**
     * Many-to-many relationship with Store.
     */
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_user', 'user_id', 'store_id');
    }

    public function isITAdmin()
    {
        return in_array(strtolower($this->position ?? ''), ['it administrator', 'administrator']);
    }

    public function isHead()
    {
        return strtolower($this->position ?? '') === 'head';
    }

    public function isSupervisor()
    {
        return strtolower($this->position ?? '') === 'supervisor';
    }

    public function isSalesRep()
    {
        return in_array(strtolower($this->position ?? ''), ['sales representative', 'operator']);
    }

    public function isAuditor()
    {
        return strtolower($this->position ?? '') === 'auditor';
    }

    public function isAccountant()
    {
        return strtolower($this->position ?? '') === 'accountant';
    }
}
