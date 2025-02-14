import express from 'express';
import { createServer } from 'http';
import { Server } from "socket.io";
import pg from 'pg';
const { Pool } = pg;
import moment from 'moment-timezone';

const app = express();
const server = createServer(app);
const io = new Server(server, {
    cors: {
        origin: "http://127.0.0.1:8000",
        methods: ["GET", "POST"]
    }
});

const pool = new Pool({
    user: 'postgres',
    host: '127.0.0.1',
    database: 'test',
    password: '123',
    port: 5432,
});

pool.connect((err) => {
    if (err) {
        console.error('Error connecting to PostgreSQL:', err);
        return;
    }
    console.log('Connected to PostgreSQL');
});

io.on('connection', (socket) => {
    console.log('A user conneted');

    // Handle receiving ticket call
    socket.on('callTicket', (data) => {
        console.log(`Calling ticket: No ${data.no_antrian} at ${data.loket}`);

        // Broadcast to all clients
        io.emit('ticketCalled', {
            no_antrian: data.no_antrian,
            loket: data.loket
        });
    });

    // Handle re-calling ticket
    socket.on('recallTicket', (data) => {
        console.log(`Re-Calling ticket: No ${data.no_antrian} at ${data.loket}`);

        // Broadcast to all clients
        io.emit('ticketRecalled', {
            no_antrian: data.no_antrian,
            loket: data.loket
        });
    });

    socket.on('disconnect', () => {
        console.log('User disconnected');
    });
});


// Define functions OUTSIDE the connection handler
async function getCarouselData(pool) {
    try {
        const result = await pool.query("SELECT * FROM carousel WHERE status = 'active'");
        return result.rows;
    } catch (err) {
        console.error("Error fetching carousel data:", err);
        return [];
    }
}

async function emitCarouselData() { // Remove pool parameter here
    try {
        const carouselData = await getCarouselData(pool); // Use the pool defined outside
        io.emit('carouselUpdate', carouselData); // Use the io defined outside
    } catch (error) {
        console.error("Error in emitCarouselData:", error)
    }
}

function emitLeaderboardData() {
    const today = new Date().toISOString().slice(0, 10);

    const queries = [
        `SELECT * FROM ticket WHERE created_at::date = '${today}' AND status = true ORDER BY updated_at DESC LIMIT 1`,
        `SELECT * FROM ticket WHERE created_at::date = '${today}' AND loket = 'Pembayaran' AND status = true ORDER BY updated_at DESC LIMIT 1`,
        `SELECT * FROM ticket WHERE created_at::date = '${today}' AND loket = 'Pengaduan' AND status = true ORDER BY updated_at DESC LIMIT 1`,
        `SELECT * FROM ticket WHERE created_at::date = '${today}' AND loket = 'Permohonan Sambungan Baru' AND status = true ORDER BY updated_at DESC LIMIT 1`,
    ];

    Promise.all(queries.map(query => pool.query(query)))
        .then(results => {
            const bottomTicket = results[0].rows.length > 0 ? results[0].rows[0] : null;
            const pembayaranTicket = results[1].rows.length > 0 ? results[1].rows[0] : null;
            const pengaduanTicket = results[2].rows.length > 0 ? results[2].rows[0] : null;
            const permohonanSambunganTicket = results[3].rows.length > 0 ? results[3].rows[0] : null;

            const data = {
                bottomTicket,
                pembayaranTicket,
                pengaduanTicket,
                permohonanSambunganTicket
            };

            io.emit('leaderboardUpdate', data); // Use io.emit
        })
        .catch(err => {
            console.error("Error fetching leaderboard data:", err);
        });
}


function emitTicketCounts() {
    const todayStart = moment().tz('Asia/Makassar').startOf('day').format('YYYY-MM-DD HH:mm:ss');
    const todayEnd = moment().tz('Asia/Makassar').endOf('day').format('YYYY-MM-DD HH:mm:ss');
    const lokets = ['Pembayaran', 'Pengaduan', 'Permohonan Sambungan Baru'];

    Promise.all(lokets.map(loket => {
        const query = `
            SELECT COUNT(*) FROM ticket 
            WHERE created_at BETWEEN '${todayStart}' AND '${todayEnd}'
            AND loket = '${loket}'
            AND status = FALSE -- Use FALSE for boolean comparison
        `;
        return pool.query(query);
    }))
    .then(results => {
        const ticketCounts = lokets.map((loket, index) => ({
            loket: loket,
            count: parseInt(results[index].rows[0].count),
        }));
        io.emit('ticketCountsUpdate', ticketCounts);
    })
    .catch(err => {
        console.error("Error fetching ticket counts:", err);
    });
}

async function emitRunningText() {
    try {
      const result = await pool.query("SELECT * FROM running_text WHERE status = 'active'");
      const runningTextData = result.rows.map(row => row.content);
      io.emit('runningTextUpdate', runningTextData);
    } catch (err) {
      console.error("Error fetching running text:", err);
    }
  }

let ticketCountsInterval;
let leaderboardDataInterval;
let carouselDataInterval;
let runningTextInterval;


// Start the intervals *outside* the connection handler
ticketCountsInterval = setInterval(emitTicketCounts, 2000);
leaderboardDataInterval = setInterval(emitLeaderboardData, 2000);
runningTextInterval = setInterval(emitRunningText, 5000); // Adjust interval as needed



io.on('connection', (socket) => {
    console.log('a user connected');
    emitTicketCounts();
    emitLeaderboardData();
    emitCarouselData(); // Call the function
    emitRunningText();

    socket.on('disconnect', () => {
        console.log('user disconnected');
        clearInterval(ticketCountsInterval);
        clearInterval(leaderboardDataInterval);
        clearInterval(carouselDataInterval);
        clearInterval(runningTextInterval);
    });
});

app.post('/update-leaderboard', (req, res) => {
    io.emit('forceUpdate');
    io.emit('forceCountsUpdate');
    emitCarouselData();
    emitRunningText()
    emitLeaderboardData();
    emitTicketCounts();
    res.send('Update triggered');
});

server.listen(3000, () => {
    console.log('listening on *:3000');
});