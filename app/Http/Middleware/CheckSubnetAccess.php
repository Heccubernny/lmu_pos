<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubnetAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!config('subnet.enabled', false)) {
            return $next($request);
        }

        $ip = $request->ip();
        $allowedSubnets = config('subnet.allowed', []);

        foreach ($allowedSubnets as $subnet) {
            if ($this->ipMatchesSubnet($ip, $subnet)) {
                return $next($request);
            }
        }

        // Return a custom 403 response detailing the subnet access issue
        abort(403, "Access Denied: Your IP address ({$ip}) is not authorized to access this system from the campus VLAN. Please contact the network administrator.");
    }

    /**
     * Check if an IP address matches a CIDR range or specific IP.
     */
    private function ipMatchesSubnet($ip, $subnet)
    {
        // Exact string match check (works for IPv4 & IPv6 direct values)
        if ($ip === $subnet) {
            return true;
        }

        // If no CIDR slash, then it was a direct IP mismatch
        if (strpos($subnet, '/') === false) {
            return false;
        }

        list($subnetIp, $netmask) = explode('/', $subnet, 2);
        
        $netmask = (int) $netmask;
        if ($netmask < 0 || $netmask > 32) {
            return false;
        }

        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnetIp);

        // If conversion fails (e.g. IPv6 values or malformed IPv4), it is not a match
        if ($ipLong === false || $subnetLong === false) {
            return false;
        }

        // Handle edge netmask sizes safely
        if ($netmask === 0) {
            return true; // 0.0.0.0/0 matches everything
        }
        if ($netmask === 32) {
            return $ipLong === $subnetLong;
        }

        $mask = ~((1 << (32 - $netmask)) - 1);

        return ($ipLong & $mask) == ($subnetLong & $mask);
    }
}
