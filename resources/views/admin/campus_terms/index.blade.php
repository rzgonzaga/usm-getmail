@extends('admin.layouts.master')

@section('title', 'Campus Term Settings')

@section('content')
<div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
    <!-- Header -->
    <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
        <div class="grow">
            <h5 class="text-16">Campus Term Settings</h5>
        </div>
        <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
            <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                <a href="#!" class="text-slate-400 dark:text-zink-200">Settings</a>
            </li>
            <li class="text-slate-700 dark:text-zink-100">Campus Terms</li>
        </ul>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Header -->
            <div class="mb-4">
                <h6 class="text-15 font-semibold">Active Terms by Campus</h6>
                <p class="text-slate-500 text-sm mt-1">Leave the Term ID blank to automatically fetch the term from the external API for that campus.</p>
            </div>
            
            @if(session('success'))
                <div class="px-4 py-3 mb-4 text-sm text-green-600 bg-green-50 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="px-4 py-3 mb-4 text-sm text-red-600 bg-red-50 rounded-md">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-end mb-4">
                <button type="button" onclick="document.getElementById('addCampusModal').classList.remove('hidden')" class="btn bg-green-500 text-white border-green-500 hover:text-white hover:bg-green-600 hover:border-green-600 focus:text-white focus:bg-green-600 focus:border-green-600 active:text-white active:bg-green-600 active:border-green-600 px-4 py-2 rounded flex items-center gap-1">
                    <i data-lucide="plus" class="h-4 w-4"></i> Add New Campus
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full stripe group whitespace-nowrap list" style="width:100%">
                    <thead class="bg-slate-100 dark:bg-zink-600">
                        <tr>
                            <th class="px-4 py-2 text-left w-24">Campus ID</th>
                            <th class="px-4 py-2 text-left">Campus Name</th>
                            <th class="px-4 py-2 text-left w-32">Tenant ID</th>
                            <th class="px-4 py-2 text-left">Active Term ID</th>
                            <th class="px-4 py-2 text-center w-32">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($campusTerms as $term)
                        <tr class="border-b border-slate-200 dark:border-zink-500">
                            <td class="px-4 py-3">{{ $term->campus_id }}</td>
                            <td class="px-4 py-3">{{ $term->campus_name }}</td>
                            <td class="px-4 py-3">{{ $term->tenant_id ?? 'Automatic' }}</td>
                            <td class="px-4 py-3">
                                @if($term->term_id)
                                    @php
                                        $termDesc = null;
                                        if(isset($apiTerms[$term->campus_id])) {
                                            foreach($apiTerms[$term->campus_id] as $apiTerm) {
                                                if($apiTerm['termId'] == $term->term_id) {
                                                    $termDesc = $apiTerm['academicYear'] . ' - ' . $apiTerm['schoolTerm'];
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    
                                    @if($termDesc)
                                        <span class="px-2 py-1 bg-custom-100 text-custom-600 rounded text-sm" title="ID: {{ $term->term_id }}">
                                            {{ $termDesc }} (ID: {{ $term->term_id }})
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-custom-100 text-custom-600 rounded text-sm">ID: {{ $term->term_id }}</span>
                                    @endif
                                @else
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-sm">API Fallback</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <button type="button" onclick="openEditModal({{ $term->id }}, '{{ $term->campus_id }}', '{{ addslashes($term->campus_name) }}', '{{ $term->tenant_id }}', '{{ $term->term_id }}')" class="text-blue-500 hover:text-blue-700 p-1 rounded transition-colors" title="Edit">
                                    <i data-lucide="edit" class="h-5 w-5 inline-block"></i>
                                </button>
                                <button type="button" onclick="if(confirm('Are you sure you want to delete this campus?')) { document.getElementById('delete-form-{{ $term->id }}').submit(); }" class="text-red-500 hover:text-red-700 p-1 rounded transition-colors" title="Delete">
                                    <i data-lucide="trash-2" class="h-5 w-5 inline-block"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Forms -->
@foreach($campusTerms as $term)
    <form id="delete-form-{{ $term->id }}" action="{{ route('admin.campus_terms.destroy', $term->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<!-- Add Campus Modal -->
<div id="addCampusModal" class="hidden fixed inset-0 z-[1050] flex items-center justify-center bg-slate-900/50 overflow-y-auto">
    <div class="bg-white dark:bg-zink-700 rounded-lg shadow-lg w-[500px] max-w-[95%] m-4 relative flex flex-col">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
            <h5 class="text-16 font-semibold text-slate-700 dark:text-zink-100">Add New Campus</h5>
            <button type="button" onclick="document.getElementById('addCampusModal').classList.add('hidden')" class="text-slate-500 hover:text-slate-700 dark:text-zink-200 dark:hover:text-white">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.campus_terms.store') }}" method="POST">
            @csrf
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-zink-100">Campus ID <span class="text-red-500">*</span></label>
                    <input type="number" name="campus_id" required class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 text-slate-700 dark:text-zink-100">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-zink-100">Campus Name <span class="text-red-500">*</span></label>
                    <input type="text" name="campus_name" required class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 text-slate-700 dark:text-zink-100">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-zink-100">Tenant ID <span class="text-slate-400">(Optional)</span></label>
                    <input type="number" name="tenant_id" id="add-tenant-id" class="tenant-input w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 text-slate-700 dark:text-zink-100">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-zink-100">Active Term ID <span class="text-slate-400">(Optional)</span></label>
                    <select name="term_id" id="add-term-id" class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 text-slate-700 dark:text-zink-100">
                        <option value="">Automatic (Fallback to API)</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end p-4 border-t border-slate-200 dark:border-zink-500 gap-2">
                <button type="button" onclick="document.getElementById('addCampusModal').classList.add('hidden')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-zink-600 dark:text-zink-100 dark:hover:bg-zink-500 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="btn bg-custom-500 text-white hover:bg-custom-600 px-4 py-2 rounded">
                    Add Campus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Campus Modal -->
<div id="editCampusModal" class="hidden fixed inset-0 z-[1050] flex items-center justify-center bg-slate-900/50 overflow-y-auto">
    <div class="bg-white dark:bg-zink-700 rounded-lg shadow-lg w-[500px] max-w-[95%] m-4 relative flex flex-col">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zink-500">
            <h5 class="text-16 font-semibold text-slate-700 dark:text-zink-100">Edit Campus</h5>
            <button type="button" onclick="document.getElementById('editCampusModal').classList.add('hidden')" class="text-slate-500 hover:text-slate-700 dark:text-zink-200 dark:hover:text-white">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        <form id="editCampusForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-zink-100">Campus ID <span class="text-red-500">*</span></label>
                    <input type="number" id="edit-campus-id" name="campus_id" required class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 text-slate-700 dark:text-zink-100">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-zink-100">Campus Name <span class="text-red-500">*</span></label>
                    <input type="text" id="edit-campus-name" name="campus_name" required class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 text-slate-700 dark:text-zink-100">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-zink-100">Tenant ID <span class="text-slate-400">(Optional)</span></label>
                    <input type="number" id="edit-tenant-id" name="tenant_id" class="tenant-input w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 text-slate-700 dark:text-zink-100">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-slate-700 dark:text-zink-100">Active Term ID <span class="text-slate-400">(Optional)</span></label>
                    <select name="term_id" id="edit-term-id" class="w-full px-3 py-2 border rounded border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:bg-zink-700 text-slate-700 dark:text-zink-100">
                        <option value="">Automatic (Fallback to API)</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center justify-end p-4 border-t border-slate-200 dark:border-zink-500 gap-2">
                <button type="button" onclick="document.getElementById('editCampusModal').classList.add('hidden')" class="btn bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-zink-600 dark:text-zink-100 dark:hover:bg-zink-500 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="btn bg-custom-500 text-white hover:bg-custom-600 px-4 py-2 rounded">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function fetchTermsForSelect(tenantId, selectElement, currentVal = null) {
        selectElement.innerHTML = '<option value="">Loading...</option>';
        
        if (!tenantId) {
            selectElement.innerHTML = '<option value="">Automatic (Fallback to API)</option>';
            return;
        }

        fetch(`{{ route('admin.campus_terms.fetch') }}?tenant_id=${tenantId}`)
            .then(response => response.json())
            .then(data => {
                let html = '<option value="">Automatic (Fallback to API)</option>';
                if (data && data.length > 0) {
                    data.forEach(item => {
                        const selected = (item.termId == currentVal) ? 'selected' : '';
                        html += `<option value="${item.termId}" ${selected}>${item.academicYear} - ${item.schoolTerm}</option>`;
                    });
                } else {
                    html += '<option value="" disabled>No terms found for this tenant</option>';
                    if (currentVal) {
                        html += `<option value="${currentVal}" selected>Current ID: ${currentVal} (API Unreachable)</option>`;
                    }
                }
                selectElement.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching terms:', error);
                selectElement.innerHTML = '<option value="">Automatic (Fallback to API)</option><option value="" disabled>Error loading terms</option>';
                if (currentVal) {
                    selectElement.innerHTML += `<option value="${currentVal}" selected>Current ID: ${currentVal}</option>`;
                }
            });
    }

    function openEditModal(id, campusId, campusName, tenantId, termId) {
        document.getElementById('editCampusForm').action = `/admin/campus-terms/${id}`;
        document.getElementById('edit-campus-id').value = campusId;
        document.getElementById('edit-campus-name').value = campusName;
        document.getElementById('edit-tenant-id').value = tenantId;
        
        const termSelect = document.getElementById('edit-term-id');
        termSelect.innerHTML = `<option value="${termId}" selected>Loading...</option>`;
        
        fetchTermsForSelect(tenantId || campusId, termSelect, termId);
        
        document.getElementById('editCampusModal').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tenantInputs = document.querySelectorAll('.tenant-input');
        
        tenantInputs.forEach(input => {
            input.addEventListener('input', function() {
                const isAdd = this.id === 'add-tenant-id';
                const selectElement = document.getElementById(isAdd ? 'add-term-id' : 'edit-term-id');
                const tenantId = this.value;
                const currentVal = selectElement.value;
                
                // Debounce slightly if needed, but for now just fetch directly
                fetchTermsForSelect(tenantId, selectElement, currentVal);
            });
        });
    });
</script>
@endsection
