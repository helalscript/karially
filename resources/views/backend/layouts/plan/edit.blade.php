@extends('backend.app')
@section('title', 'Edit Plan')

@push('styles')
@endpush

@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">Edit Plan</h3>
    </div>
    <div class="card bg-white border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('plans.update', $plan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ $plan->title }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ $plan->price }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Duration (days)</label>
                    <input type="number" name="duration" class="form-control" value="{{ $plan->duration }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Features</label>
                    <select name="features[]" multiple class="form-select">
                        @foreach($features as $feature)
                            <option value="{{ $feature->id }}" {{ $plan->features->contains($feature->id) ? 'selected' : '' }}>{{ $feature->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
