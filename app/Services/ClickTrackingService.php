<?php

namespace App\Services;

use App\Models\Clip;
use App\Models\ClickEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClickTrackingService
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|googlebot|bingbot|yandex|baiduspider|duckduckbot|slackbot|discordbot|twitterbot|facebookexternalhit|whatsapp|telegrambot|linkedinbot|embedly|pinterest|curl|wget|python-requests|libwww|go-http-client|okhttp|axios|node-fetch|headlesschrome|phantomjs/i';

    public function record(Request $request, Clip $clip, bool $passedPassword = false): void
    {
        $ua = $request->userAgent();

        if ($this->isBot($ua)) {
            return;
        }

        [$device, $browser, $os] = $this->parseUserAgent($ua);

        $payload = [
            'clip_id' => $clip->id,
            'ip' => $request->ip(),
            'user_agent' => $ua,
            'referer' => $request->headers->get('referer'),
            'accept_language' => $request->headers->get('accept-language'),
            'utm_source' => $request->query('utm_source'),
            'utm_medium' => $request->query('utm_medium'),
            'utm_campaign' => $request->query('utm_campaign'),
            'utm_term' => $request->query('utm_term'),
            'utm_content' => $request->query('utm_content'),
            'device_type' => $device,
            'browser' => $browser,
            'os' => $os,
            'passed_password' => $passedPassword,
            'country' => null,
        ];

        DB::transaction(function () use ($payload, $clip) {
            ClickEvent::create($payload);
            Clip::where('id', $clip->id)->increment('clicks_count');
        });
    }

    private function isBot(?string $ua): bool
    {
        if (empty($ua)) {
            return true;
        }

        return (bool) preg_match(self::BOT_PATTERN, $ua);
    }

    private function parseUserAgent(?string $ua): array
    {
        if (empty($ua)) {
            return [null, null, null];
        }

        $device = $this->parseDevice($ua);
        $browser = $this->parseBrowser($ua);
        $os = $this->parseOs($ua);

        return [$device, $browser, $os];
    }

    private function parseDevice(string $ua): string
    {
        if (preg_match('/iPad|Tablet/i', $ua)) {
            return 'tablet';
        }

        if (preg_match('/Mobile|Android|iPhone/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function parseBrowser(string $ua): ?string
    {
        // Order matters — Edge before Chrome, Opera before Chrome
        $patterns = [
            'Edge' => '/Edg(?:e|A|iOS)\/([\d.]+)/i',
            'Opera' => '/(?:OPR|Opera)\/([\d.]+)/i',
            'Chrome' => '/Chrome\/([\d.]+)/i',
            'Firefox' => '/Firefox\/([\d.]+)/i',
            'Safari' => '/Version\/([\d.]+).*Safari/i',
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $ua)) {
                return $name;
            }
        }

        return null;
    }

    private function parseOs(string $ua): ?string
    {
        $patterns = [
            'Windows' => '/Windows NT/i',
            'macOS' => '/Mac OS X/i',
            'iOS' => '/iPhone|iPad/i',
            'Android' => '/Android/i',
            'Linux' => '/Linux/i',
        ];

        foreach ($patterns as $name => $pattern) {
            if (preg_match($pattern, $ua)) {
                return $name;
            }
        }

        return null;
    }
}
