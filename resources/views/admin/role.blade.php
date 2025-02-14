@extends('layouts.navbar') <!-- Reference the main layout -->

@section('title', 'Admin') <!-- Define the title -->

@include('layouts.index')

@section('content') <!-- Define the content -->

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-light border-right" id="sidebar-wrapper" style="width: 250px; height: 100vh; position: fixed;">
    <div class="sidebar-heading text-center py-4">
        <h4>Options</h4>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('user') }}" class="list-group-item list-group-item-action bg-light">User</a>
        <a href="{{ route('role') }}" class="list-group-item list-group-item-action bg-light">Role</a>
        <a href="{{ route('runningtext') }}" class="list-group-item list-group-item-action bg-light">Running Text</a>
        <a href="{{ route('carousel') }}" class="list-group-item list-group-item-action bg-light">Carousel</a>
    </div>
</nav>


    <!-- Main Content -->
    <div class="container-fluid" style="margin-left: 250px; padding: 20px;">
        <h1 class="text-center">Role List</h1>
        <div class="mb-3 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">Add Role</button>
        </div>
        <table class="table table-striped table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Kode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($role as $index => $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->kode}}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRoleModal" 
                                data-id="{{ $u->id }}" 
                                data-name="{{ $u->name }}" 
                                data-kode="{{ $u->kode }}">                                
                                Edit
                            </button>
                            <form action="{{ route('role.delete', $u->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No Roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('role.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="roleName" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="roleName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="roleKode" class="form-label">Role Kode</label>
                        <input type="text" class="form-control" id="roleKode" name="kode" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add Role</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editRoleForm" method="POST" action="{{ route('role.update', 0) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editRoleId" name="id">
                    <div class="mb-3">
                        <label for="editRoleName" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="editRoleName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleKode" class="form-label">Role Kode</label>
                        <input type="text" class="form-control" id="editRoleKode" name="kode" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                const firstConfirmation = confirm('Are you sure you want to delete this role?');
                if (firstConfirmation) {
                    const secondConfirmation = confirm('This action cannot be undone. Are you absolutely sure?');
                    if (secondConfirmation) {
                        this.closest('form').submit();
                    }
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const editRoleModal = document.getElementById('editRoleModal');
        editRoleModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const kode = button.getAttribute('data-kode');

            // Populate the modal fields
            editRoleModal.querySelector('#editRoleId').value = id;
            editRoleModal.querySelector('#editRoleName').value = name;
            editRoleModal.querySelector('#editRoleKode').value = kode;

            // Update the form action dynamically
            const form = editRoleModal.querySelector('#editRoleForm');
            form.setAttribute('action', `/admin/role/update/${id}`);
        });
    });

</script>
@endsection
