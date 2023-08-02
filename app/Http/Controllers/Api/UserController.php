<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FileService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsersCollection;

class UserController extends Controller
{
    public function loggedInUser()
    {
        try {
            $user = User::where('id', auth()->id())->get();

            return response()->json(new UsersCollection($user), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateUserImage(Request $request)
    {
        //? Validate the image
        $request->validate(['image' => 'required|image|mimes:png,jpg,jpeg']);

        //? Check the size of the image
        if ($request->height == '' || $request->width == '' || $request->top == '' || $request->left == '') {
            return response()->json(['error' => 'The dimensions are incomplete.'], 400);
        }

        try {
            $user = (new FileService)->updateImage(auth()->user(), $request);
            $user->save();

            return response()->json(['success' => 'Ok.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getUser($id)
    {

        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => 'Ok.',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateUser(Request $request)
    {
        $request->validate(['name' => 'required']);

        try {
            $user = User::findOrFail(auth()->id());

            $user->update([
                'name' => $request->name,
                'bio' => $request->bio ?? $user->bio,
            ]);

            return response()->json(['success' => 'Ok.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
