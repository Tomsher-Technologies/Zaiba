<div class="col-md-4">
    <div class="user-dashboard-section">
        <div class="dashboard-left-sidebar">
            <div class="profile-box">
                <div class="cover-image">
                    <img src="{{ frontendAsset('img/account/cover-img.jpg') }}" class="img-fluid blur-up lazyloaded"
                        alt="">
                </div>
                <div class="profile-contain">

                    <div class="profile-name">
                        <h3>{{ auth()->user()->name }}</h3>
                        <h6 class="text-content">{{ auth()->user()->email }}</h6>
                    </div>
                </div>
            </div>
            <ul class="nav nav-pills user-nav-pills">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ areActiveRoutes(['dashboard']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('purchase_history.index') }}"
                        class="nav-link {{ areActiveRoutes(['purchase_history.index', 'purchase_history.details']) }}"><svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-shopping-bag">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>Order</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('wishlists.index') }}"
                        class="nav-link {{ areActiveRoutes(['wishlists.index']) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-heart">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                        Wishlist</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ areActiveRoutes(['addresses.index']) }}" href="{{ route('addresses.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-map-pin">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        Address</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ areActiveRoutes(['profile', 'profile.password']) }}"
                        href="{{ route('profile') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-user">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Profile</a>
                </li>

            </ul>
        </div>
    </div>
</div>
