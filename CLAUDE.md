# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A Laravel 12 (PHP 8.2) application for a nail studio ("Delfi Nail Technician") — a public marketing site plus a guest appointment-booking flow with a simulated deposit payment, and an admin dashboard for managing appointments, services, available time slots, and payments.

The `README.md` is the stock Laravel boilerplate — ignore it. The root also contains several AI-generated docs (`SYSTEM_DOCUMENTATION.md`, `FEATURE_MATRIX.md`, `IMPLEMENTATION_REPORT.md`, `INDEX.md`, `QUICK_START.md`, `README_PROJECT_COMPLETE.md`, `VERIFICATION_CHECKLIST.md`). Treat these as historical/aspirational, not authoritative — several describe routes, controllers (e.g. Fortify auth, `/profile`, `/my-appointments`) and a MySQL-only schema that no longer match the actual code. When in doubt, read the source (`routes/`, `app/Http/Controllers`, `app/Models`) rather than these docs.

## Commands

```bash
composer install && npm install        # install PHP + JS deps
cp .env.example .env && php artisan key:generate
php artisan migrate --seed             # sqlite by default (database/database.sqlite)

composer run dev                       # serve + queue:listen + pail (logs) + vite, concurrently
php artisan serve                      # backend only
npm run dev                            # vite only (frontend assets)
npm run build                          # production frontend build

composer test                          # config:clear + php artisan test (full suite)
php artisan test --filter=TestName     # single test method/class
php artisan test tests/Feature/BookingFlowTest.php

vendor/bin/pint                        # code style (no custom pint.json, default PSR-12 preset)
```

Tests force `DB_CONNECTION=sqlite` with an in-memory database via `phpunit.xml`, regardless of the app's `.env`.

## Architecture

**Routing** is wired in `bootstrap/app.php` (Laravel 12 style — no `Kernel.php`). Everything lives in `routes/web.php` (public site, guest booking flow, `/admin/*`); there is no `routes/api.php` or public auth scaffolding. `routes/console.php` defines `php artisan studio:admin {email} --only`, the only supported way to create/reset the admin account (never seed credentials).

**Admin auth is not a separate guard.** Admins are rows in the same `users` table with `role = 'admin'`; `AdminMiddleware` checks `isAdmin()` (guests are redirected to `/admin/login`, JSON gets 401). Login lives in `AdminAuthController` and is two-step: password (rate-limited), then a 6-digit code emailed via `AdminLoginCodeMail` and kept hashed in the session until verified (10 min, 5 attempts). `config('auth.admin_two_factor')` / `ADMIN_TWO_FACTOR` can disable it for recovery; production therefore needs working `MAIL_*` settings. Locally `MAIL_MAILER=log`, so the code appears in `storage/logs/laravel.log`.

**Booking is guest-first, not account-first.** `Appointment` rows store the customer's `name`/`email`/`phone` directly (no login required to book) and `user_id` is only populated when an authenticated user books. A single booking submission can select multiple services at once: `BookingController::store()` creates one `Appointment` row per selected service, all sharing a generated `group_id`, plus **one** `Payment` row (also tagged with `group_id`, but FK'd to only the *first* appointment) representing the shared €15 deposit (`Payment::AMOUNT`). Keep this fan-out in mind when touching booking, payment, or admin revenue logic — an "appointment" in the UI often corresponds to several `appointments` rows.

**Availability/slot logic lives in `AvailableDate`**, not on the appointment itself. Admins configure open date/time windows from the agenda modals (JSON endpoints on `admin/available-dates`, plus `available-dates/bulk` for "same hours on many weekdays"; the index route just redirects to the agenda); `AvailableDate::slotsForDate()` expands a window into 30-minute slots, and `BookingController::isTimeSlotAvailable()` / `getAvailableSlots()` cross-reference those slots against existing `appointments` (matched by total service duration, not just a single slot) to detect conflicts.

**Deposits are paid via a Revolut.me link, confirmed by hand.** `PaymentController::process()` marks the payment method as `revolut` and redirects to `REVOLUT_PAYMENT_LINK` (amount/note appended as query params); there is no webhook. Delfi checks Revolut and confirms from the agenda (`PaymentController::confirm()`), which approves the whole booking group and best-effort emails the client (failures are logged). Personal revolut.me links have weekly card-payment limits — a real gateway would plug in at `process()`/`confirm()`.

**`AdminController::dashboard()` uses SQLite-specific SQL** (`strftime(...)`) for the monthly charts. This will break if the app is ever pointed at MySQL/Postgres — check this method before changing the DB driver.

**The admin backend has its own layout** (`layouts/admin.blade.php`: sidebar on desktop, tab bar on phones, no public nav/footer/chatbot/booking sheet) and four screens: Agenda (`admin.agenda`, home after login), Bookings (`admin.appointments.index`, grouped list with filters), Services, and Stats (`admin.dashboard`). Everything is edited through Alpine modals calling JSON endpoints — there are no separate create/edit pages. The booking detail modal is shared by Agenda and Bookings via `admin/partials/booking-modal.blade.php` (`bookingManager()` is merged into each page's Alpine component). Page scripts call axios with `/admin/...` paths; both layouts set `axios.defaults.baseURL = url('/')` so this works when the app is served from a subfolder (e.g. XAMPP `localhost/delphina/public`) — keep using that pattern rather than hardcoding absolute URLs elsewhere.

**Public booking** is a 3-step Alpine flow (`partials/booking-flow.blade.php`) rendered inline on `/book` and inside a slide-up sheet (`partials/booking-sheet.blade.php`) on every other public page; `?book=1` opens the sheet. Its data comes from a view composer in `AppServiceProvider`.

**Frontend stack is Blade + Tailwind 3 + Alpine.js via Vite**, not a SPA. Tailwind is **compiled** (brand theme in `tailwind.config.js`; note `green-*` is remapped to the olive palette) — there is no CDN runtime, so run `npm run build` after changing views or new classes won't exist in `public/build`. `resources/js/app.js` bootstraps Alpine and axios. `react`/`react-dom` are in `package.json` but unused. Emails use their own inline-styled layout (`resources/views/emails/layout.blade.php`), never the site layout. The app timezone is `Europe/Dublin` (`APP_TIMEZONE`). Production runs in Docker on the okto OVH VPS behind Cloudflare Tunnel; deploy with `./deploy.sh` (see `DEPLOY.md`). The production `.env` and SQLite DB live only on the server (`~/sites/delphina/data`, bind-mounted); deploys never touch them, destructive DB commands are prohibited in production, and `scripts/backup-db.sh` backs the DB up daily (cron) and before every deploy — keep it that way.

**Models**: `User` (role-based admin flag via `isAdmin()`), `Service`, `Appointment` (belongs to `User`/`Service`, has one `Payment`, status flow `pending → approved|rejected`, then optionally `cancelled`), `Payment` (belongs to `Appointment`), `AvailableDate` (admin-managed booking windows, no direct relation to `Appointment`).
