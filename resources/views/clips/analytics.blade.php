@extends('layouts.app')

@section('title', 'Analytics — ' . $clip->slug . ' — Clippy')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">
                <i class="fas fa-chart-bar me-2"></i>Analytics
            </h1>
            <p class="text-muted mb-0">
                <code>{{ $clip->slug }}</code> — <code>{{ $clip->short_url }}</code>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back
            </a>
            <a href="{{ route('clips.analytics.export', $clip->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-download me-1"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-number">{{ number_format($totalClicks) }}</div>
                <div class="text-muted">Total Clicks</div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-number">{{ number_format($uniqueClicks) }}</div>
                <div class="text-muted">Unique Visitors</div>
            </div>
        </div>
    </div>

    <!-- Time Series Chart (inline CSS bars) -->
    @if($timeSeries->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Clicks Over Time</h5>
            </div>
            <div class="card-body">
                @php
                    $maxCount = $timeSeries->max('count') ?: 1;
                @endphp
                <div class="d-flex align-items-end gap-1" style="height: 200px; overflow-x: auto;">
                    @foreach($timeSeries as $day)
                        @php
                            $pct = ($day->count / $maxCount) * 100;
                        @endphp
                        <div class="d-flex flex-column align-items-center" style="min-width: 40px; flex: 1;">
                            <div class="text-muted small mb-1">{{ $day->count }}</div>
                            <div style="background: var(--primary-color); width: 100%; height: {{ $pct }}%; min-height: 4px; border-radius: 4px 4px 0 0;"></div>
                            <div class="text-muted small mt-1" style="writing-mode: vertical-lr; transform: rotate(180deg); font-size: 0.65rem;">
                                {{ \Carbon\Carbon::parse($day->date)->format('M j') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="row mb-4">
        <!-- Top Referers -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-link me-2"></i>Top Referers</h5>
                </div>
                <div class="card-body p-0">
                    @if($topReferers->isEmpty())
                        <div class="text-center py-4 text-muted">No referer data</div>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Referer</th>
                                    <th class="text-end">Clicks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topReferers as $ref)
                                    <tr>
                                        <td class="url-preview" title="{{ $ref->referer }}">{{ $ref->referer }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $ref->count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Devices -->
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-mobile-alt me-2"></i>Devices</h5>
                </div>
                <div class="card-body p-0">
                    @if($topDevices->isEmpty())
                        <div class="text-center py-4 text-muted">No device data</div>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Device</th>
                                    <th class="text-end">Clicks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topDevices as $dev)
                                    <tr>
                                        <td>{{ ucfirst($dev->device_type) }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $dev->count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Browsers -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-globe me-2"></i>Browsers</h5>
                </div>
                <div class="card-body p-0">
                    @if($topBrowsers->isEmpty())
                        <div class="text-center py-4 text-muted">No browser data</div>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Browser</th>
                                    <th class="text-end">Clicks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topBrowsers as $browser)
                                    <tr>
                                        <td>{{ $browser->browser }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $browser->count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- OS -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-desktop me-2"></i>Operating Systems</h5>
                </div>
                <div class="card-body p-0">
                    @if($topOs->isEmpty())
                        <div class="text-center py-4 text-muted">No OS data</div>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>OS</th>
                                    <th class="text-end">Clicks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topOs as $os)
                                    <tr>
                                        <td>{{ $os->os }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $os->count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Countries -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Countries</h5>
                </div>
                <div class="card-body">
                    @if($countries->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-1"></i>Geo data not yet available
                        </div>
                    @else
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Country</th>
                                    <th class="text-end">Clicks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($countries as $country)
                                    <tr>
                                        <td>{{ $country->country }}</td>
                                        <td class="text-end"><span class="badge bg-primary">{{ $country->count }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Raw Events Log -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Recent Events</h5>
            <a href="{{ route('clips.analytics.export', $clip->id) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-download me-1"></i>Export All
            </a>
        </div>
        <div class="card-body p-0">
            @if($events->isEmpty())
                <div class="text-center py-5 text-muted">No events recorded</div>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>IP</th>
                                <th>Device</th>
                                <th>Browser</th>
                                <th>OS</th>
                                <th>Referer</th>
                                <th>Password</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td class="small">{{ $event->created_at->format('M j g:i A') }}</td>
                                    <td><code>{{ $event->ip ?? '—' }}</code></td>
                                    <td>{{ $event->device_type ?? '—' }}</td>
                                    <td>{{ $event->browser ?? '—' }}</td>
                                    <td>{{ $event->os ?? '—' }}</td>
                                    <td class="url-preview" title="{{ $event->referer }}">{{ $event->referer ?? '—' }}</td>
                                    <td>
                                        @if($event->passed_password)
                                            <span class="badge bg-warning">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
