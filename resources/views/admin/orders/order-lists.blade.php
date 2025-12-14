@extends('admin.layouts.app')
@section('page_title', 'Order List')

@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Order List Widget -->

        <!-- Order Statistics Cards -->
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <!-- Total Orders -->
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0" id="statTotalOrders">0</h4>
                                    <p class="mb-0">Total Orders</p>
                                </div>
                                <span class="avatar me-sm-6">
                                    <span class="avatar-initial bg-label-secondary rounded text-heading">
                                        <i class="icon-base ti tabler-shopping-cart icon-26px text-heading"></i>
                                    </span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-6" />
                        </div>
                        <!-- Pending Orders -->
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <h4 class="mb-0" id="statPendingOrders">0</h4>
                                    <p class="mb-0">Pending Orders</p>
                                </div>
                                <span class="avatar p-2 me-lg-6">
                                    <span class="avatar-initial bg-label-secondary rounded"><i class="icon-base ti tabler-clock icon-26px text-heading"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none" />
                        </div>
                        <!-- Cancelled Orders -->
                        <div class="col-sm-6 col-lg-3">
                            <div
                                class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <h4 class="mb-0" id="statCancelledOrders">0</h4>
                                    <p class="mb-0">Cancelled Orders</p>
                                </div>
                                <span class="avatar p-2 me-sm-6">
                                    <span class="avatar-initial bg-label-secondary rounded"><i class="icon-base ti tabler-x icon-26px text-heading"></i></span>
                                </span>
                            </div>
                        </div>
                        <!-- Completed Orders -->
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h4 class="mb-0" id="statCompletedOrders">0</h4>
                                    <p class="mb-0">Completed Orders</p>
                                </div>
                                <span class="avatar p-2">
                                    <span class="avatar-initial bg-label-secondary rounded"><i class="icon-base ti tabler-checks icon-26px text-heading"></i></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order List Table -->
        <div class="card">
            <!-- Date Filter Section -->
            <div class="card-header border-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 col-12">
                        <h5 class="card-title mb-0">Order List</h5>
                    </div>
                    <div class="col-md-6 col-12 text-md-end">
                        <div class="d-flex gap-2 justify-content-md-end justify-content-start">
                            <!-- Date Filter Dropdown -->
                            <select id="dateFilter" class="form-select" style="max-width: 200px;">
                                <option value="today" selected>Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="last_7_days">Last 7 Days</option>
                                <option value="last_30_days">Last 30 Days</option>
                                <option value="this_month">This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="custom">Custom Range</option>
                            </select>

                            <!-- Custom Date Range Picker (hidden by default) -->
                            <div id="customDateRangeContainer" class="d-none">
                                <input type="text" id="customDateRange" class="form-control flatpickr-range" placeholder="Select date range" style="max-width: 250px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <table class="datatables-order table border-top">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>order</th>
                            <th>date</th>
                            <th>customers</th>
                            <th>payment</th>
                            <th>status</th>
                            <th>method</th>
                            <th>actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->


    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
@endsection

@push('scripts')
<script src="{{ asset('assets/js/app-ecommerce-order-list.js') }}"></script>
@endpush