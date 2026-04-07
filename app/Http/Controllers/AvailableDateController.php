<?php

namespace App\Http\Controllers;

use App\Models\AvailableDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvailableDateController extends Controller
{
    public function index()
    {
        $availableDates = AvailableDate::orderBy('date', 'desc')->get();
        return view('admin.available-dates.index', compact('availableDates'));
    }

    public function create()
    {
        return view('admin.available-dates.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        AvailableDate::create($request->only([
            'date',
            'start_time',
            'end_time',
            'notes',
        ]));

        return redirect()->route('admin.available-dates.index')
            ->with('success', 'Available date created successfully.');
    }

    public function edit(AvailableDate $availableDate)
    {
        return view('admin.available-dates.edit', compact('availableDate'));
    }

    public function update(Request $request, AvailableDate $availableDate)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $availableDate->update($request->only([
            'date',
            'start_time',
            'end_time',
            'notes',
        ]));

        return redirect()->route('admin.available-dates.index')
            ->with('success', 'Available date updated successfully.');
    }

    public function destroy(AvailableDate $availableDate)
    {
        $availableDate->delete();

        return redirect()->route('admin.available-dates.index')
            ->with('success', 'Available date deleted successfully.');
    }
}