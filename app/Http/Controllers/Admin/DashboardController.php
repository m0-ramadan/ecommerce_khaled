<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GiftCard;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCoordinate;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('admin.home');
    }
    
    public function userStatistics()
    {
        $statistics = UserCoordinate::getUserStatistics();
        return view('admin.statistics', compact('statistics'));

    }

    public function signout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function showChangePasswordForm(){
        return view('admin.changePassword');
    }

    public function changePassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::guard('admin')->user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $user = Auth::guard('admin')->user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        toastr()->success('تــم تغير كلمة المرور بنجــاح');
        return redirect()->back()->with("success","Password changed successfully !");

    }

    public function addAdminForm()
    {
        return view('admin.admins.index');
    }

    public function prient($id)
    {
        $giftCards = GiftCard::where('id',$id)->first();
        return view('admin.giftCards.prient',compact('giftCards'));
    }
}
