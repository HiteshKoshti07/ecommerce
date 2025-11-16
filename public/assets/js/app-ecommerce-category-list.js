/**
 * App eCommerce Category List
 */
'use strict';

// -------------------------------
// Quill Editor
// -------------------------------
const quillEditor = new Quill('#ecommerce-category-description', {
  modules: { toolbar: '.comment-toolbar' },
  placeholder: 'Write a Comment...',
  theme: 'snow'
});

// -------------------------------
// Global Variables
// -------------------------------
let dt_category;

// -------------------------------
// Form Validation
// -------------------------------
const fv = FormValidation.formValidation(eCommerceCategoryListForm, {
  fields: {
    categoryTitle: { validators: { notEmpty: { message: 'Please enter category title' } } },
    slug: { validators: { notEmpty: { message: 'Please enter slug' } } },
    status: { validators: { notEmpty: { message: 'Please select status' } } }
  },
  plugins: {
    trigger: new FormValidation.plugins.Trigger(),
    bootstrap5: new FormValidation.plugins.Bootstrap5({
      eleValidClass: 'is-valid',
      rowSelector: (field, ele) => '.form-control-validation'
    }),
    submitButton: new FormValidation.plugins.SubmitButton(),
    autoFocus: new FormValidation.plugins.AutoFocus()
  }
});

// -------------------------------
// Reset Offcanvas Form
// -------------------------------
function resetOffcanvas() {
  $('#ecommerce-category-title, #ecommerce-category-slug').val('').prop('readonly', false);
  $('#ecommerce-category-parent-category, #ecommerce-category-status')
    .val('')
    .trigger('change')
    .prop('disabled', false);
  quillEditor.root.innerHTML = '';
  quillEditor.enable(true);
  $('#ecommerce-category-image-container').html('<input class="form-control" type="file" id="ecommerce-category-image"/>');
  $('#offcanvasEcommerceCategoryListLabel').text('Add Category');
  $('.data-submit').text('Add').show().removeData('id');
}
$('#offcanvasEcommerceCategoryList').on('hidden.bs.offcanvas', resetOffcanvas);

// -------------------------------
// Submit Handler (Add / Update)
// -------------------------------
function handleSubmit(categoryId = null) {
  fv.validate().then(status => {
    if (status !== 'Valid') return;

    const formData = new FormData();
    if (categoryId) formData.append('_method', 'PUT'); // Laravel method override

    formData.append('title', $('#ecommerce-category-title').val());
    formData.append('slug', $('#ecommerce-category-slug').val());
    formData.append('parent_id', $('#ecommerce-category-parent-category').val());
    formData.append('status', $('#ecommerce-category-status').val());
    formData.append('description', quillEditor.root.innerHTML);

    const imageInput = document.getElementById('ecommerce-category-image');
    if (imageInput?.files.length) {
      formData.append('attachment', imageInput.files[0]);
    }


    $.ajax({
  url: `${APP_URL}/api/categories${categoryId ? '/' + categoryId : ''}`,
  type: 'POST',
  data: formData,
  processData: false,
  contentType: false,

  beforeSend: () => {
    console.log('📤 Sending category data...', Object.fromEntries(formData.entries()));
  },

  success: (res) => {
    console.log('✅ Category API Response:', res);

    toastr.success(
      categoryId ? 'Category updated successfully.' : 'Category created successfully.',
      'Success',
      { timeOut: 3000, progressBar: true }
    );

    const offcanvas = bootstrap.Offcanvas.getInstance('#offcanvasEcommerceCategoryList');
    if (offcanvas) offcanvas.hide();

    if (dt_category) dt_category.ajax.reload();
    resetOffcanvas();
  },


error: xhr => {
  console.group('❌ Category Save Failed');
  console.error('XHR:', xhr);
  console.groupEnd();

  // Clear previous errors
  $('[id^="error-"]').text('');

  let errorMsg = 'Something went wrong. Please try again.';

  if (xhr.responseJSON) {
    const res = xhr.responseJSON;

    // ✅ Laravel Validation Errors (422)
    if (res.errors) {
      errorMsg = res.message || 'Validation failed.';

      Object.entries(res.errors).forEach(([key, messages]) => {
        // Match Laravel field names to your input IDs
        const fieldMap = {
          title: 'title',
          categoryTitle: 'title',
          slug: 'slug',
          status: 'status',
        };

        const fieldId = fieldMap[key];
        if (fieldId) {
          console.log(`⚠️ Setting error for #error-${fieldId}:`, messages.join(', '));
          $(`#error-${fieldId}`).text(messages.join(', '));
        }
      });
    }

    // ✅ Custom API error (non-validation)
    else if (res.error) {
      errorMsg = `${res.message || 'Request failed.'}\n${
        typeof res.error === 'string'
          ? res.error
          : Object.values(res.error).flat().join('\n')
      }`;
    }

    // ✅ Generic message
    else if (res.message) {
      errorMsg = res.message;
    }
  }

  // ✅ Toastr notification
  toastr.error(errorMsg, 'Error', {
    timeOut: 4000,
    progressBar: true,
    closeButton: true,
    preventDuplicates: true
  });
}



});



  });
}

