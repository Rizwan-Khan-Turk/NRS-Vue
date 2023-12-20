<?php

namespace App\Services;

use App\Models\User;
use Hash;
use Illuminate\Support\Str; // Add this at the top of your file


class UserService
{
    public function fetchAllUsers($page)
    {
        $perPage = 10;
        // Modify the query to retrieve users with status 1
        $users = User::orderBy('created_at', 'DESC')
        ->paginate($perPage, ['*'], 'page', $page);
    
        return [
            'users' => $users,
            'links' => $users->links(),
            'count' => User::count(),
            'meta' => [
                'currentPage' => $users->currentPage(),
                'lastPage' => $users->lastPage(),
                'totalItems' => $users->total(),
                'perPage' => $users->perPage(),
                ],
            ];
    }
    public function search($keywords)
    {
        return User::where(function ($query) use ($keywords) {
            $query->where('name', 'like', '%' . $keywords . '%')
                  ->orWhere('last_name', 'like', '%' . $keywords . '%')
                  ->orWhere('email', 'like', '%' . $keywords . '%');
        })->get();
        
    }
    public function createUser(array $data): void
    {
        $user = User::create($data);
    }
    
    public function deleteUser($id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
    public function create(array $data)
    {
        $user = new User();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        // Add other fields as necessary

        $user->save();

        return $user;
    }


    public function createUserWithoutPassword(array $data)
    {
        $password = $this->generatePassword();
        $userData = array_merge($data, ['password' => Hash::make($password)]);

        dispatch(new \App\Jobs\Users\SendUserWelcomeEmailJob($userData['email'], $password));
        return User::create($userData);
    }


    public function update(int $userId, array $data)
    {
        $user = User::findOrFail($userId); // Find user by ID or fail
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->status = $data['status']; // Add this line to update the status
        // Add other fields as necessary

        $user->save();

        return $user;
    }

    protected function generatePassword()
    {
        //return Str::random(10);
        return "nrs_password";
    }

    // Add other methods to handle updating, deleting, etc.
}
