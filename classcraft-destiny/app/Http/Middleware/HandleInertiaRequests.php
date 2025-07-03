<?php
// app/Http/Middleware/HandleInertiaRequests.php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'nombre' => $request->user()->nombre,
                    'apellido' => $request->user()->apellido,
                    'correo' => $request->user()->correo,
                    'avatar' => $request->user()->avatar,
                    'tipo' => $request->user()->tipoUsuario->nombre,
                    'es_profesor' => $request->user()->esDocente(),
                    'es_estudiante' => $request->user()->esEstudiante(),
                    'configuracion_ui' => $request->user()->configuracion_ui,
                ] : null,
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'warning' => $request->session()->get('warning'),
                'info' => $request->session()->get('info'),
            ],
            'config' => [
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'destiny_theme' => [
                    'colors' => [
                        'primary' => '#1B1C29',
                        'accent_gold' => '#C7B88A',
                        'accent_cyan' => '#6EC1E4',
                        'accent_purple' => '#B6A1E4',
                        'panel_dark' => '#2E2F3D',
                        'panel_light' => '#3A3D53',
                    ],
                    'fonts' => [
                        'headers' => 'Orbitron',
                        'body' => 'Open Sans',
                    ]
                ]
            ]
        ]);
    }
}