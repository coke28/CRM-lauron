<div class="modal fade" id="editProductName" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="editProductName" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" id ="productNameModalContent">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus"></i>
                    Edit Product Name
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
                <form class="form" id="edit_product_name_form">
                    <input type="hidden" name="id">
                    <div class="row mb-6">
                        <label class="col-lg-2 col-form-label fw-bold fs-6">Product</label>
                        <div class="col-lg-10 fv-row">
                            <select class="form-control selectpicker" name="product" id="product">
                                @foreach ($clients as $client)
                                    <option value = {{ $client->clientName }}>{{ $client->clientName }}</option>
                                @endforeach
                             
                               
                            </select>

                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-2 col-form-label fw-bold fs-6">Product Name</label>
                        <div class="col-lg-10 fv-row">
                            <input type="text" name="productName" class="form-control form-control-lg form-control-solid" placeholder="Enter Product Name">
                        </div>
                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-2 col-form-label fw-bold fs-6">Status</label>
                        <div class="col-lg-10 fv-row">
                            <select class="form-control selectpicker" name="status" id="status">
                                <option value = 1>ACTIVE</option>
                                <option value = 0>DISABLED</option>
                            </select>

                        </div>

                    </div>
                    <div class="row mb-6">
                        <label class="col-lg-2 col-form-label fw-bold fs-6">Product Name Description</label>
                        <div class="col-lg-10 fv-row">
                            <textarea type="text" name="productNameDescription" class="form-control form-control-lg form-control-solid" placeholder="Enter Product Description"></textarea>
                        </div>
                    </div>



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger font-weight-bold" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="editProductNameSubmitBtn" class="btn btn-primary font-weight-bold">Edit Product Name</button>
            </div>
            </form>
        </div>
    </div>
</div>