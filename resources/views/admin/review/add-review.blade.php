@extends('admin.layouts.app')
@section('page_title', 'Product Review')
@section('content')

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-md-12 mx-auto">

                <div class="card">
                    <h5 class="card-header">Add Product Review</h5>

                    <div class="card-body">
                        <form id="reviewForm" method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data">
                            @csrf
                            {{-- Product ID --}}
                            <div class="mb-3">
                                <label for="upsell-products" class="form-label">Select Product</label>
                                <select class="form-select" id="upsell-products" name="product_id" aria-label="Select Product"></select>
                            </div>


                            {{-- Customer Name --}}
                            <div class="mb-3">
                                <label class="form-label">Your Name</label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Enter your name" required>
                            </div>

                            {{-- Rating --}}
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <select name="rating" class="form-select" required>
                                    <option value="">Select Rating</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4)</option>
                                    <option value="3">⭐⭐⭐ (3)</option>
                                    <option value="2">⭐⭐ (2)</option>
                                    <option value="1">⭐ (1)</option>
                                </select>
                            </div>

                            {{-- Review Title --}}
                            <div class="mb-3">
                                <label class="form-label">Review Title</label>
                                <input type="text" name="review_title" class="form-control" placeholder="Short title">
                            </div>

                            {{-- Review Text --}}
                            <div class="mb-3">
                                <label class="form-label">Your Review</label>
                                <textarea name="review_text" rows="4" class="form-control" placeholder="Write your review..."></textarea>
                            </div>

                            {{-- Review Images --}}
                            <div class="mb-3">
                                <label class="form-label">Upload Images (Optional)</label>
                                <input type="file" name="images[]" class="form-control" multiple>
                                <div id="existing-images" class="mb-3"></div>


                            </div>

                            {{-- Submit --}}
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Submit Review</button>
                                <a href="{{ route('reviews.all') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/app-ecommerce-product-review.js') }}"></script>

@endpush