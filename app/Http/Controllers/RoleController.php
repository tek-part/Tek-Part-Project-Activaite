<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct() {
        $this->middleware('permission:roles-list', ['only' => ['index']]);
        $this->middleware('permission:roles-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }

    public function index() {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create() {
        $permissions = Permission::all()->groupBy(function($permission, $key) {
                                            $name =  explode('-', $permission->name);
                                            return $name[0].'--';
                                        });
                        
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'         => 'required|unique:roles,name',
            'display_name' => 'required',
            'permissions'  => 'required',
        ]);
        
        $role = Role::create([
            'name'         => $request->name,
            'display_name' => $request->display_name,
        ]);

        $role->syncPermissions($request->permissions);

        session()->flash('success', __('messages.create'));
        return redirect()->route('roles.index');
    }

    public function edit(int $id) {
        $role = Role::findOrFail($id);

        $permissions = Permission::all()->groupBy(function($permission, $key) {
                                            $name =  explode('-', $permission->name);
                                            return $name[0].'--';
                                        });

        $rolePermissions = DB::table('permission_role')->where('role_id', $id)->pluck('permission_id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, int $id) {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'         => 'required|unique:roles,name,'.$role->id,
            'display_name' => 'required',
            'permissions'  => 'required',
        ]);
        
        $role->update([
            'name'         => $request->name,
            'display_name' => $request->display_name,
        ]);

        $role->syncPermissions($request->permissions);

        session()->flash('success', __('messages.update'));
        return redirect()->route('roles.index');
    }

    public function destroy(int $id) {
        $role = Role::findOrFail($id);
        $role->delete();

        session()->flash('success', __('messages.delete'));
        return redirect()->route('roles.index');
    }
}