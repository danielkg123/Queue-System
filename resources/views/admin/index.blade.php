@extends('layouts.navbar') <!-- Reference the main layout -->

@section('title', 'Admin') <!-- Define the title -->

@include('layouts.index') <!-- Include the navbar component -->

@section('content') <!-- Define the content -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <title>@yield('title', 'Default Title')</title>
</head>
<body>
<button id="speakButton" style="display: none;">Start Speech</button>

<div class="container mt-1">
    <div class="container mt-1">
    <div class="row align-items-center">
        <!-- Sisa Antrian Section -->
        <div class="col-md-6 text-start">
            <h1>{{ Auth::user()->name }}, {{ Auth::user()->role }}</h1>
        </div>

        <!-- User Name and Role Section -->
        <!-- Remaining Tickets Section -->
        <div class="col-md-6 text-end">
            <h3>Sisa Antrian: <span id="sisa-antrian">0</span></h3>
        </div>
    </div>
</div>

</div>
<div class="container mt-1">
    <div id="ticket-placeholder" class="card shadow-lg p-4 mb-1" role="alert">
        <div class="card-body">
            <h1 class="card-title display-1 text-muted">No Antrian</h1>
            <p class="card-text fs-2"><strong>Loket:</strong> <span class="text-muted">---</span></p>
            <p class="card-text fs-2">
                <strong>Status:</strong>
                <span class="badge bg-secondary p-2 fs-5">Waiting</span>
            </p>
            <p class="card-text fs-1"><strong>Tanggal Antrian:</strong> <span class="text-muted">---</span></p>
        </div>
    </div>


    <div id="ticket-card" class="card shadow-lg p-4 mb-5" style="display: none;">
        <div class="card-body">
            <h1 class="card-title display-1 text-primary" id="ticket-no-antrian"></h1>
            <p class="card-text fs-2"><strong>Loket:</strong> <span id="ticket-loket"></span></p>
            <p class="card-text fs-2">
                <strong>Status:</strong>
                <span id="ticket-status" class="badge p-2 fs-5"></span>
            </p>
            <p class="card-text fs-1"><strong>Tanggal Antrian:</strong> <span id="ticket-date"></span></p>
        </div>
    </div>
    <div class="d-flex mt-1">
        <button id="fetch-ticket-btn" class="btn btn-success btn-lg px-5 py-3 me-3" style="font-size: 1.5rem;">
            <h1>Call</h1>
        </button>
        <button id="panggil-btn" class="btn btn-primary btn-lg px-5 py-3 me-3" 
                style="font-size: 1.5rem;" disabled 
                onclick="">
            <h1>Re-Call</h1>
        </button>
    </div>
</div>

</div>
</body>
<script>
window.onload = function() {
    document.getElementById('speakButton').click();  // Simulate user click when the page loads
};

let currentTicketMessage = ''; // Variable to store the current ticket message
document.getElementById('fetch-ticket-btn').addEventListener('click', async function () {
    try {
        const response = await fetch('{{ route('ticket.next') }}');
        const result = await response.json();

        if (result.success) {
            const ticket = result.ticket;

            // Update UI with new ticket data
            document.getElementById('ticket-no-antrian').textContent = `No Antrian: ${ticket.no_antrian}`;
            document.getElementById('ticket-loket').textContent = ticket.loket;
            
            const statusBadge = document.getElementById('ticket-status');
            statusBadge.textContent = ticket.status ? 'Current' : 'Waiting';
            statusBadge.className = ticket.status ? 'badge bg-success p-2 fs-5' : 'badge bg-warning p-2 fs-5';

            const createdAt = new Date(ticket.created_at);
            const formattedDate = createdAt.toLocaleString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('ticket-date').textContent = formattedDate;

            // Construct the message for speech synthesis
            const ticketMessage = `Nomor antrian ${ticket.no_antrian} di Loket {{ Auth::user()->counter }}`;
            currentTicketMessage = ticketMessage;
	    const loketStr = ticket.lokasi;
            // Emit event to Node.js server via Socket.IO
            socket.emit('callTicket', {
                no_antrian: ticket.no_antrian,
                loket: loketStr
            });

            // Enable the "Re-Call" button
            const recallButton = document.getElementById('panggil-btn');
            recallButton.disabled = false;
            recallButton.classList.remove('btn-secondary');
            recallButton.classList.add('btn-primary');
            recallButton.innerHTML = `<h1>Re-Call: Nomor Antrian ${ticket.no_antrian}</h1>`;
            recallButton.setAttribute('onclick', `recallTicket('${ticket.no_antrian}', '${loketStr}')`);



            // Show the ticket card
            document.getElementById('ticket-placeholder').style.display = 'none';
            document.getElementById('ticket-card').style.display = 'block';
        } else {
            alert(result.message || 'Tidak ada tiket yang tersedia.');
        }
    } catch (error) {
        console.error('Error fetching ticket:', error);
        alert('Terjadi kesalahan saat mengambil data tiket.');
    }
});

// Function to recall a ticket
function recallTicket(no_antrian, loket) {
    socket.emit('recallTicket', {
        no_antrian: no_antrian,
        loket: loket
    });
}




</script>


 

<script src="https://cdn.socket.io/4.7.2/socket.io.js"></script>
<script>
  const socket = io('http://127.0.0.1:3000');

  socket.on('connect', () => {
    console.log("Connected to Socket.IO server!");
  });


  socket.on('ticketCountsUpdate', (ticketCounts) => {
  console.log("Ticket Counts:", ticketCounts);

  // Define a mapping between user roles and ticket roles
  const roleMapping = {
    pengaduan: "Pengaduan",
    sambunganbaru: "Permohonan Sambungan Baru",
    teller: "Pembayaran"
  };

  // Get the current user's role (you can replace this with the actual user role)
  const userRole = "{{ Auth::user()->role }}"; // Replace this with the dynamic user role, e.g., from a logged-in user context

  // Map the user role to the corresponding ticket role
  const ticketRole = roleMapping[userRole];

  if (!ticketRole) {
    console.warn(`No ticket role found for user role: ${userRole}`);
    document.getElementById('sisa-antrian').textContent = 0;
    return;
  }

  // Filter the ticket counts for the current ticket role
  const filteredCounts = ticketCounts.filter(ticket => ticket.loket === ticketRole);

  // Calculate the total count for the current role
  const totalSisaAntrian = filteredCounts.reduce((acc, curr) => acc + (curr.count || 0), 0);

  // Update the DOM with the total count
  const sisaAntrianElement = document.getElementById('sisa-antrian');
  if (sisaAntrianElement) {
    sisaAntrianElement.textContent = totalSisaAntrian;
  } else {
    console.warn('Element with ID "sisa-antrian" not found.');
  }
});



  socket.on('disconnect', () => {
    console.log("Disconnected from Socket.IO server!");
  });
</script>


@endsection
