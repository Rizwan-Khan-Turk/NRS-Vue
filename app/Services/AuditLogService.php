<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\AuditLog;

class AuditLogService
{
    public function createVendor(array $data): void
    {
        $vendor = Vendor::create($data);

    }

    public function updateVendor($id, array $data): void
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update($data);

    }

    public function deleteVendor($id): void
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
    }

    public function fetchAllAuditLog($page)
    {
        $perPage = 10;
     // Modify the query to retrieve vendors with status 1
     $audits = AuditLog::orderBy('created_at', 'DESC')
     ->paginate($perPage, ['*'], 'page', $page);


    return [
        'audits' => $audits,
        'links' => $audits->links(),
        'count' => AuditLog::count(),
        'meta' => [
            'currentPage' => $audits->currentPage(),
            'lastPage' => $audits->lastPage(),
            'totalItems' => $audits->total(),
            'perPage' => $audits->perPage(),
            ],
        ];
    }
    public function search($keywords)
    {
        return AuditLog::where(function ($query) use ($keywords) {
            $query->where('status', 'like', '%' . $keywords . '%')
                  ->orWhere('vendor', 'like', '%' . $keywords . '%')
                  ->orWhere('po_number', 'like', '%' . $keywords . '%')
                  ->orWhere('transactionType', 'like', '%' . $keywords . '%');
        })->get();

    }



}
