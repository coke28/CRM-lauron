jQuery(document).on("change", "#selectScheduleType", function (e) {
    e.preventDefault();
  
    var selectScheduleType = $(this).val();
    console.log(selectScheduleType);

    switch (selectScheduleType) {
        case "SCHEDULED":
            $("#dateGroup").show("fadein");
            break;
        case "NOW":
            $("#dateGroup").fadeOut("slow");
            $('#date').val("");
            break;

        default:
            console.log("defaulted");
        // code to be executed if n is different from case 1 and 2
    }
});

// Class definition
var uploadValidation = function () {
    // Private functions
     var initDatatable = function () {
   
  
       const fv = FormValidation.formValidation(
         document.getElementById('uploadCSV_form'),
         {
           fields: {
             'campaignName':{
               validators:{
                  regexp: {
                    regexp: /^[a-zA-Z0-9\s,_\(\)\-]+$/,
                    message: 'Only letters, numbers, space, comma, underscore, hyphen, and parentheses are allowed.'
                },
                 notEmpty:{
                   message: 'This field is required.'
                 },
               }
             },
             'leadsUserAssignment':{
                validators:{
                  notEmpty:{
                    message: 'This field is required.'
                  },
                }
              },
              'autoDialMode':{
                validators:{
                  notEmpty:{
                    message: 'This field is required.'
                  },
                }
              },
             'campaignGroup':{
               validators:{
                 notEmpty:{
                   message: 'This field is required.'
                 },
               }
             },
             'selectScheduleType':{
                validators:{
                  notEmpty:{
                    message: 'This field is required.'
                  },
                }
              },
              'campaignID':{
                validators:{
                  notEmpty:{
                    message: 'This field is required.'
                  },
                }
              },
              'campaignTimestamp':{
                validators:{
                  notEmpty:{
                    message: 'This field is required.'
                  },
                }
              },
              'file':{
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
               rowSelector: '.fv-row',
               eleInvalidClass: '',
               eleValidClass: ''
             }),
             // Validate fields when clicking the Submit button
             submitButton: new FormValidation.plugins.SubmitButton(),
             excluded: new FormValidation.plugins.Excluded({
                excluded: function(field, element, elements) {
                    // field is the field name
                    // element is the field element
                    // elements is the list of field elements in case
                    // we have multiple elements with the same name
                    return $(element).is(':hidden')
                    // return true if you want to exclude the field
                }
             }),
           }
         }
       );
      
         // this function listens to the form validation
       fv.on('core.form.valid', function() {
         // Show loading indication
         
         document.getElementById('uploadCSVSubmitBtn').setAttribute('data-kt-indicator', 'on');
  
         // Disable button to avoid multiple click
         document.getElementById('uploadCSVSubmitBtn').disabled = true;
  
         // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
         var formx = $("#uploadCSV_form")[0]; // You need to use standart javascript object here
         var formDatax = new FormData(formx);
         $.ajax({
           url: "/upload",
           type: "POST",
           data: formDatax,
           contentType: false,
           cache: false,
           processData:false,
           headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
           success: function (data){
             data = JSON.parse(data);
             if(data.success){
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
  
              toastr.success(data.message, "Success");
              $("#uploadCSV_form").trigger("reset");
              // $('#add_user_form [name="supervisor_id"]').val('').trigger('change');
              // $('#add_user_form [name="crm_user_group_id"]').val('').trigger('change');
              // $('#add_user_form [name="user_level_id"]').val('').trigger('change');
            //   $('#application_type_dt').DataTable().ajax.reload();
             }
             else {
               Swal.fire({
                  text: data.message,
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                      confirmButton: "btn btn-primary"
                  }
              });
              // window.location.reload();
             }
             document.getElementById('uploadCSVSubmitBtn').setAttribute('data-kt-indicator', 'off');
             document.getElementById('uploadCSVSubmitBtn').disabled = false;
            //  event.preventDefault();
           }
         });
  
  
       });
     }
     return {
        // public functions
        init: function() {
          // form is binded and initiliazed here
          initDatatable();
        }
     };
  }();
  
  jQuery(document).ready(function() {
      
      //DONT FOGET THIS!!!
      uploadValidation.init();
  
    });

