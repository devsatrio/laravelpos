

<!-- Left navbar links -->
<ul class="navbar-nav">
          <li class="nav-item">
            <a href="{{url('backend/home')}}" class="nav-link">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Admin Management</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="{{url('backend/roles')}}" class="dropdown-item">Roles </a></li>
              <li><a href="{{url('backend/admin')}}" class="dropdown-item">Admin</a></li>
            </ul>
          </li>
          <li class="nav-item">
             <a href="{{url('backend/test')}}" class="nav-link">test</a>
          </li>
        </ul>