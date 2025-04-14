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
        $train = Train::with(['traincompartments', 'trainupdowns'])->findOrFail($id);
        $admin = auth()->guard('admin')->user();
    
        $pdf = Pdf::loadView('pdf.train', compact('train', 'admin'));
    
        return $pdf->download('Train_Details_' . $train->trainname . '.pdf');
    }
    
    public function generatePdf($id)
    {
        $train = Train::with(['traincompartments', 'trainupdowns'])->findOrFail($id);
        $admin = auth()->guard('admin')->user();

        $pdf = Pdf::loadView('pdf.train', compact('train'));
        
        session()->flash('success', 'Train created and PDF generated successfully!');

        return $pdf->download('Train_Details_' . $train->trainname . '.pdf');
    }
    // public function generatePdf($id) is for test only, it will be removed later
}