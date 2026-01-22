@extends('admin.layouts.master')

@section('title', 'Approved Request')

@section('content')
<div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
    <!-- Header -->
    <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
        <div class="grow">
            <h5 class="text-16">Approved Request</h5>
        </div>
        <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
            <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                <a href="#!" class="text-slate-400 dark:text-zink-200">Approved</a>
            </li>
            <li class="text-slate-700 dark:text-zink-100">List</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h6 class="text-15 font-semibold">Approved</h6>
            </div>

            <!-- Table -->
            <table id="requestTable" class="w-full stripe group whitespace-nowrap list" style="width:100%">
                <thead>
                    <tr>
                        <th>Student No</th>
                        <th>Name</th>
                        <th>Campus</th>
                        <th>Email</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/datatables/jquery-3.7.0.js') }}"></script>
<script src="{{ asset('assets/js/datatables/data-tables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
<script src="{{ asset('assets/libs/list.js/list.min.js') }}"></script>
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Setup CSRF token globally
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    let table = $('#requestTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.approved.data') }}",
        columns: [
            { data: 'studentno', name: 'studentno' },
            {
                data: null,
                render: data => `${data.firstname} ${data.lastname}`
            },
            {
                data: 'campus_id',
                name: 'campus_id',
                render: id => {
                    const campuses = {
                        1: 'Main', 2: 'Palma', 3: 'Kcc', 4: "M'lang",
                        5: 'Buluan', 6: 'Grad School', 7: 'Medicine', 8: 'Law'
                    };
                    return campuses[id] ?? 'Unknown';
                }
            },
            { data: 'email', name: 'email' },
            {
                data: 'status',
                name: 'status',
                render: data => {
                    let color = data === 'approved' ? 'green' : data === 'pending' ? 'yellow' : 'red';
                    return `<span class="px-2 py-1 rounded text-white bg-${color}-500">${data}</span>`;
                }
            }
        ],
        drawCallback: function() {
            if (window.lucide) lucide.createIcons();
        }
    });
});
</script>
@endpush
