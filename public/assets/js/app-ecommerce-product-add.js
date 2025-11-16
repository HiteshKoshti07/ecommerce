/**
 * App eCommerce Add / Edit Product Script
 */
'use strict';

$(document).ready(function () {
  // ===============================
  // 🔧 Initialize Quill Editor
  // ===============================
  const commentEditor = document.querySelector('#ecommerce-category-description');
  let quill = null;

  if (commentEditor) {
    quill = new Quill(commentEditor, {
      modules: { toolbar: '.comment-toolbar' },
      placeholder: 'Product Description',
      theme: 'snow'
    });
  }

  // ===============================
  // 📁 Initialize Dropzone
  // ===============================
  let productDropzone;
  const $dropzone = $('#dropzone-basic');
  if (Dropzone.instances.length === 0 && $dropzone.length) {
    productDropzone = new Dropzone('#dropzone-basic', {
      url: '/temp-upload',
      maxFiles: 1,
      maxFilesize: 5,
      acceptedFiles: '.jpg,.jpeg,.png,.gif,.webp',
      addRemoveLinks: true,
      autoProcessQueue: false,
      previewTemplate: `
        <div class="dz-preview dz-file-preview">
          <div class="dz-details">
            <div class="dz-thumbnail">
              <img data-dz-thumbnail />
              <div class="progress"><div class="progress-bar" data-dz-uploadprogress></div></div>
            </div>
            <div class="dz-filename" data-dz-name></div>
          </div>
        </div>`
    });
  }




    const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;



let multiDropzone = null;
const dropzoneMulti = document.querySelector('#dropzone-multi');

if (dropzoneMulti) {
  multiDropzone = new Dropzone(dropzoneMulti, {
    previewTemplate: previewTemplate,
    parallelUploads: 20,
    uploadMultiple: true,
    maxFilesize: 5,
    addRemoveLinks: true
  });
}


 



  // ===============================
  // 🧾 Initialize Select2
  // ===============================
  $('.select2').each(function () {
    const $this = $(this);
    $this.wrap('<div class="position-relative"></div>').select2({
      dropdownParent: $this.parent(),
      placeholder: $this.data('placeholder') || 'Select option'
    });
  });

  // ===============================
  // 🗂️ Load Categories
  // ===============================
  function loadCategories(selectedId = null) {
    $.ajax({
      url: `${APP_URL}/api/categories`,
      type: 'GET',
      success: function (response) {
        const categoryDropdown = $('#category-org');
        categoryDropdown.empty().append('<option value="">Select Category</option>');

        response.data.data.forEach(cat => {
          categoryDropdown.append(`<option value="${cat.id}">${cat.title}</option>`);
        });

        if (selectedId) categoryDropdown.val(selectedId).trigger('change');
      },
      error: function () {
        toastr.error('Failed to load categories.');
      }
    });
  }

  loadCategories();

  // ===============================
  // 🧩 Load Product (Edit Mode)
  // ===============================
  const urlParams = new URLSearchParams(window.location.search);
  const productId = urlParams.get('id');
  const isEdit = !!productId;

  if (isEdit) {
    $('#page-title').text('Update Product');
    $('#submitProduct').text('Update Product');
    loadProductData(productId);
  }

  function loadProductData(id) {
    $.ajax({
      url: `${APP_URL}/api/products/${id}`,
      type: 'GET',
      success: function (response) {
        const p = response.data;

        $('#ecommerce-product-name').val(p.name);
        $('#ecommerce-product-sku').val(p.sku);
        $('#ecommerce-product-barcode').val(p.barcode);
        $('#ecommerce-product-price').val(p.base_price);
        $('#ecommerce-product-discount-price').val(p.discount_price);
        $('#ecommerce-product-stock').val(p.stock_quantity);
        $('#status-org').val(p.status).trigger('change');
        $('#pro-collection').val(p.collection).trigger('change');
        $('#ecommerce-product-tags').val(p.tags).trigger('change');
        $('#ecommerce-product-video-link').val(p.product_video);
        $('#ecommerce-product-fabric').val(p.product_fabric);
        $('#ecommerce-product-work').val(p.product_work);
        $('#ecommerce-product-length').val(p.product_length);
        $('#ecommerce-product-product-care').val(p.product_care);


        if (quill) quill.root.innerHTML = p.description || '';

        loadCategories(p.category_id);

        // Load Variants
        if (p.variants) {
          const variants = Array.isArray(p.variants)
            ? p.variants
            : JSON.parse(p.variants || '[]');
          const list = $('[data-repeater-list="group-a"]').empty();

          variants.forEach(v => {
            list.append(`
              <div data-repeater-item>
                <div class="row g-sm-6 mb-6">
                  <div class="col-sm-4">
                    <select class="form-select variant-option">
                      <option value="size" ${v.option === 'size' ? 'selected' : ''}>Size</option>
                      <option value="color" ${v.option === 'color' ? 'selected' : ''}>Color</option>
                      <option value="weight" ${v.option === 'weight' ? 'selected' : ''}>Weight</option>
                    </select>
                  </div>
                  <div class="col-sm-8">
                    <input type="text" class="form-control variant-value" value="${v.values.join(',')}" />
                  </div>
                </div>
              </div>`);
          });
        }
      },
      error: function (xhr) {
        toastr.error('Failed to load product details.');
        console.error(xhr.responseText);
      }
    });
  }

  // ===============================
  // 🚀 Submit Product
  // ===============================
  $('#submitProduct').click(function (e) {
    e.preventDefault();
    $('.error-text').remove();

    const data = {
      name: $('#ecommerce-product-name').val(),
      sku: $('#ecommerce-product-sku').val(),
      barcode: $('#ecommerce-product-barcode').val(),
      description: quill ? quill.root.innerHTML : '',
      quantity: $('#ecommerce-product-stock').val(),
      base_price: $('#ecommerce-product-price').val(),
      discount_price: $('#ecommerce-product-discount-price').val(),
      category_id: $('#category-org').val(),
      status: $('#status-org').val(),
      collection: $('#pro-collection').val(),
      tags: $('#ecommerce-product-tags').val(),
      stock_quantity: $('#ecommerce-product-stock').val(),
      product_video: $('#ecommerce-product-video-link').val(),
      product_fabric: $('#ecommerce-product-fabric').val(),
      product_work: $('#ecommerce-product-work').val(),
      product_length: $('#ecommerce-product-length').val(),
      product_care: $('#ecommerce-product-product-care').val(),  
      _token: $('meta[name="csrf-token"]').attr('content'),
    };

    if (!data.name || !data.base_price) {
      toastr.warning('Please fill required fields.');
      if (!data.name)
        $('#ecommerce-product-name').after('<small class="text-danger error-text">Required</small>');
      if (!data.base_price)
        $('#ecommerce-product-price').after('<small class="text-danger error-text">Required</small>');
      return;
    }

    const formData = new FormData();
    Object.keys(data).forEach(key => formData.append(key, data[key]));

    // Collect variants
    const variants = [];
    $('[data-repeater-item]').each(function () {
      const option = $(this).find('.variant-option').val();
      const values = ($(this).find('.variant-value').val() || '')
        .split(',')
        .map(v => v.trim())
        .filter(v => v);
      if (option && values.length) variants.push({ option, values });
    });
    formData.append('variants', JSON.stringify(variants));

    // Add Dropzone file
    if (productDropzone && productDropzone.files.length > 0) {
      formData.append('product_image', productDropzone.files[0]);
    }

        // Add MULTIPLE images from dropzone
if (multiDropzone && multiDropzone.files.length > 0) {
  multiDropzone.files.forEach(file => {
    formData.append('product_images[]', file);
  });
}

      

    const url = isEdit ? `${APP_URL}/api/products/${productId}` : `${APP_URL}/api/products`;
    if (isEdit) formData.append('_method', 'PUT');

    $.ajax({
      url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      beforeSend: () => {
        $('#submitProduct')
          .prop('disabled', true)
          .text(isEdit ? 'Updating...' : 'Saving...');
      },
      success: res => {
        $('.error-text').remove();
        toastr.success(isEdit ? 'Product updated successfully!' : 'Product added successfully!');
        console.log('✅ Response:', res);
        $('#submitProduct')
          .prop('disabled', false)
          .text(isEdit ? 'Update Product' : 'Publish Product');
        setTimeout(() => (window.location.href = `${APP_URL}/products`), 1000);
      },
      error: xhr => {
        $('#submitProduct')
          .prop('disabled', false)
          .text(isEdit ? 'Update Product' : 'Publish Product');
        const res = xhr.responseJSON;
        if (res?.errors) {
          Object.keys(res.errors).forEach(key => {
            $(`#error-product-${key}`).text(res.errors[key][0]);
          });
        }
        toastr.error(res?.message || 'Something went wrong!');
        console.error(xhr.responseText);
      }
    });
  });
});

