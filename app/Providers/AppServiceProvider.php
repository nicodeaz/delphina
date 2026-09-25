<?php

namespace App\Providers;

use App\Models\AvailableDate;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The production SQLite file holds every booking: refuse commands that
        // wipe it (migrate:fresh/refresh/reset, db:wipe) in production.
        DB::prohibitDestructiveCommands($this->app->isProduction());

        // Behind a tunnel/proxy, use the real visitor IP (rate limits are per IP).
        if ($proxies = config('app.trusted_proxies')) {
            TrustProxies::at($proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }

        // Shared hosts often terminate HTTPS at a proxy; make sure generated
        // links, assets and canonical URLs stay on https in production.
        if ($this->app->isProduction() && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // The booking flow is rendered both inline on /book and inside the
        // slide-up sheet on every public page, so it loads its own data.
        View::composer('partials.booking-flow', function ($view) {
            $categoryLabels = [
                'consultation' => 'Consultation',
                'biab' => 'BIAB',
                'gel' => 'Gel',
                'soft_gel' => 'Extensions',
                'polish' => 'Gel Polish',
                'addon' => 'Extras',
                'general' => 'Other',
            ];

            $services = Service::orderBy('price')->get()->map(function (Service $service) use ($categoryLabels) {
                $category = self::serviceCategory($service->name);

                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'displayName' => str_contains($service->name, ' - ')
                        ? trim(explode(' - ', $service->name, 2)[1])
                        : $service->name,
                    'description' => $service->description,
                    'price' => (float) $service->price,
                    'duration' => (int) $service->duration,
                    'category' => $category,
                    'categoryLabel' => $categoryLabels[$category],
                ];
            });

            $categories = collect($categoryLabels)
                ->filter(fn ($label, $key) => $services->contains('category', $key))
                ->map(fn ($label, $key) => ['key' => $key, 'label' => $label])
                ->values();

            $view->with('bookingConfig', [
                'services' => $services->values(),
                'categories' => $categories,
                'dates' => AvailableDate::nextAvailableDates(90),
                'deposit' => Payment::AMOUNT,
                'storeUrl' => route('bookings.store'),
                'slotsUrl' => route('api.appointments.available'),
                'policiesUrl' => route('policies'),
            ]);
        });
    }

    /**
     * Same name-based grouping the service selector uses (services have no
     * category column).
     */
    public static function serviceCategory(string $name): string
    {
        $name = strtolower($name);

        return match (true) {
            str_contains($name, 'trial') || str_contains($name, 'consultation') => 'consultation',
            str_contains($name, 'biab') => 'biab',
            str_contains($name, 'soft') || str_contains($name, 'extension') => 'soft_gel',
            str_contains($name, 'polish') => 'polish',
            str_contains($name, 'add') || str_contains($name, 'repair') || str_contains($name, 'art') || str_contains($name, 'removal') => 'addon',
            str_contains($name, 'gel') => 'gel',
            default => 'general',
        };
    }
}
