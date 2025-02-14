@extends('layouts.index') <!-- Reference the layout file -->

@section('title', 'Homepage') <!-- Define the title -->


@section('content') <!-- Define the content -->

<style>
    body {
    background-color: #04d9ff; /* Light grey background */
  }

  .container-fluid {
    background-color: #04d9ff; /* White background for the main content */
  }

  .btn-outline-secondary {
    background-color:rgb(242, 242, 242); /* Prevent changing button background */
    border: 5px solid #6c757d; /* Keep the button border color */
  }

  .btn-outline-secondary:hover {
    background-color:rgb(176, 190, 206) !important; /* Slight hover effect */
    border-color: #5a6268; /* Hover border color */
    color: black;
  }
</style>
    <img src="{{ asset('assets/BANNER-PERUMDA-MANUNTUNG-BALIKPAPAN.jpg') }}" alt="Logo" style="width: 100%; height: auto;">


<!-- Success Popup -->
@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="successModalLabel">Sukses!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
          <img src="{{ url('/assets/checkmark.jpg') }}" alt="Checkmark" class="img-fluid" style="max-width: 300px;">
        </div>
        <p class="mt-4" id="ticketDetails">{{ session('success')['no_antrian'] }} - {{ session('success')['loket'] }} - {{ session('success')['date'] }}</p>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
@endif


<!-- Buttons Section -->
<div class="container-fluid my-2 text-center">
  <div class="row justify-content-center">
    <!-- First button -->
    <div class="col-md-4 mb-3">
      <form action="{{ route('ticket.store') }}" method="POST">
        @csrf
        <input type="hidden" name="loket" value="Pembayaran">
        <button type="submit" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="border-width: 5px; height: 800px; width: 530px;">
          <div>
            <img src="{{url('/assets/pay.png')}}" style="max-width: 400px; max-height: 400px;" alt="Image"/>
            <h2>Pembayaran</h2>
          </div>
        </button>
      </form>
    </div>

    <!-- Second button -->
    <div class="col-md-4 mb-3">
      <form action="{{ route('ticket.store') }}" method="POST">
        @csrf
        <input type="hidden" name="loket" value="Pengaduan">
        <button type="submit" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="border-width: 5px; height: 800px; width: 530px;">
          <div>
            <img src="{{url('/assets/cs.png')}}" style="min-width: 400px; max-height: 390px;" alt="Image"/>
            <h2>Pengaduan</h2>
          </div>
        </button>
      </form>
    </div>

    <!-- Third button -->
    <div class="col-md-4 mb-3">
      <form action="{{ route('ticket.store') }}" method="POST">
        @csrf
        <input type="hidden" name="loket" value="Permohonan Sambungan Baru">
        <button type="submit" class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="border-width: 5px; height: 800px; width: 530px;">
          <div>
            <img src="{{url('/assets/sambungan_air.png')}}" style="min-width: 400px; min-height: 400px;" alt="Image"/>
            <h2>Permohonan Sambungan Baru</h2>
          </div>
        </button>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap Modal Trigger Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Show success modal if session('success') is available
    @if(session('success'))
      var successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();

      // Ensure that the modal is shown completely before triggering the print function
      successModal._element.addEventListener('shown.bs.modal', function () {
        // Pass both 'no_antrian' and 'loket' to the print function
        handlePrintTicket("{{ session('success')['no_antrian'] }}", "{{ session('success')['loket'] }}", "{{ session('success')['date'] }}");
      });
    @endif
});

function handlePrintTicket(no_antrian, loket, date) {
    fetch("{{ route('print.ticket') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({ no_antrian: no_antrian, loket: loket, date: date })
    })
    .then(response => response.json())

    .catch(error => {
      console.error("Error:", error);
      alert("An unexpected error occurred.");
    });
}

</script>
@endsection
