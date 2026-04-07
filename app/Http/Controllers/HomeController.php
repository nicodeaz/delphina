<?php

namespace App\Http\Controllers;

use App\Models\AvailableDate;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        Service::firstOrCreate(
            ['name' => 'Trial Consultation'],
            [
                'description' => 'Short consultation to discuss your style, nail health, and service plan.',
                'price' => 0,
                'duration' => 30,
            ]
        );

        $services = Service::all();
        $availableDates = AvailableDate::nextAvailableDates(90);
        $instagramPosts = collect(glob(public_path('instagram/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}'), GLOB_BRACE) ?: [])
            ->map(function ($path) {
                $filename = pathinfo($path, PATHINFO_FILENAME);

                return [
                    'image' => '/instagram/' . basename($path),
                    'caption' => Str::of($filename)->replace(['-', '_'], ' ')->title()->toString(),
                ];
            })
            ->take(12)
            ->values();

        return view('home', compact('services', 'availableDates', 'instagramPosts'));
    }

    public function policies()
    {
        return view('policies');
    }
}
