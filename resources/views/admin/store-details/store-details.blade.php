@extends('admin.layouts.app')
@section('content')



<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">Store Details</h5>
                    <div class="card-body">
                        <form id="storeForm" enctype="multipart/form-data" class=" row g-6 needs-validation" novalidate>

                            <!-- Store Details Card -->
                            <div class="col-12">
                                <div class="card">
                                    <h5 class="card-header">Store Details</h5>
                                    <div class="card-body row g-6">

                                        <div class="col-md-6">
                                            <label for="store_name" class="form-label">Store Name <span class="text-danger">*</span></label>
                                            <input type="text" id="store_name" name="store_name" class="form-control" placeholder="SHREECOLLECTION" required>
                                            <div class="invalid-feedback">Please enter store name.</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="store_slug" class="form-label">Store Slug / Handle <span class="text-danger">*</span></label>
                                            <input type="text" id="store_slug" name="store_slug" class="form-control" placeholder="best-saree" required>
                                            <div class="invalid-feedback">Please enter store slug.</div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <input type="text" id="short_description" name="short_description" class="form-control" placeholder="Short Description">
                                        </div>

                                        <div class="col-md-6">
                                            <label for="about_store" class="form-label">About Store</label>
                                            <input type="text" id="about_store" name="about_store" class="form-control" placeholder="About Store">
                                        </div>

                                        <!-- Logo Upload -->
                                        <div class="col-md-6 form-control-validation">
                                            <label class="form-label" for="store_logo">Store Logo</label>
                                            <input type="file" id="store_logo" name="store_logo" class="form-control" accept="image/*" />
                                            <div class="invalid-feedback">Please upload store logo.</div>
                                        </div>

                                        <!-- Cover Photo Upload -->
                                        <div class="col-md-6 form-control-validation">
                                            <label class="form-label" for="store_cover">Store Cover Photo</label>
                                            <input type="file" id="cover_banner" name="cover_banner" class="form-control" accept="image/*" />
                                            <div class="invalid-feedback">Please upload store cover photo.</div>
                                        </div>

                                        <!-- Preview Section -->
                                        <div class="col-md-6">
                                            <img id="logoPreview" src="" alt="Logo Preview" width="100" class="mt-2 rounded" />
                                        </div>
                                        <div class="col-md-6">
                                            <img id="coverPreview" src="" alt="Cover Preview" width="150" class="mt-2 rounded" />
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label" for="is_active">Store Status <span class="text-danger">*</span></label>
                                            <select id="is_active" name="is_active" class="selectpicker w-100" data-style="btn-default" required>
                                                <option value="">Select Status</option>
                                                <option value="Active">Active</option>
                                                <option value="Deactive">Deactive</option>
                                            </select>
                                            <div class="invalid-feedback">Please select store status.</div>
                                        </div>






                                    </div>
                                </div>

                                <!-- Address Info Card -->
                                <div class="col-12 mt-4">
                                    <div class="card">
                                        <h5 class="card-header">Address Information</h5>
                                        <div class="card-body row g-6">

                                            <div class="col-md-6">
                                                <label for="owner_name" class="form-label">Owner Name <span class="text-danger">*</span></label>
                                                <input type="text" id="owner_name" name="owner_name" class="form-control" required>
                                                <div class="invalid-feedback">Please enter owner name.</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" id="email" name="email" class="form-control" required>
                                                <div class="invalid-feedback">Please enter a valid email address.</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                                <input type="text" id="phone" name="phone" class="form-control" pattern="^[0-9]{10}$" required>
                                                <div class="invalid-feedback">Please enter a valid 10-digit phone number.</div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="address_line1" class="form-label">Address Line 1 <span class="text-danger">*</span></label>
                                                <textarea id="address_line1" name="address_line1" class="form-control" rows="3" required></textarea>
                                                <div class="invalid-feedback">Please enter address.</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-3 text-end">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>

                        </form>

                    </div>
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
<script src="{{ asset('assets/js/app-ecommerce-settings.js') }}"></script>
@endpush