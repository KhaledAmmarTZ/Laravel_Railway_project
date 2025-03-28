<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Train;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function downloadTrainPdf($id)
    {
        // Fetch train details
        $train = Train::with(['traincompartments', 'trainupdowns'])->findOrFail($id);

        // Generate PDF
        $pdf = Pdf::loadView('pdf.train', compact('train'));

        // Download the PDF with a dynamic filename
        return $pdf->download('Train_Details_' . $train->trainname . '.pdf');
    }
    public function generatePdf($id)
    {
        // Fetch train details along with compartments and routes
        $train = Train::with(['traincompartments', 'trainupdowns'])->findOrFail($id);

        // Generate PDF with the train details
        $pdf = Pdf::loadView('pdf.train', compact('train'));
        
        session()->flash('success', 'Train created and PDF generated successfully!');
        // Download the PDF with a dynamic filename
        return $pdf->download('Train_Details_' . $train->trainname . '.pdf');
    }
}