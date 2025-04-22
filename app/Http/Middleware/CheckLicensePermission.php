<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

class CheckLicensePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Get the permission from database
        $permissionModel = Permission::where('name', $permission)->first();

        if (!$permissionModel) {
            abort(403, 'Permission not found: ' . $permission);
        }

        // Get user's role IDs
        $roleIds = DB::table('role_user')
            ->where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->pluck('role_id');

        // Check if any of the user's roles have the permission
        $hasPermission = DB::table('permission_role')
            ->where('permission_id', $permissionModel->id)
            ->whereIn('role_id', $roleIds)
            ->exists();

        // Also check for direct permission assignment
        $hasDirectPermission = DB::table('permission_user')
            ->where('permission_id', $permissionModel->id)
            ->where('user_id', $user->id)
            ->where('user_type', get_class($user))
            ->exists();

        if (!$hasPermission && !$hasDirectPermission) {
            abort(403, 'Unauthorized action. You do not have the necessary permissions.');
        }

        return $next($request);
    }
}
