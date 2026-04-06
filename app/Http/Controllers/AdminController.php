<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
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
        
        return view('admin.dashboard', compact(
            'totalAppointments',
            'pendingAppointments',
            'approvedAppointments',
            'totalRevenue',
            'recentAppointments'
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
