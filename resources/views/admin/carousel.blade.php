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
        <h1 class="text-center">Carousel List</h1>
        <div class="mb-3 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCarouselModal">Add Carousel</button>
        </div>
        <table class="table table-striped table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Image/Video Path</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    @forelse ($carousel as $index => $u)
        <tr>
            <td>{{ $u->id }}</td>
            <td>
                <a href="{{ asset('storage/' . $u->image_path) }}" download class="btn btn-sm btn-primary">
                    Download File
                </a>
            </td>
            <td>{{ $u->status }}</td>
            <td>
                <form action="{{ route('carousel.toggle-status', $u->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">
                        {{ $u->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>    
                <form action="{{ route('carousel.delete', $u->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm delete-button">Delete</button>
                </form>
            </td>
        </tr
>
    @empty
        <tr>
            <td colspan="5" class="text-center">No Carousels found.</td>
        </tr>
    @endforelse
</tbody>

        </table>
    </div>
</div>

<!-- Add Carousel Modal -->
<div class="modal fade" id="addCarouselModal" tabindex="-1" aria-labelledby="addCarouselModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('carousel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCarouselModalLabel">Add Carousel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                    <label for="file" class="form-label">Upload File (Image or Video)</label>
                    <input type="file" class="form-control" id="file" name="file" accept="image/*,video/*" required>
                </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Carousel</button>
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
</script>

@endsection
