<nav id="sidebar" class="bg-dark text-white" style="min-width: 250px; min-height: 100vh;">
    <div class="sidebar-header p-4">
        <h3 class="text-success fw-bold"></h3>
    </div>
    <ul class="list-unstyled components p-3">
        <li class="mb-2">
            <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->is('dashboard') ? 'active bg-success rounded' : '' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
        </li>
        <li class="mb-2">
            <a href="/products" class="nav-link text-white {{ request()->is('products*') ? 'active bg-success rounded' : '' }}">
                <i class="fas fa-box me-2"></i> Products
            </a>
        </li>

        <li class="mb-2">
            <a href="/inventory" class="nav-link text-white {{ route('inventory.index')}}">
                <i class="fas fa-warehouse me-2"></i> Inventory
            </a>
        </li>

        <li class="mb-2">
            <a href="/sales" class="nav-link text-white">
                <i class="fas fa-shopping-cart me-2"></i> Sales & Orders
            </a>
        </li>

        <li class="mb-2">
            <a href="/customers" class="nav-link text-white">
                <i class="fas fa-users me-2"></i> Customers
            </a>
        </li>
        <li class="mb-2">
            <a href="/reports" class="nav-link text-white">
                <i class="fas fa-chart-bar me-2"></i> Reports
            </a>
        </li>
        <li class="mb-2">
            <a href="/settings" class="nav-link text-white">
                <i class="fas fa-cog me-2"></i> Settings
            </a>
        </li>
    </ul>
</nav>