// -------------------------------
// Fill Offcanvas Form (View / Edit)
// -------------------------------
function fillOffcanvas(category, readonly = false) {
  resetOffcanvas();

  $('#ecommerce-category-title').val(category.title).prop('readonly', readonly);
  $('#ecommerce-category-slug').val(category.slug).prop('readonly', readonly);
  $('#ecommerce-category-parent-category').val(category.parent_id).trigger('change').prop('disabled', readonly);
  $('#ecommerce-category-status').val(category.status).trigger('change').prop('disabled', readonly);
  quillEditor.root.innerHTML = category.description || '';
  quillEditor.enable(!readonly);

  const imageHtml = category.attachment
    ? `<img src="${APP_URL}/storage/${category.attachment}" class="img-fluid rounded mt-2">`
    : `<input type="file" class="form-control" id="ecommerce-category-image" ${readonly ? 'disabled' : ''}/>`;
  $('#ecommerce-category-image-container').html(imageHtml);

  $('#offcanvasEcommerceCategoryListLabel').text(readonly ? 'View Category' : 'Edit Category');
  $('.data-submit').toggle(!readonly).text(readonly ? '' : 'Update').data('id', category.id);

  new bootstrap.Offcanvas('#offcanvasEcommerceCategoryList').show();
}

// -------------------------------
// CRUD Button Actions
// -------------------------------
$(document)
  .on('click', '.data-submit', e => {
    e.preventDefault();
    handleSubmit($(e.currentTarget).data('id') || null);
  })
  .on('click', '.btn-view', function () {
    const id = $(this).data('id');
    $.get(`${APP_URL}/api/categories/${id}`, r => fillOffcanvas(r.data, true))
      .fail(() => alert('Failed to fetch category details'));
  })
  .on('click', '.btn-edit', function () {
    const id = $(this).data('id');
    $.get(`${APP_URL}/api/categories/${id}`, r => fillOffcanvas(r.data, false))
      .fail(() => alert('Failed to fetch category details'));
  })
  .on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    if (!confirm('Are you sure you want to delete this category?')) return;
    $.ajax({
      url: `${APP_URL}/api/categories/${id}`,
      type: 'DELETE',
      success: () => dt_category?.ajax.reload(),
      error: xhr => {
        console.error('❌ Delete failed:', xhr.responseText);
        alert('Failed to delete category');
      }
    });
  });

