<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::pending()->count();
        $approvedAppointments = Appointment::approved()->count();
        $totalRevenue = Payment::paid()->sum('amount');

        $recentAppointments = Appointment::with('user', 'service', 'payment')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $services = Service::orderBy('name')->get();

        // Monthly appointments data for chart (SQLite-safe)
        $monthlyAppointmentsRaw = Appointment::selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
            ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyAppointments = array_fill(1, 12, 0);
        foreach ($monthlyAppointmentsRaw as $month => $count) {
            $monthlyAppointments[(int) $month] = $count;
        }

        // Monthly revenue data for chart (SQLite-safe)
        $monthlyRevenueRaw = Payment::paid()
            ->selectRaw("strftime('%m', created_at) as month, SUM(amount) as total")
            ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyRevenue = array_fill(1, 12, 0);
        foreach ($monthlyRevenueRaw as $month => $total) {
            $monthlyRevenue[(int) $month] = $total;
        }

        // Popular services data
        $popularServices = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->selectRaw('services.name, COUNT(*) as count')
            ->groupBy('services.name')
            ->orderBy('count', 'desc')
            ->take(5)
            ->pluck('count', 'name')
            ->toArray();

        // Appointments by status for pie chart
        $appointmentsByStatus = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalAppointments',
            'pendingAppointments',
            'approvedAppointments',
            'totalRevenue',
            'recentAppointments',
            'services',
            'monthlyAppointments',
            'monthlyRevenue',
            'popularServices',
            'appointmentsByStatus'
        ))->with('appointments', $recentAppointments);
    }

    public function appointments()
    {
        $appointments = Appointment::with('user', 'service', 'payment')
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return view('admin.appointments', compact('appointments'));
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);
        
        return back()->with('success', 'Appointment status updated successfully');
    }

    public function payments()
    {
        $payments = Payment::with('appointment.user', 'appointment.service')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.payments', compact('payments'));
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->isAdmin() && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
