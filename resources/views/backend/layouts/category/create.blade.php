{{-- here add a model start --}}
<x-modal id="CreateCategory" title="Add New" labelledby="customModalLabel" size="modal-lg" saveButton="Submit">

    <div class="card bg-white border-0 rounded-3 mb-4">
        <div class="card-body p-4">

            <form action="" method="POST"enctype="multipart/form-data" id="request-form">
                @csrf
                <div class="row">
                    <span id="show-error"></span>
                    <!-- Name Field -->
                    <div class="col-lg-12">
                        <div class="form-group mb-4">
                            <label class="label text-secondary"> Name<span class="text-danger">*</span></label>
                            <div class="form-group position-relative">
                                <input type="text"
                                    class="form-control text-dark @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required placeholder="Enter name here">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap gap-3">
                            <button type="reset"
                                class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Reset</button>
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16" id="submitButton">
                                <i class="ri-check-line text-white fw-medium"></i> Submit</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

</x-modal>
{{-- here add a model end --}}
