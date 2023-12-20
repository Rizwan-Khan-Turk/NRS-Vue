<?php
namespace App\Http\Controllers;

// include 'App\vendor\autoload.php';

use App\Models\AuditLog;
use App\Models\FileFormat;
use App\Models\MappingInfo;
use App\Models\Url;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;
use phpseclib\Net\SFTP;

use function MongoDB\BSON\toJSON;
use function Psy\debug;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;



class FtpController extends Controller

{

    public function readFileFromFtp()

    {
        // FTP server details
        $ftpHost = '129.148.156.71';
        $ftpPort = 5002;
        $ftpUsername = 'oic_integration';
        $ftpPassword = 'LSCUser@1235';
        $remoteFilePath = '/home/users/oic_integration/vendor1invoice.txt';// Adjust this to the remote file's path

        // Local file path where you want to save the downloaded file

        //$localFilePath = storage_path('public/test.xml'); // Adjust the local path as needed
        $localFilePath = 'C:\laragon\www\NRS\public\invoicefile.txt';

        // Create an SFTP instance

        $sftp = new SFTP($ftpHost, $ftpPort);

        // Login to the FTP server

        if (!$sftp->login($ftpUsername, $ftpPassword)) {
            return "FTP login failed";

        }
        // Download the file from FTP and save it locally

        // Test Comment out start
        //$sftp->get($remoteFilePath, function ($output) {
           //print_r($output);
       // });
        // end


        if ($sftp->get($remoteFilePath, $localFilePath)) {
            //$fileContent = file_get_contents($localFilePath);
            $fileContent = File::get($localFilePath);
            return $fileContent;
            //return "File downloaded successfully to $localFilePath";
        } else {
            return "Failed to download the file";
        }

        //$sftp->rename($remoteFilePath, $remoteFilePath.'test');

    }
    public function multireadFileFromFtp()
    {
        // FTP server details
        $ftpHost = '129.148.156.71';
        $ftpPort = 5002;
        $ftpUsername = 'oic_integration';
        $ftpPassword = 'LSCUser@1235';
        $remoteFilePath = '/home/users/oic_integration/invoice/'; // Adjust this to the remote file's path

        // Create an SFTP instance
        $sftp = new SFTP($ftpHost, $ftpPort);

        // Login to the FTP server
        if (!$sftp->login($ftpUsername, $ftpPassword)) {
            return "FTP login failed";
        }

        // Get a list of files in the remote directory
        $files = $sftp->nlist($remoteFilePath);
        //return $files;
        $fileContentsArray = [];
        // Iterate through each file
        foreach ($files as $fileName) {
            // Check if the file has a .txt extension
            if (pathinfo($fileName, PATHINFO_EXTENSION) == 'txt') {
                // Construct the full path to the remote file
                $remoteFile = $remoteFilePath . $fileName;

                // Read the content of the remote file
                $fileContent = $sftp->get($remoteFile);

                $fileContentsArray[$fileName] = $fileContent;
            }
        }

        return json_encode($fileContentsArray);
    }
    public function multireadFileFromFtpParam($ftpHost, $ftpPort, $ftpUsername, $ftpPassword, $remoteFilePath,$vendorID)
    {
        // Find the file format info id for Mapping Id against the vendor
        $fileformatinfo = FileFormat::where('vendor',$vendorID)->where('object', 'Invoice')->first();
        $fileformatinfoid = $fileformatinfo->id;

        // Create an SFTP instance
        $sftp = new SFTP($ftpHost, $ftpPort);

        // Login to the FTP server
        if (!$sftp->login($ftpUsername, $ftpPassword)) {
            return "FTP login failed";
        }

        // Get a list of files in the remote directory
        $files = $sftp->nlist($remoteFilePath);

        foreach ($files as $fileName) {
            // Check if the file has a .txt extension
            if (pathinfo($fileName, PATHINFO_EXTENSION) == 'txt') {
                // Construct the full path to the remote file
                $remoteFile = $remoteFilePath . $fileName;

                // Read the content of the remote file
                $fileContent = $sftp->get($remoteFile);

                try {
                    // To get the File Name
                    $file_name = $fileName;
                    // To get all the file Content
                    $file_content = $fileContent;
                    // To break each line in file for processing
                    //$lines = File::lines($file_content);
                    $lines  = explode("\n", $file_content);
                    $items = [];
                    $lineCount = 0; // Initialize a line counter
                    $totalLines = count($lines); // Total Number of lines
                    foreach ($lines as $data) {

                        // Read the mappings for each vendor against their file format info id
                        $mappings = MappingInfo::where('format_info_id', $fileformatinfoid)->get();
                        // Hard coded file format info id for testing
                        //$mappings = MappingInfo::where('format_info_id', 6)->get();

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
            }
        }
        return response()->json([
            'Status' => $status,
            'error(s)' => $error,
            'message' => $message,
            'Response' => $jsonObject,
        ], $code);

    }

}
