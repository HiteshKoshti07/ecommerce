/**
 * App eCommerce Customer Management
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const dtTable = document.querySelector('.datatables-customers');
  const customerView = 'app-ecommerce-customer-details-overview.html';

  // === Initialize Select2 ===
  $('.select2').each(function () {
    const $this = $(this);
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select Country',
      dropdownParent: $this.parent()
    });
  });

  // === Initialize DataTable ===
  if (dtTable) {
    window.dt_customer = new DataTable(dtTable, {
      ajax: {
        url: `${APP_URL}/api/customers`,
        type: 'GET',
        dataSrc: r => r.data || []
      },
      columns: [
        { data: null, defaultContent: '' }, // Control column
        { data: null, defaultContent: '' }, // Checkbox column
        { data: 'name' },
        { data: 'id' },
        { data: 'country' },
        { data: 'created_at' }
      ],
      columnDefs: [
        {
          targets: 0,
          className: 'control',
          orderable: false,
          searchable: false,
          responsivePriority: 2,
          render: () => ''
        },
        {
          targets: 1,
          orderable: false,
          searchable: false,
          responsivePriority: 3,
          checkboxes: true,
          render: () => '<input type="checkbox" class="dt-checkboxes form-check-input">',
          checkboxes: { selectAllRender: '<input type="checkbox" class="form-check-input">' }
        },
        {
          targets: 2,
          responsivePriority: 1,
          render: (data, type, full) => {
            const name = full.name || 'Unknown';
            const email = full.email || '';
            const initials = (name.match(/\b\w/g) || []).slice(0, 2).join('').toUpperCase();
            const colors = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
            const bg = colors[Math.floor(Math.random() * colors.length)];
            const avatar = `<span class="avatar-initial rounded-circle bg-label-${bg}">${initials}</span>`;

            return `
              <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3">${avatar}</div>
                <div class="d-flex flex-column">
                
                  <a href="${customerView}" class="text-heading fw-medium">${name}</a>
                  <small>${email}</small>
                </div>
              </div>`;
          }
        },
        {
          targets: 3,
          render: (data, type, full) => `<span class="text-heading">#${full.id}</span>`
        },
        {
          targets: 4,
          render: (data, type, full) => `<span>${full.country || '-'}</span>`
        },
        {
          targets: 5,
          render: (data, type, full) => {
            if (!full.created_at) return '-';
            const date = new Date(full.created_at);
            return date.toLocaleString('en-US', {
              year: 'numeric',
              month: 'short',
              day: '2-digit',
              hour: '2-digit',
              minute: '2-digit',
              hour12: true
            });
          }
        }
      ],
      select: { style: 'multi', selector: 'td:nth-child(2)' },
      order: [[2, 'asc']],
      paging: true,
      responsive: {
        details: {
          display: DataTable.Responsive.display.modal({
            header: row => `Customer Details — ${row.data().name}`
          }),
          type: 'column'
        }
      },
      layout: {
        topStart: {
          rowClass: 'row m-3 justify-content-between',
          features: [{ search: { placeholder: 'Search customers...' } }]
        },
        topEnd: {
          features: [
            { pageLength: { menu: [10, 25, 50, 100] } },
            {
              buttons: [
                {
                  extend: 'collection',
                  className: 'btn btn-label-primary dropdown-toggle',
                  text: '<i class="ti tabler-upload icon-xs me-1"></i> Export',
                  buttons: ['print', 'csv', 'excel', 'pdf', 'copy']
                },
                {
                  text: '<i class="ti tabler-plus icon-xs me-1"></i> Add Customer',
                  className: 'btn btn-primary',
                  attr: { 'data-bs-toggle': 'offcanvas', 'data-bs-target': '#offcanvasEcommerceCustomerAdd' }
                }
              ]
            }
          ]
        },
        bottomEnd: 'paging'
      },
      language: {
        paginate: {
          next: '<i class="ti tabler-chevron-right icon-18px"></i>',
          previous: '<i class="ti tabler-chevron-left icon-18px"></i>'
        }
      }
    });
  }

  // === Style Fixes after Initialization ===
  setTimeout(() => {
    document.querySelectorAll('.dt-buttons .btn-group .btn').forEach(btn => {
      btn.classList.remove('btn-secondary');
      btn.classList.add('btn-label-secondary');
    });
  }, 200);
});

/* ===================================================
   ADD NEW CUSTOMER FORM HANDLER
=================================================== */
$(document).on('click', '#btnAddCustomer', function (e) {
  e.preventDefault();

  const form = $('#eCommerceCustomerAddForm');
  const formData = {
    name: $('#customer-name').val(),
    email: $('#customer-email').val(),
    mobile_no: $('#customer-mobile').val(),
    address_line1: $('#customer-address1').val(),
    city: $('#customer-town').val(),
    state: $('#customer-state').val(),
    postal_code: $('#customer-postcode').val(),
    country: $('#customer-country').val(),
  };

  $.ajax({
    url: `${APP_URL}/api/customers`,
    type: 'POST',
    data: formData,
    success: function (res) {
      if (res.success && res.data) {
        toastr.success('Customer added successfully!');
        if (window.dt_customer) window.dt_customer.row.add(res.data).draw(false);
        form[0].reset();
        bootstrap.Offcanvas.getInstance('#offcanvasEcommerceCustomerAdd')?.hide();
      } else {
        toastr.warning(res.message || 'Failed to add customer.');
      }
    },
     error: function (xhr) {
    console.group('❌ Validation Error');
    console.log(xhr.responseJSON);
    console.groupEnd();

    const errors = xhr.responseJSON?.errors || {};
    if (Object.keys(errors).length > 0) {
      // Loop through each error and show inline message
      for (const [field, messages] of Object.entries(errors)) {
        $(`#error-${field}`).text(messages[0]);
      }
      toastr.error('Please fix the highlighted errors.');
    } else {
      const msg = xhr.responseJSON?.message || 'Unexpected error occurred.';
      toastr.error(msg);
    }
  }
  });
});
