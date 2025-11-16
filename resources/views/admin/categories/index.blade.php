@extends('admin.layouts.app')
@section('content')


<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce-category">
            <!-- Category List Table -->
            <div class="card">
                <div class="card-datatable">
                    <table class="datatables-category-list table">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Categories</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- Offcanvas to add new customer -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEcommerceCategoryList" aria-labelledby="offcanvasEcommerceCategoryListLabel">
                <!-- Offcanvas Header -->
                <div class="offcanvas-header py-6">
                    <h5 id="offcanvasEcommerceCategoryListLabel" class="offcanvas-title">Add Category</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <!-- Offcanvas Body -->
                <div class="offcanvas-body border-top">
                    <form class="pt-0" id="eCommerceCategoryListForm" enctype="multipart/form-data">

                        <!-- Title -->
                        <div class="mb-6 form-control-validation">
                            <label class="form-label" for="ecommerce-category-title">Title</label>
                            <input type="text" class="form-control" id="ecommerce-category-title" placeholder="Enter category title" name="categoryTitle" aria-label="category title" />
                            <small id="error-title" class="text-danger"></small>

                        </div>
                        <!-- Slug -->
                        <div class="mb-6 form-control-validation">
                            <label class="form-label" for="ecommerce-category-slug">Slug</label>
                            <input type="text" id="ecommerce-category-slug" class="form-control" placeholder="Enter slug" aria-label="slug" name="slug" />
                            <small id="error-slug" class="text-danger"></small>

                        </div>


                        <!-- Image -->
                        <div class="mb-6">
                            <label class="form-label" for="ecommerce-category-image">Attachment</label>
                            <div id="ecommerce-category-image-container">
                                <input class="form-control" type="file" id="ecommerce-category-image" />
                            </div>
                        </div>


                        <!-- Description -->
                        <div class="mb-6">
                            <label class="form-label">Description</label>
                            <div class="form-control p-0 py-1">
                                <div class="comment-editor border-0" id="ecommerce-category-description"></div>
                                <div class="comment-toolbar border-0 rounded">
                                    <div class="d-flex justify-content-end">
                                        <span class="ql-formats me-0">
                                            <button class="ql-bold"></button>
                                            <button class="ql-italic"></button>
                                            <button class="ql-underline"></button>
                                            <button class="ql-list" value="ordered"></button>
                                            <button class="ql-list" value="bullet"></button>
                                            <button class="ql-link"></button>
                                            <button class="ql-image"></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Status -->
                        <div class="mb-6 ecommerce-select2-dropdown  form-control-validation">
                            <label class="form-label">Select category status</label>
                            <select id="ecommerce-category-status" class="select2 form-select" name="status" data-placeholder="Select category status">
                                <option value="">Select category status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <small id="error-status" class="text-danger"></small>
                        </div>
                        <!-- Submit and reset -->
                        <div class="mb-6">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit ">Add</button>
                            <button type="reset" class="btn btn-label-danger" data-bs-dismiss="offcanvas">Discard</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    @endsection


    @push('scripts')
    <script src="{{ asset('assets/js/app-ecommerce-category-list.js') }}"></script>
    @endpush