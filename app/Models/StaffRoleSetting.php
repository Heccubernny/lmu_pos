<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffRoleSetting extends Model
{
    use HasFactory;

    protected $table = 'app_staff_role_settings';
    protected $primaryKey = 'role_id';
    public $timestamps = true;

    protected $fillable = [
        'staff_id',
        'role_position',
        'role_contactus',
        'role_store_config',
        'role_employees',
        'role_app_users',
        'role_set_roles',
        'role_products',
        'role_store',
        'role_mange_requisition',
        'role_sales',
        'role_suppliers',
        'role_customers',
        'role_returnin',
        'role_reports',
        'role_dbbackup',
        'role_pkey',
        'role_b_d',
        'role_return_supplier',
        'role_departments',
        'role_positions',
        'role_mode_pay',
        'role_creator',
    ];
}
