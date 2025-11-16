            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu">
                <div class="app-brand demo">
                    <a href="{{ route('dashboard.home') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span class="text-primary">
                                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                        fill="currentColor" />
                                    <path
                                        opacity="0.06"
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                                        fill="#161616" />
                                    <path
                                        opacity="0.06"
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                                        fill="#161616" />
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-3">Vuexy</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
                        <i class="icon-base ti tabler-x d-block d-xl-none"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">

                    <!-- Dashboards -->
                    <li class="menu-item {{ request()->routeIs('dashboard.home') ? 'active open' : '' }}">
                        <a href="{{ route('dashboard.home') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-smart-home"></i>
                            <div data-i18n="Dashboards">Dashboards</div>
                        </a>
                    </li>


                    <li class="menu-item {{ request()->routeIs('products.*', 'categories.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Products">Products</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
                                <a href="{{ route('products.index') }}" class="menu-link">
                                    <div data-i18n="Product List">Product List</div>
                                </a>
                            </li>
                            <li class="menu-item {{ (request()->routeIs('products.create') || request()->routeIs('products.edit')) ? 'active' : '' }}">
                                <a href="{{ route('products.create')  }}" class="menu-link">
                                    <div data-i18n="Add Product">Add Product</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                <a href="{{ route('categories.index') }}" class="menu-link">
                                    <div data-i18n="Category List">Category List</div>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li class="menu-item   {{ (request()->routeIs('orders.list') ||  request()->routeIs('orders.details') ) ? 'active' : '' }}">
                        <a href="{{ route('orders.list')}}" class="menu-link">
                            <div data-i18n="Order List">Order List</div>
                        </a>
                    </li>


                    <!-- <li class="menu-item {{ request()->routeIs('orders.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Orders">Orders</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item   {{ request()->routeIs('orders.list') ? 'active' : '' }}">
                                <a href="{{ route('orders.list')}}" class="menu-link">
                                    <div data-i18n="Order List">Order List</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->routeIs('orders.details') ? 'active' : '' }}">
                                <a href="{{ route('orders.details')}}" class="menu-link">
                                    <div data-i18n="Order Details">Order Details</div>
                                </a>
                            </li>

                        </ul>
                    </li> -->


                    <li class="menu-item  {{ request()->routeIs('customers.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Customer">Customer</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item  {{ request()->routeIs('customers.all')  ? 'active' : '' }}">
                                <a href="{{route('customers.all')}}" class="menu-link">
                                    <div data-i18n="All Customers">All Customers</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="javascript:void(0);" class="menu-link menu-toggle">
                                    <div data-i18n="Customer Details">Customer Details</div>
                                </a>
                                <ul class="menu-sub">
                                    <li class="menu-item">
                                        <a href="{{ route('customers.overview') }}" class="menu-link">
                                            <div data-i18n="Overview">Overview</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('customers.security')}}" class="menu-link">
                                            <div data-i18n="Security">Security</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="{{ route('customers.billing')}} " class="menu-link">
                                            <div data-i18n="Address & Billing">Address & Billing</div>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>



                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Coupons & Discounts">Coupons & Discounts</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{route('discounts.all')}}" class="menu-link">
                                    <div data-i18n="All Coupons">All Coupons</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('discounts.add')}}" class="menu-link">
                                    <div data-i18n="Add New Coupon">Add New Coupon</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('discounts.active')}}" class="menu-link">
                                    <div data-i18n="Active Coupons">Active Coupons</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('discounts.expired')}}" class="menu-link">
                                    <div data-i18n="Expired Coupons">Expired Coupons</div>
                                </a>
                            </li>

                        </ul>
                    </li>



                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Review">Review</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="{{route('reviews.all')}}" class="menu-link">
                                    <div data-i18n="All Review">All Review</div>
                                </a>
                            </li>

                            <li class="menu-item">
                                <a href="{{route('reviews.add')}}" class="menu-link">
                                    <div data-i18n="Add Review">Add Review</div>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="menu-item {{ request()->routeIs('settings.*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <div data-i18n="Settings">Settings</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->routeIs('settings.store-detials')  ? 'active' : '' }}">
                                <a href="{{route('settings.store-detials')}}" class="menu-link">
                                    <div data-i18n="Store Details">Store Details</div>
                                </a>
                            </li>

                        </ul>
                    </li>


                </ul>
            </aside>

            <div class="menu-mobile-toggler d-xl-none rounded-1">
                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
                    <i class="ti tabler-menu icon-base"></i>
                    <i class="ti tabler-chevron-right icon-base"></i>
                </a>
            </div>
            <!-- / Menu -->