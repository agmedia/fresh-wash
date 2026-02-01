<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Back\Settings\Setting;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }


    public function rootView(Request $request): string
    {
        // Auth pages -> clean root (bez front bootstrap/custom.js modala)
        if (
            $request->is('login') ||
            $request->is('register') ||
            $request->is('forgot-password') ||
            $request->is('reset-password/*') ||
            $request->is('confirm-password') ||
            $request->is('verify-email') ||
            $request->is('two-factor-challenge')
        ) {
            return 'app-auth';
        }

        return $request->is('admin*') ? 'app-admin' : 'app-front';
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),

            // ⬇⬇⬇ DODAJ OVO ⬇⬇⬇
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
            // ⬆⬆⬆ DODAJ OVO ⬆⬆⬆

            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => (function () use ($request) {
                $cookie = $request->cookie('sidebar_state');

                // Accept multiple possible cookie values
                if (in_array($cookie, ['open', 'expanded', '1', 'true'], true)) {
                    return true;
                }

                if (in_array($cookie, ['closed', 'collapsed', '0', 'false'], true)) {
                    return false;
                }

                // Fallback to DB setting if available
                $user = $request->user();
                if ($user && class_exists(Setting::class)) {
                    try {
                        return Setting::get('ui', 'sidebar', $user->id, 'expanded') === 'expanded';
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }

                // Default: open
                return true;
            })(),
            'settings' => (function () use ($request) {
                $user = $request->user();

                // Theme from DB, default system
                $theme = 'system';
                $sidebar = 'expanded';

                if ($user && class_exists(Setting::class)) {
                    try {
                        $theme = Setting::get('ui', 'theme', $user->id, 'system');
                    } catch (\Throwable $e) {
                        // ignore
                    }

                    try {
                        $sidebar = Setting::get('ui', 'sidebar', $user->id, 'expanded');
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }

                // Normalize sidebar to expanded/collapsed
                if (in_array($request->cookie('sidebar_state'), ['closed', 'collapsed', '0', 'false'], true)) {
                    $sidebar = 'collapsed';
                } elseif (in_array($request->cookie('sidebar_state'), ['open', 'expanded', '1', 'true'], true)) {
                    $sidebar = 'expanded';
                }

                return [
                    'theme' => $theme,
                    'sidebar' => $sidebar,
                ];
            })(),
        ];
    }
}
