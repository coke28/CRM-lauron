// Class definition
var editCCRemarkValidation = (function () {
    // Private functions
    var initDatatable = function () {
        const fv = FormValidation.formValidation(
            document.getElementById("edit_cc_remark_form"),
            {
                fields: {
                    'product':{
                      validators:{
                        notEmpty:{
                          message: 'This field is required.'
                        },
                      }
                    },
                    'CCRemarkName':{
                       validators:{
                         notEmpty:{
                           message: 'This field is required.'
                         },
                       }
                     },
                     'CCRemarkDescription':{
                       validators:{
                         notEmpty:{
                           message: 'This field is required.'
                         },
                       }
                     },
                    'status':{
                      validators:{
                        notEmpty:{
                          message: 'This field is required.'
                        },
                      }
                    },
                  },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row",
                        eleInvalidClass: "",
                        eleValidClass: "",
                    }),
                    // Validate fields when clicking the Submit button
                    submitButton: new FormValidation.plugins.SubmitButton(),
                    excluded: new FormValidation.plugins.Excluded({
                        excluded: function (field, element, elements) {
                            // field is the field name
                            // element is the field element
                            // elements is the list of field elements in case
                            // we have multiple elements with the same name
                            return $(element).is(":hidden");
                            // return true if you want to exclude the field
                        },
                    }),
                },
            }
        );

        // this function listens to the form validation
        fv.on("core.form.valid", function () {
            // Show loading indication

            document
                .getElementById("editCCRemarkSubmitBtn")
                .setAttribute("data-kt-indicator", "on");

            // Disable button to avoid multiple click
            document.getElementById("editCCRemarkSubmitBtn").disabled = true;

            // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
            var formx = $("#edit_cc_remark_form")[0]; // You need to use standart javascript object here
            var formDatax = new FormData(formx);
            $.ajax({
                url: "/ccRemarkEdit",
                type: "POST",
                data: formDatax,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (data) {
                    data = JSON.parse(data);
                    if (data.success) {
                        toastr.options = {
                            closeButton: false,
                            debug: false,
                            newestOnTop: false,
                            progressBar: false,
                            positionClass: "toast-bottom-right",
                            preventDuplicates: false,
                            onclick: null,
                            showDuration: "300",
                            hideDuration: "1000",
                            timeOut: "5000",
                            extendedTimeOut: "1000",
                            showEasing: "swing",
                            hideEasing: "linear",
                            showMethod: "fadeIn",
                            hideMethod: "fadeOut",
                        };

                        toastr.success(data.message, "Success");
                        $("#edit_cc_remark_form").trigger("reset");
                        $("#editCCRemark").modal("toggle");
                        $("#cc_remark_dt").DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            text: data.message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                        // window.location.reload();
                    }
                    document
                        .getElementById("editCCRemarkSubmitBtn")
                        .setAttribute("data-kt-indicator", "off");
                    document.getElementById(
                        "editCCRemarkSubmitBtn"
                    ).disabled = false;
                    //  event.preventDefault();
                },
            });
        });
    };
    return {
        // public functions
        init: function () {
            console.log("here");
            // form is binded and initiliazed here
            initDatatable();
        },
    };
})();

jQuery(document).ready(function () {
    //DONT FOGET THIS!!!
    editCCRemarkValidation.init();
    // event.preventDefault();
    //
    jQuery(document).off('click', '#edit_cc_remark_btn');
    jQuery(document).on('click', '#edit_cc_remark_btn', function(e) {
      var selectedID = $(this).data('id');
      var target = document.querySelector("#ccRemarkModalContent");
      var blockUI = new KTBlockUI(target, {
          message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
      });
      blockUI.block();

      var formDatax = new FormData();
      formDatax.append('id', selectedID);

      $.ajax({
        url: "/ccRemarkGetEdit",
        type: "POST",
        data: formDatax,
        contentType: false,
        cache: false,
        processData:false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function (data){
          blockUI.release();
          blockUI.destroy();
          var obj = JSON.parse(data);
          if(obj){
            $('#edit_cc_remark_form [name="id"]').val(obj.id);
            $('#edit_cc_remark_form [name="CCRemarkName"]').val(obj.statusName);
            $('#edit_cc_remark_form [name="product"]').val(obj.product);
            $('#edit_cc_remark_form [name="CCRemarkDescription"]').val(obj.statusDefinition);
            $('#edit_cc_remark_form [name="status"]').val(obj.status);
          
        
        
          } else {
            // window.location.reload();
          }
        }
      });
    });
});