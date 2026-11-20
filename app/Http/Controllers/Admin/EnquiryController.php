<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.enquiries.index', [
            'enquiries' => $this->query($request)->paginate(25)->withQueryString(),
            'counts' => Enquiry::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function show(Enquiry $enquiry)
    {
        // Earlier messages from the same phone or email, so staff see the full conversation.
        return view('admin.enquiries.show', [
            'enquiry' => $enquiry->load('property.cover'),
            'history' => Enquiry::with('property:id,title,slug')
                ->whereKeyNot($enquiry->id)
                ->where(fn ($q) => $q->when($enquiry->phone, fn ($w) => $w->orWhere('phone', $enquiry->phone))
                    ->when($enquiry->email, fn ($w) => $w->orWhere('email', $enquiry->email)))
                ->latest()->take(10)->get(),
        ]);
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $enquiry->update($request->validate([
            'status' => ['required', Rule::in(array_keys(Enquiry::STATUSES))],
            'notes' => ['nullable', 'string', 'max:5000'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time' => ['nullable', Rule::in(array_keys(Enquiry::TIMES))],
        ]));

        return back()->with('status', 'Lead updated.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('status', 'Lead deleted.');
    }

    /** Download the filtered leads as a spreadsheet (CSV). */
    public function export(Request $request)
    {
        $rows = $this->query($request)->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Type', 'Status', 'Name', 'Phone', 'Email', 'Property', 'Reference', 'Viewing date', 'Viewing time', 'Message', 'Notes']);
            foreach ($rows as $e) {
                fputcsv($out, [
                    $e->created_at->format('Y-m-d H:i'), $e->typeLabel(), $e->statusLabel(), $e->name, $e->phone, $e->email,
                    $e->property?->title, $e->property?->reference, $e->preferred_date?->format('Y-m-d'),
                    Enquiry::TIMES[$e->preferred_time] ?? '', $e->message, $e->notes,
                ]);
            }
            fclose($out);
        }, 'leads-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    private function query(Request $request): Builder
    {
        return Enquiry::with('property:id,title,slug,reference')
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->when($request->query('type'), fn ($q, $t) => $q->where('type', $t))
            ->when($request->query('q'), fn ($q, $term) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")))
            ->latest();
    }
}
