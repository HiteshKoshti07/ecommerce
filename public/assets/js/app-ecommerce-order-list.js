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

  // 🔹 Date Filter State
  let currentFilter = 'today';
  let customDateRange = null;

  // 🔹 Helper function to build AJAX URL with filter params
  const buildAjaxUrl = (filter, startDate = null, endDate = null) => {
    let url = `${APP_URL}/api/orders?filter=${filter}`;
    if (filter === 'custom' && startDate && endDate) {
      url += `&start_date=${startDate}&end_date=${endDate}`;
    }
    return url;
  };

  // 🔹 Update statistics cards with data
  const updateStatistics = (stats) => {
    if (stats) {
      document.getElementById('statTotalOrders').textContent = stats.total_orders || 0;
      document.getElementById('statPendingOrders').textContent = stats.pending_orders || 0;
      document.getElementById('statCancelledOrders').textContent = stats.cancelled_orders || 0;
      document.getElementById('statCompletedOrders').textContent = stats.completed_orders || 0;
    }
  };

  // 🔹 Initialize DataTable with default "today" filter
  const dt_orders = new DataTable(dtTable, {
    ajax: { 
      url: buildAjaxUrl('today'),
      type: 'GET', 
      dataSrc: (json) => {
        // Update statistics when data is loaded
        if (json.statistics) {
          updateStatistics(json.statistics);
        }
        // Return data array for DataTable
        return json.data || [];
      }
    },
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

  // 📅 Date Filter Functionality
  const dateFilterSelect = document.getElementById('dateFilter');
  const customDateRangeContainer = document.getElementById('customDateRangeContainer');
  const customDateRangeInput = document.getElementById('customDateRange');

  // Initialize Flatpickr for custom date range (hidden initially)
  let flatpickrInstance = null;
  
  // Initialize flatpickr when custom range is selected
  const initCustomDateRange = () => {
    if (!flatpickrInstance && typeof flatpickr !== 'undefined' && customDateRangeInput) {
      flatpickrInstance = flatpickr(customDateRangeInput, {
        mode: 'range',
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        onChange: function(selectedDates, dateStr) {
          // Only apply filter when both dates are selected
          if (selectedDates.length === 2) {
            const startDate = selectedDates[0].toISOString().split('T')[0];
            const endDate = selectedDates[1].toISOString().split('T')[0];
            customDateRange = { startDate, endDate };
            applyDateFilter('custom', startDate, endDate);
          }
        }
      });
    }
  };

  // Apply date filter and reload DataTable
  const applyDateFilter = (filter, startDate = null, endDate = null) => {
    currentFilter = filter;
    const ajaxUrl = buildAjaxUrl(filter, startDate, endDate);
    
    // Update DataTable AJAX URL and reload (statistics will update via dataSrc callback)
    dt_orders.ajax.url(ajaxUrl).load();
  };

  // Handle date filter dropdown change
  if (dateFilterSelect) {
    dateFilterSelect.addEventListener('change', function() {
      const selectedFilter = this.value;

      if (selectedFilter === 'custom') {
        // Show custom date range picker
        if (customDateRangeContainer) {
          customDateRangeContainer.classList.remove('d-none');
        }
        
        // Initialize flatpickr if not already initialized
        setTimeout(() => {
          initCustomDateRange();
        }, 100);
        
        // If date range is already selected, apply it immediately
        if (customDateRange && customDateRange.startDate && customDateRange.endDate) {
          applyDateFilter('custom', customDateRange.startDate, customDateRange.endDate);
        }
      } else {
        // Hide custom date range picker
        if (customDateRangeContainer) {
          customDateRangeContainer.classList.add('d-none');
        }
        
        // Clear flatpickr selection if instance exists
        if (flatpickrInstance) {
          flatpickrInstance.clear();
        }
        customDateRange = null;
        
        // Apply the selected filter
        applyDateFilter(selectedFilter);
      }
    });
  }

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
