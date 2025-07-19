@extends('layouts.app')

@section('title', $title ?? 'Error - Clippy')

@section('content')
<div class="error-page">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-8 col-xl-7">
                <div class="error-content text-center">
                    <!-- Error Code -->
                    <div class="error-code mb-4">
                        <span class="error-number">{{ $code ?? '500' }}</span>
                        <div class="error-icon">
                            @switch($code ?? '500')
                                @case('403')
                                    <i class="fas fa-lock"></i>
                                    @break
                                @case('404')
                                    <i class="fas fa-unlink"></i>
                                    @break
                                @case('419')
                                    <i class="fas fa-shield-alt"></i>
                                    @break
                                @case('429')
                                    <i class="fas fa-hourglass-half"></i>
                                    @break
                                @case('500')
                                    <i class="fas fa-server"></i>
                                    @break
                                @case('503')
                                    <i class="fas fa-tools"></i>
                                    @break
                                @default
                                    <i class="fas fa-exclamation-triangle"></i>
                            @endswitch
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div class="error-message mb-5">
                        <h1 class="error-title mb-3">
                            @switch($code ?? '500')
                                @case('403')
                                    Access Forbidden
                                    @break
                                @case('404')
                                    Page Not Found
                                    @break
                                @case('419')
                                    Session Expired
                                    @break
                                @case('429')
                                    Too Many Requests
                                    @break
                                @case('500')
                                    Server Error
                                    @break
                                @case('503')
                                    Service Unavailable
                                    @break
                                @default
                                    Something Went Wrong
                            @endswitch
                        </h1>

                        <p class="error-subtitle text-muted mb-4">
                            {{ $message ?? 'An unexpected error occurred. Please try again later.' }}
                        </p>

                        @if(isset($details))
                            <div class="error-details">
                                <small class="text-muted">{{ $details }}</small>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="error-actions">
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            @if(($code ?? '500') === '419')
                                <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-redo me-2"></i>Refresh Page
                                </a>
                            @else
                                <a href="{{ route('welcome') }}" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-home me-2"></i>Go Home
                                </a>
                            @endif

                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .error-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .error-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        animation: float 20s ease-in-out infinite;
    }

    .error-content {
        position: relative;
        z-index: 2;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .error-code {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .error-number {
        font-size: 6rem;
        font-weight: 900;
        color: var(--bs-primary);
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: pulse 2s ease-in-out infinite;
    }

    .error-icon {
        font-size: 4rem;
        color: var(--bs-danger);
        animation: bounce 2s ease-in-out infinite;
    }

    .error-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 1rem;
    }

    .error-subtitle {
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 600px;
        margin: 0 auto;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-20px);
        }
        60% {
            transform: translateY(-10px);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    @media (max-width: 768px) {
        .error-content {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .error-number {
            font-size: 4rem;
        }

        .error-icon {
            font-size: 3rem;
        }

        .error-title {
            font-size: 2rem;
        }

        .error-subtitle {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .error-code {
            flex-direction: column;
            gap: 0.5rem;
        }

        .error-number {
            font-size: 3rem;
        }

        .error-icon {
            font-size: 2rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>
@endpush
