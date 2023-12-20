<?php

namespace App\Console\Commands;

use App\Http\Controllers\PurchaseOrderController;
use App\Models\AuditLog;
use App\Models\MappingInfo;
use App\Models\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class MyScheduledTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create an instance of PurchaseOrderController
        $purchaseOrderController = new PurchaseOrderController();

        // Call the sendInvoicefromFTPServer method
        $purchaseOrderController->sendInvoicefromFTPServer();
    }

}
