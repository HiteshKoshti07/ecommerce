'use strict';

$(document).ready(function () {

  const urlParams = new URLSearchParams(window.location.search);
  const editId = urlParams.get("id");


  function loadProducts(selectedIds = [], excludeId = null) {
    $.ajax({
      url: `${APP_URL}/api/products`,
      type: 'GET',
      success: function (response) {
        const dropdown = $('#upsell-products');
        dropdown.empty();

        dropdown.append(`<option value="">Select Product</option>`);

        response.data.forEach(product => {

          // Skip the product you want to exclude
          if (excludeId && product.id == excludeId) {
            return;
          }

          dropdown.append(`<option value="${product.id}">${product.name}</option>`);
        });

        if (selectedIds.length > 0) {
          setTimeout(() => {
            dropdown.val(selectedIds).trigger('change');
          }, 300);
        }
      }
    });
  }

  function loadReviewData(id) {
    $.ajax({
      url: `${APP_URL}/api/reviews/${id}`,
      type: 'GET',
      success: function (response) {
        const r = response.data;

        // fill form
        $("[name=customer_name]").val(r.customer_name);
        $("[name=review_title]").val(r.review_title);
        $("[name=review_text]").val(r.review_text);
        $("[name=rating]").val(r.rating);

        // load product dropdown + exclude current product
        loadProducts([], r.product_id);

        // show existing images
        if (r.images && r.images.length > 0) {
          let html = '';
          r.images.forEach(img => {
            html += `<img src="${APP_URL}/storage/${img}" width="60" class="me-2 border rounded">`;
          });
          $("#existing-images").html(html);
        }
      }
    });
  }


  // ADD MODE or EDIT MODE
  if (editId) {
    loadReviewData(editId);
  } else {
    loadProducts();
  }

});
