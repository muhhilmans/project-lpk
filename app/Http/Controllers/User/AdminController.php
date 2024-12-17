<?php

namespace App\Http\Controllers\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataRoles = Role::whereNotIn('name', ['pembantu', 'majikan', 'superadmin'])
            ->when(Auth::user()->roles->pluck('name')->contains('owner'), function ($query) {
                $query->where('name', '!=', 'superadmin');
            })->get();

        $users = User::with('roles')
            ->whereHas('roles', function ($query) {
                if (Auth::user()->roles->pluck('name')->contains('superadmin')) {
                    $query->whereIn('name', ['owner', 'admin']);
                } elseif (Auth::user()->roles->pluck('name')->contains('owner')) {
                    $query->where('name', 'admin');
                }
            })->get();

        return view('cms.user.admin',  compact(['users', 'dataRoles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        $auth = Auth::user();
        if ($auth->roles->first()->name == 'superadmin') {
            $user->assignRole($request->role);
        } elseif ($auth->roles->first()->name == 'owner') {
            $user->assignRole('admin');
        }

        return redirect()->route('users-admin.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
