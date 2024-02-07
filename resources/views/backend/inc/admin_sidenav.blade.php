<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-center">
                @if (get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_white')) }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100 logo-custom" src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm text-white" type="text"
                    name="" placeholder="Search in menu" id="menu-search" onkeyup="menuSearch()">
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">Dashboard</span>
                    </a>
                </li>

                <!-- Product -->
                @if (userHasPermision(2))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Products</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @if (userHasPermision(25))
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link" href="{{ route('products.create') }}">
                                        <span class="aiz-side-nav-text">Add New product</span>
                                    </a>
                                </li>
                            @endif
                            @if (userHasPermision(2))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('products.all') }}" class="aiz-side-nav-link {{ areActiveRoutes(['products.all', 'products.edit']) }}" >
                                        <span class="aiz-side-nav-text">All Products</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('categories.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit']) }}">
                                        <span class="aiz-side-nav-text">Category</span>
                                    </a>
                                </li>
                                <!-- <li class="aiz-side-nav-item">
                                    <a href="{{ route('brands.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit']) }}">
                                        <span class="aiz-side-nav-text">Brand</span>
                                    </a>
                                </li> -->

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('designs.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['designs.index', 'designs.create', 'designs.edit']) }}">
                                        <span class="aiz-side-nav-text">Designs</span>
                                    </a>
                                </li>

                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('attributes.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['attributes.index', 'attributes.create', 'attributes.edit']) }}">
                                        <span class="aiz-side-nav-text">Attribute</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('reviews.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">Product Reviews</span>
                                    </a>
                                </li>
                            @endif
                            @if (userHasPermision(30))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('product_bulk_upload.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">Bulk Import</span>
                                    </a>
                                </li>
                            @endif
                            @if (userHasPermision(31))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('product_bulk_export.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">Bulk Export</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (userHasPermision(3) || userHasPermision(28))
                    <!-- Sale -->
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-money-bill aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Sales</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @if (userHasPermision(3))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('all_orders.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['all_orders.index', 'all_orders.show']) }}">
                                        <span class="aiz-side-nav-text">All Orders</span>
                                    </a>
                                </li>
                            @endif
                            @if (userHasPermision(28))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('enquiries.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['enquiries.index', 'enquiries.show']) }}">
                                        <span class="aiz-side-nav-text">Product Enquiry</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif



                <!-- Customers -->
                @if (userHasPermision(8))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-friends aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Customers</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('customers.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Customer list</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (userHasPermision(22))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('uploaded-files.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.create']) }}">
                            <i class="las la-folder-open aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Uploaded Files</span>
                        </a>
                    </li>
                @endif
                <!-- Reports -->
                @if (userHasPermision(10))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-file-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Reports</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <!--<li class="aiz-side-nav-item">-->
                            <!--    <a href="{{ route('in_house_sale_report.index') }}"-->
                            <!--        class="aiz-side-nav-link {{ areActiveRoutes(['in_house_sale_report.index']) }}">-->
                            <!--        <span class="aiz-side-nav-text">Product Sale</span>-->
                            <!--    </a>-->
                            <!--</li>-->
                            {{-- <li class="aiz-side-nav-item">
                                <a href="{{ route('seller_sale_report.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['seller_sale_report.index']) }}">
                                    <span class="aiz-side-nav-text">Seller Products Sale</span>
                                </a>
                            </li> --}}
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('stock_report.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['stock_report.index']) }}">
                                    <span class="aiz-side-nav-text">Products Stock</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('wish_report.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['wish_report.index']) }}">
                                    <span class="aiz-side-nav-text">Products wishlist</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('user_search_report.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['user_search_report.index']) }}">
                                    <span class="aiz-side-nav-text">User Searches</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('abandoned-cart.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['abandoned-cart.index']) }}">
                                    <span class="aiz-side-nav-text">Abandoned Cart</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- marketing -->
                @if (userHasPermision(11))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-bullhorn aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Marketing</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            {{-- @if (Auth::user()->user_type == 'admin' || in_array('7', json_decode(Auth::user()->staff->role->permissions)))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('newsletters.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">Newsletters</span>
                                    </a>
                                </li>
                                @if (addon_is_activated('otp_system'))
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('sms.index') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text">Bulk SMS</span>
                                            @if (env('DEMO_MODE') == 'On')
                                                <span class="badge badge-inline badge-danger">Addon</span>
                                            @endif
                                        </a>
                                    </li>
                                @endif
                            @endif --}}
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('subscribers.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Subscribers</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('coupon.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['coupon.index', 'coupon.create', 'coupon.edit']) }}">
                                    <span class="aiz-side-nav-text">Coupon</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif


                <!-- Website Setup -->
                @if (userHasPermision(13))
                    <li class="aiz-side-nav-item">
                        <a href="#"
                            class="aiz-side-nav-link {{ areActiveRoutes(['website.footer', 'website.header', 'banners.*']) }}">
                            <i class="las la-desktop aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Website Setup</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.header') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Header</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.menu') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Menus</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.footer', ['lang' => App::getLocale()]) }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['website.footer']) }}">
                                    <span class="aiz-side-nav-text">Footer</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.pages') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['website.pages', 'custom-pages.create', 'custom-pages.edit']) }}">
                                    <span class="aiz-side-nav-text">Pages</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.appearance') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Appearance</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('home-slider.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['home-slider.index', 'home-slider.create', 'home-slider.edit']) }}">
                                    <span class="aiz-side-nav-text">Home Page Sliders</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('banners.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['banners.index', 'banners.create', 'banners.edit']) }}">
                                    <span class="aiz-side-nav-text">Banners</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Setup & Configurations -->
                @if (userHasPermision(14))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-dharmachakra aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Setup & Configurations</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('shipping_configuration.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Shipping 
                                        {{-- and Return --}}
                                    </span>
                                </a>
                            </li>

                            {{-- <li class="aiz-side-nav-item">
                                <a href="{{ route('general_setting.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">General Settings</span>
                                </a>
                            </li>


                            <li class="aiz-side-nav-item">
                                <a href="{{ route('currency.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Currency</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('smtp_settings.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">SMTP Settings</span>
                                </a>
                            </li> --}}

                            <!--<li class="aiz-side-nav-item">-->
                            <!--    <a href="javascript:void(0);" class="aiz-side-nav-link">-->
                            <!--        <span class="aiz-side-nav-text">Facebook</span>-->
                            <!--        <span class="aiz-side-nav-arrow"></span>-->
                            <!--    </a>-->
                            <!--    <ul class="aiz-side-nav-list level-3">-->
                            <!--        <li class="aiz-side-nav-item">-->
                            <!--            <a href="{{ route('facebook_chat.index') }}" class="aiz-side-nav-link">-->
                            <!--                <span class="aiz-side-nav-text">Facebook Chat</span>-->
                            <!--            </a>-->
                            <!--        </li>-->
                            <!--        <li class="aiz-side-nav-item">-->
                            <!--            <a href="{{ route('facebook-comment') }}" class="aiz-side-nav-link">-->
                            <!--                <span class="aiz-side-nav-text">Facebook Comment</span>-->
                            <!--            </a>-->
                            <!--        </li>-->
                            <!--    </ul>-->
                            <!--</li>-->

                            <!--<li class="aiz-side-nav-item">-->
                            <!--    <a href="javascript:void(0);" class="aiz-side-nav-link">-->
                            <!--        <span class="aiz-side-nav-text">Google</span>-->
                            <!--        <span class="aiz-side-nav-arrow"></span>-->
                            <!--    </a>-->
                            <!--    <ul class="aiz-side-nav-list level-3">-->
                            <!--        <li class="aiz-side-nav-item">-->
                            <!--            <a href="{{ route('google_analytics.index') }}" class="aiz-side-nav-link">-->
                            <!--                <span class="aiz-side-nav-text">Analytics Tools</span>-->
                            <!--            </a>-->
                            <!--        </li>-->
                            <!--        <li class="aiz-side-nav-item">-->
                            <!--            <a href="{{ route('google_recaptcha.index') }}" class="aiz-side-nav-link">-->
                            <!--                <span class="aiz-side-nav-text">Google reCAPTCHA</span>-->
                            <!--            </a>-->
                            <!--        </li>-->
                            <!--        <li class="aiz-side-nav-item">-->
                            <!--            <a href="{{ route('google-map.index') }}" class="aiz-side-nav-link">-->
                            <!--                <span class="aiz-side-nav-text">Google Map</span>-->
                            <!--            </a>-->
                            <!--        </li>-->
                            <!--        <li class="aiz-side-nav-item">-->
                            <!--            <a href="{{ route('google-firebase.index') }}" class="aiz-side-nav-link">-->
                            <!--                <span class="aiz-side-nav-text">Google Firebase</span>-->
                            <!--            </a>-->
                            <!--        </li>-->
                            <!--    </ul>-->
                            <!--</li>-->




                            {{-- <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">Shipping</span>
                                    <span class="aiz-side-nav-arrow"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('shipping_configuration.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}">
                                            <span class="aiz-side-nav-text">Shipping Configuration</span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('countries.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['countries.index', 'countries.edit', 'countries.update']) }}">
                                            <span class="aiz-side-nav-text">Shipping Countries</span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('states.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['states.index', 'states.edit', 'states.update']) }}">
                                            <span class="aiz-side-nav-text">Shipping States</span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('cities.index') }}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['cities.index', 'cities.edit', 'cities.update']) }}">
                                            <span class="aiz-side-nav-text">Shipping Cities</span>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}

                        </ul>
                    </li>
                @endif

                @if (userHasPermision(23))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('blog.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['blog.index','blog.create','blog.edit']) }}">
                            <i class="las la-blog aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Blogs</span>
                        </a>
                    </li>
                @endif

                @if (userHasPermision(21))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('admin.stores.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['admin.stores.index','admin.stores.create','admin.stores.edit']) }}">
                            <i class="las la-store aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Stores</span>
                        </a>
                    </li>
                @endif

                <!-- Staffs -->
                @if (userHasPermision(20))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <i class="las la-user-tie aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">Staffs</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('staffs.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit']) }}">
                                    <span class="aiz-side-nav-text">All staffs</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('roles.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit']) }}">
                                    <span class="aiz-side-nav-text">Staff permissions</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
