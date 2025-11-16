@extends('admin.layouts.app')
@section('content')


<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- customers List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="datatables-customers table border-top">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Customer</th>
                            <th class="text-nowrap">Customer Id</th>
                            <th>Country</th>
                            <th>Order</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!-- Offcanvas to add new customer -->
            <div
                class="offcanvas offcanvas-end"
                tabindex="-1"
                id="offcanvasEcommerceCustomerAdd"
                aria-labelledby="offcanvasEcommerceCustomerAddLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasEcommerceCustomerAddLabel" class="offcanvas-title">Add Customer</h5>
                    <button
                        type="button"
                        class="btn-close text-reset"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>


                <div class="offcanvas-body border-top mx-0 flex-grow-0">
                    <form id="eCommerceCustomerAddForm" onsubmit="return false">

                        <div class="mb-4">
                            <h6 class="mb-3">Customer Information</h6>
                            <div class="mb-3">
                                <label class="form-label" for="customer-name">Name*</label>
                                <input type="text" class="form-control" id="customer-name" name="name" placeholder="John Doe" required />
                                <small class="text-danger error-message" id="error-name"></small>

                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="customer-email">Email*</label>
                                <input type="email" class="form-control" id="customer-email" name="email" placeholder="john@example.com" required />
                                <small class="text-danger error-message" id="error-email"></small>

                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="customer-mobile">Mobile</label>
                                <input type="text" class="form-control" id="customer-mobile" name="mobile" placeholder="+91 9876543210" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="mb-3">Shipping Information</h6>
                            <div class="mb-3">
                                <label class="form-label" for="customer-address1">Address Line 1</label>
                                <input type="text" class="form-control" id="customer-address1" name="address_line1" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="customer-town">City / Town</label>
                                <input type="text" class="form-control" id="customer-town" name="city" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="customer-state">State</label>
                                <input type="text" class="form-control" id="customer-state" name="state" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="customer-postcode">Pincode</label>
                                <input type="text" class="form-control" id="customer-postcode" name="postal_code" maxlength="6" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="customer-country">Country</label>
                                <select class="form-select" id="customer-country" name="country">
                                    <option value="">Select</option>
                                    <option value="India">India</option>
                                    <option value="United States">United States</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Canada">Canada</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <button type="button" id="btnAddCustomer" class="btn btn-primary ">Add Customer</button>
                            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Discard</button>
                        </div>
                    </form>
                </div>



            </div>
        </div>
    </div>
    <!-- / Content -->



    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

@endsection('content')

@push('scripts')
<script src="{{ asset('assets/js/app-ecommerce-customer-all.js') }}"></script>
@endpush