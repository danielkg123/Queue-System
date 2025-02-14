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
        <h1 class="text-center">Running Text List</h1>
        <div class="mb-3 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRunningTextModal">Add Running Text</button>
        </div>

        <table class="table table-striped table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Content</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rt as $index => $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->content }}</td>
                        <td>{{ $u->status }}</td>
                        <td>
                        <form action="{{ route('runningtext.toggle-status', $u->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                {{ $u->status === 'active' ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>    
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editRunningTextModal" 
                                data-id="{{ $u->id }}" 
                                data-contents="{{ $u->content }}" >
                                Edit
                            </button>
                            <form action="{{ route('runningtext.delete', $u->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No Running Text found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for adding Running Text -->
<div class="modal fade" id="addRunningTextModal" tabindex="-1" aria-labelledby="addRunningTextModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('runningtext.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addRunningTextModalLabel">Add New Running Text</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="content" class="form-label">Contents</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add Running Text</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for editing Running Text -->
<div class="modal fade" id="editRunningTextModal" tabindex="-1" aria-labelledby="editRunningTextModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editRunningTextForm" method="POST" action="{{ route('runningtext.update', 0) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editRunningTextModalLabel">Edit Running Text</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editRunningTextId" name="id">
                    <div class="mb-3">
                        <label for="editContents" class="form-label">Contents</label>
                        <textarea class="form-control" id="editContents" name="content" rows="3" required></textarea>
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
                const firstConfirmation = confirm('Are you sure you want to delete this running text?');
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
        const editRunningTextModal = document.getElementById('editRunningTextModal');
        editRunningTextModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const contents = button.getAttribute('data-contents');

            // Populate the modal fields
            editRunningTextModal.querySelector('#editRunningTextId').value = id;
            editRunningTextModal.querySelector('#editContents').value = contents;

            // Update the form action dynamically
            const form = editRunningTextModal.querySelector('#editRunningTextForm');
            form.setAttribute('action', `/admin/runningtext/update/${id}`);
        });
    });
</script>
@endsection
