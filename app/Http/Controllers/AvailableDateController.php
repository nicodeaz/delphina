<?php

namespace App\Http\Controllers;

use App\Models\AvailableDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvailableDateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'slot_duration' => 'required|integer|min:15|max:240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        AvailableDate::create($request->all());

        return redirect()->route('admin.available-dates.index')
            ->with('success', 'Fecha disponible creada exitosamente.');
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
            'slot_duration' => 'required|integer|min:15|max:240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $availableDate->update($request->all());

        return redirect()->route('admin.available-dates.index')
            ->with('success', 'Fecha disponible actualizada exitosamente.');
    }

    public function destroy(AvailableDate $availableDate)
    {
        $availableDate->delete();

        return redirect()->route('admin.available-dates.index')
            ->with('success', 'Fecha disponible eliminada exitosamente.');
    }
}