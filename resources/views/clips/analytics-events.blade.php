@extends('layouts.app')

@section('title', 'Events — ' . $clip->slug . ' — Clippy')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">
                <i class="fas fa-list me-2"></i>Click Events
            </h1>
            <p class="text-muted mb-0">
                <code>{{ $clip->slug }}</code>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('clips.analytics', $clip->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-chart-bar me-1"></i>Analytics
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Dashboard
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($events->isEmpty())
                <div class="text-center py-5 text-muted">No events recorded</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>IP</th>
                                <th>Device</th>
                                <th>Browser</th>
                                <th>OS</th>
                                <th>Referer</th>
                                <th>UTM Source</th>
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
                                    <td>{{ $event->utm_source ?? '—' }}</td>
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
