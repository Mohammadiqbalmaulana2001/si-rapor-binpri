<?php

namespace App\Http\Controllers;

use App\Models\Raport;
use App\Services\RaportExportService;

class RaportExportController extends Controller
{
    public function pdf(Raport $raport, RaportExportService $service)
    {
        abort_unless($raport->is_finalized, 403);
        return $service->exportPdf($raport);
    }

    public function excel(Raport $raport, RaportExportService $service)
    {
        abort_unless($raport->is_finalized, 403);
        return $service->exportExcel($raport);
    }
}