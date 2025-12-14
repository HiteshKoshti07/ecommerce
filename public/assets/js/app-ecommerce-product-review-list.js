'use strict';

document.addEventListener('DOMContentLoaded', function () {

  const dtTableEl = document.querySelector('.datatables-reviews');
  let dtReviews;

  // rating filters
  const ratingObj = {
    5: "⭐⭐⭐⭐⭐",
    4: "⭐⭐⭐⭐",
    3: "⭐⭐⭐",
    2: "⭐⭐",
    1: "⭐"
  };

  const statusObj = {
    pending: { title: 'Pending', class: 'bg-label-warning' },
    approved: { title: 'Approved', class: 'bg-label-success' },
    rejected: { title: 'Rejected', class: 'bg-label-danger' }
  };

  if (dtTableEl) {
    dtReviews = new DataTable(dtTableEl, {
      ajax: {
        url: `${APP_URL}/api/reviews`,
        type: 'GET',
        dataSrc: res => res.data || []
      },

      columns: [
        { data: 'id', orderable: false },
        { data: 'id', orderable: false },
        { data: 'product_id' },
        { data: 'customer_name' },
        { data: 'rating' },
        { data: 'review_title' },
        { data: 'review_text' },
        { data: 'images' },
        { data: 'status' },
        { data: 'created_at' },
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
          render: () => `<input type="checkbox" class="dt-checkboxes form-check-input">`
        },
        {
          targets: 2,
          render: (data, type, full) => {
            return `<span class="fw-semibold">${full.product?.name || 'N/A'}</span>`;
          }
        },
        {
          targets: 4,
          render: rating => ratingObj[rating] || rating
        },
        {
          targets: 7,
          render: images => {
            if (!images || images.length === 0) return '-';
            return images.map(img =>
              `<img src="${APP_URL}/storage/${img}"
                    style="width:40px;height:40px;object-fit:cover;margin-right:3px;"
                    class="rounded border">`
            ).join('');
          }
        },
        {
          targets: 8,
          render: status => {
            const st = statusObj[status] || {};
            return `<span class="badge ${st.class}">${st.title}</span>`;
          }
        },
        {
          targets: 9,
          render: date => new Date(date).toLocaleString('en-IN')
        },
        {
          targets: 10,
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
            </div>
          `
        }
      ],

      select: { style: 'multi', selector: 'td:nth-child(2)' },
      order: [[9, 'desc']],
      pageLength: 10
    });
  }

  // DELETE review
  $(document).on('click', '.btn-delete', function () {
    const id = $(this).data('id');

    if (!confirm('Are you sure you want to delete this review?')) return;

    $.ajax({
      url: `${APP_URL}/api/reviews/${id}`,
      type: 'DELETE',
      success: function () {
        toastr.success('Review deleted successfully');
        dtReviews.ajax.reload();
      },
      error: function () {
        toastr.error('Failed to delete review');
      }
    });
  });

  // EDIT review
  $(document).on('click', '.btn-edit', function () {
    const reviewId = $(this).data('id');  // Correct variable
    window.location.href = `${APP_URL}/reviews/add?id=${reviewId}`;
  });

});
