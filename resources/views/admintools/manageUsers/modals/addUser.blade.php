<div class="modal fade" id="addUser" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addUser"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="bi bi-plus"></i>
          Add User
        </h5>
        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i aria-hidden="true" class="bi bi-x fs-2x"></i>
        </div>
        <!--end::Close-->
      </div>
      <div class="modal-body">
        <div class="formAlertDiv">

        </div>
        <form class="form" id="add_user_form">
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Username <span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="username" class="form-control form-control-lg form-control-solid"
                placeholder="Enter Username">
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Password <span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="password" class="form-control form-control-lg form-control-solid"
                placeholder="Enter Password"></input>
            </div>
          </div>
          {{-- <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Password Confirmation</label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="passwordConfirmation" class="form-control form-control-lg form-control-solid"
                placeholder="Retype Password"></input>
            </div>
          </div> --}}
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">First Name <span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="firstName" class="form-control form-control-lg form-control-solid"
                placeholder="Enter First Name">
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Middle Name <span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="middleName" class="form-control form-control-lg form-control-solid"
                placeholder="Enter Middle Name"></input>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Last Name <span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="lastName" class="form-control form-control-lg form-control-solid"
                placeholder="Enter Last Name">
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Phone Extension <span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="phoneExtension" class="form-control form-control-lg form-control-solid"
                placeholder="Enter Phone Extension">
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Level</label>
            <div class="col-lg-10 fv-row">
              <select class="form-control selectpicker" name="level" id="level">
                <option value=0>AGENT</option>
                <option value=1>SUPERVISOR</option>
                <option value=2>ADMIN</option>
              </select>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Group</label>
            <div class="col-lg-10 fv-row">
              <select class="form-control selectpicker" name="groupe" id="groupe">
                @foreach ($groups as $group )
                <option value="{{ $group->groupName }}">{{ $group->groupName  }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Product</label>
            <div class="col-lg-10 fv-row">
              <select class="form-control selectpicker" name="product" id="product">
                @foreach ($clients as $client )
                <option value="{{ $client->clientName }}">{{ $client->clientName  }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">End to End</label>
            <div class="col-lg-10 fv-row">
              <select class="form-control selectpicker" name="endToEnd" id="endToEnd">
                <option>YES</option>
                <option>NO</option>
              </select>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Upload <span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="file" name="file" placeholder="Leave blank"
                class="form-control form-control-lg form-control-solid" accept=".png, .jpg" />
            </div>
          </div>
          <div class="row mb-6">
            {{-- <label class="col-lg-2 col-form-label fw-bold fs-6">Preview</label> --}}
            <div class="col-lg-10 fv-row userImagePreview">

            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Agent Number<span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="agentNum" class="form-control form-control-lg form-control-solid"
                placeholder="Enter Agent Number">
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-lg-2 col-form-label fw-bold fs-6">Salesman ID <span class="text-danger">*</span></label>
            <div class="col-lg-10 fv-row">
              <input type="text" name="salesmanID" class="form-control form-control-lg form-control-solid"
                placeholder="Enter Salesman ID">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Close</button>
        <button type="submit" id="addUserSubmitBtn" class="btn btn-primary font-weight-bold">Add User</button>
      </div>
      </form>
    </div>
  </div>
</div>