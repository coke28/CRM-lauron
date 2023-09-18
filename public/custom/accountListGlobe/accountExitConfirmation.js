$(document).ready(function (){

    jQuery(document).off('click', '#exitAccount');
    jQuery(document).on('click', '#exitAccount', function(e) {
      e.preventDefault();
  
      Swal.fire({
          html: `Are you sure you want to exit? Changes made will not be saved.`,
          icon: "info",
          buttonsStyling: false,
          showCancelButton: true,
          confirmButtonText: "Exit",
          cancelButtonText: 'Cancel',
          customClass: {
              confirmButton: "btn btn-primary",
              cancelButton: 'btn btn-danger'
          }
      }).then(function (result) {

        if(result.isConfirmed){
          // var target = document.querySelector("#application_type_dt");
          // var blockUI = new KTBlockUI(target, {
          //     message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
          // });
          // blockUI.block();

          var formDatax = new FormData();
          formDatax.append('id', 'test');

          $.ajax({
            url: "/list/exitEditAccount",
            type: "POST",
            data: formDatax,
            contentType: false,
            cache: false,
            processData:false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data){
              toastr.options = {
               "closeButton": false,
               "debug": false,
               "newestOnTop": false,
               "progressBar": false,
               "positionClass": "toast-bottom-right",
               "preventDuplicates": false,
               "onclick": null,
               "showDuration": "300",
               "hideDuration": "1000",
               "timeOut": "5000",
               "extendedTimeOut": "1000",
               "showEasing": "swing",
               "hideEasing": "linear",
               "showMethod": "fadeIn",
               "hideMethod": "fadeOut"
             };

            //  toastr.success(data, "Success");
             window.location.href ="/list/account"
            //  blockUI.release();
            //  blockUI.destroy();
            //  $('#application_type_dt').DataTable().ajax.reload();
            }
          });
        }
      });

    });

    jQuery(document).off('click', '#exitAccountAgent');
    jQuery(document).on('click', '#exitAccountAgent', function(e) {
      e.preventDefault();
  
      Swal.fire({
          html: `Are you sure you want to exit? Changes made will not be saved.`,
          icon: "info",
          buttonsStyling: false,
          showCancelButton: true,
          confirmButtonText: "Exit",
          cancelButtonText: 'Cancel',
          customClass: {
              confirmButton: "btn btn-primary",
              cancelButton: 'btn btn-danger'
          }
      }).then(function (result) {

        if(result.isConfirmed){
          // var target = document.querySelector("#application_type_dt");
          // var blockUI = new KTBlockUI(target, {
          //     message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
          // });
          // blockUI.block();

          var formDatax = new FormData();
          formDatax.append('id', 'test');

          $.ajax({
            url: "/list/exitEditAccount",
            type: "POST",
            data: formDatax,
            contentType: false,
            cache: false,
            processData:false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function (data){
              toastr.options = {
               "closeButton": false,
               "debug": false,
               "newestOnTop": false,
               "progressBar": false,
               "positionClass": "toast-bottom-right",
               "preventDuplicates": false,
               "onclick": null,
               "showDuration": "300",
               "hideDuration": "1000",
               "timeOut": "5000",
               "extendedTimeOut": "1000",
               "showEasing": "swing",
               "hideEasing": "linear",
               "showMethod": "fadeIn",
               "hideMethod": "fadeOut"
             };

            //  toastr.success(data, "Success");
             window.location.href ="/agent/callBack"
            //  blockUI.release();
            //  blockUI.destroy();
            //  $('#application_type_dt').DataTable().ajax.reload();
            }
          });
        }
      });

    });

  });