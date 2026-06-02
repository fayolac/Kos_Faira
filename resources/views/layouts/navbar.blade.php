<nav class="navbar-faira d-flex justify-content-between align-items-center">
    <a href="/" class="navbar-brand-wrap">
        <div class="navbar-logo-placeholder" style="display:none;">K</div>
        <span class="navbar-brand-name">Rumah Kos Faira</span>
    </a>

    <ul class="navbar-menu">
        <li>
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a>
        </li>
        <li>
            <a href="/kamar" class="{{ request()->is('kamar*') ? 'active' : '' }}">Kamar & Fasilitas</a>
        </li>
        <li>
            <a href="/peraturan"
            class="{{ request()->is('peraturan') ? 'active' : '' }}">
                Peraturan
            </a>
        </li>
        {{-- ── Dropdown Layanan Penyewa ── --}}
        <li class="dropdown-faira">

            @php
                $penyewaLogin = auth()->guard('penyewa')->check();
                $bisaCekStatus = false;
                $bisaLayananPenuh = false;

                if ($penyewaLogin) {
                    $idPenyewa = auth()->guard('penyewa')->id();
                    $statusReservasi = \App\Models\Reservasi::where('id_penyewa', $idPenyewa)
                                        ->orderByDesc('created_at')
                                        ->value('status'); // ambil status reservasi terbaru

                    $bisaCekStatus    = in_array($statusReservasi, ['Menunggu', 'Aktif']);
                    $bisaLayananPenuh = $statusReservasi === 'Aktif';
                }
            @endphp

            <button class="dropdown-toggle-faira {{ !$bisaCekStatus ? 'disabled' : '' }}"
                    title="{{ !$bisaCekStatus ? 'Ajukan reservasi terlebih dahulu' : '' }}">
                Layanan Penyewa ▾
            </button>

            @if($bisaCekStatus)
                <div class="dropdown-menu-faira">

                    @if($bisaLayananPenuh)
                        <a href="/perpanjangan">Perpanjangan Sewa</a>
                    @else
                        <span class="dropdown-item-disabled" title="Tersedia setelah reservasi dikonfirmasi">
                            Perpanjangan Sewa
                        </span>
                    @endif

                    @if($bisaLayananPenuh)
                        <a href="/pengaduan">Pengaduan</a>
                    @else
                        <span class="dropdown-item-disabled" title="Tersedia setelah reservasi dikonfirmasi">
                            Pengaduan
                        </span>
                    @endif

                    <a href="/cek-status">Cek Status</a>

                </div>
            @endif
        </li>
        
        <li>
            @if($penyewaLogin)
                <div class="navbar-user">
                    <span class="navbar-user-name">
                        {{ auth()->guard('penyewa')->user()->nama }}
                    </span>
                    <form action="/logout" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-navbar-logout">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="/login" class="btn-navbar-login">Login</a>
            @endif
        </li>

    </ul>
</nav>