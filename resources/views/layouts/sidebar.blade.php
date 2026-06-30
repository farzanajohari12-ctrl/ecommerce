<!-- <div class="bg-danger text-white text-center small p-2 m-2 rounded">
    Logged In User ID: {{ auth()->id() }} | 
    Assigned Roles: {{ implode(', ', auth()->user()->getRoleNames()->toArray()) }}
</div> -->
<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon">
        <img src="{{ asset('img/logo/logo2.png') }}">
    </div>
    <div class="sidebar-brand-text mx-3">RuangAdmin</div>
    </a>
    <hr class="sidebar-divider my-0">
    @can('view_dashboard')
    <li class="nav-item active">
        <a class="nav-link" href="./dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @endcan
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
    App
    </div>
    @can('manage_ecommerce')
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
        aria-expanded="true" aria-controls="collapseBootstrap">
        <i class="far fa fa-shopping-cart"></i>
        <span>E commerce</span>
    </a>
    <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <h6 class="collapse-header">Admin</h6>
        <a class="collapse-item" href="{{ route('add-product.create') }}">Add product</a>
        <a class="collapse-item" href="{{ route('products.index') }}">Products</a>
        <a class="collapse-item" href="dropdowns.html">Customers</a>
        <a class="collapse-item" href="modals.html">Orders</a>
        <a class="collapse-item" href="popovers.html">Refund</a>
        </div>
    </div>
    </li>
    @endcan
    @can('manage_catalog')
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCatalog" aria-expanded="true"
        aria-controls="collapseCatalog">
        <i class="fas fa-fw fa-columns"></i>
        <span>Catalog</span>
    </a>
    <div id="collapseCatalog" class="collapse" aria-labelledby="headingCatalog" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{ url('categories') }}">
            Categories
        </a>
        <a class="collapse-item" href="{{ url('badges') }}">Badges</a>
        <a class="collapse-item" href="{{ url('tags') }}">Tags</a>
        <a class="collapse-item" href="{{ url('variant-attributes') }}">Product Variants</a>
        <a class="collapse-item" href="#">Product Reviews</a>
        </div>
    </div>
    </li>
    @endcan
    @can('manage_inventory')
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
        aria-controls="collapseTable">
        <i class="fas fa-fw fa-table"></i>
        <span>Inventory</span>
    </a>
    <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item"  href="{{ route('product.stockList') }}">Stock Management</a>
        <a class="collapse-item" href="#">Inventory Reports</a>
        </div>
    </div>
    </li>
    @endcan
    @can('manage_marketing')
    <li class="nav-item">
    <a class="nav-link" href="charts.html">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Marketing</span>
    </a>
    </li>
    @endcan
    @can('manage_reports')
    <li class="nav-item">
    <a class="nav-link" href="charts.html">
        <i class="far fa-fw fa-window-maximize"></i>
        <span>Reports & Analytics</span>
    </a>
    </li>
    @endcan
    @can('manage_access')
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUserAccess"
        aria-expanded="true" aria-controls="collapseUserAccess">
        <i class="far fa fa-user"></i>
        <span>Access</span>
    </a>
    <div id="collapseUserAccess" class="collapse" aria-labelledby="headingUserAccess" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{ url('users') }}">Admin Users</a>
        <a class="collapse-item" href="{{ route('roles.permissions.index') }}">Roles and Permissions</a>
        <a class="collapse-item" href="#">Activity Logs</a>
        </div>
    </div>
    </li>
    @endcan
    @can('manage_system_settings')
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSystemSettings"
        aria-expanded="true" aria-controls="collapseSystemSettings">
        <i class="far fa fa-cog"></i>
        <span>System Settings</span>
    </a>
    <div id="collapseSystemSettings" class="collapse" aria-labelledby="headingSystemSettings" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="#">General Settings</a>
        <a class="collapse-item" href="#">Payment Settings</a>
        <a class="collapse-item" href="#">Shipping Settings</a>
        <a class="collapse-item" href="#">SEO Settings</a>
        </div>
    </div>
    </li>
    @endcan
    
    <hr class="sidebar-divider">
    <div class="version" id="version-ruangadmin"></div>
</ul>