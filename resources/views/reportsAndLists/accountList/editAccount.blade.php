<x-base-layout>
    @section('styles')
    <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        .ldCallBtn{
          padding:20px;
          font-size:20px;

          transition: background-color 1s, width 1s;
        }
    </style>
    @endsection
    <button type="button" data-callnumber="{{ $account->mobileNumber }}" id="startCallBtn" class="btn btn-lg btn-block btn-success w-100 ldCallBtn">
      <i class="bi bi-telephone" style="font-size:20px"></i>
      <span id="callBtnDesc">Start Call</span>
    </button>

    <div class="card card-custom mt-5">
        <div class="card-body">
            {{-- begin:: Navigation --}}
            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active fw-bolder text-primary" data-bs-toggle="tab" href="#history_tab">
                        History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bolder text-primary" data-bs-toggle="tab" href="#accountDetail_tab">Account
                        Details</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-bolder text-primary" data-bs-toggle="tab" href="#status_tab">Status
                    </a>
                </li>
                @if ($level == 2 || $level == 1)
                <li class="nav-item">
                    <a class="nav-link fw-bolder text-danger" id="exitAccount" href="">Exit
                    </a>
                </li>
                @endif

                @if ($level == 0)
                <li class="nav-item">
                    <a class="nav-link fw-bolder text-danger" id="exitAccountAgent" href="">Exit
                    </a>
                </li>
                @endif
            </ul>
            {{-- end:: Navigation --}}

            <div class="tab-content" id="productsTabContent">
                <div class="tab-pane fade show active" id="history_tab" role="tabpanel">
                    <!--begin::Wrapper-->
                    <div class="d-flex justify-content-end mb-5">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-absolute my-1 mb-2 mb-md-0">
                            <div class="input-group input-group-solid">
                                <span class="svg-icon svg-icon-1 input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="historySearch"
                                    class="form-control form-control-lg form-control-solid" placeholder="Search">
                                <button class="input-group-text clearInp">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>
                        </div>
                        <!--end::Search-->

                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">

                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Wrapper-->
                    
                    <!--begin::Datatable-->
                    <table id="history_dt" class="table align-middle table-row-bordered fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                <th>ID</th>
                                <th>Action</th>
                                <th>Agent</th>
                                <th>Full Name</th>
                                <th>Date</th>
                                <th>Mobile Number</th>
                                <th>Campaign Status</th>
                                <th>Campaign ID</th>
                                <th>Campaign Name</th>
                                <th>IP Address</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody class="text-black-600 fw-bold">
                        </tbody>
                    </table>
                    <!--end::Datatable-->
                </div>
                {{-- begin:: Status Form  --}}
                <div class="tab-pane fade" id="status_tab" role="tabpanel">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack mb-5">
                        <!--begin::Search-->

                        <!--end::Search-->

                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-docs-table-toolbar="base">

                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Wrapper-->
                    <form class="form" id="edit_status_form">
                        {{-- <input type ="hidden" name="action" value="{{ $action }}"> --}}
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="hidden" name="leadID" id="leadID" value="{{ $leadID }}">
                                        <input type="hidden" name="fromVerified" id="fromVerified" value="{{ $fromVerified }}">
                                        <label for="agentName" class="col-form-label fw-bold fs-6">Agent Name</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level !=0)
                                                <select class="form-select form-select-sm form-select-solid"
                                                name="agentName">
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->agentNum }}" {{ ( $agent->agentNum == $user->agentNum) ? 'selected' : '' }}> {{ $user->first_name." ". "$user->middle_name" ." ". $user->last_name}} </option>
                                                    @endforeach
                                                </select>
                                            @else
                                            <p>{{ " $agent->first_name"." ". "$agent->middle_name" ." ". "$agent->last_name" }}</p>
                                                <input type="hidden" name="agentName"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Agent Name" value="{{ " $agent->first_name"." ". "$agent->middle_name" ." ". "$agent->last_name" }}" readonly>
                                                
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="campaignName" class="col-form-label fw-bold fs-6">Campaign Name
                                        </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="campaignName"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Campaign Name" value="{{ $account->campaignName }}" readonly>
                                            @else
                                            <p>{{ $account->campaignName }}</p>
                                                <input type="hidden" name="campaignName"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Campaign Name" value="{{ $account->campaignName }}" readonly>
                                                
                                            @endif
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="campaignID" class="col-form-label fw-bold fs-6">Campaign ID</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="campaignID"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Campaign ID" value="{{ $account->campaignID }}" readonly>
                                            @else
                                            <p>{{ $account->campaignID }}</p>
                                                <input type="hidden" name="campaignID"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Campaign ID" value="{{ $account->campaignID }}" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="leadStatus" class="col-form-label fw-bold fs-6">Status</label>
                                        <div class="col-lg-12 fv-row" id="leadStatus">
                                            <select class="form-select form-select-sm form-select-solid"
                                                name="leadStatus" id="select_lead_status">
                                                <option value="">{{ "Select A Status" }}</option>
                                                @foreach ( $status as $status )
                                                <option value="{{ $status->statusName }}">{{ $status->statusName }}
                                                </option>
                                                @endforeach
                                            </select>

                                            {{-- <select class="form-select-sm form-select-solid" data-control="select2" data-placeholder="Select an option" name="leadStatus">
                                                <option value="">{{ "Select A Status" }}</option>
                                                @foreach ( $status as $status )
                                                <option value="{{ $status->statusName }}">{{ $status->statusName }}
                                                </option>
                                                @endforeach
                                            </select> --}}
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        
                        {{-- STATUS : PTP Additional Fields --}}
                        <div class="row mb-4" id="ptp_fields" style="display: none">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="ptpAmount" class="col-form-label fw-bold fs-6">Amount to Pay</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="text" name="ptpAmount" id="ptpAmount"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Amount Due">
                                        </div>
                                    </div>
    
                                    <div class="col-lg-6">
                                        <label for="ptpDate" class="col-form-label fw-bold fs-6">Due Date</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="date" name="ptpDate" id="ptpDate"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Due Date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- STATUS : CB Additional Field --}}
                        <div class="row mb-4" id="cb_field" style="display: none">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="callbackDate" class="col-form-label fw-bolder fs-6">Callback Date</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="datetime-local" name="callbackDate" id="callbackDate"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Callback Date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4" id="rfd_field" style="display: none">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="reason" class="col-form-label fw-bolder fs-6">Reason for
                                            Denial</label>
                                        <div class="col-lg-12 fv-row">
                                            <select name="reason" id="reason"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Reason Why Application Did Not Proceed To Online">
                                                <option value="">{{ "Select An RFD" }}</option>
                                                @foreach ($reasonForDenials as $reasonForDenial)
                                                <option value="{{ $reasonForDenial->statusName }}"> {{ $reasonForDenial->statusName }} </option>
                                                 @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="dateStamp" class="col-form-label fw-bold fs-6">Date Stamp</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="dateStamp"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Date Stamp" value="{{ $currentDatestamp }}" readonly>
                                            @else
                                            <p>{{ $currentDatestamp }}</p>
                                                <input type="hidden" name="dateStamp"
                                                class="form-control form-control-sm form-control-solid"
                                                placeholder="Date Stamp" value="{{ $currentDatestamp }}" readonly>
                                            @endif
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <label for="remarkStatus" class="col-form-label fw-bold fs-6">Remark for Status</label>
                                <div class="col-lg-12 fv-row">
                                    <textarea style="height:75px;resize:none;" name="remarkStatus"
                                        class="form-control form-control-sm form-control-solid"
                                        placeholder="{{ $account->remarkStatus }}">{{ $account->remarkStatus }}</textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="note" class="col-form-label fw-bold fs-6">Notes</label>
                                <div class="col-lg-12 fv-row">
                                    <textarea style="height:75px;resize:none;" name="note"
                                        class="form-control form-control-sm form-control-solid"
                                        placeholder="{{ $account->notes }}">{{ $account->notes }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="submit" id="editAccountSubmitBtn"
                                class="btn btn-primary font-weight-bold">Save</button>
                        </div>
                        {{--
                    </form> --}}

                </div>
                {{-- end:: Status Form  --}}
                <div class="tab-pane fade " id="accountDetail_tab" role="tabpanel">
                    <form class="form" id="edit_accountDetail_form">
                        <div class="row mb-6">

                            <div class="col-6">
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <p class="text-primary fs-2 fw-bold mb-0">Account Details</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="segment" class="col-form-label fw-bolder fs-6">Segment</label>
                                        <div class="col-lg-12 fv-row" id="segment">
                                            @if ($level != 0)
                                                <select class="form-select form-select-sm form-select-solid"
                                                    name="segment">
                                                    @foreach ($segments as $segment)
                                                        <option value="{{ $segment->statusName }}" {{ ( $segment->statusName == $account->segment) ? 'selected' : '' }}> {{ $segment->statusName }} </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <div>
                                                    <p class="mb-0">{{ $account -> segment }}</p>
                                                    
                                                </div>
                                                <select hidden class="form-select form-select-sm form-select-solid"
                                                name="segment">
                                                    @foreach ($segments as $segment)
                                                        <option value="{{ $segment->statusName }}" {{ ( $segment->statusName == $account->segment) ? 'selected' : '' }}> {{ $segment->statusName }} </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="accountNumber" class="col-form-label fw-bolder fs-6">Account Number</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="accountNumber"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->accountNumber }}" 
                                                placeholder="Account Number">
                                            @else
                                                <div>
                                                    <p class="mb-0">{{ $account->accountNumber }}</p>
                                                    
                                                </div>
                                                <input type="hidden" name="accountNumber"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->accountNumber }}" 
                                                placeholder="Account Number">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="collectionEffort" class="col-form-label fw-bolder fs-6">Collection Effort</label>
                                        <div class="col-lg-12 fv-row" id="collectionEffort">
                                            {{-- @if ($level != 0) --}}
                                                <select class="form-select form-select-sm form-select-solid"
                                                    name="collectionEffort">
                                                    @foreach ($collectionEfforts as $collectionEffort)
                                                        <option value="{{ $collectionEffort->statusName }}" {{ ( $collectionEffort->statusName == $account->collectionEffort) ? 'selected' : '' }}> {{ $collectionEffort->statusName }} </option>
                                                    @endforeach
                                                </select>
                                            {{-- @else
                                                <p>{{ $account -> collectionEffort }}</p>
                                                <select hidden class="form-select form-select-sm form-select-solid"
                                                    name="collectionEffort">
                                                    @foreach ($collectionEfforts as $collectionEffort)
                                                        <option value="{{ $collectionEffort->statusName }}" {{ ( $collectionEffort->statusName == $account->collectionEffort) ? 'selected' : '' }}> {{ $collectionEffort->statusName }} </option>
                                                    @endforeach
                                                </select>
                                            @endif --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="transaction" class="col-form-label fw-bolder fs-6">Transaction</label>
                                        <div class="col-lg-12 fv-row" id="transaction">
                                            {{-- @if ($level != 0) --}}
                                                <select class="form-select form-select-sm form-select-solid" name="transaction">
                                                    @foreach ($transactions as $transaction)
                                                        <option value="{{ $transaction->statusName }}" {{ ( $transaction->statusName == $account->transaction) ? 'selected' : '' }}> {{ $transaction->statusName }} </option>
                                                    @endforeach
                                                </select>
                                            {{-- @else
                                            <p>{{ $account -> transaction}}</p>
                                                <select hidden class="form-select form-select-sm form-select-solid" name="transaction">
                                                    @foreach ($transactions as $transaction)
                                                        <option value="{{ $transaction->statusName }}" {{ ( $transaction->statusName == $account->transaction) ? 'selected' : '' }}> {{ $transaction->statusName }} </option>
                                                    @endforeach
                                                </select>
                                            @endif --}}
                                        </div>
                                    </div>
                                  
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                     <label for="placeOfContact" class="col-form-label fw-bolder fs-6">Place Of Contact</label>
                                     <div class="col-lg-12 fv-row" id="placeOfContact">
                                         {{-- @if ($level !=0) --}}
                                             <select class="form-select form-select-sm form-select-solid"
                                                 name="placeOfContact">
                                                 @foreach ($placeOfContacts as $placeOfContact)
                                                     <option value="{{ $placeOfContact->statusName }}" {{ ( $placeOfContact->statusName == $account->placeOfContact) ? 'selected' : '' }}> {{ $placeOfContact->statusName }} </option>
                                                 @endforeach
                                             </select>
                                         {{-- @else
                                         <p>{{ $account -> placeOfContact}}</p>
                                             <select hidden class="form-select form-select-sm form-select-solid"
                                                 name="placeOfContact">
                                                 @foreach ($placeOfContacts as $placeOfContact)
                                                     <option value="{{ $placeOfContact->statusName }}" {{ ( $placeOfContact->statusName == $account->placeOfContact) ? 'selected' : '' }}> {{ $placeOfContact->statusName }} </option>
                                                 @endforeach
                                             </select>
                                         @endif --}}
                                         
                                     </div>
                                 </div>
                                 <div class="col-lg-6">
                                     <label for="pointOfContact" class="col-form-label fw-bolder fs-6">Point Of Contact</label>
                                     <div class="col-lg-12 fv-row" id="pointOfContact">
                                         {{-- @if ($level != 0) --}}
                                             <select class="form-select form-select-sm form-select-solid" name="pointOfContact">
                                                 @foreach ($pointOfContacts as $pointOfContact)
                                                     <option value="{{ $pointOfContact->statusName }}" {{ ( $pointOfContact->statusName == $account->pointOfContact) ? 'selected' : '' }}> {{ $pointOfContact->statusName }} </option>
                                                 @endforeach
                                             </select>
                                         {{-- @else
                                         <p>{{ $account -> pointOfContact}}</p>
                                             <select hidden class="form-select form-select-sm form-select-solid" name="pointOfContact">
                                                 @foreach ($pointOfContacts as $pointOfContact)
                                                     <option value="{{ $pointOfContact->statusName }}" {{ ( $pointOfContact->statusName == $account->pointOfContact) ? 'selected' : '' }}> {{ $pointOfContact->statusName }} </option>
                                                 @endforeach
                                             </select>
                                         @endif --}}
                                     </div>
                                 </div>
                              
                             </div>
                                
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="endoDate" class="col-form-label fw-bolder fs-6">Endo Date </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="endoDate"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->endoDate }}" placeholder="">
                                            @else 
                                            <p>{{ $account -> endoDate}}</p>
                                                <input type="hidden" name="endoDate"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->endoDate }}" placeholder="">
                                            @endif
                                        </div>
                                    </div>
                               
                                    <div class="col-lg-3">
                                        <label for="pullOutDate" class="col-form-label fw-bolder fs-6">Pull Out Date</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level !=0)
                                                <input type="text" name="pullOutDate" 
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->pullOutDate }}" placeholder="">
                                            @else 
                                            <p>{{ $account -> pullOutDate}}</p>
                                                <input type="hidden" name="pullOutDate" 
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->pullOutDate }}" placeholder="">
                                            @endif
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-3">
                                        <label for="writeOffDate" class="col-form-label fw-bolder fs-6"> Write Off Date</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level !=0)
                                                <input type="text" name="writeOffDate"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->writeOffDate }}" placeholder="">
                                            @else 
                                            <p>{{ $account -> writeOffDate}}</p>
                                                <input type="hidden" name="writeOffDate"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->writeOffDate }}" placeholder="">
                                            @endif
                                        </div>
                                    </div>
                                
                                    <div class="col-lg-3">
                                        <label for="activationDate" class="col-form-label fw-bolder fs-6"> Activation Date</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="activationDate"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->activationDate }}" placeholder="">
                                            @else
                                            <p>{{ $account -> activationDate}}</p>
                                                <input type="hidden" name="activationDate"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->activationDate }}" placeholder="">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="originalBalance" class="col-form-label fw-bolder fs-6"> 
                                            Original Balance
                                        </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level !=0)
                                                <input type="text" name="originalBalance"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->originalBalance }}" >
                                            @else 
                                            <p>{{ $account -> originalBalance}}</p>
                                                <input type="hidden" name="originalBalance"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->originalBalance }}" >
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="principalBalance" class="col-form-label fw-bolder fs-6">
                                            Principal Balance
                                        </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="principalBalance"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->principalBalance }}">
                                            @else
                                            <p>{{ $account -> principalBalance}}</p>
                                                <input type="hidden" name="principalBalance"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->principalBalance }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="penalties" class="col-form-label fw-bolder fs-6">
                                            Penalties For Account
                                        </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level !=0)
                                                <input type="text" name="penalties"
                                                class="form-control form-control-sm form-control-solid"value ="{{ $account->penalties }}">
                                            @else
                                            <p>{{ $account -> penalties}}</p>
                                                <input type="hidden" name="penalties"
                                                class="form-control form-control-sm form-control-solid"value ="{{ $account->penalties }}">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="totalAmountDue" class="col-form-label fw-bolder fs-6">
                                            Total Amount Due
                                        </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level !=0)
                                                <input type="text" name="totalAmountDue"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->totalAmountDue }}">
                                            @else
                                            <p>{{ $account -> totalAmountDue}}</p>
                                                <input type="hidden" name="totalAmountDue"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->totalAmountDue }}">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="lastPaymentDate" class="col-form-label fw-bolder fs-6"> 
                                            Last Payment Date
                                        </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level !=0)
                                                <input type="text" name="lastPaymentDate"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->lastPaymentDate }}" >
                                            @else
                                            <p>{{ $account -> lastPaymentDate}}</p>
                                                <input type="hidden" name="lastPaymentDate"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->lastPaymentDate }}" >
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="lastPaymentAmount" class="col-form-label fw-bolder fs-6">
                                            Last Payment Amount
                                        </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="lastPaymentAmount"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->lastPaymentAmount }}">
                                            @else
                                            <p>{{ $account ->lastPaymentAmount}}</p>
                                                <input type="hidden" name="lastPaymentAmount"
                                                class="form-control form-control-sm form-control-solid" value ="{{ $account->lastPaymentAmount }}">  
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            <hr>
                            <div class="row mb-1">
                                <div class="col-lg-12">
                                    <p class="text-primary fs-2 fw-bolder mb-0">Addressess</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <label for="homeAddress" class="col-form-label fw-bolder fs-6">Home 
                                    Address</label>
                                <div class="col-lg-12 fv-row">
                                    @if ($level !=0)
                                        <textarea style="height:75px;resize:none;" name="homeAddress"
                                        class="form-control form-control-sm form-control-solid"
                                        value ="{{ $account->homeAddress }}"  placeholder="">{{ $account->homeAddress }}</textarea>
                                    @else
                                    <p>{{ $account->homeAddress }}</p>
                                        <textarea hidden style="height:75px;resize:none;" name="homeAddress"
                                        class="form-control form-control-sm form-control-solid"
                                        value ="{{ $account->homeAddress }}"  placeholder="">{{ $account->homeAddress }}</textarea>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="companyName" class="col-form-label fw-bolder fs-6">Company
                                        Name</label>
                                    <div class="col-lg-12 fv-row">
                                        @if ($level !=0)
                                            <input type="text" name="companyName"
                                            class="form-control form-control-sm form-control-solid"
                                            value ="{{ $account->companyName }}" placeholder="Company Name">
                                        @else 
                                        <p>{{ $account->companyName }}</p>
                                            <input type="hidden" name="companyName"
                                            class="form-control form-control-sm form-control-solid"
                                            value ="{{ $account->companyName }}" placeholder="Company Name">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label for="companyAddress" class="col-form-label fw-bolder fs-6">Office 
                                    Address /  Business Address</label>
                                <div class="col-lg-12 fv-row">
                                    @if ($level != 0)
                                        <textarea style="height:75px;resize:none;" name="companyAddress"
                                        class="form-control form-control-sm form-control-solid"
                                        value ="{{ $account->CEAddressBusinessAddress }}"  placeholder="">{{ $account->CEAddressBusinessAddress }}</textarea>
                                    @else
                                    <p>{{ $account->CEAddressBusinessAddress }}</p>
                                        <textarea hidden style="height:75px;resize:none;" name="companyAddress"
                                        class="form-control form-control-sm form-control-solid"
                                        value ="{{ $account->CEAddressBusinessAddress }}"  placeholder="">{{ $account->CEAddressBusinessAddress }}</textarea>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            </div>
                            <div class="col-6">
                                
                                <div class="row mb-2">
                                    <div class="col-lg-12">
                                        <p class="text-primary fs-2 fw-bolder mb-0"> Debtor Details</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="firstName" class="col-form-label fw-bolder fs-6"> First Name </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                            <input type="text" name="firstName"
                                            class="form-control form-control-sm form-control-solid"
                                            value ="{{ $account->firstname }}" placeholder="First Name"> 

                                            @else
                                            <p>{{ $account->firstname }}</p>
                                            <input type="hidden" name="firstName"
                                            class="form-control form-control-sm form-control-solid"
                                            value ="{{ $account->firstname }}" placeholder="First Name">  
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="middleName" class="col-form-label fw-bolder fs-6"> Middle Name</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="middleName"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->middlename }}" placeholder="Middle Name">
                                            @else
                                            <p>{{ $account->middlename }}</p>
                                                <input type="hidden" name="middleName"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->middlename }}" placeholder="Middle Name">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="lastName" class="col-form-label fw-bolder fs-6"> Last Name </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="lastName"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->lastname }}" placeholder="Last Name">
                                            @else
                                            <p>{{ $account->lastname }}</p> 
                                                <input type="hidden" name="lastName"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->lastname }}" placeholder="Last Name">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="dateOfBirth" class="col-form-label fw-bolder fs-6"> Date of Birth </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="dateOfBirth"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->dateOfBirth }}" placeholder="Date of Birth">
                                            @else
                                            <p>{{ $account->dateOfBirth }}</p>
                                                <input type="hidden" name="dateOfBirth"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->dateOfBirth }}" placeholder="Date of Birth">
                                            @endif
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <label for="civilStatus" class="col-form-label fw-bolder fs-6"> Civil Status </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="civilStatus"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->civilStatus }}" placeholder="Civil Status">
                                            @else
                                            <p>{{ $account->civilStatus }}</p>
                                                <input type="hidden" name="civilStatus"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->civilStatus }}" placeholder="Civil Status"> 
                                                
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="email" class="col-form-label fw-bolder fs-6">Email Address</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="email"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->emailAddress }}" placeholder="ex. johndoe@hotmail.com">
                                            @else
                                            <p>{{ $account->emailAddress }}</p>
                                                <input type="hidden" name="email"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->emailAddress }}" placeholder="ex. johndoe@hotmail.com">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- NUMBERS --}}
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="mobileNumber" class="col-form-label fw-bolder fs-6">Cellphone Number</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level !=0)
                                                <input type="text" name="mobileNumber"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->mobileNumber }}" placeholder="Cellphone">
                                            @else
                                            <p>{{ $account->mobileNumber }}</p>
                                                <input type="hidden" name="mobileNumber"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->mobileNumber }}" placeholder="Cellphone">
                                            @endif
                                        </div>
                                    </div>
                                    <input type="hidden" name="existingMobileNumber" value="{{ $account->mobileNumber }}">
                                    <div class="col-lg-4">
                                        <label for="homeNumber" class="col-form-label fw-bolder fs-6">Home Number</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="homeNumber"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->homeNumber }}" placeholder="Home Number">
                                            @else
                                            <p>{{ $account->homeNumber }}</p>
                                                <input type="hidden" name="homeNumber"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->homeNumber }}" placeholder="Home Number">
                                            @endif
                                        </div>

                                    </div>
                                    <div class="col-lg-4">
                                        <label for="officeNumber" class="col-form-label fw-bolder fs-6">Office Number</label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="officeNumber"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->officeNumber }}" placeholder="Office Number">
                                            @else
                                            <p>{{ $account->officeNumber }}</p>
                                                <input type="hidden" name="officeNumber"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->officeNumber }}" placeholder="Office Number">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="otherContact1" class="col-form-label fw-bolder fs-6">Other Contact 1</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="text" name="otherContact1"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->otherContact1 }}" placeholder="Other Contact 1">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="otherContact2" class="col-form-label fw-bolder fs-6">Other Contact 2</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="text" name="otherContact2"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->otherContact2 }}" placeholder="Other Contact 2">
                                        </div>

                                    </div>
                                    <div class="col-lg-4" >
                                        <label for="otherContact3" class="col-form-label fw-bolder fs-6">Other Contact 3</label>
                                        <div class="col-lg-12 fv-row">
                                            <input type="text" name="otherContact3"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->otherContact3 }}" placeholder="Other Contact 3">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="autoloanCarInfo" class="col-form-label fw-bolder fs-6">Autoloan / 
                                            Car Info
                                        </label>
                                        <div class="col-lg-12 fv-row">
                                            @if ($level != 0)
                                                <input type="text" name="autoloanCarInfo"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->autoloanCarInfo }}" placeholder="Company Name">
                                            @else
                                            <p>{{ $account->autoloanCarInfo }}</p>
                                                <input type="hidden" name="autoloanCarInfo"
                                                class="form-control form-control-sm form-control-solid"
                                                value ="{{ $account->autoloanCarInfo }}" placeholder="Company Name">   
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                
                                {{-- Addresses :: Right Column --}}
                                <div class="row" >
                                    <label for="otherAddress1" class="col-form-label fw-bolder fs-6">Other Address 1</label>
                                    <div class="col-lg-12 fv-row">
                                        @if ($level != 0)
                                            <textarea style="height:88px;resize:none;" name="otherAddress1"
                                            class="form-control form-control-sm form-control-solid"
                                            value ="{{ $account->otherAddress1 }}" placeholder="">{{ $account->otherAddress1 }} </textarea>
                                        @else
                                        <p>{{ $account->otherAddress1 }}</p>
                                            <textarea hidden style="height:88px;resize:none;" name="otherAddress1"
                                            class="form-control form-control-sm form-control-solid"
                                            value ="{{ $account->otherAddress1 }}" placeholder="">{{ $account->otherAddress1 }} </textarea>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="otherAddress2" class="col-form-label fw-bolder fs-6">Other Address 2</label>
                                    <div class="col-lg-12 fv-row">
                                        @if ($level != 0)
                                            <textarea style="height:88px;resize:none;" name="otherAddress2"
                                            class="form-control form-control-sm form-control-solid"
                                            value ="{{ $account->otherAddress2 }}" placeholder="">{{ $account->otherAddress2 }}</textarea>
                                        @else
                                        <p>{{ $account->otherAddress2 }}</p>
                                        <textarea hidden style="height:88px;resize:none;" name="otherAddress2"
                                        class="form-control form-control-sm form-control-solid"
                                        value ="{{ $account->otherAddress2 }}" placeholder="">{{ $account->otherAddress2 }}</textarea>
                                            
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="col-lg-12">
                                            <label for="area" class="col-form-label fw-bolder fs-6">Area</label>
                                            <div class="col-lg-12 fv-row" id="area">
                                                @if ($level != 0)
                                                    <select class="form-select form-select-sm form-select-solid"
                                                        name="area">
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->statusName }}" {{ ( $area->statusName == $account->area) ? 'selected' : '' }}> {{ $area->statusName }} </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                <p>{{ $account->otherAddress2 }}</p>
                                                    <select hidden class="form-select form-select-sm form-select-solid"
                                                        name="area">
                                                        @foreach ($areas as $area)
                                                            <option value="{{ $area->statusName }}" {{ ( $area->statusName == $account->area) ? 'selected' : '' }}> {{ $area->statusName }} </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <hr class="mt-5">
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="row mb-1 mt-1">
                                        <div class="col-lg-12">
                                            <p class="text-primary fs-2 fw-bolder mb-0">Other Details</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="motherMaidenName" class="col-form-label fw-bolder fs-6">Mother's Maiden Name</label>
                                            <div class="col-lg-12 fv-row">
                                                @if ($level != 0)
                                                    <input type="text" name="motherMaidenName"
                                                    class="form-control form-control-sm form-control-solid"
                                                    value ="{{ $account->motherMaidenname }}" placeholder="">
                                                @else
                                                <p>{{ $account->motherMaidenname }}</p>
                                                    <input type="hidden" name="motherMaidenName"
                                                    class="form-control form-control-sm form-control-solid"
                                                    value ="{{ $account->motherMaidenname }}" placeholder="">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">

                            {{-- <button type="submit" id="editAccountSubmitBtn"
                                class="btn btn-primary font-weight-bolder">Edit</button> --}}
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    </div>
    </div>




    <!--start::Include your modals here-->


    <!--start::Include your scripts here-->
    @section('scripts')

    <script type="text/javascript" src="{{ "/".'custom/accountListLauron/editLauronAccount.js?v=' . rvndev()->getRandom(30)}}"></script>
    <script type="text/javascript" src="{{ "/".'custom/lead/accountCallHistory.js?v=' . rvndev()->getRandom(30)}}"></script>
    <script type="text/javascript" src="{{ "/".'custom/accountListLauron/accountExitConfirmation.js?v=' . rvndev()->getRandom(30)}}"></script>
    <script type="text/javascript" src="{{ "/".'custom/lead/voicecall.js?v=' . rvndev()->getRandom(30)}}"></script>



    @endsection

    <!--start::Include your styles here-->
    {{-- @section('styles') <style>
        .dataTables_wrapper .dataTables_filter {
            display: none;
        }
    </style>
    @endsection --}}


</x-base-layout>