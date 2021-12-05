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

    @if(auth()->user()->can('view-supplier')
    || auth()->user()->can('create-supplier')
    || auth()->user()->can('edit-supplier')
    || auth()->user()->can('delete-supplier')
    || auth()->user()->can('view-customer')
    || auth()->user()->can('create-customer')
    || auth()->user()->can('edit-customer')
    || auth()->user()->can('delete-customer')
    || auth()->user()->can('view-kategori-barang')
    || auth()->user()->can('create-kategori-barang')
    || auth()->user()->can('edit-kategori-barang')
    || auth()->user()->can('delete-kategori-barang')
    || auth()->user()->can('view-barang')
    || auth()->user()->can('create-barang')
    || auth()->user()->can('edit-barang')
    || auth()->user()->can('delete-barang'))
    <li class="nav-item dropdown">
        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Master Data</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            @if(auth()->user()->can('view-supplier')
            || auth()->user()->can('create-supplier')
            || auth()->user()->can('edit-supplier')
            || auth()->user()->can('delete-supplier'))
            <li><a href="{{url('backend/supplier')}}" class="dropdown-item">Data Supplier</a></li>
            @endif

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
            @if(auth()->user()->can('view-barang')
            || auth()->user()->can('create-barang')
            || auth()->user()->can('edit-barang')
            || auth()->user()->can('delete-barang'))
            <li><a href="{{url('backend/barang')}}" class="dropdown-item">Data Barang</a></li>
            @endif
        </ul>
    </li>
    @endif

    @if(auth()->user()->can('view-penjualan')
    || auth()->user()->can('create-penjualan')
    || auth()->user()->can('delete-penjualan')
    || auth()->user()->can('update-hutang-penjualan')
    || auth()->user()->can('view-pembelian')
    || auth()->user()->can('create-pembelian')
    || auth()->user()->can('edit-pembelian')
    || auth()->user()->can('delete-pembelian')
    || auth()->user()->can('approve-pembelian'))
    <li class="nav-item dropdown">
        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Transaksi</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            @if(auth()->user()->can('view-penjualan')
            || auth()->user()->can('create-penjualan')
            || auth()->user()->can('delete-penjualan')
            || auth()->user()->can('update-hutang-penjualan'))
            <li><a href="{{url('backend/penjualan')}}" class="dropdown-item">Transaksi Penjualan</a></li>
            @endif
            @if(auth()->user()->can('view-pembelian')
            || auth()->user()->can('create-pembelian')
            || auth()->user()->can('edit-pembelian')
            || auth()->user()->can('delete-pembelian')
            || auth()->user()->can('approve-pembelian'))
            <li><a href="{{url('backend/pembelian')}}" class="dropdown-item">Transaksi Pembelian</a></li>
            @endif
        </ul>
    </li>
    @endif

    <li class="nav-item dropdown">
        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Laporan</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            <li><a href="{{url('backend/laporan-penjualan')}}" class="dropdown-item">Laporan Penjualan</a></li>
            <li><a href="{{url('backend/laporan-detail-penjualan')}}" class="dropdown-item">Laporan Detail Penjualan</a></li>
            <li><a href="{{url('backend/laporan-pembelian')}}" class="dropdown-item">Laporan Pembelian</a></li>
            <li><a href="{{url('backend/laporan-pemasukan-pengeluaran-lain')}}" class="dropdown-item">Laporan Pengeluaran / Pemasukan Lainya</a></li>
            <li><a href="#" class="dropdown-item">Laporan Laba Rugi</a></li>
        </ul>
    </li>

    @if(auth()->user()->can('view-perbaikan-stok')
    || auth()->user()->can('create-perbaikan-stok')
    || auth()->user()->can('edit-perbaikan-stok')
    || auth()->user()->can('delete-perbaikan-stok')
    || auth()->user()->can('approve-perbaikan-stok')
    || auth()->user()->can('view-transaksi-lain')
    || auth()->user()->can('create-transaksi-lain')
    || auth()->user()->can('edit-transaksi-lain')
    || auth()->user()->can('delete-transaksi-lain')
    || auth()->user()->can('setting-web'))
    <li class="nav-item dropdown">
        <a id="dropdownSubMenu2" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
            class="nav-link dropdown-toggle">Lain - Lain</a>
        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
            @if(auth()->user()->can('view-perbaikan-stok')
            || auth()->user()->can('create-perbaikan-stok')
            || auth()->user()->can('edit-perbaikan-stok')
            || auth()->user()->can('delete-perbaikan-stok')
            || auth()->user()->can('approve-perbaikan-stok'))
            <li><a href="{{url('backend/perbaikan-stok')}}" class="dropdown-item">Perbaikan Stok</a></li>
            @endif

            @if(auth()->user()->can('view-transaksi-lain')
            || auth()->user()->can('create-transaksi-lain')
            || auth()->user()->can('edit-transaksi-lain')
            || auth()->user()->can('delete-transaksi-lain'))
            <li><a href="{{url('backend/transaksi-lain')}}" class="dropdown-item">Pengeluaran / Pemasukan Lainnya</a></li>
            @endif

            @if(auth()->user()->can('setting-web'))
            <li><a href="{{url('backend/web-setting')}}" class="dropdown-item">Web Setting</a></li>
            @endif
        </ul>
    </li>
    @endif
</ul>