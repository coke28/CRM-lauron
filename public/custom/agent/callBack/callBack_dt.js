"use strict";

var currentRoleFilter = 0;

// Class definition
var KTDatatablesServerSide = (function () {
    // Shared variables
    var table;
    var dt;
    var filterPayment;

    // alert("in ajax");

    // Private functions
    var initDatatable = function () {
        dt = $("#callback_dt").DataTable({
            "lengthMenu": [10, 50, 100, 1000],
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[8, "desc"]],
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
                input: $("#callBackSearch"),
                key: "dtsearch",
            },
            ajax: {
                url: "/agent/callBack",
                type: "post",
                beforeSend: function (request) {
                    request.setRequestHeader(
                        "X-CSRF-TOKEN",
                        $('meta[name="csrf-token"]').attr("content")
                    );
                },
                data: function (d) {
                    d.statusFilter = $("#statusSelect").val();
                },
            },
            language:{
                lengthMenu: ' _MENU_'
            },
            columns: [
                { data: 'id' },
                { data: "campaignName" },
                { data: "remarkStatus" },
                { data: "firstname" },
                { data: "middlename" },
                { data: "lastname" },
                { data: "mobileNumber" },
                { data: "campaignID" },
                { data: "leadStatus" },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 9,
                    orderable: false,
                    render: function(data, type, row) {
                        if(data.verifier == "full"){
                            return `
                            <a href="#" class="btn btn-primary btn-active-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                    Caution Call Limit Exceeded  
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="/list/editAccount?campaignID=`+data.campaignID+`&mobileNumber=`+data.mobileNumber+'&campaignName='+data.campaignName+'&leadID='+data.leadID+`" class="menu-link px-3">
                                            Update Account
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                        `;
                        }else{
                            return `
                                <a href="#" class="btn btn-primary btn-active-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                    `+data.verifier+` Time/s Called Previously
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="/list/editAccount?campaignID=`+data.campaignID+`&mobileNumber=`+data.mobileNumber+'&campaignName='+data.campaignName+'&leadID='+data.leadID+`" class="menu-link px-3">
                                            Update Account
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                            `;
    
                        }
    
                   
                    }
                },
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
        dt.on("draw", function () {
            KTMenu.createInstances();
        });
    };

    //   Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable2 = function () {
        const filterSearch = document.querySelector("#callBackSearch");
        filterSearch.addEventListener("keyup", function (e) {
            dt.search(e.target.value).draw();
        });
    };
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector("#statusSelect");
        filterSearch.addEventListener("change", function (e) {
            dt.search(e.target.value).draw();
        });
    };

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
            handleSearchDatatable2();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();

    jQuery(document).off("change", "#callBackSearch");
    jQuery(document).on("change", "#callBackSearch", function (e) {
        if ($(this).val().length == 0) {
            $("#callback_dt").DataTable().search($("#callBackSearch").val()).draw();
        }
    });

    // jQuery(document).off('change', '#statusSelect');
    // jQuery(document).on('change', '#statusSelect', function(e) {
    //   e.preventDefault();
    //   if($(this).val().length == 0){
    //     $("#campaign_dt").DataTable().draw();
    //     console.log("koki");

    //   }
    // });

    jQuery(document).off("click", ".clearInp");
    jQuery(document).on("click", ".clearInp", function (e) {
        e.preventDefault();
        $(this).closest(".input-group").find("input").val("").trigger("change");
    });
});