<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\TranslatableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{
    use TranslatableTrait;

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'old_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = Auth::user();

            if (!Hash::check($request->old_password, $user->password)) {
                throw ValidationException::withMessages([
                    'old_password' => [$this->translate('old_password_wrong')],
                ]);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json(['message' => $this->translate('password_updated')]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $this->translate('validation_error'),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $this->translate('error_changing_password'),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}