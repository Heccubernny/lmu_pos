<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subnet Access Control Configuration
    |--------------------------------------------------------------------------
    |
    | Define if IP access control is active and the list of static IPs or
    | CIDR subnet ranges allowed to access the system (e.g. campus VLANs).
    |
    */

    'enabled' => filter_var(env('SUBNET_ACCESS_CONTROL', false), FILTER_VALIDATE_BOOLEAN),

    'allowed' => array_filter(array_map('trim', explode(',', env('ALLOWED_SUBNETS', '127.0.0.1,::1,192.168.0.0/16,10.0.0.0/8')))),
];
