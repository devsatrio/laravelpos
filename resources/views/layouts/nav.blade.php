<!-- Left navbar links -->
<ul class="navbar-nav">
    <li class="nav-item">
        <a href="{{url('/backend/home')}}" class="nav-link">Home</a>
    </li>
    @if(auth()->user()->can('view-roles')
    || auth()->user()->can('create-roles')
    || auth()->user()->can('edit-roles')
    || auth()->user()->can('delete-roles')
    || auth()->user()->can('view-users')
    || auth()->user()->can('create-users')
    || auth()->user()->can('edit-users')
    || auth()->user()->can('delete-users'))
    <li class="nav-item dropdown">
        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Admin Management</a>
        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
            @if(auth()->user()->can('view-roles')
            || auth()->user()->can('create-roles')
            || auth()->user()->can('edit-roles')
            || auth()->user()->can('delete-roles'))
            <li><a href="{{url('backend/roles')}}" class="dropdown-item">Roles </a></li>
            @endif
            @if(auth()->user()->can('view-users')
            || auth()->user()->can('create-users')
            || auth()->user()->can('edit-users')
            || auth()->user()->can('delete-users'))
            <li><a href="{{url('backend/admin')}}" class="dropdown-item">Admin</a></li>
            @endif
        </ul>
    </li>
    @endif
    <li class="nav-item dropdown">
        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Master Data</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            <li><a href="#" class="dropdown-item">Data Supplier</a></li>
            @if(auth()->user()->can('view-customer')
            || auth()->user()->can('create-customer')
            || auth()->user()->can('edit-customer')
            || auth()->user()->can('delete-customer'))
            <li><a href="{{url('backend/customer')}}" class="dropdown-item">Data Customer</a></li>
            @endif
            
            @if(auth()->user()->can('view-kategori-barang')
            || auth()->user()->can('create-kategori-barang')
            || auth()->user()->can('edit-kategori-barang')
            || auth()->user()->can('delete-kategori-barang'))
            <li><a href="{{url('backend/kategori-barang')}}" class="dropdown-item">Data Kategori Barang </a></li>
            @endif
            <li><a href="#" class="dropdown-item">Data Barang</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Transaksi</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            <li><a href="#" class="dropdown-item">Transaksi Penjualan</a></li>
            <li><a href="#" class="dropdown-item">Transaksi Pembelian</a></li>
            <li><a href="#" class="dropdown-item">Transaksi Hutang</a></li>
            <li><a href="#" class="dropdown-item">Transaksi Piutang</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Laporan</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            <li><a href="#" class="dropdown-item">Laporan Penjualan</a></li>
            <li><a href="#" class="dropdown-item">Laporan Pembelian</a></li>
            <li><a href="#" class="dropdown-item">Laporan Hutang</a></li>
            <li><a href="#" class="dropdown-item">Laporan Piutang</a></li>
            <li><a href="#" class="dropdown-item">Laporan Laba Rugi</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Lain - Lain</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            <li><a href="#" class="dropdown-item">Perbaikan Stok</a></li>
            <li><a href="#" class="dropdown-item">Pengeluaran / Pembelian Lainnya</a></li>
        </ul>
    </li>
</ul>