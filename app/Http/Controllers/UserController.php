<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users-list', ['only' => ['index']]);
        $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = User::with('roles');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm)
                  ->orWhere('address', 'like', $searchTerm);
            });
        }

        // Apply role filter
        if ($request->has('role') && !empty($request->role)) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('roles.id', $request->role);
            });
        }

        // Paginate results with 10 per page
        $users = $query->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email:filter|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'role'     => 'required',
            'phone'    => 'required|unique:users,phone',
            'address'  => 'nullable',
            'image'  => 'required|image',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imageName = rand() . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('users', $imageName, 'public');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'address'  => $request->address,
            'image'  => $imagePath, // Store the image path
        ]);

        $user->addRole($request->role);

        session()->flash('success', __('messages.create'));
        return redirect()->route('users.index');
    }


    public function edit(int $id)
    {
        $roles = Role::all();
        $user = User::findOrFail($id);
        return view('users.edit', compact('roles', 'user'));
    }

    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email:filter|unique:users,email,' . $user->id,
            'role'     => 'required',
            'phone'    => 'required|unique:users,phone,' . $user->id,
            'address'  => 'nullable',
            'image'    => 'nullable|image', // Image is optional during update
        ]);

        $imagePath = $user->image; // Retain the old image path if no new image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $imageName = rand() . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->storeAs('users', $imageName, 'public');
        }

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'image'    => $imagePath, // Update the image path
        ]);

        $user->roles()->sync([]);
        $user->addRole($request->role);

        session()->flash('success', __('messages.update'));
        return redirect()->route('users.index');
    }


    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->roles()->sync([]);
        $user->delete();

        session()->flash('success', __('messages.delete'));
        return redirect()->route('users.index');
    }
}
