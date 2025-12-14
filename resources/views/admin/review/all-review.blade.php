@extends('admin.layouts.app')
@section('page_title', 'All Review')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Product Reviews</h4>
        <a href="{{ route('reviews.add') }}" class="btn btn-primary">Add Review</a>
    </div>

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title">Filter</h5>

            <div class="row pt-4 gap-4 gap-md-0 g-md-4">
                <div class="col-md-4 review_rating"></div>
                <div class="col-md-4 review_status"></div>
                <div class="col-md-4 review_product"></div>
            </div>
        </div>

        <div class="card-datatable" style="margin-left: 10px;">
            <table class="datatables-reviews table">
                <thead class="border-top">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Product</th>
                        <th>Name</th>
                        <th>Rating</th>
                        <th>Title</th>
                        <th>Review</th>
                        <th>Images</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/app-ecommerce-product-review-list.js') }}"></script>
@endpush