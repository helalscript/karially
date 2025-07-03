@extends('backend.app')
@section('title', 'Plan Edit')

@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">Edit Plan</h3>
    </div>
    <div class="card bg-white border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('plans.update', $data->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label class="label text-secondary">Name</label>
                            <input type="text" name="name" value="{{ old('name', $data->name) }}" class="form-control" placeholder="Plan Name">
                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label class="label text-secondary">Price</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $data->price) }}" class="form-control" placeholder="Price">
                            @error('price')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group mb-3">
                            <label class="label text-secondary">Duration (days)</label>
                            <input type="number" name="duration" value="{{ old('duration', $data->duration) }}" class="form-control" placeholder="Duration">
                            @error('duration')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group mb-3">
                            <label class="label text-secondary">Description</label>
                            <textarea name="description" class="form-control" placeholder="Description">{{ old('description', $data->description) }}</textarea>
                            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap gap-3">
                            <button type="reset" class="btn btn-danger py-2 px-4 fw-medium fs-16" onclick="window.location.href='{{ route('plans.index') }}'">Cancel</button>
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-medium fs-16">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
