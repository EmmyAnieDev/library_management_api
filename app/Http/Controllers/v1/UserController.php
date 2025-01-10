<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\UserUpdateRequest;
use App\Models\User;
use App\Traits\v1\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderBy('id')->paginate(10);

        return $this->successResponse(
            [
                'data' => $users->items(),
                'current_page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'total_users' => $users->total(),
            ],
            'Successfully retrieved users'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Implement store logic
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        return $this->successResponse($user, 'User retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        $user->update($request->validated());

        return $this->successResponse($user, 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('User not found', 404);
        }

        $user->delete();

        return $this->successResponse(null, 'User deleted successfully');
    }
}
