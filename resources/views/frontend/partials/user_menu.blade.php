<aside class="user-sidebar p-3 p-md-4 sticky">
    <style>
        .user-sidebar { max-width: 320px; }
        .user-sidebar.sticky { position: sticky; top: 6rem; }
        .user-sidebar .card { border-radius: 12px; }
        .user-sidebar .avatar { width:56px; height:56px; font-size:20px; }
        .user-sidebar .nav-link { color: #1f2937; }
        .user-sidebar .nav-link.active { background: rgba(16,185,129,0.12); color: #065f46 !important; }
        .user-sidebar .nav-link:hover { background: rgba(16,185,129,0.06); }
        @media (max-width: 767px) { .user-sidebar.sticky { position: static; top: auto; margin-top: 1rem; max-width: 100%; } }
    </style>
    <div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                    <div>
                        <div class="fw-bold text-dark">{{ auth()->user()->name }}</div>
                        <small class="text-muted">Member</small>
                    </div>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link text-dark py-2 px-2 rounded mb-1 {{ Request::is('profile') ? 'active' : '' }}" href="{{ url('profile') }}">Profile</a>
                    <a class="nav-link text-dark py-2 px-2 rounded mb-1 {{ Request::is('orders*') ? 'active' : '' }}" href="{{ url('orders') }}">Orders</a>
                    <a class="nav-link text-dark py-2 px-2 rounded mb-1 {{ Request::is('carts*') ? 'active' : '' }}" href="{{ url('carts') }}">Cart</a>
                    <form action="{{ route('logout') }}" method="post" class="mt-3">
                        @csrf
                        <button class="btn btn-success w-100">Logout</button>
                    </form>
                </nav>
            </div>
        </div>
    </div>
</aside>
