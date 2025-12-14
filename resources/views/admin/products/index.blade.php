@extends('admin.layouts.app')
@section('page_title', 'Product List')

@section('content')


<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Product List Table -->
    <div class="card">

        <div class="card-datatable" style="margin-left: 10px;">
            <table class="datatables-products table">
                <thead class="border-top">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>product</th>
                        <th>category</th>
                        <th>stock</th>
                        <th>sku</th>
                        <th>price</th>
                        <th>qty</th>
                        <th>status</th>
                        <th>actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- / Content -->

@endsection


@push('scripts')
<script src="{{ asset('assets/js/app-ecommerce-product-list.js') }}"></script>
@endpush