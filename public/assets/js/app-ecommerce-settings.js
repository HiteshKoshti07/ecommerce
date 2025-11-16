/**
 * App Store Settings - Single Record CRUD (Improved)
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const APP_URL = window.APP_URL || 'http://localhost:8000';
  let storeId = null;

  // ✅ Load existing store data
  function loadStoreData() {
    $.ajax({
      url: `${APP_URL}/api/stores`,
      type: 'GET',
      dataType: 'json',
      success: function (res) {
        if (res.data && res.data.length > 0) {
          const store = res.data[0];
          storeId = store.id;

          // 🧩 Prefill form fields
          $('#store_name').val(store.store_name);
          $('#store_slug').val(store.store_slug);
          $('#short_description').val(store.short_description);
          $('#about_store').val(store.about_store);
          $('#owner_name').val(store.owner_name);
          $('#email').val(store.email);
          $('#phone').val(store.phone);
          $('#address_line1').val(store.address_line1);

          // 🟢 Handle Active/Deactive dropdown

          if (store) {
      
// Only run once on page load
$('.selectpicker').selectpicker();

// Then inside your AJAX success:
if (store.is_active == 1) {
  store.is_active = 'Active';
} else if (store.is_active == 0) {
  store.is_active = 'Deactive';
}

// Set and refresh only once
$('#is_active').val(store.is_active);
$('#is_active').selectpicker('val', store.is_active); //

            // 🖼️ Show logo preview (if exists)
        if (store.store_logo) {
          $('#logoPreview').attr('src', `${APP_URL}/storage/${store.store_logo}`);
        }

        if (store.cover_banner) {
          $('#coverPreview').attr('src', `${APP_URL}/storage/${store.cover_banner}`);
        }


          }
          // 🖼️ Show existing logo/cover preview
          if (store.logo_path) {
            $('#logoPreview').attr('src', `${APP_URL}/storage/${store.logo_path}`);
          }
          
          if (store.cover_banner) {
            console.log(APP_URL);
            
          console.log(store.cover_banner,'store.cover_banner');

            $('#coverPreview').attr('src', `${APP_URL}/storage/${store.cover_banner}`);
          }

          console.log('✅ Store loaded:', store);
        } else {
          console.log('ℹ️ No store found');
        }
      },
      error: function (xhr) {
        console.error('❌ Error fetching store data:', xhr.responseText);
      },
    });
  }

  // Load store data when page ready
  loadStoreData();

  // 🧩 Handle image preview instantly
  $('#store_logo').on('change', function (e) {
    const file = e.target.files[0];
    if (file) $('#logoPreview').attr('src', URL.createObjectURL(file));
  });

  $('#cover_banner').on('change', function (e) {
    const file = e.target.files[0];
    if (file) $('#coverPreview').attr('src', URL.createObjectURL(file));
  });

  // ✅ Handle form submit (Insert or Update)
$('#storeForm').on('submit', function (e) {
  e.preventDefault();

  const submitBtn = $(this).find('[type="submit"]');
  submitBtn.prop('disabled', true);

  const formData = new FormData(this);
  formData.append('is_active', $('#is_active').val() === 'Active' ? 1 : 0);
  if (storeId) formData.append('_method', 'PUT');

  const url = storeId
    ? `${APP_URL}/api/stores/${storeId}`
    : `${APP_URL}/api/stores`;

  $.ajax({
    url,
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      toastr.success(response.message || (storeId ? 'Store updated' : 'Store created'));
      loadStoreData();
    },
    error: function (xhr) {
      toastr.error('Something went wrong!');
      console.error(xhr.responseText);
    },
    complete: function () {
      submitBtn.prop('disabled', false);
    }
  });
});





});
