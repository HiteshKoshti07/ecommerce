/**
 * app-ecommerce-product-list.js (Optimized Version)
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const { borderColor, bodyBg, headingColor } = config.colors;
  const dtTableEl = document.querySelector('.datatables-products');
  let dtProducts;

  // ✅ Status, Category, Stock mappings
  const statusObj = {
    draft: { title: 'Scheduled', class: 'bg-label-warning' },
    active: { title: 'Publish', class: 'bg-label-success' },
    inactive: { title: 'Inactive', class: 'bg-label-danger' }
  };

  // const categoryObj = {
  //   '711648c6-891e-400d-9ca9-a9131f7d0087': 'Woman',
  //   'd3d90e42-a192-4044-824b-a79a73fd107e': 'Man'
  // };


    const categoryObj = {
    '0fe88ae2-e985-4e83-aee8-fa0ae3aa631f': 'designer-sarees',
    '3339cee4-5dcc-4cd9-becb-0772e07974e2': 'cotton-saree',
    '92416b88-b01e-4d0f-8c70-e8b197e0f953': 'organza-saree',
    'c1c09abc-a06d-4c5d-a4b0-c7df46a52e80': 'party-wear-saree',
    'd61bcea6-1cac-43bb-86f6-645771491bb4': 'trending-saree',
    '3b539e1a-d205-4a48-8d0d-3cf142045c69': 'jacquard-saree'
  };

  const stockObj = { 0: 'Out of Stock', 1: 'In Stock' };

  // ✅ Initialize DataTable
  if (dtTableEl) {
    dtProducts = new DataTable(dtTableEl, {
      ajax: {
        url: `${APP_URL}/api/products`,
        type: 'GET',
        dataSrc: res => res.data || []
      },
      columns: [
        { data: 'id', orderable: false },
        { data: 'id', orderable: false },
        { data: 'name' },
        { data: 'category_id' },
        { data: 'stock_quantity' },
        { data: 'sku' },
        { data: 'base_price' },
        { data: 'stock_quantity' },
        { data: 'status' },
        { data: 'id', orderable: false }
      ],
      columnDefs: [
        {
          targets: 0,
          className: 'control',
          render: () => ''
        },
        {
          targets: 1,
          render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">'
        },
        {
          targets: 2,
          render: (data, type, full) => {
            const maxLength = 60;

            const name =
              full.name && full.name.length > maxLength
                ? full.name.substring(0, maxLength) + '...'
                : full.name;

            const image = full.product_image
              ? `<img src="${full.product_image}"
                    class="rounded img-fluid"
                    style="width:60px; height:60px; object-fit:cover;">`
              : `<span class="avatar-initial rounded-2 bg-label-secondary"
                    style="width:60px; height:60px;"></span>`;

            return `
              <div class="d-flex align-items-center">
                <div class="me-2">${image}</div>
                <div>
                  <h6 class="text-nowrap mb-0" title="${full.name}">
                    ${name}
                  </h6>
                </div>
              </div>`;
          }
        },

        {
          targets: 3,
          render: (data, type, full) =>
            `<span>${categoryObj[full.category_id] || 'Unknown'}</span>`
        },
        {
          targets: 4,
          render: (data, type, full) => {
            const stock = full.stock_quantity > 0 ? 1 : 0;
            return `
              <label class="switch switch-primary switch-sm">
                <input type="checkbox" class="switch-input" ${stock ? 'checked' : ''}>
                <span class="switch-toggle-slider">
                  <span class="switch-on"></span><span class="switch-off"></span>
                </span>
              </label>
              <span class="d-none">${stockObj[stock]}</span>`;
          }
        },
        { targets: 5, render: data => `<span>${data || '-'}</span>` },
        { targets: 6, render: data => `<span>${data || '-'}</span>` },
        { targets: 7, render: data => `<span>${data || '-'}</span>` },
        {
          targets: 8,
          render: data => {
            const status = statusObj[data] || {};
            return `<span class="badge ${status.class || 'bg-label-secondary'}">
                      ${status.title || data}
                    </span>`;
          }
        },
        {
          targets: 9,
          render: (data, type, full) => `
            <div class="d-inline-block text-nowrap">
              <button class="btn btn-text-secondary rounded-pill btn-icon btn-edit" 
                      data-id="${full.id}" title="Edit">
                <i class="ti tabler-edit icon-22px"></i>
              </button>
              <button class="btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" 
                      data-bs-toggle="dropdown">
                <i class="ti tabler-dots-vertical icon-22px"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item btn-view" data-id="${full.id}">View</a>
                <a class="dropdown-item btn-delete" data-id="${full.id}">Delete</a>
              </div>
            </div>`
        }
      ],
      select: { style: 'multi', selector: 'td:nth-child(2)' },
      order: [[2, 'asc']],
      pageLength: 10,
      responsive: true,

      // ✅ Filter dropdowns
      initComplete: function () {
        const api = this.api();
        const filters = [
          { colIndex: 8, container: '.product_status', obj: statusObj, placeholder: 'Status' },
          { colIndex: 3, container: '.product_category', obj: categoryObj, placeholder: 'Category' },
          { colIndex: 4, container: '.product_stock', obj: stockObj, placeholder: 'Stock' }
        ];

        filters.forEach(f => {
          const column = api.column(f.colIndex);
          const container = document.querySelector(f.container);
          if (!container) return;

          const select = document.createElement('select');
          select.className = 'form-select text-capitalize mb-2';
          select.innerHTML = `<option value="">${f.placeholder}</option>`;
          container.appendChild(select);

          Object.entries(f.obj).forEach(([key, val]) => {
            const text = val.title || val;
            const option = new Option(text, text);
            select.appendChild(option);
          });

          select.addEventListener('change', () => {
            const val = select.value ? `^${select.value}$` : '';
            column.search(val, true, false).draw();
          });
        });
      }
    });
  }

  // ✅ Delete Product
  $(document).on('click', '.btn-delete', function () {
    const productId = $(this).data('id');
    const row = $(this).closest('tr');

    if (!confirm('Are you sure you want to delete this product?')) return;

    $.ajax({
      url: `${APP_URL}/api/products/${productId}`,
      type: 'DELETE',
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success: response => {
        toastr.success('Product deleted successfully');
        if (dtProducts) dtProducts.row(row).remove().draw(false);
      },
      error: xhr => {
        console.error('Delete failed:', xhr.responseText);
        toastr.error('Failed to delete product');
      }
    });
  });

  // ✅ Edit Product Redirect
  $(document).on('click', '.btn-edit', function () {
    const productId = $(this).data('id');
    window.location.href = `${APP_URL}/products/edit?id=${productId}`;
  });
});
