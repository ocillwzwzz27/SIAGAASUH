<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChildController extends Controller
{
    public function index()
    {
        $children = auth()->user()->children()->with('devices')->get();
        return view('parent.children.index', compact('children'));
    }

    public function create()
    {
        return view('parent.children.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'catatan_kesehatan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama', 'tanggal_lahir', 'jenis_kelamin', 'catatan_kesehatan']);
        $data['parent_id'] = auth()->id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('children', 'public');
        }

        Child::create($data);
        return redirect()->route('parent.children.index')->with('success', 'Data anak berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $child = Child::where('parent_id', auth()->id())->findOrFail($id);
        return view('parent.children.edit', compact('child'));
    }

    public function update(Request $request, $id)
    {
        $child = Child::where('parent_id', auth()->id())->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'catatan_kesehatan' => 'nullable|string',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama', 'tanggal_lahir', 'jenis_kelamin', 'catatan_kesehatan']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('children', 'public');
        }

        $child->update($data);
        return redirect()->route('parent.children.index')->with('success', 'Data anak berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $child = Child::where('parent_id', auth()->id())->findOrFail($id);
        $child->delete();
        return back()->with('success', 'Data anak berhasil dihapus.');
    }

    // Device management
    public function addDevice(Request $request, $childId)
    {
        $child = Child::where('parent_id', auth()->id())->findOrFail($childId);
        $request->validate([
            'device_code' => 'required|string|unique:devices,device_code',
            'nama_perangkat' => 'required|string|max:100',
        ]);

        Device::create([
            'child_id' => $child->id,
            'device_code' => $request->device_code,
            'device_token' => Str::random(60),
            'nama_perangkat' => $request->nama_perangkat,
            'aktif' => true,
        ]);

        return back()->with('success', 'Perangkat berhasil ditambahkan!');
    }
}
