<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
      // Display a listing of users
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,user,super-admin', 
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'txt'=>$request->password,
        ]);
    
        $role = Role::where('name', $request->role)->first(); 
    
        if (in_array($request->role, ['admin', 'super-admin'])) {
            $user->permissions()->detach(); 
            $permissions = $role->permissions; 
            $user->permissions()->attach($permissions->pluck('id')->toArray()); 
        }
    
        $user->roles()->sync([
            $role->id => ['model_type' => User::class]
        ]);
    
        toastr()->success('تم إضافة المستخدم بنجاح.');      
        return redirect()->back()->with('status', 'تم إضافة المستخدم بنجاح.');
    }


    // Display the specified user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Show the form for editing the specified user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,super-admin',
        ]);
    
        $user = User::findOrFail($id);
    
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
        $role = Role::where('name', $request->role)->first(); 
    
        if (in_array($request->role, ['admin', 'super-admin'])) {
            $user->permissions()->detach(); 
            $permissions = $role->permissions; 
            $user->permissions()->attach($permissions->pluck('id')->toArray()); 
        }
        $user->roles()->sync([
            $role->id => ['model_type' => User::class] // Provide the model type
        ]);
    
        toastr()->success('تم تحديث الصلاحية بنجاح');
        return redirect()->back()->with('status', 'دور المستخدم تم تعديله بنجاح.');
    }



    // Remove the specified user from storage
    public function destroy(User $user)
    {
        $user->forceDelete();
        return redirect()->route('users.index')->with('success', 'تم حذف حساب الموظف بنجاح');
    }

}