// -------------------------------
// DataTable Initialization
// -------------------------------
document.addEventListener('DOMContentLoaded', () => {
  $('.select2').each(function () {
    $(this)
      .wrap('<div class="position-relative"></div>')
      .select2({ dropdownParent: $(this).parent(), placeholder: $(this).data('placeholder') });
  });

  const tableEl = document.querySelector('.datatables-category-list');
  if (!tableEl) return;

  dt_category = new DataTable(tableEl, {
    ajax: {
      url: `${APP_URL}/api/categories`,
      type: 'GET',
      dataSrc: res => {
        console.log('✅ API Response:', res);
        return res.success && res.data?.data ? res.data.data : [];
      },
      error: (xhr, status, error) => {
        console.error('❌ AJAX Error:', status, error);
        console.error(xhr.responseText);
      }
    },
    columns: [
      { data: 'id' },
      { data: 'id', orderable: false, render: DataTable.render.select() },
      { data: null },
      { data: 'id' }
    ],
    columnDefs: [
      {
        targets: 0,
        className: 'control',
        orderable: false,
        searchable: false,
        responsivePriority: 1,
        render: () => ''
      },
      {
        targets: 1,
        orderable: false,
        searchable: false,
        responsivePriority: 4,
        render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">'
      },
      {
        targets: 2,
        responsivePriority: 2,
        render: (data, type, full) => {
          const img = full.attachment
            ? `<img src="${APP_URL}/storage/${full.attachment}" alt="Category-${full.id}" class="rounded">`
            : `<span class="avatar-initial rounded-2 bg-label-secondary">${(full.title || '?').slice(0,2).toUpperCase()}</span>`;
          const desc = full.description?.replace(/<\/?[^>]+(>|$)/g, '').slice(0, 50) || '';
          return `
            <div class="d-flex align-items-center">
              <div class="avatar-wrapper me-3 rounded-2 bg-label-secondary"><div class="avatar">${img}</div></div>
              <div class="d-flex flex-column justify-content-center">
                <span class="text-heading fw-medium">${full.title || ''}</span>
                <small class="text-truncate d-none d-sm-block">${desc}</small>
              </div>
            </div>`;
        }
      },
      {
        targets: -1,
        title: 'Actions',
        className: 'text-center',
        searchable: false,
        orderable: false,
        render: full => `
          <div class="d-flex justify-content-center">
            <button class="btn btn-text-secondary rounded-pill waves-effect btn-icon btn-edit" data-id="${full.id}">
              <i class="icon-base ti tabler-edit icon-22px"></i>
            </button>
            <button class="btn btn-text-secondary rounded-pill waves-effect btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="icon-base ti tabler-dots-vertical icon-22px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end m-0">
              <a href="javascript:void(0);" class="dropdown-item btn-view" data-id="${full.id}">View</a>
              <a href="javascript:void(0);" class="dropdown-item btn-delete" data-id="${full.id}">Delete</a>
            </div>
          </div>`
      }
    ],
    select: { style: 'multi', selector: 'td:nth-child(2)' },
    displayLength: 7,
    layout: {
      topStart: {
        rowClass: 'row m-3 my-0 justify-content-between',
        features: [{ search: { placeholder: 'Search Category', text: '_INPUT_' } }]
      },
      topEnd: {
        rowClass: 'row m-3 my-0 justify-content-between',
        features: {
          pageLength: { menu: [7, 10, 25, 50, 100], text: '_MENU_' },
          buttons: [{
            text: `<i class="icon-base ti tabler-plus icon-16px me-0 me-sm-2"></i><span class="d-none d-sm-inline-block">Add Category</span>`,
            className: 'add-new btn btn-primary',
            attr: { 'data-bs-toggle': 'offcanvas', 'data-bs-target': '#offcanvasEcommerceCategoryList' }
          }]
        }
      },
      bottomStart: { rowClass: 'row mx-3 justify-content-between', features: ['info'] },
      bottomEnd: 'paging'
    },
    language: {
      paginate: {
        next: '<i class="icon-base ti tabler-chevron-right icon-18px"></i>',
        previous: '<i class="icon-base ti tabler-chevron-left icon-18px"></i>'
      }
    }
  });
});