// =====================================
// 🧩 Separate Repeater + Select2 Setup
// =====================================
$(function () {
  const select2 = $('.select2');
  if (select2.length) {
    select2.each(function () {
      const $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        dropdownParent: $this.parent(),
        placeholder: $this.data('placeholder')
      });
    });
  }

  const formRepeater = $('.form-repeater');
  if (formRepeater.length) {
    let row = 2;
    let col = 1;

    formRepeater.on('submit', e => e.preventDefault());

    formRepeater.repeater({
      show: function () {
        const fromControl = $(this).find('.form-control, .form-select');
        const formLabel = $(this).find('.form-label');

        fromControl.each(function (i) {
          const id = `form-repeater-${row}-${col}`;
          $(fromControl[i]).attr('id', id);
          $(formLabel[i]).attr('for', id);
          col++;
        });

        row++;
        $(this).slideDown();

        $('.select2-container').remove();
        $('.select2.form-select').select2({
          placeholder: 'Placeholder text'
        });

        $('.select2-container').css('width', '100%');
        $('.form-repeater:first .form-select').select2({
          dropdownParent: $(this).parent(),
          placeholder: 'Placeholder text'
        });

        $('.position-relative .select2').each(function () {z
          $(this).select2({
            dropdownParent: $(this).closest('.position-relative')
          });
        });
      }
    });
  }
});
