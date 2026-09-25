<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount('appointments')->orderBy('name')->get();

        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:5|max:600',
        ]);

        // The services.description column is NOT NULL at the DB level; the
        // field itself is optional for the person filling out the form.
        $validated['description'] = $validated['description'] ?? '';

        $service = Service::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'service' => $service->loadCount('appointments'),
            ]);
        }

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:5|max:600',
        ]);

        $validated['description'] = $validated['description'] ?? '';

        $service->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'service' => $service->fresh()->loadCount('appointments'),
            ]);
        }

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Request $request, Service $service)
    {
        // Services cascade-delete their appointments at the DB level, so any
        // service with bookings on record (past, upcoming, or cancelled) must
        // be kept for historical accuracy. Only untouched services may go.
        if ($service->appointments()->exists()) {
            $message = "This service has bookings on record, so it can't be deleted. Rename it or change its price instead.";

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return redirect()->route('admin.services.index')->with('error', $message);
        }

        $service->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    /**
     * Lightweight endpoint for the inline price editor on the services page,
     * so a price tweak doesn't require the full edit form.
     */
    public function updatePrice(Request $request, Service $service)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $service->update(['price' => $validated['price']]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'price' => number_format($service->price, 2)]);
        }

        return back()->with('success', 'Price updated successfully.');
    }
}
