<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetLocation;
use App\Models\Vessel;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = $request->get('category', 'Computers');

        $categories = AssetCategory::all();
        $locations = AssetLocation::all();
        $vessels = Vessel::all(); // Mengambil daftar kapal

        $assets = Asset::with(['vessel', 'location', 'category'])
            ->whereHas('category', function($q) use ($selectedCategory) {
                $q->where('name', $selectedCategory);
            })
            ->orderBy('last_seen', 'desc')
            ->get();

        return view('assets.index', compact('assets', 'categories', 'locations', 'vessels', 'selectedCategory'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:asset_categories,id',
            'vessel_id' => 'required|exists:vessels,id',
            'location_id' => 'nullable|exists:asset_locations,id',
            'status' => 'required|string',
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'group_name' => 'nullable|string',
            'ip_address' => 'nullable|string',
            'mac_address' => 'nullable|string',
        ]);

        $data['asset_type'] = AssetCategory::find($request->category_id)->name;

        Asset::create($data);
        return back()->with('success', 'Asset berhasil ditambahkan!');
    }

    public function update(Request $request, Asset $asset)
    {
        $data = $request->validate([
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:asset_categories,id',
            'vessel_id' => 'required|exists:vessels,id',
            'location_id' => 'nullable|exists:asset_locations,id',
            'status' => 'required|string',
            'manufacturer' => 'nullable|string',
            'model' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'group_name' => 'nullable|string',
            'ip_address' => 'nullable|string',
            'mac_address' => 'nullable|string',
        ]);

        $data['asset_type'] = AssetCategory::find($request->category_id)->name;

        $asset->update($data);
        return back()->with('success', 'Data Asset berhasil diperbarui!');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return back()->with('success', 'Asset berhasil dihapus dari sistem!');
    }
}
