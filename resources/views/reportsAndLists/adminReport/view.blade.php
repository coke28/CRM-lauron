<x-base-layout>
    <div class="card card-custom">
        <div class="card-body">
            <!--begin::Wrapper-->

            <form class="form-horizontal" method="POST" id="admin_report_form" enctype="multipart/form-data">

                <div class="portlet-body form">
                    <!-- BEGIN FORM-->

                    <div class="form-body">
                        {{-- <div class="row"> --}}
                            <!--/row-->
                            {{-- <div class="col-md-6"> --}}
                                <div class="form-group">
                                    <label class="col-form-label fw-bold fs-6">Choose Campaign</label>
                                    <input type="hidden" id="pagelevel" value="3">
                                    <select id="product_bd_list" name="product_bd_list" class="form-control">
                                        <option value="0" selected="selected">All Campaigns</option>
                                        @foreach ($clients as $client )
                                        <option value="{{ $client->clientName }}">{{ $client->clientName }}</option>

                                        @endforeach
                                        {{-- <option value="3">POSTPAID</option>
                                        <option value="4">BROADBAND</option>
                                        <option value="5">MIGRATION</option>
                                        <option value="6">POSTPAID-LUKEWARM</option>
                                        <option value="7">BROADBAND-LUKEWARM</option>
                                        <option value="8">BROADBAND-NOTSERVICEABLE</option>
                                        <option value="9">BROADBAND-ASSIST</option>
                                        <option value="10">BROADBAND-SCHEDULEREQUEST</option> --}}
                                    </select>

                                </div>
                                {{--
                            </div> --}}
                            {{--
                        </div> --}}
                    </div>
                    <div class="row" id="postpaidReportType">
                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Postpaid Report Type</label>
                                {{-- <div class="col-md-9"> --}}
                                    <div class="input-icon">
                                        {{-- <i class="fa fa-code"></i> --}}
                                        <select class="form-control" id="select_report_type" name="select_report_type">
                                            <option value="ExtractAccounts">Extract Accounts</option>
                                            <option value="ExtractLeads">Leads for Extract</option>
                                            {{-- <option value="agent_aht">Agent AHT</option> --}}
                                        </select>
                                    </div>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}
                    </div>
                    <div class="row" id="type_row" >

                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Type</label>
                                {{-- <div class="col-md-9"> --}}
                                    <select id="filtertype" name="filtertype" class="form-control">
                                        <option value="1" selected>ALL</option>
                                        <option value="2">NOT CONTACTED</option>
                                        <option value="3">CONTACTED</option>
                                    </select>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}


                    </div>
                    <div class="row" id="groupz" >
                        <!--/row-->
                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Choose Group</label>
                                {{-- <div class="col-md-9"> --}}

                                    <input type="hidden" id="pagelevel" value="3"> <select id="groupzz" name="groupzz"
                                        class="selectpicker bs-select form-control" data-live-search="true"
                                        data-size="8">
                                        <option value="">All GROUPS</option>
                                        @foreach ($groups as $group)
                                        <option value="{{ $group->groupName }}">{{ $group->groupName }} </option>
                                        @endforeach
                                
                                    </select>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="row" id="campaignname_row">

                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Campaign</label>
                                {{-- <div class="col-md-9"> --}}
                                    <select id="campaignname" name="campaignname" style=""
                                        class="selectpicker bs-select input-large form-control" data-live-search="true"
                                        data-size="8">
                                        <option value="">Choose Campaign Name</option>

                                        @foreach ($campaignIDs as $campaignID)
                                        <option value="{{ $campaignID->campaignID }}"> {{ $campaignID->campaignID }}
                                        </option>

                                        @endforeach
                                    </select>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}


                    </div>

                    <div class="row" id="date_choice">

                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Date Filter</label>
                                {{-- <div class="col-md-9"> --}}
                                    <select id="select_date_type" name="select_date_type" class="form-control">
                                        <option value="">ALL TIME</option>
                                        <option value="today">TODAY</option>
                                        <option value="date_range">DATE RANGE</option>
                                    </select>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="row" id="date_filter" style="display:none">

                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Date Filter</label>
                                {{-- <div class="col-md-9"> --}}
                                    <input class="form-control form-control-inline input-medium form_datetime"
                                        id="start_date" type="date" name="start_date" placeholder="Start Date & Time">

                                    <input class="form-control form-control-inline input-medium form_datetime"
                                        id="end_date" type="date" name="end_date" placeholder="End Date & Time">
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="row" id="day_filter" style="display:none">

                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Day Filter</label>
                                {{-- <div class="col-md-9"> --}}
                                    <select class="selectpicker form-control input-xsmall" data-live-search="true"
                                        data-size="8" id="ahtday" name="ahtday">
                                        <option value="">Select Day</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                    </select>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="row" id="week_filter" style="display:none">

                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Week Filter</label>
                                {{-- <div class="col-md-9"> --}}
                                    <select class="selectpicker form-control input-xsmall" data-live-search="true"
                                        data-size="8" id="ahtweek" name="ahtweek">
                                        <option value="">Select Week</option>
                                        @foreach ($weeks as $key => $value)
                                        <option value="{{ $value }}"> {{ $key }}</option>
                                        @endforeach
                                    </select>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="row" id="month_filter" style="display:none">

                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Month Filter</label>
                                {{-- <div class="col-md-9"> --}}
                                    <select class="selectpicker form-control input-xsmall" data-live-search="true"
                                        data-size="8" id="ahtmonth" name="ahtmonth">
                                        <option value="">Select Month</option>
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="row" id="camp_row" style="display:none">
                        {{-- <div class="col-md-6"> --}}
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Campaign ID</label>
                                {{-- <div class="col-md-9"> --}}
                                    <span id="loadcampid"></span>
                                    {{--
                                </div> --}}
                            </div>
                            {{--
                        </div> --}}
                    </div>

                    <div class="row" id="campaignagent_row" style="display:none">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Agent</label>
                                <div class="col-md-9">
                                    <select id="agentname" name="agentname" class="selectpicker bs-select form-control"
                                        data-live-search="true" data-size="8">
                                        <option value="AGENT/1014">EDELYN - FLORES / (AGENT/1014)</option>
                                        <option value="AGENT/1066">MARY JOY - REYES / (AGENT/1066)</option>
                                        <option value="AGENT/1129">ARENIA - DAVID / (AGENT/1129)</option>
                                        <option value="AGENT/1218">EMMEL NOE ARNEEL - HUYO-A / (AGENT/1218)</option>
                                        <option value="AGENT/1267">JIRAH - CUMAYAS / (AGENT/1267)</option>
                                        <option value="AGENT/1357">JON EMMANUEL - FLORES / (AGENT/1357)</option>
                                        <option value="AGENT/1049">JOSEPH - BASILAN / (AGENT/1049)</option>
                                        <option value="AGENT/1289">DENIS JUSTINE - OCUMEN / (AGENT/1289)</option>
                                        <option value="AGENT/1301">JOVEN - VOSOTROS / (AGENT/1301)</option>
                                        <option value="AGENT/1327">VECENTE JR - BARDAJE / (AGENT/1327)</option>
                                        <option value="AGENT/1362">RYAN JAY - DOMONDON / (AGENT/1362)</option>
                                        <option value="AGENT/1276">JANE SWITSHY - ABING / (AGENT/1276)</option>
                                        <option value="AGENT/1189">ANJELU SALCEDO - VINCULADO / (AGENT/1189)</option>
                                        <option value="AGENT/1373">FROILAN JR DELA FUENTE - PAIGALAN / (AGENT/1373)
                                        </option>
                                        <option value="AGENT/1266">ANGELO - MORALES / (AGENT/1266)</option>
                                        <option value="AGENT/1277">KIMBERLY KATE - ELIMEN / (AGENT/1277)</option>
                                        <option value="AGENT/1268">GERALDINE - SAYSON / (AGENT/1268)</option>
                                        <option value="AGENT/1272">JOHN ANDRIE - JACOB / (AGENT/1272)</option>
                                        <option value="AGENT/1240">MAYEEN - PEREZ / (AGENT/1240)</option>
                                        <option value="AGENT/1371">BERLIN JIN - PASCUAL / (AGENT/1371)</option>
                                        <option value="AGENT/1382">JOANA FE CENEN - ILAGAN / (AGENT/1382)</option>
                                        <option value="AGENT/1274">OSCAR - TULALIAN Jr., / (AGENT/1274)</option>
                                        <option value="AGENT/1383">JOSEPH MALASARTE - ALFANOSO / (AGENT/1383)</option>
                                        <option value="AGENT/1364">MARK ANTHONY BELTRAN - LICANDA / (AGENT/1364)
                                        </option>
                                        <option value="AGENT/1359">EMALYN - CABALING / (AGENT/1359)</option>
                                        <option value="AGENT/1283">PAULO - TOLENTINO / (AGENT/1283)</option>
                                        <option value="AGENT/1086">JEAN - ALARBA / (AGENT/1086)</option>
                                        <option value="AGENT/1270">CAMELA - SALANGUIT / (AGENT/1270)</option>
                                        <option value="AGENT/1334">SHERRY MAE ANTONETTE SIERRA - NON / (AGENT/1334)
                                        </option>
                                        <option value="AGENT/1355">KENNETH - BABOR / (AGENT/1355)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="campaignverifier_row" style="display:none">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label fw-bold fs-6">Agent</label>
                                <div class="col-md-9">
                                    <select id="verifiername" name="verifiername"
                                        class="selectpicker bs-select form-control" data-live-search="true"
                                        data-size="8"></select>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-actions" id="submit_row">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <div class="separator my-10"></div>
                                        <button type="submit" id="btn-submit" class="btn btn-primary font-weight-bold"
                                            data-style="expand-right" disabled>
                                            <span class="ladda-label">
                                                <i class="icon-arrow-right"></i>Submit</span>
                                            <span class="ladda-spinner"></span>
                                        </button>
                                        <span id="error"></span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>
                    </div>


                    <div id="div-report-type">
                    </div>

                    <div id="div-user">
                    </div>

                    <div id="reports-tbl" class="row">
                    </div>
                    <!-- END FORM-->
                </div>
        </div>
        </form>
        <!--end::Wrapper-->
        <!--begin::Datatable-->

        <!--end::Datatable-->
    </div>
    </div>
    </div>

    <!--start::Include your modals here-->
    {{-- @include('admintools/manageApplicationType/modals/addApplicationType') --}}


    <!--start::Include your scripts here-->
    @section('scripts')
    <script type="text/javascript" src="{{ " /".'custom\adminReport\adminReportForm.js?v='. rvndev()->getRandom(30) }}"></script>
    <script type="text/javascript" src="{{ " /".'custom\adminReport\adminReportValidation.js?v='. rvndev()->getRandom(30) }}"></script>

    @endsection

    <!--start::Include your styles here-->
    @section('styles')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
    </style>
    @endsection



</x-base-layout>