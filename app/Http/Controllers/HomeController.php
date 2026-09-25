<?php

namespace App\Http\Controllers;

use App\Models\AvailableDate;
use App\Models\Service;
use App\Providers\AppServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::all();
        $availableDates = AvailableDate::nextAvailableDates(90);
        $galleryPhotos = collect([
            '01-pink-polka-dot.jpg',
            '02-butter-yellow-gel.jpg',
            '03-nude-pink-natural.jpg',
            '04-chocolate-glossy-almond.jpg',
            '05-milky-nude-almond.jpg',
            '06-chrome-celestial-art.jpg',
            '07-milky-white-square.jpg',
            '08-polka-dot-nude-long.jpg',
        ])
            ->filter(fn (string $filename) => File::exists(public_path("cv-photos/{$filename}")))
            ->map(function ($path) {
                $filename = pathinfo($path, PATHINFO_FILENAME);

                // Optimised WebP versions (600w thumbs, 1400w for the lightbox) live in public/img/opt.
                $optimised = fn (int $width) => File::exists(public_path("img/opt/{$filename}-{$width}.webp"))
                    ? asset("img/opt/{$filename}-{$width}.webp")
                    : asset("cv-photos/{$path}");

                return [
                    'image' => $optimised(1400),
                    'thumb' => $optimised(600),
                    'caption' => Str::of(preg_replace('/^\d{2}-/', '', $filename))
                        ->replace(['-', '_'], ' ')
                        ->title()
                        ->toString(),
                ];
            })
            ->values();

        // Server-rendered price list (indexable, also used for the JSON-LD offer catalog).
        $categoryLabels = [
            'biab' => 'BIAB',
            'gel' => 'Gel Nails',
            'soft_gel' => 'Soft Gel Extensions',
            'polish' => 'Gel Polish',
            'addon' => 'Extras',
            'consultation' => 'Consultation',
            'general' => 'Other',
        ];
        $menu = collect($categoryLabels)
            ->map(fn ($label, $key) => [
                'label' => $label,
                'services' => $services
                    ->filter(fn (Service $service) => AppServiceProvider::serviceCategory($service->name) === $key)
                    ->sortBy('price')
                    ->values(),
            ])
            ->filter(fn ($group) => $group['services']->isNotEmpty());

        return view('home', compact('services', 'availableDates', 'galleryPhotos', 'menu'));
    }

    public function policies()
    {
        return view('policies');
    }

    public function cv()
    {
        return view('cv');
    }
}
