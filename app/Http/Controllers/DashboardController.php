<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;




class DashboardController extends Controller
{
    public function index() {
        return Inertia::render('Dashboard');
    }
    public function getDashboardData()
    {
        $oneMonthAgo = Carbon::now()->subMonth();

        $vendorCount = Vendor::count();
        
        $successfulTransactions = DB::table('auditlog')
        ->where('status', '=', 'Successful')
        ->where('created_at', '>=', $oneMonthAgo)
        ->count();
        
        $purchaseOrderCount = DB::table('auditlog')
        ->where('transactionType', '=', 'Purchase Order')
        ->where('created_at', '>=', $oneMonthAgo)
        ->count();
        
        $invoiceCount = DB::table('auditlog')
        ->where('transactionType', '=', 'Invoice')
        ->where('created_at', '>=', $oneMonthAgo)
        ->count();

        return response()->json(['vendorCount' => $vendorCount,
        'successfulTransactions'=>$successfulTransactions,
        'purchaseOrderCount'=>$purchaseOrderCount,
        'invoiceCount'=>$invoiceCount]);
    }
}
