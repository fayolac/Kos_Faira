<aside class="sidebar">

    <div class="sidebar-header">
        <div class="sidebar-brand">
            <span class="sidebar-brand-name">Rumah Kos Faira</span>
            <span class="sidebar-brand-sub">Panel Admin</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="/admin/keuangan"
           class="{{ request()->is('admin/keuangan*') || request()->is('admin/pengeluaran*') ? 'active' : '' }}">
            • &nbsp; Data Keuangan
        </a>
        <a href="/admin/pengaduan"
           class="{{ request()->is('admin/pengaduan*') ? 'active' : '' }}">
            • &nbsp; Data Pengaduan
        </a>
        <a href="/admin/verifikasi"
            class="{{ request()->is('admin/verifikasi*') || request()->is('admin/pembayaran*') ? 'active' : '' }}">
                • &nbsp; Verifikasi Pembayaran
        </a>
        <a href="/admin/penyewa"
           class="{{ request()->is('admin/penyewa*') || request()->is('admin/pembayaran*') ? 'active' : '' }}">
            • &nbsp; Data Penyewa
        </a>
        <a href="/admin/data"
           class="{{ request()->is('admin/data*') || request()->is('admin/kamar*') || request()->is('admin/fasilitas*') || request()->is('admin/bank*') || request()->is('admin/peraturan*') ? 'active' : '' }}">
            • &nbsp; Data Fasilitas
        </a>
    </nav>

    <div class="sidebar-footer">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit">• &nbsp; Logout</button>
        </form>
    </div>

</aside>