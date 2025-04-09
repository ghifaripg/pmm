<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\IkuEvaluationsExport;

class IkuEvaluationExportController extends Controller
{
    public function export(Request $request)
    {
        $monthYear = $request->query('month-year');

        $export = new IkuEvaluationsExport($monthYear);
        return $export->export();
    }

}
