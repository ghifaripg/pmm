<?php

namespace App\Http\Controllers;

use App\Exports\KontrakManajemenExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportKontrakManajemen(Request $request)
    {
        $year = $request->query('year', date('Y'));
        return Excel::download(new KontrakManajemenExport($year), "Kontrak_Manajemen_{$year}.xlsx");
    }
}
