<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Http\Requests\UserWithoutPasswordRequest;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\JsonResponse;




class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$users = User::with('permissions')->orderBy('id', 'desc')->get();
        //return view('users.index', compact('users')); // returns the view in the 'resources/views/users' directory named 'index.blade.php'

        $users = User::orderBy('id', 'desc')->get();
        // Return view with list of vendors
        //return view('vendors.index', compact('vendors'));
        return Inertia::render('User/Index');
    }
    public function fetchAllUsers(Request $request): JsonResponse
	{
		$users = $this->userService->fetchAllUsers($request->query('page', 1));
		return response()->json($users, 201);
	}
    public function search(Request $request): JsonResponse
	{
		$users = $this->userService->search($request->keywords);
		return response()->json($users, 201);
	}


    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }
    public function create(): Response
	{
		return Inertia::render('User/Create');
	}


    /**
     * Store a newly created resource in storage.
     */
    // public function store(UserWithoutPasswordRequest $request)
    // {

    //     $userData = $request->only('first_name', 'last_name', 'email');
    //     $user = $this->userService->createUserWithoutPassword($userData);

    //     // Get permissions from the request
    //     $permissionsData = [];
    //     $modules = [
    //         'user_management' => 'user_management',
    //         'vendor_management' => 'vendor_management',
    //         'audit_management' => 'audit_management',
    //     ];

    //     foreach ($modules as $key => $label) {
    //         $permissions = $request->input('permissions.' . $key, []);
    //         $permissionsData[] = [
    //             'module_name' => $label,
    //             'permissions' => implode(',', $permissions),
    //         ];
    //     }

    //     // Attach permissions to the user
    //     foreach ($permissionsData as $permissionData) {
    //         $user->permissions()->create($permissionData);
    //     }


    //     return response()->json(['success' => true], 200);
    // }

    public function store(Request $request)
    {
        $userData = $request->only([
                    'name', 'last_name', 'email'                  
        ]);
        // Bcrypt the password
        $userData['password'] = bcrypt($request->input('password'));
        $this->userService->createUser($userData);

		return response()->json([
			'message' => 'User successfully created!'
		], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(User $user)
    // {
    //     $permissions = $user->permissions; // Assuming there is a relationship set up
    //     return response()->json(['user' => $user, 'permissions' => $permissions]);
    // }
    public function edit($id): Response
	{
		$user = User::find($id);

		return Inertia::render('User/Edit', [
			'user' => $user
		]);
	}


    /**
     * Update the specified resource in storage.
     */

    // public function update(UpdateUserRequest $request, UserService $userService, $id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json(['success' => false, 'errors' => ['User not found.']], 404);
    //     }

    //     $data = $request->only(['first_name', 'last_name', 'status']); // Include other fields as necessary
    //     $user = $userService->update($id, $data);

    //     // Define modules
    //     $modules = [
    //         'user_management',
    //         'vendor_management',
    //         'audit_management',
    //     ];

    //     // Update permissions for each module
    //     foreach ($modules as $module) {
    //         $permissions = $request->input($module, []);
    //         $permissionString = implode(',', $permissions);

    //         // Use the updateOrCreate method to either update the existing permission or create a new one
    //         $user->permissions()->updateOrCreate(['module_name' => $module], ['permissions' => $permissionString]);
    //     }

    //     return response()->json(['success' => true], 200);
    // }

    public function update($id, Request $request)
	{
        
        $userData = $request->only([
            'name', 'last_name', 'email'                  
        ]);
		$this->userService->updateUser($id, $userData);
		return response()->json([
			'message' => 'User successfully updated!'
		], 200);
	}

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(User $user)
    // {
    //     $user->delete(); // This will soft delete the user
    //     return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    // }
    public function destroy($id): JsonResponse
	{
		$this->userService->deleteUser($id);

		return response()->json([
			'message' => 'User successfully deleted!'
		], 200);
	}
}
