<?php

namespace App\Http\Controllers;

use App\Mail\UserWelcomeEmail;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\AuditLog;
use phpseclib\Net\SFTP;
use function MongoDB\BSON\toJSON;
use function Psy\debug;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor;
use App\Models\FileFormat;
use App\Models\MappingInfo;
use Exception;
use Throwable;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Services\AuditLogService;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;

class AuditLogController extends Controller
{
    private $module_name, $add, $read, $update;
    protected $auditService;
    
    public function __construct(AuditLogService $auditService)
    {
        //$this->module_name = config('constants.VENDOR.MODULE');
        //[$this->add, $this->read, $this->update] = array_values(config('constants.PERMISSIONS'));
        $this->auditService = $auditService;

    }
    public function store(Request $request)
    {
        function generateFixedFile($data,$accountNumber,$vendorId) {

            $format = [];


            //$mappings = MappingInfo::where('vendor', $vendorId)->get();
            //comment out for testing
            //$mappings = MappingInfo::where('format_info_id', 1)->get();

            // Get Vendor id via Vendor Code receieved from request body
            $vendorCode = $data['nrs_vendor_id'];

            // Set Vendor Id from Vendor Code
            $vendorObject = Vendor::where('vcode', $vendorCode)->first();
            $vendorfileformatid = $vendorObject->id;


            // Use the vendor id to get file format id
            $fileFormatInfo =FileFormat::where('vendor',$vendorfileformatid)->where('object', 'PO')->first();
            $fileFormatInfoId = $fileFormatInfo->id;

            // Use the File Format id to get the mappings for the respective vendors
            $mappings = MappingInfo::where('format_info_id', $fileFormatInfoId)->get();
            //   print_r($mappings);
            if ($mappings->count() > 0) {
                // print_r($mappings);
                foreach ($mappings as $map) {
                    $Attribute = $map->Attribute;
                    $length = $map->Length;
                    $leftpad = $map->Leftpad;
                    $format[$Attribute] = $length;
                    // Perform any operations on the retrieved data.
                }
            }
            else{
                throw new Exception("mapping not found");
            }

            //  print_r($mappings);


            // $status = 'Failed';
            // $error = true;
            // $message = "mapping not found against vendor";
            // $code = 400;
            // print_r($mappings);
            // return response()->json([
            //     'Status' => "Failed",
            //     'error(s)' => true,
            //     'message' => "mapping not found against vendor"
            // ],400);

            // print_r($format);

            // print_r($mappings[0]['Attribute']);
            // You can also perform any operations on the retrieved data.
            // }
            // Define the format for each section of the data
            // $format = [
            //     'po_number' => 20,
            //     'nrs_vendor_id' => 20,
            //     'edi_vendor_id' => 20,
            //     'ordered_date' => 15,
            //     'expected_date' => 15,
            // ];

            //$fileName =  $accountNumber.'_ff'.".txt";

            // Get the current timestamp using Carbon
            $currentTimestamp = Carbon::now()->timestamp;

            // Append the timestamp to the account number
            $fileName = $accountNumber . '_' . $currentTimestamp . '_ff.txt';
            // Specify a custom storage path (change this to your desired path)
            // This commented code is for changing file location from default storage path
            //$storagePath = 'E:/po/';
            //$fullFilePath = $storagePath . $fileName;
            // Open a file for writing
            $file = fopen($fileName, 'w');
            //$file = fopen($fullFilePath, 'w');

            // Write the header section
            $headerLine = '';
            foreach ($format as $field => $width) {
                $headerLine .= str_pad($field, $width, ' ');
            }
            // fwrite($file, $headerLine . PHP_EOL);

            // Write the data section
            $dataLine = '';
            foreach ($format as $field => $width) {
                if (isset($data[$field])) {
                    $dataLine .= str_pad(substr($data[$field], 0, $width), $width, ' ');
                }

            }
            fwrite($file, $dataLine . PHP_EOL);

            // Iterate through the 'items' section
            foreach ($data['items'] as $item) {
                // $itemFormat = [
                //     'upc' => 10,
                //     'qty' => 5,
                //     'cost' => 10,
                //     'cost_qty' => 5,
                //     'unit' => 10,
                //     'order_notes' => 20,
                // ];

                $itemLine = '';
                foreach ($format as $field => $width) {
                    if (isset($item[$field])) {
                        $itemLine .= str_pad(substr($item[$field], 0, $width), $width, ' ');
                    }

                }
                fwrite($file, $itemLine . PHP_EOL);
            }

            // Iterate through the 'ship_to' section (assuming only one 'ship_to' item)
            // $shipToFormat = [
            //     'name' => 30,
            //     'line1' => 20,
            //     'line2' => 20,
            //     'city' => 15,
            //     'state' => 2,
            //     'country' => 2,
            // ];

            foreach ($data['ship_to'] as $shipTo) {
                $shipToLine = '';
                foreach ($format as $field => $width) {
                    if (isset($shipTo[$field])) {
                        $shipToLine .= str_pad(substr($shipTo[$field], 0, $width), $width, ' ');
                    }
                }
                fwrite($file, $shipToLine . PHP_EOL);
            }

            // Iterate through the 'contact_info' section (assuming only one 'contact_info' item)
            // $contactInfoFormat = [
            //     'name' => 20,
            //     'phone' => 15,
            // ];

            foreach ($data['contact_info'] as $contactInfo) {
                $contactInfoLine = '';
                foreach ($format as $field => $width) {
                    if (isset($contactInfo[$field])) {
                        $contactInfoLine .= str_pad(substr($contactInfo[$field], 0, $width), $width, ' ');
                    }

                }
                fwrite($file, $contactInfoLine . PHP_EOL);
            }

            fclose($file);

            // comment out for testing
            // This work is for a single vendor to test it
            //$vendorId = 2;
            //$vendor = Vendor::find($vendorId);

            // this is the updated work in which the vendor code is received from request body and vendor is searched
            try {
                $vendorId = $data['nrs_vendor_id'];
                $vendor = Vendor::where('vcode', $vendorId)->first();
                if($vendor){

                }
                else{
                    throw new Exception("NRS Vendor id not found in the system");

                }

                $ftpHost = $vendor->po_ip;
                $ftpPort = $vendor->po_port;
                $ftpUsername = $vendor->ftp_username;
                $ftpPassword = $vendor->ftp_password;
            }
            catch(Throwable $th){
                $status = 'Failed';
                $error = true;
                $message = $th->getMessage();

            }

            // Create an SFTP instance
            $sftp = new SFTP($ftpHost, $ftpPort);

            // Login to the SFTP server
            if (!$sftp->login($ftpUsername, $ftpPassword)) {
                return "SFTP login failed";
            }

            // Specify the remote directory on the SFTP server where the file will be uploaded
            //$remoteDirectory = '/home/users/oic_integration/po/';
            $remoteDirectory =  $vendor->po_directory;

            // Specify the remote filename
            $remoteFilename = $fileName;

            // Upload the file to the SFTP server
            if (!$sftp->put($remoteDirectory . $remoteFilename, $fileName, SFTP::SOURCE_LOCAL_FILE)) {
                return "File upload to SFTP failed";
            } else {
                return "File uploaded to SFTP successfully";
            }

            //code...

            // echo 'Fixed file generated successfully.';
        }
        function generateCsvFile($data,$accountNumber) {
            // Specify the file path
            $filePath = $accountNumber.''.'_PO.csv';

            // Open the file for writing
            $file = fopen($filePath, 'w');

            if (!$file) {
                echo 'Unable to open the file for writing.';
                return;
            }

            // Define the CSV header with the desired format
            $csvHeader = [
                'po_number',
                'nrs_vendor_id',
                'edi_vendor_id',
                'items_upc',
                'items_qty',
                'items_cost',
                'items_cost_qty',
                'items_unit',
                'items_order_notes',
                'ship_to_name',
                'ship_to_line1',
                'ship_to_line2',
                'ship_to_city',
                'ship_to_state',
                'ship_to_country',
                'ordered_date',
                'expected_date',
                'contact_info_name',
                'contact_info_phone',
            ];

            // Write the CSV header
            fputcsv($file, $csvHeader);

            // Write the CSV data
            foreach ($data['items'] as $item) {
                $rowData = [
                    $data['po_number'],
                    $data['nrs_vendor_id'],
                    $data['edi_vendor_id'],
                    $item['upc'],
                    $item['qty'],
                    $item['cost'],
                    $item['cost_qty'],
                    $item['unit'],
                    $item['order_notes'],
                    $data['ship_to'][0]['name'],
                    $data['ship_to'][0]['line1'],
                    $data['ship_to'][0]['line2'],
                    $data['ship_to'][0]['city'],
                    $data['ship_to'][0]['state'],
                    $data['ship_to'][0]['country'],
                    $data['ordered_date'],
                    $data['expected_date'],
                    $data['contact_info'][0]['name'],
                    $data['contact_info'][0]['phone'],
                ];

                fputcsv($file, $rowData);
            }

            fclose($file);


        }
        // Validate the incoming JSON data based on your requirements
        $requestData = $request->post();
        //  print_r($requestData);
        $stringifyJson = json_encode($requestData);

        $jsonObjectData = ['jsonObjectData' => $stringifyJson];

        $rules = [
            'po_number' => 'required',
            'nrs_vendor_id' => 'required',
            'edi_vendor_id' => 'required',
            'items' => 'required',
            'ship_to' => 'required',
            'ordered_date' => 'required',
            'items.*.upc'=> 'required',
            'items.*.qty'=> 'required|integer',
            'items.*.cost'=> 'integer',
            'items.*.cost_qty'=> 'integer',
        ];


        $validator = Validator::make($request->post(), $rules);



        if($validator->fails()){

            return response()->json([
                'Status' => "Failed",
                'error(s)' => true,
                'message' => $validator->errors()
            ],400);

        }
        $status = 'Success';
        $error = false;
        $message = '';
        $code = 201;
        try {

            $vendorId = $requestData['edi_vendor_id'];
            $record = Vendor::find($vendorId);
            //$record = Vendor::where('vcode', $vendorId);
            if ($record) {
                $accountNumber = $record->vcode;
            }
            else {
                throw new Exception("Vendor id not found in the system");
            }








            // }
            // echo($fileType);
            // Generate the fixed file



            $records = FileFormat::where('vendor', $vendorId)->first();
            // if ($records) {

            if($records){
                $fileFormat = $records->file_format;
            }else {
                throw new Exception("File format not found");
            }
            if ($fileFormat == 'FFF'){
                generateFixedFile($requestData,$accountNumber,$vendorId);
            }else if ($fileFormat == 'CSV') {
                generateCsvFile($requestData,$accountNumber);
            }
        } catch (\Throwable $th) {
            $status = 'Failed';
            $error = true;
            $message = $th->getMessage();
            // $auditlog = new AuditLog();
            // $auditlog->object = $jsonObjectData['jsonObjectData'];
            // $auditlog->vendor = $requestData['edi_vendor_id'];
            // $auditlog->status = "Failed";
            // $auditlog->transactionType = "Purchase Order";
            // $auditlog->error_detail = "Error in finding file format against vendor";
            // $auditlog->save();
            //  response()->json([
            //     'Status' => "Failed",
            //     'error(s)' => true,
            //     'message' => "Error in finding file format against vendor"
            // ],500);
        }


        try {
            $auditlog = new AuditLog();
            $auditlog->object = $jsonObjectData['jsonObjectData'];
            $auditlog->vendor = $requestData['edi_vendor_id'];
            $auditlog->po_number = $requestData['po_number'];
            $auditlog->status = "Successful";
            $auditlog->transactionType = "Purchase Order";
            $auditlog->error_detail = $message;
            $auditlog->save();
            // get the ID of newly created audit log
            $auditlogid = $auditlog->id;
            if ($error != true) {
                $status = 'Successful';
                $error = false;
            }


            // return response()->json(['Status' => 'Success',
            // 'Response' => $requestData ], 201);

        } catch (\Throwable $th) {
            $status = 'Failed';
            $error = true;
            $message = "failed to create log";
            $code = 500;
            // response()->json([
            //     'Status' => "Failed",
            //     'error(s)' => true,
            //     'message' => "Error in finding file format against vendor"
            // ],500);
            // Send Email to Admin User in case of Purchase Order Failure
            $user = User::find(3);
            $email = $user->email;
            $po_number = $requestData['po_number'];
            Mail::to($email)->send(new UserWelcomeEmail($po_number));
        }
        try {
            // Save Entry in Notification Table when status of PO is not failed
            if($status!='Failed') {
                $po_number = $requestData['po_number'];
                $notification_error = "";
                $notification = new Notification();
                $notification->user_id = 1;
                $notification->notification_type = 'app';
                $notification->message = 'Purchase Order (' . $po_number . ') has been Received in System!';
                $notification->read = 0;
                $notification->auditlogid = $auditlogid;
                $notification->save();
            }
        }
        catch(\Throwable $th){
            $notification_error = "Failed to Save Notification Entry for Purchase Order with (' . $po_number . ')";
        }

        return response()->json([
            'Status' => $status,
            'error(s)' => $error,
            'message' => $message,
            'notification_error'=> $notification_error,
            'Response' => $requestData
        ],$code);






    }

