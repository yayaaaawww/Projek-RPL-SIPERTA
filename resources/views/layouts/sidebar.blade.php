@php $role = auth()->user()->role ?? null; @endphp

<div class="sidebar p-3">
    @if ($role === 'petani')
        <a href="{{ route('petani.dashboard') }}" title="Dashboard"><i class="bi bi-house-door"></i></a>
        <a href="{{ route('petani.data') }}" title="Data Pertanian"><i class="bi bi-database"></i></a>
        <a href="{{ route('petani.konsultasi') }}" title="Konsultasi"><i class="bi bi-chat-left"></i></a>
        <a href="{{ route('petani.laporan') }}" title="Laporan"><i class="bi bi-exclamation-triangle"></i></a>
        <a href="{{ route('petani.katalog') }}" title="Katalog / Update Panen"><i class="bi bi-cart-plus"></i></a>
        <a href="{{ route('petani.pesanan') }}" title="Pesanan Masuk"><i class="bi bi-bag-check"></i></a>

    @elseif ($role === 'pedagang')
        <a href="{{ route('pedagang.dashboard') }}" title="Dashboard"><i class="bi bi-house-door"></i></a>
        <a href="{{ route('pedagang.katalog') }}" title="Katalog"><i class="bi bi-shop"></i></a>
        <a href="{{ route('pedagang.pesanan') }}" title="Pesanan Saya"><i class="bi bi-cart"></i></a>
        <a href="{{ route('pedagang.laporan') }}" title="Laporan"><i class="bi bi-exclamation-triangle"></i></a>
        <a href="{{ route('pedagang.profile') }}" title="Profile"><i class="bi bi-person"></i></a>

    @elseif ($role === 'ahli')
        <a href="{{ route('ahli.dashboard') }}" title="Dashboard"><i class="bi bi-house-door"></i></a>
        <a href="{{ route('ahli.konsultasi') }}" title="Konsultasi"><i class="bi bi-chat-left"></i></a>
        <a href="{{ route('ahli.laporan') }}" title="Laporan"><i class="bi bi-exclamation-triangle"></i></a>
        <a href="{{ route('ahli.profile') }}" title="Profile"><i class="bi bi-person"></i></a>

    @elseif ($role === 'admin')
        <a href="{{ route('admin.dashboard') }}" title="Dashboard"><i class="bi bi-house-door"></i></a>
        <a href="{{ route('admin.users') }}" title="Kelola Pengguna"><i class="bi bi-people"></i></a>
        <a href="{{ route('admin.transaksi') }}" title="Transaksi"><i class="bi bi-receipt"></i></a>
        <a href="{{ route('admin.konsultasi') }}" title="Pantau Konsultasi"><i class="bi bi-chat-left"></i></a>
        <a href="{{ route('admin.laporan') }}" title="Laporan"><i class="bi bi-exclamation-triangle"></i></a>
    @endif

    <hr class="text-white">

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" title="Logout"
                style="background:none; border:none; color:white; width:100%; padding:12px; cursor:pointer;">
            <i class="bi bi-box-arrow-right" style="font-size:25px;"></i>
        </button>
    </form>
</div>
