/**
 * E-Commerce Order List
 */
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const dtTable = document.querySelector('.datatables-order');
  if (!dtTable) return;

  const { borderColor, bodyBg, headingColor } = config.colors;

  // 🔹 Status & Payment Labels
  const statusObj = {
    pending: { title: 'Pending', class: 'bg-label-warning' },
    processing: { title: 'Processing', class: 'bg-label-success' },
    shipped: { title: 'Out for Delivery', class: 'bg-label-primary' },
    delivered: { title: 'Delivered', class: 'bg-label-info' },
    cancelled: { title: 'Cancelled Order', class: 'bg-label-danger' }
  };

  const paymentObj = {
    paid: { title: 'Paid', class: 'text-success' },
    pending: { title: 'Pending', class: 'text-warning' },
    failed: { title: 'Failed', class: 'text-danger' },
    cancelled: { title: 'Cancelled', class: 'text-secondary' }
  };

  // 🔹 Initialize DataTable
  const dt_orders = new DataTable(dtTable, {
    ajax: { url: `${APP_URL}/api/orders`, type: 'GET', dataSrc: r => r.data },
    columns: [
      { data: 'id' },
      { data: 'id', orderable: false, render: DataTable.render.select() },
      { data: 'order_number' },
      { data: 'created_at' },
      { data: 'customer_name' },
      { data: 'payment_status' },
      { data: 'status' },
      { data: 'payment_method' },
      { data: 'id' }
    ],
    columnDefs: [
      { targets: 0, className: 'control', orderable: false, searchable: false, render: () => '' },
      {
        targets: 1,
        orderable: false,
        searchable: false,
        checkboxes: true,
        render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">',
        checkboxes: { selectAllRender: '<input type="checkbox" class="form-check-input">' }
      },
      {
        targets: 2,
        render: (data, type, full, meta) => {
          console.log(full,'data--');
          
          return `<a href="${APP_URL}/orders/details?id=${full.id}" class="text-primary fw-semibold">#${data}</a>`;
        }
      },
      {
        targets: 3,
        render: data => {
          const date = new Date(data);
          return `<span class="text-nowrap">${date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
          })}</span>`;
        }
      },
      {
        targets: 4,
        render: (data, _, full) => {
          const initials = (data.match(/\b\w/g) || []).slice(0, 2).join('').toUpperCase();
          const avatar = full.avatar
            ? `<img src="${assetsPath}img/avatars/${full.avatar}" class="rounded-circle" alt="Avatar">`
            : `<span class="avatar-initial rounded-circle bg-label-primary">${initials}</span>`;
          return `
            <div class="d-flex align-items-center">
              <div class="avatar avatar-sm me-3">${avatar}</div>
              <div>
                <h6 class="m-0"><a href="pages-profile-user.html" class="text-heading">${data}</a></h6>
                <small>${full.customer_email}</small>
              </div>
            </div>`;
        }
      },
      {
        targets: 5,
        render: data => {
          const p = paymentObj[data];
          return p
            ? `<h6 class="mb-0 ${p.class} d-flex align-items-center">
                 <i class="icon-base ti tabler-circle-filled icon-12px me-1"></i>${p.title}
               </h6>`
            : data;
        }
      },
      {
        targets: 6,
        render: data => {
          const s = statusObj[data];
          return s
            ? `<span class="badge px-2 ${s.class}">${s.title}</span>`
            : data;
        }
      },
      { targets: 7, render: data => `<span class="badge px-2">${data}</span>` },
      {
        targets: 8,
        title: 'Actions',
        orderable: false,
        searchable: false,
        render: (_, __, full) => `
          <div class="d-flex justify-content-start">
            <button class="btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
              <i class="icon-base ti tabler-dots-vertical"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item btn-view" data-id="${full.id}">View</a>
              <a class="dropdown-item delete-record" data-id="${full.id}">Delete</a>
            </div>
          </div>`
      }
    ],
    select: { style: 'multi', selector: 'td:nth-child(2)' },
    order: [[3, 'desc']],
    responsive: true,
    language: {
      paginate: {
        next: '<i class="icon-base ti tabler-chevron-right"></i>',
        previous: '<i class="icon-base ti tabler-chevron-left"></i>'
      }
    }
  });

  // 🗑️ Delete Record
  $(document).on('click', '.delete-record', function () {
    const orderId = $(this).data('id');
    const row = $(this).closest('tr');
    if (!confirm('Are you sure you want to delete this order?')) return;

    $.ajax({
      url: `${APP_URL}/api/orders/${orderId}`,
      type: 'DELETE',
      data: { _token: $('meta[name="csrf-token"]').attr('content') },
      success: res => {
        dt_orders.row(row).remove().draw(false);
        toastr.success(res.message || 'Order deleted successfully.');
        $('.dtr-bs-modal.show').each(function () {
          const modal = bootstrap.Modal.getInstance(this);
          modal?.hide();
        });
      },
      error: xhr => {
        console.error(xhr.responseText);
        toastr.error('Failed to delete order.');
      }
    });
  });

  // 👁️ View Order Details
  $(document).on('click', '.btn-view', function () {
    const orderId = $(this).data('id');
    window.location.href = `${APP_URL}/orders/details?id=${orderId}`;
  });

  // 🎨 UI Class Tweaks after table render
  setTimeout(() => {
    const adjust = (sel, remove = '', add = '') =>
      document.querySelectorAll(sel).forEach(el => {
        if (remove) remove.split(' ').forEach(c => el.classList.remove(c));
        if (add) add.split(' ').forEach(c => el.classList.add(c));
      });

    adjust('.dt-buttons .btn', 'btn-secondary', 'btn-label-secondary');
    adjust('.dt-search .form-control', 'form-control-sm', 'ms-0');
    adjust('.dt-length .form-select', 'form-select-sm');
    adjust('.dt-layout-table', 'row mt-2');
    adjust('.dt-layout-end', '', 'gap-md-2 gap-0');
    adjust('.dt-layout-full', 'col-md col-12', 'table-responsive');
  }, 100);
});
