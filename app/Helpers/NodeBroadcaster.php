<?php
// app/Helpers/NodeBroadcaster.php
namespace App\Helpers;

use GuzzleHttp\Client;

class NodeBroadcaster
{
    // Function to send broadcast data to Node.js server
    public static function broadcast($event, $data)
    {
        // Initialize Guzzle HTTP Client
        $client = new Client();

        // URL of your Node.js server
        $url = 'http://localhost:3000/broadcast';

        try {
            // Send a POST request to the Node.js server with event and data
            $client->post($url, [
                'json' => [
                    'event' => $event,
                    'data' => $data
                ]
            ]);
        } catch (\Exception $e) {
            // Log error in case of failure
            \Log::error("Error broadcasting to Node.js server: " . $e->getMessage());
        }
    }
}
