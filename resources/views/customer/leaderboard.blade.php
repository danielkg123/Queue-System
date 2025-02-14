@extends('layouts.index') <!-- Reference the layout file -->

@section('title', 'Papan Pengumuman') <!-- Define the title -->

@section('content') <!-- Define the content -->
<head>
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
    <style>
        /* Running text container */
        .running-text-container {
            width: 100%;
            background-color: #0084ff;
            color: #fff;
            padding: 10px 0;
            overflow: hidden;
            position: fixed;
            bottom: 0;
            left: 0;
            text-align: center;
            height: 13%;
        }

        /* Running text animation */
        .running-text {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 30s linear infinite;
            font-size: 3rem;
            font-weight: bold;
        }
        
        .img-running {
            width: 1%;  /* Adjust this value as needed */
            height: 1%;  /* Maintain aspect ratio */
        }

        /* Keyframe animation to make the text scroll */
        @keyframes marquee {
    0% {
        transform: translateX(100%);
        animation-timing-function: linear;
    }
    100% {
        transform: translateX(-100%);
        animation-timing-function: linear;
    }
        }
    </style>
</head>

<div class="row">
    <!-- Header Section -->
    <div class="col-6">
        <div class="box">
            <p class="main-title">Nomor Panggilan Terakhir</p>
            <p id="bottomTicket" class="queue-number queue-number-bottom">
                {{ $bottomTicket ? $bottomTicket->no_antrian : '-' }}
            </p>
            <p  id="bottomTicketUser" class="user-location">{{ isset($bottomTicket) ? ucwords(str_replace('loket', 'Loket ', $bottomTicket->lokasi)) : 'No User Found' }}</p>
        </div>
    </div>
    <!-- Carousel Section -->
    <div class="col-6">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            </div>
        <div class="carousel-inner">
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>


            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

<div class="row bottom-cards mt-4">
    <!-- Bottom Cards -->
    <div class="col-md-4">
        <div class="boxbottom">
	   <div class="textbottom">
            <h1>Antrian Teller</h1>
	   </div>
	    <div class="antrian">
            <p id="pembayaranTicket" class="queue-number queue-number-teller">
                {{ $pembayaranTicket ? $pembayaranTicket->no_antrian : '-' }}
            </p>
	</div>
            <p  id="pembayaranTicketUser" class="user-location">{{ isset($pembayaranTicket) ? ucwords(str_replace('loket', 'Loket ', $pembayaranTicket->lokasi)) : 'No User Found' }}</p>

        </div>
    </div>
    <div class="col-md-4">
        <div class="boxbottom">
	   <div class="textbottom">
            <h1>Antrian Pengaduan</h1>
	   </div>
	    <div class="antrian">
            <p id="pengaduanTicket" class="queue-number queue-number-keluhan">
                {{ $pengaduanTicket ? $pengaduanTicket->no_antrian : '-' }}
            </p>
	</div>
            <p  id="pengaduanTicketUser" class="user-location">{{ isset($pengaduanTicket) ? ucwords(str_replace('loket', 'Loket ', $pengaduanTicket->lokasi)) : 'No User Found' }}</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="boxbottom">
	   <div class="textbottom">
            <h1>Antrian Sambungan Baru</h1>
	   </div>
	    <div class="antrian">
            <p id="permohonanSambunganTicket" class="queue-number queue-number-sambungan">
                {{ $permohonanSambunganTicket ? $permohonanSambunganTicket->no_antrian : '-' }}
            </p>
	</div>
            <p id="permohonanSambunganTicketUser" class="user-location">{{ isset($permohonanSambunganTicket) ? ucwords(str_replace('loket', 'Loket ', $permohonanSambunganTicket->lokasi)) : 'No User Found' }}</p>
        </div>
    </div>
</div>

<!-- Running Text Section -->
<div class="running-text-container">
    <div class="running-text">
        <p id="running-text-content"></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.socket.io/4.7.2/socket.io.js"></script>
