<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Api\V1\BaseController;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    // ─── Settings ──────────────────────────────────────────────
    public function settings()
    {
        return $this->success(Setting::first());
    }

    public function updateSettings(Request $request)
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create($request->all());
        } else {
            $setting->update($request->all());
        }
        return $this->success($setting, 'Settings updated');
    }

    // ─── Slides ────────────────────────────────────────────────
    public function slides()
    {
        return $this->success(Slide::orderBy('position')->get());
    }

    public function storeSlide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'status' => 'nullable|in:0,1',
            'image' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['image'] = $request->file('image')->store('assets/slides', 'public');
        $validated['status'] = $validated['status'] ?? 1;

        $slide = Slide::create($validated);

        return $this->success($slide, 'Slide created', 201);
    }

    public function updateSlide(Request $request, $id)
    {
        $slide = Slide::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'url' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'status' => 'nullable|in:0,1',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('assets/slides', 'public');
        }

        $slide->update($validated);

        return $this->success($slide->fresh(), 'Slide updated');
    }

    public function destroySlide($id)
    {
        Slide::findOrFail($id)->delete();
        return $this->success(null, 'Slide deleted');
    }

    // ─── Suppliers ─────────────────────────────────────────────
    public function suppliers(Request $request)
    {
        $query = Supplier::query();
        if ($search = $request->input('search')) {
            $query->where('nama', 'like', "%{$search}%");
        }
        return $this->success($query->orderBy('nama')->get());
    }

    public function storeSupplier(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        $supplier = Supplier::create($validated);

        return $this->success($supplier, 'Supplier created', 201);
    }

    public function updateSupplier(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->validate([
            'nama' => 'sometimes|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]));

        return $this->success($supplier->fresh(), 'Supplier updated');
    }

    public function destroySupplier($id)
    {
        Supplier::findOrFail($id)->delete();
        return $this->success(null, 'Supplier deleted');
    }

    // ─── Purchases ─────────────────────────────────────────────
    public function purchases(Request $request)
    {
        $query = Pembelian::with('supplier');
        if ($start = $request->input('start')) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($end = $request->input('end')) {
            $query->whereDate('created_at', '<=', $end);
        }
        return $this->success($query->orderBy('created_at', 'desc')->paginate(20));
    }

    public function storePurchase(Request $request)
    {
        $validated = $request->validate([
            'id_supplier' => 'required|exists:suppliers,id',
            'total_item' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'bayar' => 'required|numeric|min:0',
        ]);

        $purchase = Pembelian::create($validated);

        return $this->success($purchase->load('supplier'), 'Purchase recorded', 201);
    }

    public function destroyPurchase($id)
    {
        Pembelian::findOrFail($id)->delete();
        return $this->success(null, 'Purchase deleted');
    }
}
