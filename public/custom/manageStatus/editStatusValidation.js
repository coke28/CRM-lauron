// Class definition
var editStatusValidation = (function () {
  // Private functions
  var initDatatable = function () {
      const fv = FormValidation.formValidation(
          document.getElementById("edit_status_form"),
          {
              fields: {
                  client: {
                      validators: {
                          notEmpty: {
                              message: "This field is required.",
                          },
                      },
                  },
                  // displayTable: {
                  //     validators: {
                  //         notEmpty: {
                  //             message: "This field is required.",
                  //         },
                  //     },
                  // },
                  statusCode: {
                      validators: {
                          notEmpty: {
                              message: "This field is required.",
                          },
                      },
                  },
                  statusID: {
                      validators: {
                          notEmpty: {
                              message: "This field is required.",
                          },
                      },
                  },
                  statusName: {
                      validators: {
                          notEmpty: {
                              message: "This field is required.",
                          },
                      },
                  },
                  statusDescription: {
                      validators: {
                          notEmpty: {
                              message: "This field is required.",
                          },
                      },
                  },
                  status: {
                      validators: {
                          notEmpty: {
                              message: "This field is required.",
                          },
                      },
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
              .getElementById("editStatusSubmitBtn")
              .setAttribute("data-kt-indicator", "on");

          // Disable button to avoid multiple click
          document.getElementById("editStatusSubmitBtn").disabled = true;

          // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
          var formx = $("#edit_status_form")[0]; // You need to use standart javascript object here
          var formDatax = new FormData(formx);
          formDatax.append("displayTableArray", $('#editDisplayTable').val());
          $.ajax({
              url: "/statusEdit",
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
                      $("#edit_status_form").trigger("reset");
                      $("#editStatus").modal("toggle");
                      $("#status_dt").DataTable().ajax.reload();
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
                      .getElementById("editStatusSubmitBtn")
                      .setAttribute("data-kt-indicator", "off");
                  document.getElementById(
                      "editStatusSubmitBtn"
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
  editStatusValidation.init();
  // event.preventDefault();

  //
  jQuery(document).off("click", "#edit_status_btn");
  jQuery(document).on("click", "#edit_status_btn", function (e) {
      var selectedID = $(this).data("id");
      var target = document.querySelector("#statusModalContent");
      var blockUI = new KTBlockUI(target, {
          message:
              '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
      });
      blockUI.block();
      var formDatax = new FormData();
      formDatax.append("id", selectedID);
      $.ajax({
          url: "/statusGetEdit",
          type: "POST",
          data: formDatax,
          contentType: false,
          cache: false,
          processData: false,
          headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
          success: function (data) {
              blockUI.release();
              blockUI.destroy();
              var obj = JSON.parse(data);
              if (obj) {
                  $('#edit_status_form [name="id"]').val(obj.id);
                  $('#edit_status_form [name="client"]').val(obj.client);
                  var selectedOptions = obj.displayTable // Example array of selected option values
                  if(selectedOptions){
                      var selectElement = document.getElementById("editDisplayTable");

                      for (var i = 0; i < selectElement.options.length; i++) {
                        var option = selectElement.options[i];
                        if (selectedOptions.includes(option.value)) {
                          option.selected = true;
                        }
                      }

                  }
                  $('#edit_status_form [name="statusCode"]').val(
                      obj.statusCode
                  );
                  $('#edit_status_form [name="statusID"]').val(obj.statusID);
                  $('#edit_status_form [name="status"]').val(obj.status);
                  $('#edit_status_form [name="statusName"]').val(
                      obj.statusName
                  );
                  $('#edit_status_form [name="statusDescription"]').val(
                      obj.statusDefinition
                  );
              } else {
                  // window.location.reload();
              }
          },
      });
  });
});