<script>
    function speakText(text, preferredVoiceName = 'Google Bahasa Indonesia', fallbackVoiceGender = 'female') {
    if ('speechSynthesis' in window) {
        const utterance = new SpeechSynthesisUtterance(text);

        // Set the speech rate to make it slower
        utterance.rate = 0.8; // Adjust this value to control speed (0.1 to 1 is slower than default)

        // Function to find and set the preferred voice
        function setPreferredVoice() {
            const voices = speechSynthesis.getVoices();
            console.log('Available voices:', voices); // Log all available voices

            // Try to find the preferred voice
            let selectedVoice = voices.find(voice => voice.name.includes(preferredVoiceName));

            // If no exact match is found, fallback to a gender-based selection
            if (!selectedVoice) {
                selectedVoice = voices.find(voice => 
                    voice.lang.startsWith('id') && // Check for Indonesian voices
                    voice.name.toLowerCase().includes(fallbackVoiceGender)
                );
            }

            // Set the selected voice if available
            if (selectedVoice) {
                utterance.voice = selectedVoice;
            } else {
                console.warn('Preferred voice not found. Using default voice.');
            }

            // Speak the text
            speechSynthesis.speak(utterance);
        }

        // Listen for the voiceschanged event to make sure voices are loaded
        if (speechSynthesis.onvoiceschanged !== undefined) {
            speechSynthesis.onvoiceschanged = () => {
                const voices = speechSynthesis.getVoices();
                console.log('Voices after voiceschanged event:', voices); // Log voices after the event
                setPreferredVoice();
            };
        }

        // If voices are already loaded, set the voice immediately
        const voices = speechSynthesis.getVoices();
        console.log('Initial voices:', voices); // Log voices immediately if available
        if (voices.length > 0) {
            setPreferredVoice();
        }
    } else {
        alert('Text-to-Speech is not supported in this browser.');
    }}

    const socket = io('http://127.0.0.1:3000');

    socket.on('connect', () => {
        console.log("Connected to Socket.IO server!");
    });

    socket.on('leaderboardUpdate', (data) => {
        console.log(data);
        document.getElementById('bottomTicket').textContent = data.bottomTicket ? data.bottomTicket.no_antrian : '-';
        document.getElementById('pembayaranTicket').textContent = data.pembayaranTicket ? data.pembayaranTicket.no_antrian : '-';
        document.getElementById('permohonanSambunganTicket').textContent = data.permohonanSambunganTicket ? data.permohonanSambunganTicket.no_antrian : '-';
        document.getElementById('pengaduanTicket').textContent = data.pengaduanTicket ? data.pengaduanTicket.no_antrian : '-';
        document.getElementById('bottomTicketUser').textContent = data.bottomTicket ? `Loket ${data.bottomTicket.lokasi}` : 'Loket -';
        document.getElementById('pembayaranTicketUser').textContent = data.pembayaranTicket ? `Loket ${data.pembayaranTicket.lokasi}` : 'Loket -';
        document.getElementById('permohonanSambunganTicketUser').textContent = data.permohonanSambunganTicket ? `Loket ${data.permohonanSambunganTicket.lokasi}` : 'Loket -';
        document.getElementById('pengaduanTicketUser').textContent = data.pengaduanTicket ? `Loket ${data.pengaduanTicket.lokasi}` : 'Loket -';
    });

    socket.on('disconnect', () => {
        console.log("Disconnected from Socket.IO server!");
    });

    socket.on('carouselUpdate', (carouselData) => {
    console.log("Carousel Data:", carouselData);

    const carouselInner = document.querySelector('.carousel-inner');
    const carouselIndicators = document.querySelector('.carousel-indicators');

    if (!carouselInner || !carouselIndicators) {
        console.error("Carousel elements not found.");
        return;
    }



    carouselInner.innerHTML = '';
    carouselIndicators.innerHTML = '';

    carouselData.forEach((item, index) => {
        const carouselItem = document.createElement('div');
        carouselItem.classList.add('carousel-item'); // Remove active class here

        if (index === 0) { // Add active class only to the first item
            carouselItem.classList.add('active');
        }

        let carouselContent;
        let fileUrl = `/storage/${item.image_path}`;
let downloadButton = `<a href="${fileUrl}" download class="btn btn-primary mt-2">Download</a>`;

if (item.image_path.endsWith('.mp4') || item.image_path.endsWith('.ogg')) {
    carouselContent = `
        <video class="d-block w-100" controls autoplay loop muted style="height: 460px; object-fit: cover;">
            <source src="${fileUrl}" type="video/mp4">
            <source src="${fileUrl.replace('.mp4', '.webm')}" type="video/webm">
            Your browser does not support the video tag.
        </video>
        ${downloadButton}
    `;
} else {
    carouselContent = `
        <img src="${fileUrl}" class="d-block w-100 object-fit: contain;" alt="Carousel Image">
        ${downloadButton}
    `;
}
        carouselItem.innerHTML = carouselContent;
        carouselInner.appendChild(carouselItem);

        const indicatorButton = document.createElement('button');
        indicatorButton.type = 'button';
        indicatorButton.dataset.bsTarget = '#carouselExampleIndicators';
        indicatorButton.dataset.bsSlideTo = index;
        if (index === 0) {
            indicatorButton.classList.add('active');
            indicatorButton.setAttribute('aria-current', 'true');
        }
        indicatorButton.setAttribute('aria-label', `Slide ${index + 1}`);
        carouselIndicators.appendChild(indicatorButton);
        const prevButton = document.querySelector('.carousel-control-prev');
        const nextButton = document.querySelector('.carousel-control-next');

    if (prevButton) prevButton.remove();
    if (nextButton) nextButton.remove();
    // Initialize the Bootstrap Carousel AFTER the items have been added:

    });

    const runningTextContent = document.getElementById('running-text-content');

socket.on('runningTextUpdate', (data) => {
    try {
        if (data && Array.isArray(data) && data.length > 0) {
            let combinedText = "";
            data.forEach(text => {
                if (typeof text === 'string') {
                    combinedText += text + " • ";
                } else {
                    console.warn("Received non-string data in runningTextUpdate:", text);
                }
            });

            runningTextContent.innerHTML = combinedText;

        } else {
            runningTextContent.innerHTML = "No running text available.";
        }
    } catch (error) {
        console.error("Error handling running text update:", error);
        runningTextContent.innerHTML = "Error loading running text.";
    }
});
});

socket.on('ticketCalled', function (data) {

	speakText(`Nomor antrian ${data.no_antrian} di loket ${data.loket}`);
    });

    socket.on('ticketRecalled', function (data) {

	speakText(`Nomor antrian ${data.no_antrian} di loket ${data.loket}`);
    });
</script>

@endsection
