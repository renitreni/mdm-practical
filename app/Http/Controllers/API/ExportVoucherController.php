<?php

namespace App\Http\Controllers\API;

use App\Exports\VoucherExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportVoucherController extends Controller
{
    public function exportByUser(Request $request)
    {
        $userIds = collect($request->input())->pluck('user_id');

        return Excel::raw(new VoucherExport($userIds), \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportAll()
    {
        return Excel::raw(new VoucherExport, \Maatwebsite\Excel\Excel::CSV);
    }
}
