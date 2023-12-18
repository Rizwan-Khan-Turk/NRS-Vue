<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Services\VendorService;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;



class VendorController extends Controller
{
    private $module_name, $add, $read, $update;

    protected $vendorService;

    public function __construct(VendorService $vendorService)
    {
        //$this->module_name = config('constants.VENDOR.MODULE');
        //[$this->add, $this->read, $this->update] = array_values(config('constants.PERMISSIONS'));
        $this->vendorService = $vendorService;

    }

    /**
     * This function is used to display the list of vendors
     */
    public function index()
    {
        // Check if user has permission to access this module
        //if (checkPermission($this->module_name, [$this->read])) {
            // Get all vendors from database and order them by id in descending order
            $vendors = Vendor::orderBy('id', 'desc')->get();
            // Return view with list of vendors
            //return view('vendors.index', compact('vendors'));
            return Inertia::render('Vendor/Index');

            //return "Hello World!";
        //} else {
            // Throw an error if user does not have permission
            //abort(403, 'Unauthorized action.');
        //}
    }
    public function search(Request $request): JsonResponse
	{
		$vendors = $this->vendorService->search($request->keywords);
		return response()->json($vendors, 201);
	}

    public function fetchAllVendors(Request $request): JsonResponse
	{
		$vendors = $this->vendorService->fetchAllVendors($request->query('page', 1));
		return response()->json($vendors, 201);
	}
    public function destroy($id): JsonResponse
	{
		$this->vendorService->deleteVendor($id);

		return response()->json([
			'message' => 'Vendor successfully deleted!'
		], 200);
	}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVendorRequest $request)
    {

        if (checkPermission($this->module_name, [$this->add])) {
            $vendorData = $request->only([
                'vname', 'vcode', 'po_ip', 'po_port', 'po_directory',
                'invoice_ip', 'invoice_port', 'invoice_directory','ftp_username','ftp_password','file_pattern','ftp_username_invoice','ftp_password_invoice',
            ]);

            Vendor::create($vendorData);

            return response()->json(['message' => 'Vendor created successfully!'], 201);
        } else {
            // Throw an error if the user does not have permission
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        if (checkPermission($this->module_name, [$this->read])) {
            return response()->json($vendor);
        } else {
            // Throw an error if the user does not have permission
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        if (checkPermission($this->module_name, [$this->update])) {
            $vendor->update($request->all());
            return response()->json(['message' => 'Vendor updated successfully!', 'vendor' => $vendor]);
        } else {
            // Throw an error if the user does not have permission
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     $vendor = Vendor::find($id);

    //     if (!$vendor) {
    //         return redirect()->route('vendors.index')
    //             ->with('error', 'Vendor not found');
    //     }

    //     $vendor->delete();

    //     return redirect()->route('vendors.index')
    //         ->with('success', 'Vendor deleted successfully');
    // }
}
