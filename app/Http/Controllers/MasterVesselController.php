<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vessel;

class MasterVesselController extends Controller
{
    public function index()
    {
        // Tampilkan semua kapal urut berdasarkan nama
        $vessels = Vessel::orderBy('vessel_name', 'asc')->get();
        return view('master.vessels', compact('vessels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vessel_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
        ]);

        Vessel::create($request->all());
        return redirect()->route('master.vessels.index')->with('success', 'Data Kapal berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'vessel_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
        ]);

        $vessel = Vessel::findOrFail($id);
        $vessel->update($request->all());

        return redirect()->route('master.vessels.index')->with('success', 'Data Kapal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $vessel = Vessel::findOrFail($id);
        $vessel->delete();

        return redirect()->route('master.vessels.index')->with('success', 'Data Kapal berhasil dihapus!');
    }
}
