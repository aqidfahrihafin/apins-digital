<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = hrtime(true);
        $response = $next($request);

        if ($response->isSuccessful() && ! $this->isBot($request->userAgent())) {
            $userAgent = mb_strtolower($request->userAgent() ?? '');
            $referrerHost = parse_url($request->headers->get('referer', ''), PHP_URL_HOST);
            $currentHost = $request->getHost();

            PageView::create([
                'path' => '/'.ltrim($request->path(), '/'),
                'route_name' => $request->route()?->getName(),
                'page_title' => $this->pageTitle($request),
                'visitor_hash' => hash('sha256', ($request->ip() ?? 'unknown').'|'.$userAgent.'|'.config('app.key')),
                'session_hash' => hash('sha256', $request->session()->getId().'|'.config('app.key')),
                'device' => str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')
                    ? 'tablet'
                    : (preg_match('/mobile|android|iphone|ipod/', $userAgent) ? 'mobile' : 'desktop'),
                'referrer_host' => $referrerHost && $referrerHost !== $currentHost ? $referrerHost : null,
                'response_ms' => max(1, (int) round((hrtime(true) - $startedAt) / 1_000_000)),
                'viewed_at' => now(),
            ]);
        }

        return $response;
    }

    private function isBot(?string $userAgent): bool
    {
        return ! $userAgent || preg_match('/bot|crawl|spider|slurp|preview|headless|lighthouse|uptime/i', $userAgent);
    }

    private function pageTitle(Request $request): string
    {
        return match ($request->route()?->getName()) {
            'home' => 'Beranda',
            'blog.index' => 'Insight',
            'blog.show' => 'Artikel: '.($request->route('slug') ?? ''),
            'portfolio.index' => 'Karya',
            'portfolio.show' => 'Karya: '.($request->route('slug') ?? ''),
            default => ucfirst(str_replace(['-', '/'], ' ', $request->path())),
        };
    }
}
