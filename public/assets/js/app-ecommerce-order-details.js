/**
 * app-ecommerce-order-details.js
 * Clean, optimized, reload-safe version
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const dtTable = document.querySelector('.datatables-order-details');
  const params = new URLSearchParams(window.location.search);
  const orderId = params.get('id');

  if (!orderId) {
    console.error('❌ Missing order ID in URL.');
    return;
  }

  // --- Fetch Order Details ---
  fetch(`${APP_URL}/api/orders/${orderId}`)
    .then(res => res.json())
    .then(response => {
      if (!response.success || !response.data) {
        console.error('❌ Invalid response from API:', response);
        return;
      }

      const order = response.data;
    console.log(order, 'order-----');

      fillOrderDetails(order);
      initOrderItemsTable(dtTable, order.items || []);
    })
    .catch(err => console.error('Error fetching order details:', err));

    
  // --- Fill Order Info in DOM ---
  function fillOrderDetails(order) {
    $('#order-number').text(`Order #${order.order_number}`);
    $('#order-payment-status').text(order.payment_status ?? 'N/A');
    $('#order-status').text(order.status ?? 'N/A');
    $('#customer-name').text(order.customer_name ?? 'N/A');
    $('#customer-email').text(`Email: ${order.customer_email ?? '-'}`);
    $('#customer-number').text(`Mobile: ${order.customer_phone ?? '-'}`);
    $('.customer-address').text(order.customer_address ?? '-');

    // Set dropdown value
    $('#status-org').val(order.status).trigger('change.select2');

    // Format date
    const date = new Date(order.created_at);
    const formatted = date.toLocaleString('en-IN', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    });
    $('#order-date-text').text(formatted);
    $('#orderYear').text(date.getFullYear());

    // Totals
    $('#order-subtotal').text(`₹ ${order.subtotal ?? 0}`);
    $('#order-discount').text(`₹ ${order.discount ?? 0}`);
    $('#order-tax').text(`₹ ${order.tax ?? 0}`);
    $('#order-total').text(`₹ ${order.total_amount ?? 0}`);
  }

  // --- DataTable Initialization ---
function initOrderItemsTable(tableEl, items) {
  if (!tableEl || !items?.length) {
    console.warn('⚠️ No table element or items to display');
    return;
  }

  // Destroy any previous instance cleanly
  if ($.fn.DataTable.isDataTable(tableEl)) {
    $(tableEl).DataTable().clear().rows.add(items).draw();
    return;
  }

  // Ensure DataTable initializes only after element is visible
  setTimeout(() => {
    $(tableEl).DataTable({
      data: items,
      columns: [
        { data: 'product_id', title: 'Product ID' },
        { data: 'product_name', title: 'Product' },
        { data: 'sku', title: 'SKU' },
        { data: 'quantity', title: 'Qty' },
        { data: 'price', title: 'Price', render: d => `₹ ${parseFloat(d).toFixed(2)}` },
        {
          data: null,
          title: 'Total',
          render: row => `₹${(row.quantity * row.price).toFixed(2)}`
        }
      ],
      paging: false,
      searching: false,
      info: false,
      responsive: true,
      autoWidth: false,
      language: {
        emptyTable: 'No items found in this order',
      },
    });
  }, 200); // short delay ensures DOM ready
}


  // --- Handle Order Deletion ---
  $(document).on('click', '.delete-order', function (e) {
    e.preventDefault();
    Swal.fire({
      title: 'Are you sure?',
      text: 'You will delete this order permanently.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it',
      customClass: {
        confirmButton: 'btn btn-danger me-2',
        cancelButton: 'btn btn-secondary'
      },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        $.ajax({
          url: `${APP_URL}/api/orders/${orderId}`,
          type: 'DELETE',
          data: { _token: $('meta[name="csrf-token"]').attr('content') },
          success: function () {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Order removed successfully.',
              timer: 1500,
              showConfirmButton: false
            });
            setTimeout(() => {
              window.location.href = `${APP_URL}/orders/list`;
            }, 1500);
          },
          error: xhr => {
            console.error('❌ Delete failed:', xhr.responseText);
            Swal.fire('Error', 'Failed to delete order.', 'error');
          }
        });
      }
    });
  });

  // --- Handle Status Change ---
  $('#status-org').on('change', function (e) {
    e.preventDefault(); // 🧩 Prevent reload
    const newStatus = $(this).val();
    if (!newStatus) return;

    $.ajax({
      url: `${APP_URL}/api/orders/${orderId}/status`,
      method: 'PUT',
      data: { status: newStatus },
      success: response => {
        if (response.success) {
          // Save success flag in sessionStorage to show message after reload
          sessionStorage.setItem('orderStatusUpdated', 'true');
          location.reload();
        } else {
          Swal.fire('Warning', 'Failed to update order status.', 'warning');
        }
      },
      error: xhr => {
        console.error('❌ Error updating status:', xhr.responseText);
        Swal.fire('Error', 'Something went wrong updating status.', 'error');
      }
    });
  });

  // --- Show success toast after reload ---
  if (sessionStorage.getItem('orderStatusUpdated') === 'true') {
    sessionStorage.removeItem('orderStatusUpdated');
    Swal.fire({
      icon: 'success',
      title: '✅ Order status updated!',
      showConfirmButton: false,
      timer: 1800
    });
  }
});
