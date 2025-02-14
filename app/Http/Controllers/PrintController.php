<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class PrintController extends Controller
{
    public function printTicket(Request $request)
    {
        $loket = $request->input('loket');
        $date = $request->input('date');
        $no_antrian = $request->input('no_antrian');
        $ticketDetails = "$loket"; // Example ticket content

        try {
            // Define your printer name (as configured on your server)
            $printerName = "EPSON TM-U325 Receipt"; // Replace with your printer's actual name

            // Connect to the printer
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);

            // Define the maximum number of characters per line for a 58mm printer
            $maxLineLength = 48;


            // Function to center text within the printer's width
            function centerText($text, $maxLineLength) {
                $textLength = strlen($text);
                $spaces = $maxLineLength - $textLength;
                $leftPadding = floor($spaces / 2);
                $rightPadding = ceil($spaces / 2);

                return str_repeat(" ", $leftPadding) . $text . str_repeat(" ", $rightPadding);
            }

            // Set text alignment and layout for a thermal printer
            $printer->selectPrintMode(Printer::MODE_FONT_A); // Standard font

            // Header
            $printer->text(centerText("Perumda Tirta Manuntung", $maxLineLength) . "\n");
            $printer->text(centerText($date, $maxLineLength) . "\n");
            $printer->feed(1);

            // Separator line
            $printer->text(str_repeat("-", $maxLineLength) . "\n");
            $printer->feed(1);

            // Ticket details (No Antrian in bold for a larger effect)
	    $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true); // Bold text for emphasis
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT | Printer::MODE_DOUBLE_WIDTH);
            $printer->text("No Antrian" . "\n");
            $printer->text($no_antrian . "\n");
            $printer->text("Loket: " . $loket . "\n");
            $printer->selectPrintMode(Printer::MODE_FONT_A);
            $printer->setEmphasis(false); // Stop bold
            $printer->feed(1);

            // Separator line
            $printer->text(str_repeat("-", $maxLineLength) . "\n");
            $printer->feed(2);

            // Footer (centered)
            $printer->text(centerText("Terima kasih", $maxLineLength) . "\n");
            $printer->text(centerText("Atas Kunjungannya! di PTMB!", $maxLineLength) . "\n");
            $printer->feed(3);

            // Cut the paper
            $printer->cut();

            // Close the printer connection
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Ticket printed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
