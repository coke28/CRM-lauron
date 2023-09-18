@php
$menu = bootstrap()->getHorizontalMenu();
\App\Core\Adapters\Menu::filterMenuPermissions($menu->items);
@endphp

<!--begin::Menu wrapper-->
<div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend"
    data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
    <!--begin::Menu-->
    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch"
        id="#kt_header_menu" data-kt-menu="true">
        {{-- {!! $menu->build() !!} --}}

        @php
        $permission = auth()->user()->level;
        // print_r($permission);
        @endphp
        {{-- @if ($permission == 2 )
        <div class="menu-item me-lg-1 {{ (request()->is('admintools/category*')) ? 'active' : '' }}">
            <a class="menu-link py-2" href="{{ route(" admintools.category") }}"><span
                    class="menu-title">Dashboard</span></a>
        </div>
        @endif

        @if ($permission == 2 )
        <div class="menu-item me-lg-1 {{ (request()->is('admintools/product*')) ? 'active' : '' }}">
            <a class="menu-link py-2" href="{{ route(" admintools.product") }}"><span
                    class="menu-title">Dashboard2</span></a>
        </div>
        @endif --}}

        <!--begin::Menu-->

        {{-- <div class="menu-item me-lg-1" {{ (request()->is('index')) ? 'active' : '' }}>
            <a class="menu-link py-2" href="{{  route(" home") }}"><span class="menu-title">Dashboard</span></a>
        </div> --}}

        <div class="menu-item me-lg-1 {{ (request()->is('index')) ? 'active' : '' }}">
            <a class="menu-link py-2" href="{{ route("home") }}"><span class="menu-title">Dashboard</span></a>
        </div>

        {{-- //List and Reports --}}
        @if ($permission > 0 )
        <div class="menu-item me-lg-1 {{ (request()->is('index')) ? 'active' : '' }}">
            <a class="menu-link py-2" href="{{ route("verify.account") }}"><span class="menu-title">Verify Accounts</span></a>
        </div>

        <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-lg-1">
            <span class="menu-link py-2">
                <span class="menu-title">List & Reports</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>

            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-150px">

                <!--begin:Row-->
                <div class="row" data-kt-menu-dismiss="true">
                    <!--begin:Col-->
                    <div class="col-lg-4 border-left-lg-1">
                        <div class="menu-inline menu-column menu-active-bg">
                            <div class="menu-item">
                                <a href="{{route('list.lead')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Leads</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{route('list.account')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Accounts</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{route('list.campaignList')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Campaigns</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{route('report.admin')  }} " class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Reports</span>
                                </a>
                            </div>


                        </div>
                    </div>

                </div>
                <!--end:Row-->

            </div>

        </div>
        @endif

        {{-- //Administartion Tools --}}
        @if ($permission == 2 )
        <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-lg-1">
            <span class="menu-link py-2">
                <span class="menu-title" {{ (request()->is('admintools/*')) ? 'active' : '' }}>Administration
                    Tools</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>

            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 w-100 w-lg-600px p-5 p-lg-5"
                style="">

                <!--begin:Row-->
                <div class="row" data-kt-menu-dismiss="true">
                    <!--begin:Col-->
                    <div class="col-lg-4 border-left-lg-1">
                        <div class="menu-inline menu-column menu-active-bg">
                            
                            <div class="menu-item">
                                <a href="{{ route('admintools.area') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Area</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('admintools.client') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Client</span>
                                </a>
                            </div> 

                            <div class="menu-item">
                                <a href="{{route('admintools.collectionEffort')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Collection Effort</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('admintools.group') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Group</span>
                                </a>
                            </div> 

                        </div>
                    </div>
                    <!--end:Col-->

                    <!--begin:Col-->
                    <div class="col-lg-4 border-left-lg-1">
                        <div class="menu-inline menu-column menu-active-bg">
                            <div class="menu-item">
                                <a href="{{route('admintools.placeOfContact')  }} " class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Place Of Contact</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href=" {{route('admintools.pointOfContact')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Point Of Contact</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('admintools.reasonForDenial') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Reason For Denial</span>
                                </a>
                            </div>
                            
                        </div>
                    </div>
                    <!--end:Col-->

                    <!--begin:Col-->
                    <div class="col-lg-4 border-left-lg-1">
                        <div class="menu-inline menu-column menu-active-bg">
                            <div class="menu-item">
                                <a href="{{route('admintools.segment')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Segment</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('admintools.status') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Status</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{route('admintools.transaction')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Transaction</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="{{ route('admintools.user') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Users</span>
                                </a>
                            </div>
                            {{-- <div class="menu-item">
                                <a href="{{ route('admintools.plan') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Plans</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('admintools.planBreakdown') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Plan Breakdown</span>
                                </a>
                            </div> --}}






                        </div>
                    </div>
                    <!--end:Col-->
                    <!--begin:Col-->
                    {{-- <div class="col-lg-4 border-left-lg-1">
                        <div class="menu-inline menu-column menu-active-bg">
                            <div class="menu-item">
                                <a href="{{ route('admintools.planFee') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Plan Fee</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="{{ route('admintools.promoName') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Promo Name</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('admintools.installationFee') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Installation Fee</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="{{ route('admintools.modemFee') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Modem Fee</span>
                                </a>
                            </div>



                        </div>
                    </div> --}}
                    <!--end:Col-->
                    <!--begin:Col-->
                    {{-- <div class="col-lg-4 border-left-lg-1">
                        <div class="menu-inline menu-column menu-active-bg">
                            <div class="menu-item">
                                <a href="{{ route('admintools.technology') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Technology</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <div class="menu-item">
                                    <a href="{{ route('admintools.installType') }}" class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Manage Install Type</span>
                                    </a>
                                </div>

                                <div class="menu-item">
                                    <a href="{{ route('admintools.upfrontFee') }}" class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Manage Upfront Fee</span>
                                    </a>
                                </div>
                                <a href="{{ route('admintools.lockup') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Lockup</span>
                                </a>
                            </div>



                        </div>
                    </div> --}}
                    <!--end:Col-->
                    <!--begin:Col-->
                    {{-- <div class="col-lg-4 border-left-lg-1">
                        <div class="menu-inline menu-column menu-active-bg">
                            <div class="menu-item">
                                <a href="{{ route('admintools.applicationType') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Application Type</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="{{ route('admintools.CCRemark') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage CC Remarks</span>
                                </a>
                            </div>

                            <div class="menu-item">
                                <a href="{{ route('admintools.freebie') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Freebies</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="{{ route('admintools.paymentMethod') }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Manage Payment Method</span>
                                </a>
                            </div>
                        </div>

                    </div> --}}
                    <!--end:Col-->
                </div>
                <!--end:Row-->

            </div>

        </div>
        @endif

        {{-- //Misc --}}
        @if ($permission > 0 )
        <div data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-lg-1">
            <span class="menu-link py-2">
                <span class="menu-title">Miscellaneous</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>

            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown menu-rounded-0 py-lg-4 w-lg-150px">

                <!--begin:Row-->
                <div class="row" data-kt-menu-dismiss="true">
                    <!--begin:Col-->
                    <div class="col-lg-4 border-left-lg-1">
                        <div class="menu-inline menu-column menu-active-bg">
                            <div class="menu-item">
                                <a href="{{route('misc.upload')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Upload CSV</span>
                                </a>
                            </div>
                            @if ($permission == 2 && auth()->user()->username == "root")
                            <div class="menu-item">
                                <a href="{{route('misc.auditLog')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Audit Logs</span>
                                </a>
                            </div>
                            @endif

                            <div class="menu-item">
                                <a href="{{route('misc.campaignUpload')  }}" class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Campaign Upload Logs</span>
                                </a>
                            </div>
                            @if ($permission == 2 && auth()->user()->username == "root")
                            <div class="menu-item">
                                <a href="{{url('log/system')  }} " class="menu-link">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Error Logs</span>
                                </a>
                            </div>
                            @endif


                        </div>
                    </div>

                </div>
                <!--end:Row-->

            </div>

        </div>
        @endif

        

        @if ($permission == 0 )
        <div class="menu-item me-lg-1 {{ (request()->RouteIs('agent.lead')) ? 'active' : '' }}">
            <a class="menu-link py-2" href="{{ route("agent.lead") }}"><span class="menu-title">Lead List</span></a>
        </div>
        @endif

        @if ($permission == 0 )
        <div class="menu-item me-lg-1 {{ (request()->RouteIs('agent.manualCall')) ? 'active' : '' }}">
            <a class="menu-link py-2" href="{{ route("agent.manualCall") }}"><span class="menu-title">Manual Call List</span></a>
        </div>
        @endif

        @if ($permission == 0 )
        <div class="menu-item me-lg-1 {{ (request()->RouteIs('agent.callBack')) ? 'active' : '' }}">
            <a class="menu-link py-2" href="{{ route("agent.callBack") }}"><span class="menu-title">Call Back List</span></a>
        </div>
        @endif

        @if ($permission == 0 )
        <div class="menu-item me-lg-1 {{ (request()->RouteIs('agent.ptpAndPaid')) ? 'active' : '' }}">
            <a class="menu-link py-2" href="{{ route("agent.ptpAndPaid") }}"><span class="menu-title">PTP & Paid Accounts List</span></a>
        </div>
        @endif

    </div>
    <!--end::Menu-->
</div>
<!--end::Menu wrapper-->


<!--end::Menu-->

<!--end::Menu wrapper-->