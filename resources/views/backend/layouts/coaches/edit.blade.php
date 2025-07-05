@extends('backend.app')
@section('title', 'Social Link')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Edit CMS Social Link</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Edit CMS Social Link</h4>
                    <p class="fs-15">Update the details of the Social Link here.</p>
                </div>

                <form action="{{ route('coaches.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- Include the method to specify this is an update request -->
                    <div class="row">
                        <!-- Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $data->name) }}" required
                                        placeholder="Enter Title here">
                                </div>
                                @error('name')
                                    <div id="name-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Category<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <select class="form-select h-100 @error('category_id') is-invalid @enderror"
                                        name="category_id" required style="height: 55px !Important;">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $data->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                    <div id="category_id-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Calendly User ID Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Calendly User ID<span
                                        class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('calendly_user_id') is-invalid @enderror"
                                        name="calendly_user_id"
                                        value="{{ old('calendly_user_id', $data->calendly_user_id) }}" required
                                        placeholder="Enter Calendly User ID here">
                                </div>
                                @error('calendly_user_id')
                                    <div id="calendly_user_id-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <!-- Calendly Link Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Calendly Link<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="url"
                                        class="form-control text-dark ps-5 h-55 @error('calendly_link') is-invalid @enderror"
                                        name="calendly_link" value="{{ old('calendly_link', $data->calendly_link) }}"
                                        required placeholder="Enter Calendly Link here">
                                </div>
                                @error('calendly_link')
                                    <div id="calendly_link-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bio Field -->
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Bio</label>
                                <div class="form-group position-relative">
                                    <textarea class="form-control text-dark h-55 @error('bio') is-invalid @enderror" name="bio"
                                        placeholder="Enter Bio here">{{ old('bio', $data->bio) }}</textarea>
                                </div>
                                @error('bio')
                                    <div id="bio-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                         <!-- Email Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Email</label>
                                <div class="form-group position-relative">
                                    <input type="email"
                                        class="form-control text-dark ps-5 h-55 @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $data->email) }}" placeholder="Enter Email here">
                                </div>
                                @error('email')
                                    <div id="email-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Avatar Field -->
                        <div class="col-lg-12">
                            <div class="form-group ">
                                <label class="label text-secondary mb-1">Avatar<span class="text-danger">*</span></label>
                                <input class="dropify form-control @error('avatar') is-invalid @enderror" type="file"
                                    name="avatar" data-default-file="{{ $data->avatar ? asset($data->avatar) : '' }}">
                                @error('avatar')
                                    <div id="avatar-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Instagram Link</label>
                                <div class="form-group position-relative">
                                    <input type="url"
                                        class="form-control text-dark ps-5 h-55 @error('instagram_link') is-invalid @enderror"
                                        name="instagram_link"
                                        value="{{ old('instagram_link', $socials['Instagram'] ?? '') }}"
                                        placeholder="Enter Instagram Link here">
                                </div>
                                @error('instagram_link')
                                    <div id="instagram_link-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Twitter Link Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Facebook Link</label>
                                <div class="form-group position-relative">
                                    <input type="url"
                                        class="form-control text-dark ps-5 h-55 @error('facebook_link') is-invalid @enderror"
                                        name="facebook_link"
                                        value="{{ old('facebook_link', $socials['Facebook'] ?? '') }}"
                                        placeholder="Enter Facebook Link here">
                                </div>
                                @error('facebook_link')
                                    <div id="facebook_link-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- LinkedIn Link Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">LinkedIn Link</label>
                                <div class="form-group position-relative">
                                    <input type="url"
                                        class="form-control text-dark ps-5 h-55 @error('linkedin_link') is-invalid @enderror"
                                        name="linkedin_link"
                                        value="{{ old('linkedin_link', $socials['LinkedIn'] ?? '') }}"
                                        placeholder="Enter LinkedIn Link here">
                                </div>
                                @error('linkedin_link')
                                    <div id="linkedin_link-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex flex-wrap gap-3">
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16">
                                    <i class="ri-check-line text-white fw-medium"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script src="{{ asset('frontend/assets/js/plugins/jquery-3.7.1.min.js') }}"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })
    </script>
@endpush
