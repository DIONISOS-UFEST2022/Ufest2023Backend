<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        if (!$users) {
            return response()->json([
                'success' => false,
            ], 409);
        }
        return response()->json([
            'success' => true,
            'user'    => UserResource::collection($users),
        ], 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'nim' => 'required|digits:11|numeric|unique:users|regex:/^0+\d\d\d\d\d$/',
            'division' => 'string',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],
        ]);

        $password = Hash::make($request->password);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'nim'       => $request->nim,
            'password'  => $password,
            'division'  => $request->division,
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'users'    => new UserResource($user),
                'login_token'   => $user->createToken('userLogin')->plainTextToken,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            return response()->json([
                'success' => true,
                'user'    => new UserResource($user),
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 409);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'email|unique:users,email,' . $id . ',id',
            'nim' => 'digits:11|regex:/^0+\d\d\d\d\d$/|unique:users,nim,' . $id . ',id',
            'role_id' => 'digits:1',
            'division' => 'string',
            'password' => [
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers(),
            ],
        ]);

        $password = Hash::make($request->password);

        //only updates the requested one
        $input = collect(request()->all())->filter()->all();

        $user->update($input);
        $user->update([$input, 'password' => $password]);

        if ($user) {
            return response()->json([
                'success' => true,
                'user'    => new userResource($user),
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->forceDelete();

        if ($user) {
            return response()->json([
                'success' => true,
                'msg'    => "User " . $user->name . " has been deleted!",
            ], 201);
        } else {
            return response()->json([
                'success' => false,
            ], 409);
        }
    }
}
