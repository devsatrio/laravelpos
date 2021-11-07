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
    @if(auth()->user()->can('view-test')
    || auth()->user()->can('create-test')
    || auth()->user()->can('edit-test')
    || auth()->user()->can('delete-test'))
    <li class="nav-item">
        <a href="{{url('backend/test')}}" class="nav-link">test</a>
    </li>
    @endif
</ul>