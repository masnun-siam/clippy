<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use App\Models\ClickEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\StreamedResponse;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function show(int $id): View
    {
        $clip = Clip::findOrFail($id);

        $totalClicks = $clip->clicks_count;

        $uniqueClicks = ClickEvent::where('clip_id', $id)
            ->select('ip', 'user_agent')
            ->distinct()
            ->count();

        $topReferers = ClickEvent::where('clip_id', $id)
            ->whereNotNull('referer')
            ->selectRaw('referer, COUNT(*) as count')
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        $topBrowsers = ClickEvent::where('clip_id', $id)
            ->whereNotNull('browser')
            ->selectRaw('browser, COUNT(*) as count')
            ->groupBy('browser')
            ->orderByDesc('count')
            ->get();

        $topOs = ClickEvent::where('clip_id', $id)
            ->whereNotNull('os')
            ->selectRaw('os, COUNT(*) as count')
            ->groupBy('os')
            ->orderByDesc('count')
            ->get();

        $topDevices = ClickEvent::where('clip_id', $id)
            ->whereNotNull('device_type')
            ->selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->get();

        $countries = ClickEvent::where('clip_id', $id)
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->orderByDesc('count')
            ->get();

        $timeSeries = ClickEvent::where('clip_id', $id)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $events = ClickEvent::where('clip_id', $id)
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('clips.analytics', compact(
            'clip',
            'totalClicks',
            'uniqueClicks',
            'topReferers',
            'topBrowsers',
            'topOs',
            'topDevices',
            'countries',
            'timeSeries',
            'events',
        ));
    }

    public function events(int $id): View
    {
        $clip = Clip::findOrFail($id);

        $events = ClickEvent::where('clip_id', $id)
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('clips.analytics-events', compact('clip', 'events'));
    }

    public function exportCsv(int $id): StreamedResponse
    {
        $clip = Clip::findOrFail($id);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"clicks-{$clip->slug}.csv\"",
        ];

        return new StreamedResponse(function () use ($id) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'IP',
                'User Agent',
                'Referer',
                'Accept Language',
                'UTM Source',
                'UTM Medium',
                'UTM Campaign',
                'UTM Term',
                'UTM Content',
                'Device',
                'Browser',
                'OS',
                'Passed Password',
                'Country',
                'Created At',
            ]);

            ClickEvent::where('clip_id', $id)
                ->orderBy('created_at')
                ->chunk(200, function ($events) use ($handle) {
                    foreach ($events as $event) {
                        fputcsv($handle, [
                            $event->id,
                            $event->ip,
                            $event->user_agent,
                            $event->referer,
                            $event->accept_language,
                            $event->utm_source,
                            $event->utm_medium,
                            $event->utm_campaign,
                            $event->utm_term,
                            $event->utm_content,
                            $event->device_type,
                            $event->browser,
                            $event->os,
                            $event->passed_password ? 'Yes' : 'No',
                            $event->country,
                            $event->created_at,
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
