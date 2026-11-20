<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    public function index()
    {
        return view('admin.agents.index', ['agents' => Agent::withCount('properties')->orderBy('name')->get()]);
    }

    public function create()
    {
        return view('admin.agents.form', ['agent' => new Agent(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        Agent::create($this->validated($request, new Agent));

        return redirect()->route('admin.agents.index')->with('status', 'Agent added.');
    }

    public function edit(Agent $agent)
    {
        return view('admin.agents.form', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $agent->update($this->validated($request, $agent));

        return redirect()->route('admin.agents.index')->with('status', 'Agent saved.');
    }

    public function destroy(Agent $agent)
    {
        if ($agent->photo_path) {
            Storage::disk('public')->delete($agent->photo_path);
        }
        $agent->delete();

        return back()->with('status', 'Agent removed. Their listings now show the main office contact.');
    }

    private function validated(Request $request, Agent $agent): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            if ($agent->photo_path) {
                Storage::disk('public')->delete($agent->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('agents', 'public');
        } elseif ($request->boolean('remove_photo') && $agent->photo_path) {
            Storage::disk('public')->delete($agent->photo_path);
            $data['photo_path'] = null;
        }
        unset($data['photo']);

        return $data;
    }
}
