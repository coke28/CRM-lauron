<!--begin::Toolbar-->
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->

    <div id="kt_toolbar_container"
        class="{{ theme()->printHtmlClasses('toolbar-container', false) }} d-flex flex-stack">
        {{-- @if (theme()->getOption('layout', 'page-title/display') && theme()->getOption('layout', 'header/left') !==
        'page-title')
        {{ theme()->getView('layout/page-title/_default') }}
        @endif --}}

        @if(Route::current()->getName() == 'testing')
        Hello This is testing
        @endif

        @switch(Route::current()->getName())
        @case("admintools.")
        First case...
        @break

        @case("admintools.category")
        <h1>Manage Category</h1>
        @break
        @case("admintools.product")
        <h1>Manage Product</h1>
        @break

        @case("admintools.phoneBrand")
        <h1>Manage Phone Brands</h1>
        @break
        @case("admintools.phone")
        <h1>Manage Phones</h1>
        @break

        @case("admintools.user")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Users</a></li>
        </ol>
        @break

        @case("admintools.group")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Groups</a></li>
        </ol>
        @break

        @case("admintools.status")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Status</a></li>
        </ol>
        @break
        @case("admintools.campaign")
        <h1>Manage Campaigns</h1>
        @break

        @case("admintools.productName")
        <h1>Manage Product Names</h1>
        @break
        @case("admintools.plan")
        <h1>Manage Plans</h1>
        @break

        @case("admintools.planBreakdown")
        <h1>Manage Plan Breakdown</h1>
        @break

        @case("admintools.planFee")
        <h1>Manage Plan Fee</h1>
        @break
        @case("admintools.promoName")
        <h1>Manage Promo Name</h1>
        @break

        @case("admintools.installationFee")
        <h1>Manage Installation Fee</h1>
        @break
        @case("admintools.modemFee")
        <h1>Manage Modem Fee</h1>
        @break
        @case("admintools.technology")
        <h1>Manage Technology</h1>
        @break

        @case("admintools.installType")
        <h1>Manage Install Type</h1>
        @break
        @case("admintools.upfrontFee")
        <h1>Manage Upfront Fee</h1>
        @break
        @case("admintools.lockup")
        <h1>Manage Lockup</h1>
        @break

        @case("admintools.applicationType")
        <h1>Manage Application Type</h1>
        @break
        @case("admintools.CCRemark")
        <h1>Manage CC Remarks</h1>
        @break
        @case("admintools.freebie")
        <h1>Manage Freebies</h1>
        @break

        @case("admintools.paymentMethod")
        <h1>Manage Payment Methods</h1>
        @break

        @case("admintools.client")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Clients</a></li>
        </ol>
        @break

        @case("admintools.segment")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Segment</a></li>
        </ol>
        @break

        @case("admintools.collectionEffort")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Collection Effort</a></li>
        </ol>
        @break

        @case("admintools.transaction")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Transaction</a></li>
        </ol>
        @break

        @case("admintools.placeOfContact")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Place of Contact</a></li>
        </ol>
        @break

        @case("admintools.pointOfContact")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Point of Contact</a></li>
        </ol>
        @break

        @case("admintools.reasonForDenial")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Reason for Denial</a></li>
        </ol>
        @break

        @case("admintools.area")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Administration Tools</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Area</a></li>
        </ol>
        @break

        @case("verify.account")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Verify Accounts</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3"></a></li>
        </ol>
        @break

        @case("list.lead")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">List & Reports</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Leads</a></li>
        </ol>
        @break

        @case("list.campaignList")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">List & Reports</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Campaigns</a></li>
        </ol>
        @break

        @case("list.account")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">List & Reports</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Accounts</a></li>
        </ol>
        @break

        @case("report.admin")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">List & Reports</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Generate Report</a></li>
        </ol>
        @break

        @case("misc.upload")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Miscellaneous</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Upload CSV</a></li>
        </ol>
        @break

        @case("misc.auditLog")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Miscellaneous</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Audit Logs</a></li>
        </ol>
        @break

        @case("misc.campaignUpload")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Miscellaneous</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Campaign Upload Logs</a></li>
        </ol>
        @break

        @case("misc.auditLog")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Miscellaneous</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Audit Logs</a></li>
        </ol>
        @break
        
        @case("edit.account")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">List & Reports</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Accounts</a></li>
            <li class="breadcrumb-item pe-3 text-muted">Edit Account</li>
        </ol>
        @break

        @case("edit.editLeadStatus")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">List & Reports</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manage Leads</a></li>
            <li class="breadcrumb-item pe-3 text-muted">Edit Lead</li>
        </ol>
        @break

        @case("settings.index")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Accounts</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Settings</a></li>
        </ol>
        @break

        @case("settings.settings")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Accounts</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">s</a></li>
        </ol>
        @break

        @case("agent.lead")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Lead List</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3"></a></li>
        </ol>
        @break

        @case("agent.callHistory")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Lead List</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Call History</a></li>
        </ol>
        @break

        @case("agent.manualCall")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Manual Call List</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3"></a></li>
        </ol>
        @break

        @case("edit.editLeadStatusAgent")
        <h1>Edit Lead</h1>
        @break

        @case("agent.callBack")
        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Call Back List</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3"></a></li>
        </ol>
        @break

        @case("agent.hotLead")
        <h1>Hot Lead List</h1>
        @break

        @case("misc.system")
        <h1>Hot Lead List</h1>
        @break
        
        @default

        <ol class="breadcrumb text-muted fs-6 fw-semibold">
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3">Overview</a></li>
            <li class="breadcrumb-item pe-3"><a href="#" class="pe-3"></a></li>
        </ol>
      
        @endswitch


        {{-- @if (Request::is('admintools/*'))
        {{ theme()->getView('layout/page-title/_default') }}
        ASDASDASD
        @endif --}}

        <!--begin::Actions-->
        {{-- <div class="d-flex align-items-center py-1"> --}}
            <!--begin::Wrapper-->
            {{-- <div class="me-4"> --}}
                <!--begin::Menu-->
                {{-- <a href="#" class="btn btn-sm btn-flex btn-light btn-active-primary fw-bolder"
                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                    {!! theme()->getSvgIcon("icons/duotone/Text/Filter.svg", "svg-icon-5 svg-icon-gray-500 me-1") !!}
                    Filter
                </a> --}}
                {{-- {{ theme()->getView('partials/menus/_menu-1') }} --}}
                <!--end::Menu-->
                {{--
            </div> --}}
            <!--end::Wrapper-->

            <!--begin::Wrapper-->
            {{-- <div data-bs-toggle="tooltip" data-bs-placement="left" data-bs-trigger="hover"
                title="Create a new account">
                <a href="#" class="btn btn-sm btn-primary fw-bolder" data-bs-toggle="modal"
                    data-bs-target="#kt_modal_create_account" id="kt_toolbar_create_button">
                    Create
                </a>
            </div> --}}
            <!--end::Wrapper-->
            {{--
        </div> --}}
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->