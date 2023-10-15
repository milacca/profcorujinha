<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class CountVisits
{
    public function handle($request, Closure $next)
    {
        // Obtém o número atual de visitas do cache
        $visits = Cache::get('visits', 0);

        // Incrementa o número de visitas
        $visits++;

        // Armazena o número atualizado de visitas no cache por 10 minutos (ou o tempo que você preferir)
        Cache::put('visits', $visits, now()->addMinutes(10));

        return $next($request);
    }
}
