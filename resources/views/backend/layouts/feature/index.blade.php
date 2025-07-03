@extends('backend.app')
@section('title', 'Features')
@push('styles')
@endpush


@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">Feature List</h3>
        <a href="{{ route('features.create') }}" class="btn btn-outline-primary py-1 px-2">Add Feature</a>
    </div>
    <div class="card bg-white border-0 rounded-3 mb-4">
        <div class="card-body p-4">
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('features.index') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action', orderable:false, searchable:false },
            ]
        });
    });
</script>
@endpush
