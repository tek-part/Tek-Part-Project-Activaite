<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index() {
        $user = User::findOrFail(auth('web')->user()->id);
        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request) {
        $user = User::findOrFail(auth('web')->user()->id);

        $request->validate([
            'name'    => 'required',
            'email'   => 'required|unique:users,email,'.$user->id,
            'phone'   => 'required|unique:users,phone,'.$user->id,
            'address' => 'nullable',
        ]);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'address'  => $request->address,
        ]);

        session()->flash('success', __('messages.update'));
        return back();
    }

    public function updatePicture(Request $request)
{
    try {
        // Get the authenticated user
        $user = User::findOrFail(auth()->id());

        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'image.required' => 'بالرجاء تحديد صورة من مدير الملفات',
            'image.image' => 'يجب أن يكون الملف صورة صالحة',
            'image.mimes' => 'يجب أن تكون الصورة بتنسيق: jpeg, png, jpg, gif',
            'image.max' => 'يجب ألا تزيد الصورة عن 2 ميجابايت',
        ]);

        // Delete old image if it exists
        if ($user->image && file_exists(storage_path('app/public/' . $user->image))) {
            unlink(storage_path('app/public/' . $user->image));
        }

        // Store new image
        $imageName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
        $imagePath = $request->file('image')->storeAs('users', $imageName, 'public');

        // Update user's image path
        $user->update(['image' => $imagePath]);

        // Set success message
        session()->flash('success', __('messages.update'));
        return back();
    } catch (\Exception $e) {
        // Log error for debugging
        \Log::error('Profile Picture Update Error: ' . $e->getMessage());

        // Return with error message
        return back()->with('error', __('messages.error') . ': ' . $e->getMessage());
    }
}


    public function updatePassword(Request $request) {
        try {
            $user = User::findOrFail(auth('web')->user()->id);

            $validatedData = $request->validate([
                'password'     => 'required|confirmed|min:8',
                'old_password' => 'required',
            ]);

            if(Hash::check($request->input('old_password'), $user->password)) {
                $user->password = Hash::make($validatedData['password']);
                $user->save();

                session()->flash('success', __('messages.update'));
                return back();
            } else {
                session()->flash('error', 'كلمة المرور القديمة غير متطابقة');
                return back();
            }
        } catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
}
