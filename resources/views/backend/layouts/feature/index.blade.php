@extends('backend.app')
@section('title', 'Feature')

@section('content')
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h3 class="mb-0">Feature List</h3>
        <a href="{{ route('features.create') }}" class="btn btn-outline-primary py-1 px-2 px-sm-4 fs-14 fw-medium rounded-3 hover-bg">
            <span class="py-sm-1 d-block">
                <i class="ri-add-line d-none d-sm-inline-block"></i>
                <span>Add New Feature</span>
            </span>
        </a>
    </div>
    <div class="col-xl-12">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle" id="basic_tables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('backend') }}/admin/assets/datatables/data-tables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#basic_tables').DataTable({
            order: [],
            destroy: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('features.index') }}",
                type: "get",
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
    function deleteRecord(event, id){
        event.preventDefault();
        let deleteUrl = '{{ route('features.destroy', ':id') }}';
        showDeleteConfirm(id, deleteUrl);
    }
</script>
@endpush
