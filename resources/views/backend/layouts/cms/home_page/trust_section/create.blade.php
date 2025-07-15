@extends('backend.app')
@section('title', 'Trust Section')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">CMS Home Page Trust</h3>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <div class="mb-4">
                    <h4 class="fs-20 mb-1">CMS Home Page Trust</h4>
                    <p class="fs-15">Update Home Page Trust and site details here.</p>
                </div>

                <form action="{{ route('cms.home_page.trust_section.update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Sub Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Section Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('sub_title') is-invalid @enderror"
                                        name="sub_title" value="{{ old('sub_title', $data->sub_title ?? '') }}" required
                                        placeholder="Enter section title here">
                                </div>
                                @error('sub_title')
                                    <div id="sub_title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title', $data->title ?? '') }}" required
                                        placeholder="Enter Title here">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <!-- Description Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Description<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <textarea class="form-control text-dark ps-5 h-55 @error('description') is-invalid @enderror" name="description"
                                        placeholder="Enter Description here">{{ old('description', $data->description ?? '') }}</textarea>
                                </div>
                                @error('description')
                                    <div id="description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Title Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Button Text<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('button_text') is-invalid @enderror"
                                        name="button_text" value="{{ old('button_text', $data->button_text ?? '') }}"
                                        required placeholder="Enter button text here">
                                </div>
                                @error('button_text')
                                    <div id="button_text-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group ">
                                <label class="label text-secondary mb-1">Image<span class="text-danger">*</span></label>
                                <input class="dropify form-control @error('image') is-invalid @enderror" type="file"
                                    name="image" accept="image/*"
                                    data-default-file="{{ isset($data) && $data->image ? asset($data->image) : '' }}">
                                @error('image')
                                    <div id="image-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tag Field -->
                    <div class="col-md-12">
                        <div class="form-group mb-4">
                            <label class="label text-secondary">Tags</label>
                            <div class="input-group mb-3">
                                <input type="url" class="form-control" placeholder="Enter tag text" name="tag"
                                    aria-label="Video URL" aria-describedby="basic-addon2" title="Must be a valid url"
                                    value="{{ old('tag', $data->tag ?? '') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button" id="button-addon2"
                                        onclick="addTags()">Add</button>
                                </div>
                            </div>
                            <div id="tag-container" class="row">
                                {{-- isset($data) ?? $data->tags --}}
                                @if (isset($data) && $data->tags)
                                    @foreach (json_decode($data->tags) as $tag)
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-3">
                                                <input type="text" class="form-control" name="tags[]"
                                                    value="{{ $tag }}" readonly>
                                                <button type="button" class="btn btn-danger ml-2" onclick="removeTag(this)"
                                                    style="border-radius: 50%; background-color: red;">
                                                    <i class="ri-close-fill"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap gap-3">
                        {{-- <button type="submit" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white">Cancel</button> --}}
                        <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                class="ri-check-line text-white fw-medium"></i> Submit</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>



@endsection

@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })
    </script>

    <script>
        function addTags() {
            console.log('Adding tag');
            let tag = document.querySelector('input[name="tag"]').value;
            if (tag.trim() === '') {
                flasher.warning('Please enter a valid tag.');
                return;
            }

            if (tag) {
                let tagContainer = document.getElementById('tag-container');
                tagContainer.insertAdjacentHTML('beforeend', `<div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3 ">
                                    <input type="text" class="form-control" name="tags[]" value="${tag}" readonly>
                                    <button type="button" class="btn btn-danger ml-2" onclick="removeTag(this)" style="border-radius: 50%; background-color: red;"><i class="ri-close-fill"></i></button>
                                </div>
                                </div>`);
                document.querySelector('input[name="tag"]').value = '';
            }
        }

        function removeTag(element) {
            element.parentElement.remove();
        }
    </script>
@endpush
