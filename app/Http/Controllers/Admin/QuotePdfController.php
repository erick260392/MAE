<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotePdfController extends Controller
{
    public function __invoke(Quote $quote)
    {
        $quote->load('customer', 'items.product');

        $pdf = Pdf::loadView('pdf.quote', compact('quote'))
            ->setPaper('letter', 'portrait');

        return $pdf->download("cotizacion-{$quote->folio}.pdf");
    }
}
