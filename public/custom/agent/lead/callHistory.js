
  "use strict";

  var currentRoleFilter = 0;

  // Class definition
  var KTDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;
    var filterPayment;

    // alert("in ajax");

    // Private functions
    var initDatatable = function () {
        console.log("hello Init")
        dt = $("#koki").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[ 8, 'desc' ]],
            stateSave: false,
            dom: "<'row'<'col-sm-6 mt-0 mb-5'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-1 'l><'col-sm-4 mt-2'i><'col-sm-7'p>>",
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            search: {
                input: $('#callHistorySearch'),
                key: 'dtsearch'
            },
            ajax: {
                url: "/agent/agentCallHistoryDataTable",
                type: "post",
                beforeSend: function (request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                data: function(d) {
                    const queryString = window.location.search;
                    const urlParams = new URLSearchParams(queryString);
                   
                    // d.action = "ADDED NEW ENTRY";
                    if(urlParams.has('mobileNumber')){
                      d.mobileNumber = urlParams.get('mobileNumber');
                    }
                    if(urlParams.has('campaignID')){
                      d.campaignID = urlParams.get('campaignID');
                    }
                    if(urlParams.has('campaignName')){
                      d.campaignName = urlParams.get('campaignName');
                    }
                    
                    if(urlParams.has('leadID')){
                      d.leadID = urlParams.get('leadID');
                    }
                  }
            },
            language:{
                lengthMenu: ' _MENU_'
            },
            columns: [
                { data: 'action' },
                { data: 'agent' },
                { data: 'fullname' },
                { data: 'date' },
                { data: 'mobileNumber' },
                { data: 'statusID' },
                { data: 'campaignID' },
                { data: 'ip' },
                { data: 'aht' },
            ],
        });

        // <!--begin::Menu item-->
        // <div class="menu-item px-3">
        //     <a href="#" class="menu-link px-3" data-kt-docs-table-filter="cpass_row" id="changeuser_password_btn" data-id="`+data.id+`" data-bs-toggle="modal" data-bs-target="#changeUserPasswordModal">
        //         Change Password
        //     </a>
        // </div>
        // <!--end::Menu item-->

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on('draw', function () {
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
   var handleSearchDatatable = function () {
       const filterSearch = document.querySelector('#callHistorySearch');
       filterSearch.addEventListener('keyup', function (e) {
           dt.search(e.target.value).draw();
       });
   }

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
        }
    }
  }();

  // On document ready
  KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();

    jQuery(document).off('change', '#callHistorySearch');
    jQuery(document).on('change', '#callHistorySearch', function(e) {
      if($(this).val().length == 0){
        $("#koki").DataTable().search($('#callHistorySearch').val()).draw();
      }
    });

    jQuery(document).off('click', '.clearInp');
    jQuery(document).on('click', '.clearInp', function(e) {
      e.preventDefault();
      $(this).closest('.input-group').find('input').val('').trigger('change');
    });
  });

 

