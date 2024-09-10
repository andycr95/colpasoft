<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $authUser = User::findOrFail(Auth::user()->id);
        $authUserRole = $authUser->getRoleNames()[0];
        $search = $request->search;

        if ($authUserRole != 'company') {
            if ($search) {
                $users = User::where(['status' => 1])->where('name', 'like', '%' . $search . '%')->orderBy('created_at', 'desc')->paginate(10);
            } else {
                $users = User::where(['status' => 1])->orderBy('created_at', 'desc')->paginate(10);
            }
            $roles = Role::all();
            $companies = Company::where(['status' => 1])->get();
            return view('user.users', compact('users', 'roles', 'companies', 'authUser'));
        } else {
            if ($search) {
                $users = User::where(['status' => 1, 'company_id' => $authUser->company_id])->where('name', 'like', '%' . $search . '%')->orderBy('created_at', 'desc')->paginate(10);
            } else {
                $users = User::where(['status' => 1, 'company_id' => $authUser->company_id])->orderBy('created_at', 'desc')->paginate(5);
            }
            $company = Company::findOrFail($authUser->company_id);
            return view('user.users', compact('users', 'company', 'authUser'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request...
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', Password::defaults()],
            'role_id' => 'required'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->role_id == 2) {
            if (!$request->company_id) {
                return response()->json(['error' => 'El campo empresa es requerido'], 400);
            }
            $user->company_id = $request->company_id;
        }

        $user->save();

        $role = Role::findOrFail($request->role_id);

        $user->syncRoles($role);

        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // 
        $roles = Role::all();
        $userRoles = $user->getRoleNames();
        $authUser = User::findOrFail(Auth::user()->id);
        $authUser->getRoleNames()->first();
        $companies = Company::where(['status' => 1])->get();
        return view('user.user-detail', compact('user', 'roles', 'authUser', 'companies'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        // Validate the request...
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'role' => 'required|numeric',
        ]);
        $user = User::findOrFail($id);

        $user->email = $request->email;
        $user->name = $request->name;
        $user->company_id = $request->company_id;
        $role = Role::find($request->role);
        $user->syncRoles($role);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // delete user from database
        $user->delete();

        return redirect()->back();
    }
}
