<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Reservasi;

class PenyewaAktifMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('penyewa')->check()) {
            return redirect('/login')
                   ->with('error', 'Silahkan login terlebih dahulu.');
        }
        
        $penyewa = Auth::guard('penyewa')->user();

        // Cek apakah penyewa punya reservasi aktif
        $reservasiAktif = Reservasi::where('id_penyewa', $penyewa->id_penyewa)
                                   ->where('status', 'Aktif')
                                   ->first();

        if (!$reservasiAktif) {
            return redirect('/')
                   ->with('error', 'Anda tidak memiliki kamar aktif.');
        }
        return $next($request);
    }
}
