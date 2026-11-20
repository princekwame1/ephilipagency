<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AmenityController extends Controller
{
    public function index()
    {
        return view('admin.amenities.index', ['amenities' => Amenity::withCount('properties')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        Amenity::create($request->validate(['name' => ['required', 'string', 'max:60', 'unique:amenities,name']]));

        return back()->with('status', 'Amenity added.');
    }

    public function update(Request $request, Amenity $amenity)
    {
        $amenity->update($request->validate(['name' => ['required', 'string', 'max:60', Rule::unique('amenities', 'name')->ignore($amenity)]]));

        return back()->with('status', 'Amenity renamed.');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();

        return back()->with('status', 'Amenity deleted.');
    }
}
