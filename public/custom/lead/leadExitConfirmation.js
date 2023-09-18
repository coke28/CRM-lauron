$(document).ready(function (){

  jQuery(document).off('click', '#exitLead');
  jQuery(document).on('click', '#exitLead', function(e) {
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
          window.location.href = "/list/lead"
        }
    });

  });

  jQuery(document).off('click', '#exitLeadAgent');
  jQuery(document).on('click', '#exitLeadAgent', function(e) {
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
          const queryString = window.location.search;
          const urlParams = new URLSearchParams(queryString);
          const fromVerified = urlParams.get('fromVerified')
          if(fromVerified == "manual"){
            window.location.href = "/agent/manualCall";
          }else{
            window.location.href = "/agent/lead";
          }
        }
    });

  });

});