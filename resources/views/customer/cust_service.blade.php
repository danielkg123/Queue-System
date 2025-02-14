@extends('layouts.index') <!-- Reference the layout file -->

@section('title', 'customer_service') <!-- Define the title -->

@section('content') <!-- Define the content -->
<div class="container mt-5">
  <h1>Selamat Datang di Ticket PTMB</h1>
    <h2>Ticket Customer Service</h2>
</div>
<div class="container mt-5">

    <!-- @csrf Include CSRF token for security -->
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
    </div>
    
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter description" required></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</div>

@endsection
