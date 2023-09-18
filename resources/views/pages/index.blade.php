<x-base-layout>
  <div class="card-group card-group-gap-5">
  {{-- <div class="row gy-5 g-xl-8"> --}}
    {{-- System Overview --}}
    {{-- <div class="col-6"> --}}
      <div class="card card-custom m-5 gutter-b rounded-card">
        <div class="card-header">
          <div class="card-title">
            <h2 class="card-label">
              System Overview 
            </h2>
          </div>
        </div>
  
        <div class="card-body">
          <div class="card-group card-group-gap-4 d-flex align-items-center justify-content-center">
            <div class="dashboardCard m-5">
                {{-- <img src="{{ asset('demo1/media/misc/dashboardCardImage.png') }}" class="card-img-top" alt="..."> --}}
                <div class="backgroundImage" 
                style="background: linear-gradient(rgba(232, 128, 93, 0.7), rgba(232, 128, 93, 0.7)), 
                url('{{ asset('demo1/media/misc/dashboardCardImage.png') }}') no-repeat center center/cover;
                border-radius: 7px 7px 0 0;">
                  <i class="fa fa-headset" style="font-size: 50px; color: #fff; padding: 30px;"></i>
                </div>
                
                <div class="card-footer">
                  <h3 class="card-title">Agents Online</h3>
                  <p class="card-text">
                    <span class="text-primary" id="agentsOnline">{{ (empty($usersOnlineCount) ? 0 : $usersOnlineCount)  }} Agents Online</span>
                  </p>
                </div>
            </div>

            <div class="dashboardCard m-5 ">
              <div class="backgroundImage" 
              style="background: linear-gradient(rgba(135, 188, 122, 0.7), rgba(135, 188, 122, 0.7)), 
              url('{{ asset('demo1/media/misc/dashboardCardImage.png') }}') no-repeat center center/cover;
              border-radius: 7px 7px 0 0;">
                <i class="fa fa-bullhorn" style="font-size: 50px; color: #fff; padding: 30px;"></i>
              </div>
                <div class="card-footer">
                  <h3 class="card-title">Campaigns Running</h3>
                  <p class="card-text"> 
                    <span class="text-primary" id="campaignsRunning">{{ $campaignUploadsCount }} Campaigns Running</span>
                  </p>
                  {{-- <a href="#" class="btn btn-primary">Go somewhere</a>  --}}
                </div>
            </div>
          </div>
          <div class="card-group card-group-gap-5 d-flex align-items-center justify-content-center">
            <div class="dashboardCard m-5">
              <div class="backgroundImage" 
              style="background: linear-gradient(rgba(105, 93, 232, 0.7), rgba(105, 93, 232, 0.7)), 
              url('{{ asset('demo1/media/misc/dashboardCardImage.png') }}') no-repeat center center/cover;
              border-radius: 7px 7px 0 0;">
                <i class="fa fa-phone-volume" style="font-size: 50px; color: #fff; padding: 30px;
                transform: rotate(320deg);"></i>
              </div>
                <div class="card-footer">
                  <h3 class="card-title">Agents on Call</h3>
                  <p class="card-text">
                    <span class="text-primary" id="agentsOnCall">{{ $usersOnCallCount }} Agents On Call</span>
                  </p>
                  {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                </div>
            </div>
          </div>
        </div>
      </div>
    {{-- </div> --}}
    {{-- Selected Campaign Overview --}}
      <div class="card card-custom m-5 gutter-b">
        <div class="card-header">
          <div class="card-title">
            <h2 class="card-label">
              Select A Campagn to Preview
            </h2>
          </div>

          <div class="card-toolbar">
            <select id="statusSelect" class="form-select rounded-start-0" data-control="select" data-placeholder="Select an option">
              <option value="">{{"Select A Campaign"}}</option>
              @foreach ($campaignUploads as $campaignUpload)
                <option value="{{$campaignUpload->campaignID}}">{{$campaignUpload->campaignID}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="card-body">
          <div class="card-group card-group-gap-5 d-flex align-items-center justify-content-center">
            <div class="dashboardCard m-5">
              <div class="backgroundImage" 
                style="background: linear-gradient(rgba(232, 223, 93, 0.7), rgba(232, 223, 93, 0.7)), 
                url('{{ asset('demo1/media/misc/dashboardCardImage.png') }}') no-repeat center center/cover;
                border-radius: 7px 7px 0 0;">
                  <i class="fa fa-user-tag" style="font-size: 50px; color: #fff; padding: 30px;"></i>
                </div>
              <div class="card-footer">
                <h3 class="card-title">Total Leads</h3>
                <p class="card-text">
                
                  <span class="text-primary" id="totalLeads"></span>
                </p>
                {{-- <a href="#" class="btn btn-primary">Go somewhere</a>  --}}
              </div>
            </div>

            <div class="dashboardCard m-5">
              <div class="backgroundImage" 
              style="background: linear-gradient(rgba(224, 70, 70, 0.7), rgba(224, 70, 70, 0.7)), 
              url('{{ asset('demo1/media/misc/dashboardCardImage.png') }}') no-repeat center center/cover;
              border-radius: 7px 7px 0 0;">
                <i class="fa fa-phone" style="font-size: 50px; color: #fff; padding: 30px;"></i>
              </div>
                <div class="card-footer">
                  <h3 class="card-title">Calls</h3>
                  <p class="card-text">
                    <span class="text-primary" id="called"></span>
                  </p>
                  {{-- <a href="#" class="btn btn-primary">Go somewhere</a>  --}}
                </div>
            </div>
          </div>

          <div class="card-group card-group-gap-5 d-flex align-items-center justify-content-center">
            <div class="dashboardCard m-5">
              <div class="backgroundImage" 
              style="background: linear-gradient(rgba(93, 232, 204, 0.7), rgba(93, 232, 204, 0.7)), 
              url('{{ asset('demo1/media/misc/dashboardCardImage.png') }}') no-repeat center center/cover;
              border-radius: 7px 7px 0 0;">
                <i class="fa fa-phone-slash" style="font-size: 50px; color: #fff; padding: 30px;"></i>
              </div>
                <div class="card-footer">
                  <h3 class="card-title">Not Called</h3>
                  <p class="card-text">
                    <span class="text-primary" id="notCalled"></span>
                  </p>
                  {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                </div>
            </div>

            <div class="dashboardCard m-5">
              <div class="backgroundImage" 
              style="background: linear-gradient(rgba(224, 107, 210, 0.7), rgba(224, 107, 210, 0.7)), 
              url('{{ asset('demo1/media/misc/dashboardCardImage.png') }}') no-repeat center center/cover;
              border-radius: 7px 7px 0 0;">
                <i class="fa fa-user-check" style="font-size: 50px; color: #fff; padding: 30px;"></i>
              </div>
                <div class="card-footer">
                  <h3 class="card-title">Contact %</h3>
                  <p class="card-text">
                    <span class="text-primary" id="percentageContacted"></span>
                  </p>
                  {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                </div>
            </div>
          </div>
        </div>
       </div>
    {{-- </div> --}}
  {{-- </div> --}}
</div>
    

    <!--start::Include your modals here-->
  
    @section('scripts')
      <script type="text/javascript" src="{{ "/".'custom/campaignList/campaignListDashboard.js?v='. rvndev()->getRandom(30) }}"></script>
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
