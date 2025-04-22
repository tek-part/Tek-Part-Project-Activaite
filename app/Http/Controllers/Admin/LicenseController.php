<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Product;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
        $this->middleware('auth'); // Ensure user is authenticated
    }

    /**
     * Display a listing of the licenses.
     */
    public function index()
    {
        $licenses = License::with('product')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.licenses.index', compact('licenses'));
    }

    /**
     * Show the form for creating a new license.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.licenses.create', compact('products'));
    }

    /**
     * Store a newly created license in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'product_id' => 'required|string|exists:products,product_id',
            'domain' => 'nullable|string|max:255',
            'is_active' => 'required',
            'is_permanent' => 'required',
            'expires_at' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['is_permanent'] = $request->has('is_permanent');

        $license = $this->licenseService->createLicense($data);

        return redirect()->route('admin.licenses.show', $license->id)
            ->with('success', 'License created successfully.');
    }

    /**
     * Display the specified license.
     */
    public function show(License $license)
    {
        $license->load('product', 'logs');
        return view('admin.licenses.show', compact('license'));
    }

    /**
     * Show the form for editing the specified license.
     */
    public function edit(License $license)
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.licenses.edit', compact('license', 'products'));
    }

    /**
     * Update the specified license in storage.
     */
    public function update(Request $request, License $license)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_permanent' => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $license->update([
            'client_name' => $request->client_name,
            'domain' => $request->domain,
            'is_active' => $request->has('is_active'),
            'is_permanent' => $request->has('is_permanent'),
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.licenses.show', $license->id)
            ->with('success', 'License updated successfully.');
    }

    /**
     * Remove the specified license from storage.
     */
    public function destroy(License $license)
    {
        $license->delete();
        return redirect()->route('admin.licenses.index')
            ->with('success', 'License deleted successfully.');
    }

    /**
     * Regenerate activation code for a license
     */
    public function regenerateActivationCode(License $license)
    {
        $license->activation_code = License::generateActivationCode();
        $license->save();

        return redirect()->route('admin.licenses.show', $license->id)
            ->with('success', 'Activation code regenerated successfully.');
    }

    /**
     * Deactivate a license
     */
    public function deactivate(License $license)
    {
        $license->is_active = false;
        $license->save();

        return redirect()->route('admin.licenses.show', $license->id)
            ->with('success', 'License deactivated successfully.');
    }

    /**
     * Activate a license
     */
    public function activate(License $license)
    {
        $license->is_active = true;
        $license->save();

        return redirect()->route('admin.licenses.show', $license->id)
            ->with('success', 'License activated successfully.');
    }

    /**
     * Revoke a license
     */
    public function revoke(License $license)
    {
        $license->status = 'revoked';
        $license->is_active = false;
        $license->save();

        return redirect()->back()
            ->with('success', 'License revoked successfully.');
    }
}
