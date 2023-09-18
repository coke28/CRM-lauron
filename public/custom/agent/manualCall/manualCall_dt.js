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
        dt = $("#manualCall_dt").DataTable({
            "lengthMenu": [10, 50, 100, 1000],
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[6, "desc"]],
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
                input: $("#manualCallSearch"),
                key: "dtsearch",
            },
            ajax: {
                url: "/agent/manualCall",
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
                { data: 'segment' },
                { data: "campaignName" },
                { data: "customerName" },
                { data: "mobileNumber" },
                { data: "originalBalance" },
                { data: "agent" },
                { data: "locked" },
                { data: null },
            ],
            columnDefs: [
                {
                    targets: 7,
                    orderable: false,
                    render: function (data, type, row) {

                            return (
                                `


                                <a href="/agent/editLeadStatusZ?campaignID=`+data.campaignID+`&mobileNumber=`+data.mobileNumber+'&campaignName='+data.campaignName+'&id='+data.id+'&fromVerified=manual'+`" class="menu-link px-3">
                                    Edit
                                </a>



                  `
                            );


                    },
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
        const filterSearch = document.querySelector("#manualCallSearch");
        filterSearch.addEventListener("keyup", function (e) {
            dt.search(e.target.value).draw();
        });
    };
    // var handleSearchDatatable = function () {
    //     const filterSearch = document.querySelector("#statusSelect");
    //     filterSearch.addEventListener("change", function (e) {
    //         dt.search(e.target.value).draw();
    //     });
    // };

    // Public methods
    return {
        init: function () {
            initDatatable();
            // handleSearchDatatable();
            handleSearchDatatable2();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();

    jQuery(document).off("change", "#manualCallSearch");
    jQuery(document).on("change", "#manualCallSearch", function (e) {
        if ($(this).val().length == 0) {
            $("#manualCall_dt").DataTable().search($("#manualCallSearch").val()).draw();
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