    public function index()
    {
        return Inertia::render('Audit/Index');
    }
    public function fetchAllAuditLog(Request $request): JsonResponse
	{
		$audits = $this->auditService->fetchAllAuditLog($request->query('page', 1));
		return response()->json($audits, 201);
	}
    public function search(Request $request): JsonResponse
	{
		$audits = $this->auditService->search($request->keywords);
		return response()->json($audits, 201);
	}

    public function details($id)
    {
        // Check if user has permission to access this module
        //if (checkPermission($this->audit_management, [$this->read])) {
        // Get all vendors from database and order them by id in descending order
        // $id = $this->$id;
        $audit = AuditLog::where('id', $id)->first();
        // Return view with list of vendors
        return view('audit.details', compact('audit','id'));
        //} else {
        // Throw an error if user does not have permission
        //  abort(403, 'Unauthorized action.');
        //}
    }
    public function invoicedetails($id)
    {
        $audit = AuditLog::where('id', $id)->first();
        // Return view with list of vendors
        return view('audit.invoice', compact('audit','id'));
    }

    public function show(AuditLog $audit)
    {
        //if (checkPermission($this->module_name, [$this->read])) {
        return response()->json($audit);
        //} else {
        //Throw an error if the user does not have permission
        //abort(403, 'Unauthorized action.');
        //}
    }


}
