<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserFormController extends Controller
{
    /**
     * Handle the form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:512',
            'last_name' => 'required|string|max:512',
            'role_id' => 'required|integer|exists:roles,id',
            'phone_number' => [
                'required',
                'regex:/^\+91[6-9]\d{9}$/',
                'string',
                'size:13'
            ],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'description' => 'required|string',
            'profile_image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user = \App\Models\User::create([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'role_id' => $request->input('role_id'),
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'description' => $request->input('description'),
            'profile_image_path' => $profileImagePath,
        ]);

        $users = User::with('role')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($user) {
                $user->role_name = $user->role ? $user->role->name : null;
                return $user;
            });

        return response()->json([
            'message' => 'User registered successfully!',
            'users' => $users
        ], 201);
    }
}
