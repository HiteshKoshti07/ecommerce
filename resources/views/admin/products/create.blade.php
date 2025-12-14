@extends('admin.layouts.app')
@section('page_title', 'Add Products')

@section('content')


<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="app-ecommerce">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-6 row-gap-4">
                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1" id="page-title">Add a new Product</h4>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-4">
                    <div class="d-flex gap-4">
                        <button class="btn btn-label-secondary">Discard</button>
                        <button class="btn btn-label-primary">Save draft</button>
                    </div>
                    <button type="submit" class="btn btn-primary" id="submitProduct">Publish product</button>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-12 col-lg-8">
                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Product information</h5>
                        </div>
                        <div class="card-body">
                            <!-- Product Name -->
                            <div class="mb-6">
                                <label class="form-label" for="ecommerce-product-name">Name</label>
                                <input type="text" class="form-control" id="ecommerce-product-name" placeholder="Product title" name="productTitle" aria-label="Product title" />
                                <small id="error-product-name" class="text-danger"></small>
                            </div>

                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="ecommerce-product-sku">SKU</label>
                                    <input type="text" class="form-control" id="ecommerce-product-sku" placeholder="SKU" name="productSku" aria-label="Product SKU" />
                                    <small id="error-product-sku" class="text-danger"></small>
                                </div>
                                <div class="col">
                                    <label class="form-label" for="ecommerce-product-barcode">Barcode</label>
                                    <input type="text" class="form-control" id="ecommerce-product-barcode" placeholder="0123-4567" name="productBarcode" aria-label="Product barcode" />
                                    <small id="error-product-barcode" class="text-danger"></small>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="mb-1">Description (Optional)</label>
                                <div class="form-control p-0">
                                    <div class="comment-toolbar border-0 border-bottom">
                                        <div class="d-flex justify-content-start">
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
                                    <div class="comment-editor border-0 pb-6" id="ecommerce-category-description"></div>
                                </div>
                                <small id="error-product-description" class="text-danger"></small>
                            </div>
                        </div>
                    </div>

                    @if(!env('IMAGE_FROM'))
                    <!-- Media -->
                    <div class="card mb-6">
                        <h5 class="card-header">Product Photos</h5>
                        <div class="card-body">
                            <form action="/upload" class="dropzone needsclick p-0" id="dropzone-basic">
                                <div class="dz-message needsclick">
                                    <p class="h4 needsclick pt-3 mb-2">Drag and drop your image here</p>
                                    <p class="h6 text-body-secondary d-block fw-normal mb-2">or</p>
                                    <span class="needsclick btn btn-sm btn-label-primary" id="btnBrowse">Browse image</span>
                                </div>
                                <div class="fallback">
                                    <input name="file" type="file" />
                                </div>
                            </form>
                            <small id="error-product-image" class="text-danger"></small>
                        </div>
                    </div>

                    <!-- Multi  -->
                    <div class="col-12">
                        <div class="card">
                            <h5 class="card-header">Multiple Product Photos</h5>
                            <div class="card-body">
                                <form action="/upload_multi" class="dropzone needsclick" id="dropzone-multi">
                                    <div class="dz-message needsclick">
                                        Drop files here or click to upload
                                        <span class="note needsclick">(This is just a demo dropzone. Selected files are
                                            <span class="fw-medium">not</span> actually uploaded.)</span>
                                    </div>
                                    <div class="fallback">
                                        <input name="file" type="file" />
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <!-- Multi  -->
                    @else

                    <!-- Product imagekit Images -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">ImageKit Images</h5>
                        </div>

                        <div class="card-body">
                            <!-- Product Main Image -->
                            <div class="mb-6">
                                <label class="form-label" for="product-imagekit-main">
                                    Product Main Image (ImageKit URL)
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="product-imagekit-main"
                                    name="product_imagekit_main"
                                    placeholder="Enter ImageKit main image URL"
                                    aria-label="Product Main Image" />
                                <small id="error-product-imagekit-main" class="text-danger"></small>
                            </div>

                            <!-- Product Gallery Images -->
                            <div class="mb-6">
                                <label class="form-label" for="product-imagekit-gallery">
                                    Product Gallery Images (comma separated URLs)
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="product-imagekit-gallery"
                                    name="product_imagekit_gallery"
                                    placeholder="Enter ImageKit gallery image URLs"
                                    aria-label="Product Gallery Images" />
                                <small id="error-product-imagekit-gallery" class="text-danger"></small>
                            </div>
                        </div>
                    </div>

                    @endif


                    <!-- vidoe link  -->
                    <div class="card mb-6" style=" margin-top: 10px;">
                        <div class=" card-body">
                            <label class="form-label" for="ecommerce-product-video-link">Video Link</label>
                            <input type="text" class="form-control" id="ecommerce-product-video-link" placeholder="Video Link" name="videoLink" aria-label="Video Link" />
                            <small id="vidoe-link" class="text-danger"></small>
                        </div>
                    </div>




                    <!-- Variants -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Variants</h5>
                        </div>
                        <div class="card-body">
                            <form class="form-repeater">
                                <div data-repeater-list="group-a">
                                    <div data-repeater-item>
                                        <div class="row g-sm-6 mb-6">
                                            <div class="col-sm-4">
                                                <label class="form-label">Options</label>
                                                <select class="select2 form-select variant-option" data-placeholder="Select option">
                                                    <option value="">Select Option</option>
                                                    <option value="size">Size</option>
                                                    <option value="color">Color</option>
                                                    <option value="weight">Weight</option>
                                                    <option value="smell">Smell</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-8">
                                                <label class="form-label invisible">Values</label>
                                                <input type="text" class="form-control variant-value" placeholder="Enter values separated by comma (e.g. S,M,L)" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-primary" data-repeater-create>
                                        <i class="icon-base ti tabler-plus icon-xs me-2"></i>
                                        Add another option
                                    </button>
                                </div>
                            </form>
                            <small id="error-product-variants" class="text-danger"></small>
                        </div>
                    </div>
                    <!-- Variants END -->


                    <!-- Product Information -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Product Fabric Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="ecommerce-product-fabric">Fabric</label>
                                    <input type="text" class="form-control"
                                        id="ecommerce-product-fabric"
                                        placeholder="Premium Quality"
                                        value="Premium Quality"
                                        name="productFabric" />
                                    <small id="error-product-fabric" class="text-danger"></small>
                                </div>

                                <div class="col">
                                    <label class="form-label" for="ecommerce-product-work">Work</label>
                                    <input type="text" class="form-control"
                                        id="ecommerce-product-work"
                                        placeholder="Traditional Craftsmanship"
                                        value="Traditional Craftsmanship"
                                        name="productWork" />
                                    <small id="error-product-work" class="text-danger"></small>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <div class="col">
                                    <label class="form-label" for="ecommerce-product-length">Length</label>
                                    <input type="text" class="form-control"
                                        id="ecommerce-product-length"
                                        placeholder="6.5 meters with blouse piece"
                                        value="6.5 meters with blouse piece"
                                        name="productLength" />
                                    <small id="error-product-length" class="text-danger"></small>
                                </div>

                                <div class="col">
                                    <label class="form-label" for="ecommerce-product-product-care">Product Care</label>
                                    <input type="text" class="form-control"
                                        id="ecommerce-product-product-care"
                                        placeholder="Dry clean only"
                                        value="Dry clean only"
                                        name="productProductCare" />
                                    <small id="error-product-product-care" class="text-danger"></small>
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mb-6 col ecommerce-select2-dropdown">
                                    <label class="form-label mb-1" for="upsell-products">Select Product</label>
                                    <select id="upsell-products"
                                        class="select2 form-select"
                                        data-placeholder="Select Product"
                                        multiple>
                                    </select>
                                    <small id="error-upsell-products" class="text-danger"></small>
                                </div>
                                <a href="javascript:void(0);" class="fw-medium btn btn-icon btn-label-primary ms-4"><i class="icon-base ti tabler-plus icon-md"></i></a>
                            </div>
                        </div>
                    </div>


                    <!-- Product Seo -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-tile mb-0">Product SEO</h5>
                        </div>
                        <div class="card-body">
                            <!-- Product Name -->
                            <div class="mb-6">
                                <label class="form-label" for="ecommerce-product-mata-description">Product Meta Description</label>
                                <input type="text" class="form-control" id="ecommerce-product-meta-description" placeholder="Product Meta Description" name="productMetaDescrption" aria-label="Product Meta Description" />
                                <small id="error-product-meta-description" class="text-danger"></small>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="ecommerce-product-meta-keywords">Product Meta Keywords</label>
                                <input type="text" class="form-control" id="ecommerce-product-meta-keywords" placeholder="Product Meta Keywords" name="productMetaKeywords" aria-label="Product Meta Keywords" />
                                <small id="error-product-meta-keywords" class="text-danger"></small>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="col-12 col-lg-4">
                    <!-- Pricing Card -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pricing</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-6">
                                <label class="form-label" for="ecommerce-product-price">Base Price</label>
                                <input type="number" class="form-control" id="ecommerce-product-price" placeholder="Price" name="productPrice" />
                                <small id="error-product-price" class="text-danger"></small>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="ecommerce-product-discount-price">Discounted Price</label>
                                <input type="number" class="form-control" id="ecommerce-product-discount-price" placeholder="Discounted Price" name="productDiscountedPrice" />
                                <small id="error-product-discount-price" class="text-danger"></small>
                            </div>
                            <div class="mb-6">
                                <label class="form-label" for="ecommerce-product-stock">Add to Stock</label>
                                <input type="number" class="form-control" id="ecommerce-product-stock" placeholder="Quantity" name="quantity" />
                                <small id="error-product-stock" class="text-danger"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Organize Card -->
                    <div class="card mb-6">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Organize</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="mb-6 col ecommerce-select2-dropdown">
                                    <label class="form-label mb-1" for="category-org">Category</label>
                                    <select id="category-org" class="select2 form-select" data-placeholder="Select Category">
                                        <option value="">Select Category</option>
                                    </select>
                                    <small id="error-product-category" class="text-danger"></small>
                                </div>
                                <a href="javascript:void(0);" class="fw-medium btn btn-icon btn-label-primary ms-4"><i class="icon-base ti tabler-plus icon-md"></i></a>
                            </div>

                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="status-org">Status</label>
                                <select id="status-org" class="select2 form-select" data-placeholder="Published">
                                    <option value="">Published</option>
                                    <option value="active">Active</option>
                                    <option value="draft">Draft</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <small id="error-product-status" class="text-danger"></small>
                            </div>

                            <div class="mb-6 col ecommerce-select2-dropdown">
                                <label class="form-label mb-1" for="pro-collection">Collections</label>
                                <select id="pro-collection" class="select2 form-select" data-placeholder="Select Collection">
                                    <option value="">Select Collection</option>
                                    <option value="women-sarees">Women Saree</option>
                                    <option value="women-western-wear">Women Westernwear</option>
                                    <option value="women-dress">Women Dress</option>
                                </select>
                                <small id="error-product-collection" class="text-danger"></small>
                            </div>



                            <div>
                                <label for="ecommerce-product-tags" class="form-label mb-1">Tags</label>
                                <input id="ecommerce-product-tags" class="form-control" name="ecommerce-product-tags" value="" aria-label="Product Tags" />
                                <small id="error-product-tags" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Right Column -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/app-ecommerce-product-add.js') }}"></script>
<script src="{{ asset('assets/js/forms-file-upload.js') }} "></script>

@endpush