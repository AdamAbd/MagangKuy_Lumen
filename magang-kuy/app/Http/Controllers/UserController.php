<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        // Initial value
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        // Check if field is empty
        if (empty($name) or empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 'error', 'message' => 'You must enter a valid email']);
        }

        // Check if password is greater than 5 character
        if (strlen($password) < 6) {
            return response()->json(['status' => 'error', 'message' => 'Password should be min 6 character']);
        }

        // Check if user already exist
        if (User::where('email', '=', $email)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User already exists with this email']);
        }

        // Create new user
        try {
            // Run this when picture_path field is null
            if ($request->file('picture_path') == null) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                // Hash password from request
                $user->password = app('hash')->make($request->password);
                $user->goal = $request->goal;

                if ($user->save()) {
                    // If the user is saved then all requests will continue to login
                    return $this->login($request);
                }
            }
            // Run this when picture_path field is not null
            // Initial value for gambar(picture)
            $gambar = $request->file('picture_path')->getClientOriginalName();
            // Get name from request field
            $newName = "$request->name.$gambar";
            // Move picture_path to storage/user folder with new name
            $request->file('picture_path')->move('storage/user', $newName);

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            // Hash password from request
            $user->password = app('hash')->make($request->password);
            // Returns the value of picture_path
            $user->picture_path = 'storage/user/' . $newName;
            $user->goal = $request->goal;

            if ($user->save()) {
                // If the user is saved then all requests will continue to login
                return $this->login($request);
            }
        } catch (\Exception $e) {
            // If error this will run
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        // Initial value
        $email = $request->email;
        $password = $request->password;

        // Check if field is empty
        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        // All the credentials to be provided
        $credentials = request(['email', 'password']);
        $token = auth()->setTTL(7200)->attempt($credentials);

        // If credential is null this code will give error massage
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // If not get the email where email = request email
        $user = User::where('email', $email)->first();

        // Then return with token and the user [the passwrod will be hidden]
        return $this->respondWithToken($token, $user, $credentials);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function update(Request $request)
    {
        // this will run if picture_path is null
        if ($request->file('picture_path') == null) {
            try {
                // Initial user by user token
                $user = auth()->user();

                // Get user where id = user id from token
                $userData = User::where('id', $user->id)->first();
                $userData->name = $request->name;
                $userData->email = $request->email;
                $userData->goal = $request->goal;

                if ($userData->save()) {
                    $data = User::where('id', $user->id)->first();
                    return response()->json($data);
                }
            } catch (\Throwable $e) {
                return response()->json($e->getMessage());
            }
        } elseif ($request->name == null && $request->email == null && $request->goal == null) {
            try {
                $user = auth()->user();

                $gambar = $request->file('picture_path')->getClientOriginalName();
                $newName = "$user->name.$gambar";
                $request->file('picture_path')->move('storage/user', $newName);

                $userData = User::where('id', $user->id)->first();
                $userData->picture_path = 'storage/user/' . $newName;

                if ($userData->save()) {
                    $data = User::where('id', $user->id)->first();
                    return response()->json($data);
                }
            } catch (\Throwable $e) {
                return response()->json($e->getMessage());
            }
        } else {
            try {
                $user = auth()->user();

                $gambar = $request->file('picture_path')->getClientOriginalName();
                $newName = "$user->name.$gambar";
                $request->file('picture_path')->move('storage/user', $newName);

                $userData = User::where('id', $user->id)->first();
                $userData->name = $request->name;
                $userData->email = $request->email;
                $userData->goal = $request->goal;
                $userData->picture_path = 'storage/user/' . $newName;

                if ($userData->save()) {
                    $data = User::where('id', $user->id)->first();
                    return response()->json($data);
                }
            } catch (\Throwable $e) {
                return response()->json($e->getMessage());
            }
        }
    }

    public function logout()
    {
        // Logout user by token
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        // Refresh user token
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'email' => $user->email
        ]);
    }
}
