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
        dt = $("#hotlead_dt").DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[6, "desc"]],
            stateSave: false,
            dom: "<'row'<'col-sm-6 mt-0 mb-5'B>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-1 'l><'col-sm-4 mt-2'i><'col-sm-7'p>>",
            buttons: [
                // 'copyHtml5',
                // 'excelHtml5',
                // 'csvHtml5',
                // 'pdfHtml5',
                {
                    text: "Click to Copy",
                    attr: {
                        id: "copyButton",
                    },
                    action: function (e, dt, node, config) {
                        // the column headings are here:
                        var copyString =
                            "Reference Number\tProduct\tOrder Number Ultima\tTransmittal Date\tGlobe Account\tSalutation\tGender\tBirthday\tCivil Status\tLast Name\tFirst Name\tMiddle Name\tMother Last Name\tMother First Name\tMother Middle Name\tNumber Of Children\tHome Ownership\tHome Address W/ Postal\tLength Of Stay\tLandline Contact Number\tExisting Mobile Number\tMobile Contact Number\tEmail\tTIN\tGSISS\tCitizenship\tIf Foreign Country\tSpouse Name\tSpouse Birthday\tSpouse Contact Number\tOffice Name\tOffice Address Postal\tDate Of Employment\tOffice Telephone Number\tYears In Company\tOccupation\tMonthly Income\tAuthorized Contact Person\tRelation\tAuthorized Contact Number\tHome Office Paperless\tPreferred Mode Of Payment\tPlan Type\tPlan MSF\tPlan Combo\tPlan Booster\tMandatory Arrow Addon\tGoSurf Bundle\tArrow Addon\tHandset\tCash Amount\tPromo Price Bulletin\tValue Added Service\tHBP\tLockup Period\tTransmittal Type\tSource Of Sales\tApplication Mode\tDc Remark\tSalesman ID\tSalesman Name\tAgency Name\tAccount Manager\tSales Channel\tProject Promo\tApp Received Source\tTimeStamp\tType Of POID\tPOID Number\tDocu Link\tLead Type\tSales Agent Name\tApp Date\tGcash GUI\tEvia Fastlane\tEplan Gscore\tDelivery Address\tsadmin\tGDF Promo Tag\tDate Called\tDate Compiled\tQualified\tDelivery Zip Code\tAndale Area\tPorting Number\tProject Chamomile\tGadget Care Amount\tExtra Field1\tExtra Field 2\tExtra Field 3\n";

                        dt.rows().every(function () {
                            // for each row, get the data items, separated by tabs:
                            copyString =
                                copyString +
                                Object.values(this.data()).join("\t") +
                                "\n";
                        });

                        if (copyString !== undefined) {
                            // create a textarea, write the data to it, then
                            // select it for copy/paste:
                            var dummy = document.createElement("textarea");
                            document.body.appendChild(dummy);
                            dummy.setAttribute("id", "dummy_id");
                            document.getElementById("dummy_id").value =
                                copyString;
                            dummy.select();
                            document.execCommand("copy");
                            document.body.removeChild(dummy);
                        }
                    },
                },
                {
                    text: "Click to Export",
                    attr: {
                        id: "exportButton",
                    },
                    action: function (e, dt, node, config) {
                        console.log($("#statusSelect").val());
                        document
                            .getElementById("exportButton")
                            .setAttribute("data-kt-indicator", "on");
                        document.getElementById("exportButton").disabled = true;
                        var data = { campaignID: $("#statusSelect").val(), search: $("#hotLeadSearch").val()};
                        console.log(data);
                        $.ajax({
                            url: "/agent/hotLeadExport",
                            method: "POST",
                            data: data,

                            xhrFields: {
                                responseType: "blob",
                            },
                            // contentType: false,
                            cache: false,
                            processData: true,
                            beforeSend: function (request) {
                                request.setRequestHeader(
                                    "X-CSRF-TOKEN",
                                    $('meta[name="csrf-token"]').attr("content")
                                );
                            },
                            // data: function (d) {
                            //     d.koki = $("#statusSelect").val();
                            //     console.log(d);
                            // },

                            success: function (data, status, xhr) {
                                let disposition = xhr.getResponseHeader(
                                    "content-disposition"
                                );
                                let matches = /"([^"]*)"/.exec(disposition);
                                let filename =
                                    matches != null && matches[1]
                                        ? matches[1]
                                        : "Reports.csv";

                                let blob = new Blob([data], {
                                    type: "octet/stream",
                                });
                                let link = document.createElement("a");
                                link.href = window.URL.createObjectURL(blob);
                                link.download = filename;
                                link.style.display = "none";
                                document.body.appendChild(link);
                                link.click();
                                document.body.removeChild(link);
                            },
                        });
                        document
                            .getElementById("exportButton")
                            .setAttribute("data-kt-indicator", "off");
                        document.getElementById(
                            "exportButton"
                        ).disabled = false;
                    },
                },
                //   {
                //     extend:    'excelHtml5',
                //     text:      'Excel',
                //     titleAttr: 'Excel',
                //     "oSelectorOpts": { filter: 'applied', order: 'current' },
                //     "sFileName": "report.xls",
                //     action : function( e, dt, button, config ) {
                //         exportTableToCSV.apply(this, [$('#hotlead_dt'), 'export.xls']);

                //     }

                // },
            ],
            search: {
                input: $("#hotLeadSearch"),
                key: "dtsearch",
            },
            ajax: {
                url: "/agent/hotLead",
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
                {
                    class: "dt-control",
                    orderable: false,
                    data: null,
                    defaultContent: "",
                },
                { data: "id" },
                { data: "referenceNumber" },
                { data: "product" },
                { data: "campaignID" },
                { data: "mobileContactNumber" },
                { data: "firstname" },
                { data: "lastname" },
                // { data: null },
            ],
            // columnDefs: [
            //     {
            //         targets: 8,
            //         orderable: false,
            //         render: function(data, type, row) {

            //                 return `
            //                 <a href="/agent/hotLeadDetails?campaignID=`+data.campaignID+`&mobileContactNumber=`+data.mobileContactNumber+`" class="menu-link px-3">
            //                                 More Details
            //                             </a>
            //             `;

            //         }
            //     },
            // ],
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

    var detailRows = [];

    $("#hotlead_dt tbody").on("click", "tr td.dt-control", function () {
        var tr = $(this).closest("tr");
        var row = dt.row(tr);
        var idx = detailRows.indexOf(tr.attr("id"));

        if (row.child.isShown()) {
            tr.removeClass("details");
            row.child.hide();

            // Remove from the 'open' array
            detailRows.splice(idx, 1);
        } else {
            tr.addClass("details");
            row.child(format(row.data())).show();

            // Add to the 'open' array
            if (idx === -1) {
                detailRows.push(tr.attr("id"));
            }
        }
    });
    function format(d) {
        console.log("here");
        return (
            "Reference Number: " +
            d.referenceNumber +
            " " +
            "<br>" +
            "Product: " +
            d.product +
            "<br>" +
            "Order Number Ultima: " +
            d.orderNumberUltima +
            " " +
            "<br>" +
            "Transmittal Date: " +
            d.transmittalDate +
            "<br>" +
            "Globe Account: " +
            d.globeAccount +
            " " +
            "<br>" +
            "Salutation: " +
            d.salutation +
            "<br>" +
            "Gender: " +
            d.gender +
            " " +
            "<br>" +
            "Birthday: " +
            d.birthday +
            "<br>" +
            "Civil Status: " +
            d.civilStatus +
            " " +
            "<br>" +
            "Full name: " +
            d.firstname +
            " " +
            d.middlename +
            " " +
            d.lastname +
            "<br>" +
            "Mother Full name: " +
            d.motherFirstname +
            " " +
            d.motherMiddlename +
            " " +
            d.motherLastname +
            " " +
            "<br>" +
            "Number Of Children: " +
            d.NumberOfChildren +
            "<br>" +
            "Home Ownership: " +
            d.homeOwnership +
            " " +
            "<br>" +
            "Home Address /W Postal: " +
            d.howAddressWithPostal +
            "<br>" +
            "Length Of Stay: " +
            d.lengthOfStay +
            " " +
            "<br>" +
            "Landline Contact Number: " +
            d.landlineContact +
            "<br>" +
            "Existing Mobile Number: " +
            d.existingMobile +
            " " +
            "<br>" +
            "Mobile Contact Number: " +
            d.mobileContactNumber +
            "<br>" +
            "Email: " +
            d.email +
            " " +
            "<br>" +
            "TIN: " +
            d.tin +
            "<br>" +
            "GSISS: " +
            d.gsiss +
            " " +
            "<br>" +
            "Citizenship: " +
            d.citizenship +
            "<br>" +
            "Full name: " +
            d.globeAccount +
            " " +
            "<br>" +
            "If Foreign Country: " +
            d.ifForeignCountry +
            "<br>" +
            "Spouse name: " +
            d.spousename +
            " " +
            "<br>" +
            "Spouse Birthday: " +
            d.spouseBirthday +
            "<br>" +
            "Spouse Contact Number: " +
            d.spouseContactNumber +
            " " +
            "<br>" +
            "Office Name: " +
            d.officeName +
            "<br>" +
            "Office Address Postal: " +
            d.officeAddressPostal +
            " " +
            "<br>" +
            "Date Of Employment: " +
            d.dateOfemployment +
            "<br>" +
            "Office Telephone Number: " +
            d.officeTelephoneNumber +
            " " +
            "<br>" +
            "Years In Company: " +
            d.yearsInCompany +
            "<br>" +
            "Occupation: " +
            d.occupation +
            " " +
            "<br>" +
            "Monthly Income: " +
            d.monthlyIncome +
            "<br>" +
            "Authorized Contact Person: " +
            d.authorizedContactPerson +
            " " +
            "<br>" +
            "Relation: " +
            d.relation +
            "<br>" +
            "Authorized Contact Number: " +
            d.authorizedContactNumber +
            " " +
            "<br>" +
            "Home Office Paperless: " +
            d.homeOfficePaperless +
            "<br>" +
            "Preferred ModeOf Payment: " +
            d.preferredModeOfPayment +
            " " +
            "<br>" +
            "Plan Type: " +
            d.planType +
            "<br>" +
            "Plan MSF: " +
            d.planMSF +
            " " +
            "<br>" +
            "Plan Combo: " +
            d.planCombo +
            "<br>" +
            "Plan Booster: " +
            d.planBooster +
            " " +
            "<br>" +
            "Mandatory ArrowAddon: " +
            d.mandatoryArrowAddon +
            "<br>" +
            "GoSurf Bundle: " +
            d.goSurfBundle +
            " " +
            "<br>" +
            "ArrowAddon: " +
            d.arrowAddon +
            "<br>" +
            "Full name: " +
            d.globeAccount +
            " " +
            "<br>" +
            "Handset: " +
            d.handset +
            "<br>" +
            "Cash Amount: " +
            d.cashAmount +
            " " +
            "<br>" +
            "Promo Price Bulletin: " +
            d.promoPriceBulletin +
            "<br>" +
            "Value Added Service: " +
            d.valueAddedService +
            " " +
            "<br>" +
            "HBP: " +
            d.hbp +
            "<br>" +
            "Lockup Period: " +
            d.lockupPeriod +
            " " +
            "<br>" +
            "Transmittal Type: " +
            d.transmittalType +
            "<br>" +
            "Source Of Sales: " +
            d.sourceOfSales +
            " " +
            "<br>" +
            "Application Mode: " +
            d.applicationMode +
            "<br>" +
            "dcRemark: " +
            d.dcRemark +
            " " +
            "<br>" +
            "Salesman ID: " +
            d.salesmanID +
            "<br>" +
            "Salesman Name: " +
            d.salesmanName +
            " " +
            "<br>" +
            "Agency Name: " +
            d.agencyName +
            "<br>" +
            "Account Manager: " +
            d.accountManager +
            " " +
            "<br>" +
            "Sales Channel: " +
            d.salesChannel +
            "<br>" +
            "Project Promo: " +
            d.projectPromo +
            " " +
            "<br>" +
            "App Receive Source: " +
            d.appReceiveSource +
            "<br>" +
            "Timestamp: " +
            d.stimestamp +
            " " +
            "<br>" +
            "Type Of POID: " +
            d.typeOfPOID +
            "<br>" +
            "POID Number: " +
            d.poidNumber +
            " " +
            "<br>" +
            "Docu link: " +
            d.doculink +
            "<br>" +
            "Lead Type: " +
            d.leadType +
            " " +
            "<br>" +
            "Sales Agent Name: " +
            d.salesAgentName +
            "<br>" +
            "App Date: " +
            d.appDate +
            " " +
            "<br>" +
            "Gcash GUI: " +
            d.gcashGui +
            "<br>" +
            "Evia Fastlane: " +
            d.eviaFastlane +
            " " +
            "<br>" +
            "Eplan Gscore: " +
            d.eplanGscore +
            "<br>" +
            "Delivery Address: " +
            d.deliveryAddress +
            " " +
            "<br>" +
            "sadmin: " +
            d.sadmin +
            "<br>" +
            "GDF PromoTag: " +
            d.gdfPromoTag +
            " " +
            "<br>" +
            "Date Called: " +
            d.dateCalled +
            "<br>" +
            "Date Compiled: " +
            d.dateCompiled +
            " " +
            "<br>" +
            "Qualified: " +
            d.qualified +
            "<br>" +
            "Delivery ZipCode: " +
            d.deliveryZipCode +
            " " +
            "<br>" +
            "Andale Area: " +
            d.andaleArea +
            "<br>" +
            "Porting Number: " +
            d.portingNumber +
            " " +
            "<br>" +
            "Project Chamomile: " +
            d.projectChamomile +
            "<br>" +
            "Gadget Care Amount: " +
            d.gadgetCareAmount +
            " " +
            "<br>" +
            "Extra Field 1: " +
            d.extraField1 +
            "<br>" +
            "Extra Field 2: " +
            d.extraField2 +
            " " +
            "<br>" +
            "Extra Field 3: " +
            d.extraField3 +
            "<br>"
        );
    }
    //   Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable2 = function () {
        const filterSearch = document.querySelector("#hotLeadSearch");
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

    function exportTableToCSV($table, filename) {
        $.ajax({
            url: "/list/generateReport",
            method: "POST",
            xhrFields: {
                responseType: "blob",
            },
            data: formDatax,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (data, status, xhr) {
                let disposition = xhr.getResponseHeader("content-disposition");
                let matches = /"([^"]*)"/.exec(disposition);
                let filename =
                    matches != null && matches[1] ? matches[1] : "Reports.csv";

                let blob = new Blob([data], {
                    type: "octet/stream",
                });
                let link = document.createElement("a");
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                link.style.display = "none";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                document
                    .getElementById("btn-submit")
                    .setAttribute("data-kt-indicator", "off");
                document.getElementById("btn-submit").disabled = false;
            },
        });
    }

    function download_csv(csv, filename) {
        var csvFile;
        var downloadLink;

        // CSV FILE
        csvFile = new Blob([csv], { type: "text/csv" });

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // We have to create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        // Make sure that the link is not displayed
        downloadLink.style.display = "none";

        // Add the link to your DOM
        document.body.appendChild(downloadLink);

        // Lanzamos
        downloadLink.click();
    }

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

    jQuery(document).off("change", "#hotLeadSearch");
    jQuery(document).on("change", "#hotLeadSearch", function (e) {
        if ($(this).val().length == 0) {
            $("#hotlead_dt")
                .DataTable()
                .search($("#hotLeadSearch").val())
                .draw();
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
