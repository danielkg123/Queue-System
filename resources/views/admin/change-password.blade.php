@extends('layouts.navbar') <!-- Reference the main layout -->

@include('layouts.index') <!-- Include the navbar component -->

@section('title', 'Change Password')

@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <h2 class="mb-4">Change Password</h2>

      @if(session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      <form action="{{ route('change-password.update') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label for="current_password" class="form-label">Current Password</label>
          <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror">
          @error('current_password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="new_password" class="form-label">New Password</label>
          <input type="password" name="new_password" id="new_password" class="form-control @error('new_password') is-invalid @enderror">
          @error('new_password')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
          <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Change Password</button>
      </form>
    </div>
  </div>
</div>
@endsection
