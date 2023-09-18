// const { defaultsDeep } = require("lodash");

// Class definition
var editAccountValidation = (function () {
    // Private functions
    var initDatatable = function () {
        const fv = FormValidation.formValidation(
            document.getElementById("edit_status_form"),
            {
                fields: {
                    campaignID: {
                        validators: {
                            notEmpty: {
                                message: "This field is required.",
                            },
                        },
                    },
                    campaignName: {
                        validators: {
                            notEmpty: {
                                message: "This field is required.",
                            },
                        },
                    },
                    mobileNumber: {
                        validators: {
                            notEmpty: {
                                message: "This field is required.",
                            },
                        },
                    },
                    leadStatus: {
                        validators: {
                            notEmpty: {
                                message: "This field is required.",
                            },
                        },
                    },
                    ptpAmount: {
                        validators: {
                            notEmpty: {
                                message: "This field is required.",
                            },
                            currency: {
                                message: 'The amount is not a valid currency value'
                            },
                        },
                    },
                    ptpDate: {
                        validators: {
                            notEmpty: {
                                message: "This field is required.",
                            },
                        },
                    },
                    callbackDate: {
                        validators: {
                            notEmpty: {
                                message: "This field is required.",
                            },
                        },
                    },
                    reason: {
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
                .getElementById("editAccountSubmitBtn")
                .setAttribute("data-kt-indicator", "on");

            // Disable button to avoid multiple click
            document.getElementById("editAccountSubmitBtn").disabled = true;

            // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
            var formx = $("#edit_status_form")[0]; // You need to use standart javascript object here
            var formDatax = new FormData(formx);
            $.ajax({
                url: "/list/editAccount",
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

                    console.log(data);
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

                        Swal.fire({
                            html: "Account Updated successfully.",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Okay",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // window.location.href = "/list/account";
                                if(data.fromVerified == "1"){
                                    console.log("user is from ptpAndPaid");
                                    window.location.href = "/agent/ptpAndPaid";

                                }else{
                                    if(data.level == 0){

                                        console.log("user is agent");
                                        window.location.href = "/agent/callBack";
                                    }
                                    else{
                                        console.log("user is admin");
                                        if(data.fromVerified == "verifyAdmin"){
                                            window.location.href = "/verify";

                                        }else{
                                            window.location.href = "/list/account";
                                        }
                                        
                                    }

                                }

                               
                              
                            }
                        });

                        toastr.success(data.message, "Success");
                        $("#edit_status_form").trigger("reset");
                    } else {
                      
                        Swal.fire({
                            text: data.message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                if(data.fromVerified == "1"){
                                    console.log("user is from ptpAndPaid");
                                    window.location.href = "/agent/ptpAndPaid";

                                }else{
                                    if(data.level == 0){

                                        console.log("user is agent");
                                        window.location.href = "/agent/callBack";
                                    }
                                    else{
                                        console.log("user is admin");
                                        if(data.fromVerified == "verifyAdmin"){
                                            window.location.href = "/verify";

                                        }else{
                                            window.location.href = "/list/account";
                                        }
                                        
                                    }

                                }
                            }
                        });
                    }
                    document
                        .getElementById("editAccountSubmitBtn")
                        .setAttribute("data-kt-indicator", "off");
                    document.getElementById(
                        "editAccountSubmitBtn"
                    ).disabled = false;
                    //  event.preventDefault();
                },
            });
        });
    };
    return {
        // public functions
        init: function () {
            // form is binded and initiliazed here
            initDatatable();
        },
    };
})();

jQuery(document).ready(function () {
    //DONT FOGET THIS!!!
    editAccountValidation.init();
    jQuery(document).on('change', '#select_lead_status', function(e){
        e.preventDefault();
        $("#ptpAmount").val("");
        $('#ptpDate').val('');
        $('#callbackDate').val('');
        $('#reason').val('');
      

        var lead_status = $('#select_lead_status').val();

        switch (lead_status) {
            case 'PTP - Promise to Pay':
                    $('#ptp_fields').show('fadeIn');
                    $('#rfd_field').hide('fadeOut');
                    $('#cb_field').hide('fadeOut');
                break;

            case 'CB - Callback':
                    $('#cb_field').show('fadeIn');
                    $('#rfd_field').hide('fadeOut');
                    $('#ptp_fields').hide('fadeOut');
                break;
            case 'NPTP - No Promise To Pay':
                    $('#rfd_field').show('fadeIn');
                    $('#cb_field').hide('fadeOut');
                    $('#ptp_fields').hide('fadeOut');
                break;

            default:
                    $('#ptp_fields').hide('fadeOut');
                    $('#cb_field').hide('fadeOut');
                    $('#rfd_field').hide('fadeOut');
                break;
        }
       });
});
