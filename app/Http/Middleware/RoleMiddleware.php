<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[]  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        $userRole = $user->role;

        // Jika user adalah admin, kita cek jabatannya di tabel admins
        if ($userRole === 'admin') {
            // Cek tabel admins untuk melihat jabatan (admin atau super_admin)
            $jabatan = $user->admin ? $user->admin->jabatan : 'admin';

            // Jika route mensyaratkan super_admin, hanya super_admin yang boleh
            if (in_array('super_admin', $roles)) {
                if ($jabatan === 'super_admin') {
                    return $next($request);
                }
                return abort(403, 'Hanya Management / Super Administrator yang dapat mengakses halaman ini.');
            }

            // Jika route mensyaratkan admin, maka admin biasa dan super_admin boleh masuk
            if (in_array('admin', $roles)) {
                return $next($request);
            }
        } else {
            // Untuk role selain admin (seperti pembeli, penjual, kurir)
            if (in_array($userRole, $roles)) {
                return $next($request);
            }
        }

        return abort(403, 'Unauthorized Access.');
    }
}
