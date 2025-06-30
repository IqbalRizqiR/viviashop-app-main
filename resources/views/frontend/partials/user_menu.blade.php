<div class="sidebar-widget mb-45 d-flex flex-column justify-content-center align-items-center" style="margin-top: 130px;">
    <div class="sidebar-categories">
        <ol class="text-center">
            <h3 class="sidebar-title text-center" style="">User Menu</h3>
			<h6><a style="color: black;" href="{{ url('profile') }}">Profile</a></h6>
			<h6><a style="color: black;" href="{{ url('orders') }}">Orders</a></h6>
			<h6><a style="color: black;" href="{{ url('carts') }}">Cart</a></h6>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="btn btn-primary mt-5">Logout</button>
            </form>
		</ol>
	</div>
</div>
