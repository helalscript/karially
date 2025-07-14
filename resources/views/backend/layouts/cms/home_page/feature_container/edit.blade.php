@extends('backend.app')
@section('title', 'Feature Page')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    {{-- CKEditor CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
        }
    </style>
@endpush

@section('content')

    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h3 class="mb-0">Feature Page</h3>

            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="#" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-4-line fs-18 text-primary me-1"></i>
                            <span class="text-secondary fw-medium hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Feature Edit Page</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="fw-medium">Edit</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">


                <div class="mb-4">
                    <h4 class="fs-20 mb-1">Feature Page</h4>
                    <p class="fs-15">Edit Feature Page here.</p>
                </div>

                <form action="{{ route('cms.home_page.feature.update', $data->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT') <!-- This is important for update actions -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Section Title<span class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <input type="text"
                                        class="form-control text-dark ps-5 h-55 @error('title') is-invalid @enderror"
                                        name="title" value="{{ old('title', $data->title ?? '') }}"
                                        placeholder="Enter title here">
                                </div>
                                @error('title')
                                    <div id="title-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Section Description<span
                                        class="text-danger">*</span></label>
                                <div class="form-group position-relative">
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                        placeholder="Enter description here">{{ old('description', $data->description ?? '') }}</textarea>
                                </div>
                                @error('description')
                                    <div id="description-error" class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Button text Field -->
                        <div class="col-lg-6">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Button Text<span class="text-danger">*</span><span
                                        class="text-danger">*</span></label>
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
                        <!-- Tag Field -->
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="label text-secondary">Tags Items<span class="text-danger">*</span></label>
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
                                                    <button type="button" class="btn btn-danger ml-2"
                                                        onclick="removeTag(this)"
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
                                <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16 text-white"
                                    onclick="window.location.href='{{ route('cms.home_page.feature.index') }}'">Cancel</button>
                                <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16"> <i
                                        class="ri-check-line text-white fw-medium"></i> Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        ClassicEditor
            .create(document.querySelector('#page_content'), {
                removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'ImageUpload', 'MediaEmbed'],
                toolbar: ['bold', 'italic', 'heading', '|', 'undo', 'redo']
            })
            .catch(error => {
                console.error(error);
            });
    </script>

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
