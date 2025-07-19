@extends('layouts.app')

@section('title', 'Dashboard - Clippy')

@section('content')
<div class="container py-4">
    <!-- Hero Section -->
    <div class="hero-section rounded-4 mb-5">
        <div class="container">
            <h1 class="hero-title">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="hero-subtitle">Manage your shortened URLs and track their performance</p>
            <a href="{{ route('clips.create') }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus me-2"></i>Create New Link
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="stats-number">{{ $stats['total'] }}</div>
                <div class="text-muted">Total Links</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="stats-number">{{ $stats['active'] }}</div>
                <div class="text-muted">Active Links</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="stats-number">{{ $stats['protected'] }}</div>
                <div class="text-muted">Protected Links</div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="stats-card">
                <div class="stats-number">{{ $stats['expired'] }}</div>
                <div class="text-muted">Expired Links</div>
            </div>
        </div>
    </div>

    <!-- Links Management -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-link me-2"></i>Your Links
            </h5>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search links..." style="width: 200px;">
                <a href="{{ route('clips.create') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center">
                    <i class="fas fa-plus me-1"></i>New Link
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            @if($clips->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-link text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">No links yet</h4>
                    <p class="text-muted">Create your first shortened URL to get started!</p>
                    <a href="{{ route('clips.create') }}" class="btn btn-primary d-flex align-items-center justify-content-center">
                        <i class="fas fa-plus me-2"></i>Create Your First Link
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="clipsTable">
                        <thead>
                            <tr>
                                <th>Original URL</th>
                                <th>Short URL</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Expires</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clips as $clip)
                                <tr>
                                    <td>
                                        <div class="url-preview" title="{{ $clip->url }}">
                                            <i class="fas fa-external-link-alt me-2 text-muted"></i>
                                            {{ $clip->url }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <code class="me-2">{{ url('/' . $clip->slug) }}</code>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('{{ url('/' . $clip->slug) }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        @if($clip->expires_at && $clip->expires_at < now())
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Expired
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Active
                                            </span>
                                        @endif

                                        @if($clip->password)
                                            <span class="badge bg-warning ms-1">
                                                <i class="fas fa-lock me-1"></i>Protected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            {{ $clip->created_at->format('M j, Y') }}
                                            <div class="small">{{ $clip->created_at->format('g:i A') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($clip->expires_at)
                                            <div class="text-muted">
                                                {{ \Carbon\Carbon::parse($clip->expires_at)->format('M j, Y') }}
                                                <div class="small">{{ \Carbon\Carbon::parse($clip->expires_at)->diffForHumans() }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">Never</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="clip-actions">
                                            <a href="{{ url('/' . $clip->slug) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               target="_blank"
                                               title="Open Link">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                            <a href="{{ route('clips.edit', $clip->id) }}"
                                               class="btn btn-sm btn-outline-secondary"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('clips.destroy', $clip->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this link?')"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Copy to clipboard functionality with fallback
    function copyToClipboard(text) {
        // Try modern clipboard API first
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showCopySuccess();
            }, function(err) {
                console.error('Clipboard API failed, trying fallback: ', err);
                fallbackCopyToClipboard(text);
            });
        } else {
            // Fallback for older browsers or insecure contexts
            fallbackCopyToClipboard(text);
        }
    }

    // Fallback copy method
    function fallbackCopyToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.top = '-1000px';
        textArea.style.left = '-1000px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showCopySuccess();
            } else {
                showCopyError();
            }
        } catch (err) {
            console.error('Fallback copy failed: ', err);
            showCopyError();
        }

        document.body.removeChild(textArea);
    }

    // Show success message
    function showCopySuccess() {
        const toast = document.createElement('div');
        toast.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <i class="fas fa-check me-2"></i>Link copied to clipboard!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Show error message
    function showCopyError() {
        const toast = document.createElement('div');
        toast.className = 'alert alert-warning alert-dismissible fade show position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>Unable to copy automatically. Please copy manually.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#clipsTable tbody tr');

        tableRows.forEach(row => {
            const url = row.querySelector('td:first-child').textContent.toLowerCase();
            const shortUrl = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

            if (url.includes(searchTerm) || shortUrl.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Auto-refresh expired status every minute
    setInterval(function() {
        location.reload();
    }, 60000);
</script>
@endpush
