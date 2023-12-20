<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\MappingInfo;
use App\Models\Url;
use App\Models\Vendor;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use phpseclib\Net\SFTP;
use SimpleXMLElement;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcomeEmail;
use App\Models\User;


class PurchaseOrderController extends Controller
{
    public function fetchDataFromAPI()
    {
        $response = Http::get('https://official-joke-api.appspot.com/random_joke');
        if ($response->successful()) {
            $data = $response->json(); // Parse the JSON response
            // Process the data here
            return $data;
        } else {
            // Handle the error
            return redirect()->back()->with('error', 'Failed to fetch data from the API');
        }


        // return response()->json(['success' => true], 200);
    }
    public function convertToJSON()
    {
        // Sample fixed-format data
        //$data = '111112011-20-2020';
        $filePath = 'C:\laragon\www\NRS\public\file123.txt';
        if (File::exists($filePath)) {
            //test
            //$data = File::get($filePath);

            // To get the File Name
            $file_name = File::name($filePath);
            // To get all the file Content
            $file_content = File::get($filePath);
            // To break each line in file for processing
            $lines = File::lines($filePath);
            $items = [];
            foreach ($lines as $data) {

                $mappings = MappingInfo::where('vendor', 3)->get();

                if ($mappings->count() > 0) {
                    // print_r($mappings);
                    foreach ($mappings as $map) {
                        $Attribute = $map->Attribute;
                        $length = $map->Length;
                        $start_end_index = $map->start_end_index;
                        $format[$Attribute] = $length;
                        $attr_index[$Attribute] = $start_end_index;
                        $date_format = $map->date_format;

                        // Perform any operations on the retrieved data.
                    }
                }
                //return $attr_index['date'];
                $invoiceNumber = explode(',', $attr_index['invoice_number']);
                $invoiceAmount = explode(',', $attr_index['amount']);
                $invoiceDate = explode(',', $attr_index['date']);

                // Extract account number, amount, and date
                $invoiceNo = substr($data, $invoiceNumber[0] - 1, $format['invoice_number']);
                $amount = substr($data, $invoiceAmount[0] - 1, $format['amount']);
                $date = substr($data, $invoiceDate[0] - 1);

                // Create a JSON object
                $jsonObject = [
                    'invoice_number' => $invoiceNo,
                    'amount' => (int)$amount,
                    'date' => $date,
                ];
                $items[] = $jsonObject;
            }
                try {
                    //throw new \Exception("This is a deliberate exception");
                    $auditlog = new AuditLog();
                    $string = json_encode($items);
                    $auditlog->object = $string;
                    $auditlog->vendor = "2";
                    $auditlog->status = "Successful";
                    $auditlog->transactionType = "Invoice";
                    $auditlog->error_detail = "";
                    //$auditlog->file_name = $file_name;
                    //$auditlog->file_content = $file_content;
                    $auditlog->save();
                    $status = 'Success';
                    $error = false;
                    $message = '';
                    $code = 201;
                } catch (\Throwable $th) {
                    $status = 'Failed';
                    $error = true;
                    $message = "failed to create log";
                    $code = 500;
                    $auditlog = new AuditLog();
                    $auditlog->status = $status;
                    $auditlog->transactionType = "Invoice";
                    $auditlog->file_name = $file_name;
                    $auditlog->file_content = $file_content;
                    $auditlog->save();
                }
                // Return the JSON response
                //return response()->json($jsonObject);
            //}
            return response()->json([
                'Status' => $status,
                'error(s)' => $error,
                'message' => $message,
                'Response' => $jsonObject,
            ], $code);
        }
        else{
            return Response::json(['error' => $filePath]);
        }


    }
    public function sendEmail()
    {
        $user = User::find(3);
        $po_number = "PO12345678910";
        $email = $user->email;
        Mail::to($email)->send(new UserWelcomeEmail($po_number));
        return "Email sent successfully!";
    }
    public function sendInvoice()
    {
        // Sample fixed-format data
        //$data = '111112011-20-2020';
        $filePath = 'C:\laragon\www\NRS-Vue\public\vendor1invoice.txt';
        if (File::exists($filePath)) {
            try {
            //test
            //$data = File::get($filePath);

            // To get the File Name
            $file_name = File::name($filePath);
            // To get all the file Content
            $file_content = File::get($filePath);
            // To break each line in file for processing
            $lines = File::lines($filePath);
            $items = [];
            $lineCount = 0; // Initialize a line counter
            $totalLines = count($lines); // Total Number of lines
            foreach ($lines as $data) {

                $mappings = MappingInfo::where('format_info_id', 6)->get();

                if ($mappings->count() > 0) {
                    // print_r($mappings);
                    foreach ($mappings as $map) {
                        $Attribute = $map->Attribute;
                        $length = $map->Length;
                        $start_end_index = $map->start_end_index;
                        $format[$Attribute] = $length;
                        $attr_index[$Attribute] = $start_end_index;
                        $date_format = $map->date_format;

                        // Perform any operations on the retrieved data.
                    }
                }
                if ($lineCount === 0) {
                    //return $attr_index['date'];
                    $poNumber = explode(',', $attr_index['po_number']);
                    $nrsVendorId = explode(',', $attr_index['nrs_vendor_id']);
                    $ediVendorId = explode(',', $attr_index['edi_vendor_id']);
                    $statusvalue = explode(',', $attr_index['status']);
                    $paymentType = explode(',', $attr_index['payment_type']);
                    $paymentCents = explode(',', $attr_index['payment_cents']);
                    $paymentDue = explode(',', $attr_index['payment_due']);
                    $shippedDate = explode(',', $attr_index['shipped_date']);
                    $paymentClosed = explode(',', $attr_index['payment_closed']);
                    $orderNotes = explode(',', $attr_index['order_notes']);

                    // Extract account number, amount, and date
                    $poNumberVal = substr($data, $poNumber[0] - 1, $format['po_number']);
                    $nrsVendorIdVal = substr($data, $nrsVendorId[0] - 1, $format['nrs_vendor_id']);
                    $ediVendorIdVal = substr($data, $ediVendorId[0] - 1, $format['edi_vendor_id']);
                    $statusvalueVal = substr($data, $statusvalue[0] - 1, $format['status']);
                    $paymentTypeVal = substr($data, $paymentType[0] - 1, $format['payment_type']);
                    $paymentCentsVal = substr($data, $paymentCents[0] - 1, $format['payment_cents']);
                    $paymentDueVal = substr($data, $paymentDue[0] - 1, $format['payment_due']);
                    $shippedDateVal = substr($data, $shippedDate[0] - 1, $format['shipped_date']);
                    $paymentClosedVal = substr($data, $paymentClosed[0] - 1, $format['payment_closed']);
                    $orderNotesVal = substr($data, $orderNotes[0] - 1, $format['order_notes']);

                    if($paymentClosedVal === "false"){
                        $paymentClosedVal = false;
                    }
                    else{
                        $paymentClosedVal = true;
                    }
                }
                else if($lineCount === $totalLines-1){
                    $name = explode(',', $attr_index['name']);
                    $vendor_name = explode(',', $attr_index['vendor_name']);
                    $phone = explode(',', $attr_index['phone']);
                    $role =  explode(',', $attr_index['role']);
                    $email =  explode(',', $attr_index['email']);

                    $nameVal = substr($data, $name[0] - 1, $format['name']);
                    $vendor_nameVal = substr($data, $vendor_name[0] - 1, $format['vendor_name']);
                    $phoneVal = substr($data, $phone[0] - 1, $format['phone']);
                    $roleVal = substr($data, $role[0] - 1, $format['role']);
                    $emailVal = substr($data, $email[0] - 1, $format['email']);


                }
                else{
                    $upc = explode(',', $attr_index['upc']);
                    $qty_shipped = explode(',', $attr_index['qty_shipped']);
                    $cost = explode(',', $attr_index['cost']);
                    $cost_qty =  explode(',', $attr_index['cost_qty']);


                    $upcVal = substr($data, $upc[0] - 1, $format['upc']);
                    $qty_shippedVal = substr($data, $qty_shipped[0] - 1, $format['qty_shipped']);
                    $costVal = substr($data, $cost[0] - 1, $format['cost']);
                    $cost_qtyVal = substr($data, $cost_qty[0] - 1, $format['cost_qty']);

                }

               // $items[] = $jsonObject;

                // Incrementing with each line for the file
                $lineCount++;
            }
             // Create a JSON object
                $jsonObject = [
                    'po_number' => (int)$poNumberVal,
                    'nrs_vendor_id' => (int)$nrsVendorIdVal,
                    'edi_vendor_id' => $ediVendorIdVal,
                    'status' =>(int)$statusvalueVal,
                    'payment_type' =>$paymentTypeVal,
                    'payment_cents'=>(int)$paymentCentsVal,
                    'payment_due'=>$paymentDueVal,
                    'shipped_date'=>$shippedDateVal,
                    'payment_closed'=>$paymentClosedVal,
                    'order_notes'=>$orderNotesVal,
                    'items' => [
                        [
                            'upc' => (int)$upcVal,
                            'qty_shipped' => (int)$qty_shippedVal,
                            'cost' => (int)$costVal,
                            'cost_qty' => (int)$cost_qtyVal,
                        ]
                    ],
                    'contact_info' => [
                        [
                            'name'=>$nameVal,
                            'vendor_name'=>$vendor_nameVal,
                            'phone'=>$phoneVal,
                            'role'=>$roleVal,
                            'email'=>$emailVal
                        ]
                    ]

                ];
             $items = $jsonObject;
            //$jsonString = json_encode($jsonObject);


                // Audit Log Entry Work
                //throw new \Exception("This is a deliberate exception");
                $auditlog = new AuditLog();
                $string = json_encode($items);
                $auditlog->object = $string;
                $auditlog->vendor = "2";
                $auditlog->po_number = $poNumberVal;
                $auditlog->status = "Successful";
                $auditlog->transactionType = "Invoice";
                $auditlog->error_detail = "";
                //$auditlog->file_name = $file_name;
                //$auditlog->file_content = $file_content;
                $auditlog->save();
                $status = 'Success';
                $error = false;
                $message = '';
                $code = 201;

                $transactionID = $auditlog->id;
                $id = 1;
                $urlModel = Url::find($id);
                if ($urlModel) {
                    $url = $urlModel->url;

                    // Make the HTTP request with the fetched URL
                    $response = Http::post($url, [
                        'Transaction ID'=>$transactionID,
                        'invoice' => $jsonObject,
                    ]);
                }
            } catch (\Throwable $th) {
                $errormessage = $th->getMessage();
                $status = 'Failed';
                $error = true;
                $message = "failed to create log";
                $code = 500;
                $auditlog = new AuditLog();
                $auditlog->status = $status;
                $auditlog->vendor = "2";
                $auditlog->po_number = $poNumberVal;
                $auditlog->transactionType = "Invoice";
                $auditlog->file_name = $file_name;
                $auditlog->file_content = $file_content;
                $auditlog->error_detail = $errormessage;
                $auditlog->save();
            }
            // Return the JSON response
            //return response()->json($jsonObject);
            //}
            return response()->json([
                'Status' => $status,
                'error(s)' => $error,
                'message' => $message,
                'Response' => $jsonObject,
            ], $code);
        }
        else{
            return Response::json(['error' => $filePath]);
        }

    }
    public function sendInvoicefromFTPServer()
    {
        // Get vendors where status is 1 which means the vendors are active
        $vendors = Vendor::where('status', 1)->get();

        // Initialize the result array
        $results = [];

        // Loop through each vendor
        foreach ($vendors as $vendor) {
//          $vendorId = 2;

//          $vendor = Vendor::find($vendorId);

            $fileReader = new FtpController();
//         Define FTP and file path parameters
//        $ftpHost = '129.148.156.71';
//        $ftpPort = 5002;
//        $ftpUsername = 'oic_integration';
//        $ftpPassword = 'LSCUser@1235';
//        $remoteFilePath = '/home/users/oic_integration/invoice/';

            $ftpHost = $vendor->invoice_ip;
            $ftpPort = $vendor->invoice_port;
            $ftpUsername = $vendor->ftp_username_invoice;
            $ftpPassword = $vendor->ftp_password_invoice;
            $remoteFilePath = $vendor->invoice_directory;
            $vendorID = $vendor->id;


            // Pass All the connection variables into the method
            $result = $fileReader->multireadFileFromFtpParam($ftpHost, $ftpPort, $ftpUsername, $ftpPassword, $remoteFilePath, $vendorID);
            //return $result;
            // Store the result for each vendor
            $results[$vendorID] = $result;
        }
        return $results;

    }


}
