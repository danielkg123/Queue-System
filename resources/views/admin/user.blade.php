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
        <h1 class="text-center">User List</h1>

        <!-- Add User Button -->
        <div class="mb-3 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
        </div>

        <table class="table table-striped table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Counter</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($user as $index => $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->role ?? 'N/A' }}</td>
                        <td>{{ $u->counter }}</td>
                        <td>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal" 
                            data-id="{{ $u->id }}" 
                            data-name="{{ $u->name }}" 
                            data-role="{{ $u->role }}" 
                            data-counter="{{ $u->counter }}">
                            Edit
                        </button>
                            <form action="{{ route('user.delete', $u->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">Select Role</option>
                            @foreach($role as $roles)
                                <option value="{{ $roles->name }}">{{ $roles->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="counter" class="form-label">Counter</label>
                        <input type="number" class="form-control" id="counter" name="counter">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm" method="POST" action="{{ route('user.update', 0) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Role</label>
                        <select class="form-control" id="editRole" name="role" required>
                            <option value="">Select Role</option>
                            @foreach($role as $roless)
                                <option value="{{ $roless->name }}">{{ $roless->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editCounter" class="form-label">Counter</label>
                        <input type="number" class="form-control" id="editCounter" name="counter">
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
                const firstConfirmation = confirm('Are you sure you want to delete this user?');
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
    const editUserModal = document.getElementById('editUserModal');
    editUserModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const role = button.getAttribute('data-role');
        const counter = button.getAttribute('data-counter');

        // Populate the modal fields
        editUserModal.querySelector('#editUserId').value = id;
        editUserModal.querySelector('#editName').value = name;
        editUserModal.querySelector('#editRole').value = role;
        editUserModal.querySelector('#editCounter').value = counter;

        // Update the form action dynamically
        const form = editUserModal.querySelector('#editUserForm');
        form.setAttribute('action', `/admin/user/update/${id}`);
    });
});

</script>

@endsection
