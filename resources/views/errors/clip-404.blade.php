@extends('layouts.app')

@section('title', 'Link Not Found - Clippy')

@section('content')
<div class="clip-error-page">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-8 col-xl-7">
                <div class="clip-error-content text-center">
                    <!-- Broken Link Animation -->
                    <div class="broken-link-animation mb-4">
                        @if(isset($expired) && $expired)
                            <div class="expired-clock">
                                <div class="clock-icon">
                                    <i class="fas fa-clock"></i>
                                    <div class="clock-hands">
                                        <div class="hour-hand"></div>
                                        <div class="minute-hand"></div>
                                    </div>
                                </div>
                                <div class="expired-text">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        @else
                            <div class="link-chain">
                                <div class="link-part link-1">
                                    <i class="fas fa-link"></i>
                                </div>
                                <div class="link-break">
                                    <span class="break-spark"></span>
                                    <span class="break-spark"></span>
                                    <span class="break-spark"></span>
                                </div>
                                <div class="link-part link-2">
                                    <i class="fas fa-unlink"></i>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Error Message -->
                    <div class="error-message mb-5">
                        @if(isset($expired) && $expired)
                            <h1 class="error-title mb-3 text-warning">This Link Has Expired!</h1>
                            <p class="error-subtitle text-muted mb-4">
                                The short link you're looking for has reached its expiration date and is no longer active.
                                The creator set an expiration time to protect the content or limit access.
                            </p>
                        @else
                            <h1 class="error-title mb-3">This Link is Broken!</h1>
                            <p class="error-subtitle text-muted mb-4">
                                The short link you're looking for doesn't seem to exist or has been removed.
                                Here are some possible reasons:
                            </p>
                        @endif

                        @unless(isset($expired) && $expired)
                        <!-- Possible reasons with icons -->
                        <div class="error-reasons mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="reason-item">
                                        <div class="reason-icon">
                                            <i class="fas fa-clock text-warning"></i>
                                        </div>
                                        <div class="reason-text">
                                            <strong>Link Expired</strong>
                                            <small class="d-block text-muted">The link has reached its expiration date</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="reason-item">
                                        <div class="reason-icon">
                                            <i class="fas fa-trash text-danger"></i>
                                        </div>
                                        <div class="reason-text">
                                            <strong>Link Deleted</strong>
                                            <small class="d-block text-muted">The creator removed this short link</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="reason-item">
                                        <div class="reason-icon">
                                            <i class="fas fa-keyboard text-info"></i>
                                        </div>
                                        <div class="reason-text">
                                            <strong>Typo in URL</strong>
                                            <small class="d-block text-muted">Check if the link was typed correctly</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="reason-item">
                                        <div class="reason-icon">
                                            <i class="fas fa-ban text-secondary"></i>
                                        </div>
                                        <div class="reason-text">
                                            <strong>Never Existed</strong>
                                            <small class="d-block text-muted">This link was never created</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endunless

                        <!-- Searched URL Display -->
                        @if(isset($slug))
                            <div class="searched-url mb-4">
                                <p class="text-muted mb-2">You were looking for:</p>
                                <code class="bg-light px-3 py-2 rounded">{{ url('/' . $slug) }}</code>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="error-actions">
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="{{ route('welcome') }}" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center">
                                <i class="fas fa-home me-2"></i>Go Home
                            </a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-tachometer-alt me-2"></i>My Dashboard
                                </a>
                                <a href="{{ route('clips.create') }}" class="btn btn-outline-success btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-plus me-2"></i>Create New Link
                                </a>
                            @else
                                @if(config('app.registration_enabled'))
                                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user-plus me-2"></i>Create Account
                                    </a>
                                @endif
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="help-section mt-5">
                        <div class="help-box">
                            <h6 class="mb-3">
                                <i class="fas fa-question-circle me-2"></i>Need Help?
                            </h6>
                            <p class="text-muted small mb-3">
                                If you believe this link should work, try contacting the person who shared it with you.
                            </p>
                            <div class="help-links">
                                <a href="/docs" class="text-decoration-none me-3">
                                    <i class="fas fa-book me-1"></i>Documentation
                                </a>
                                @auth
                                    <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                        <i class="fas fa-history me-1"></i>Your Links
                                    </a>
                                @else
                                    <a href="{{ route('welcome') }}" class="text-decoration-none">
                                        <i class="fas fa-info-circle me-1"></i>Learn More
                                    </a>
                                @endauth
                            </div>
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
    .clip-error-page {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .clip-error-page::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
        animation: drift 30s ease-in-out infinite;
    }

    .clip-error-content {
        position: relative;
        z-index: 2;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Broken Link Animation */
    .broken-link-animation {
        height: 120px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .link-chain {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .link-part {
        font-size: 3rem;
        color: var(--bs-primary);
        animation: shake 2s ease-in-out infinite;
    }

    .link-1 {
        animation-delay: 0s;
    }

    .link-2 {
        animation-delay: 0.5s;
        color: var(--bs-danger);
    }

    .link-break {
        position: relative;
        width: 40px;
        height: 4px;
        background: linear-gradient(90deg, var(--bs-primary) 0%, var(--bs-danger) 100%);
        border-radius: 2px;
        overflow: hidden;
    }

    .break-spark {
        position: absolute;
        width: 4px;
        height: 4px;
        background: #ffd700;
        border-radius: 50%;
        animation: spark 1.5s ease-in-out infinite;
    }

    .break-spark:nth-child(1) {
        animation-delay: 0s;
        left: 10%;
    }

    .break-spark:nth-child(2) {
        animation-delay: 0.3s;
        left: 50%;
    }

    .break-spark:nth-child(3) {
        animation-delay: 0.6s;
        left: 80%;
    }

    /* Expired Clock Animation */
    .expired-clock {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .clock-icon {
        position: relative;
        font-size: 3rem;
        color: var(--bs-warning);
        animation: clockPulse 2s ease-in-out infinite;
    }

    .clock-hands {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .hour-hand, .minute-hand {
        position: absolute;
        background: var(--bs-warning);
        border-radius: 2px;
        transform-origin: bottom center;
    }

    .hour-hand {
        width: 2px;
        height: 12px;
        top: -12px;
        left: -1px;
        animation: hourRotate 12s linear infinite;
    }

    .minute-hand {
        width: 1px;
        height: 16px;
        top: -16px;
        left: -0.5px;
        animation: minuteRotate 2s linear infinite;
    }

    .expired-text {
        font-size: 2rem;
        color: var(--bs-danger);
        animation: fadeInOut 1.5s ease-in-out infinite;
    }

    /* Error Message Styles */
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

    /* Reason Items */
    .reason-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-radius: 12px;
        background: rgba(108, 117, 125, 0.05);
        border: 1px solid rgba(108, 117, 125, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .reason-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        background: rgba(108, 117, 125, 0.08);
    }

    .reason-icon {
        font-size: 1.5rem;
        margin-top: 0.25rem;
    }

    .reason-text strong {
        color: #495057;
        font-weight: 600;
    }

    /* Searched URL */
    .searched-url code {
        font-size: 1rem;
        word-break: break-all;
    }

    /* Help Section */
    .help-section {
        border-top: 1px solid rgba(108, 117, 125, 0.2);
        padding-top: 2rem;
    }

    .help-box {
        background: rgba(13, 110, 253, 0.05);
        border: 1px solid rgba(13, 110, 253, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .help-links a {
        color: var(--bs-primary);
        font-size: 0.9rem;
        transition: opacity 0.3s ease;
    }

    .help-links a:hover {
        opacity: 0.7;
    }

    /* Animations */
    @keyframes shake {
        0%, 100% {
            transform: translateX(0);
        }
        25% {
            transform: translateX(-5px);
        }
        75% {
            transform: translateX(5px);
        }
    }

    @keyframes spark {
        0%, 100% {
            opacity: 0;
            transform: scale(0.5);
        }
        50% {
            opacity: 1;
            transform: scale(1.2);
        }
    }

    @keyframes clockPulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.8;
        }
    }

    @keyframes hourRotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes minuteRotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes fadeInOut {
        0%, 100% {
            opacity: 0.6;
        }
        50% {
            opacity: 1;
        }
    }

    @keyframes drift {
        0%, 100% {
            transform: translateX(0) translateY(0);
        }
        25% {
            transform: translateX(10px) translateY(-10px);
        }
        50% {
            transform: translateX(-5px) translateY(10px);
        }
        75% {
            transform: translateX(-10px) translateY(-5px);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .clip-error-content {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .link-part {
            font-size: 2rem;
        }

        .error-title {
            font-size: 2rem;
        }

        .error-subtitle {
            font-size: 1rem;
        }

        .reason-item {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }

        .reason-icon {
            margin-top: 0;
        }
    }

    @media (max-width: 576px) {
        .link-chain {
            gap: 0.5rem;
        }

        .link-part {
            font-size: 1.5rem;
        }

        .link-break {
            width: 20px;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .searched-url code {
            font-size: 0.8rem;
            padding: 0.5rem;
        }
    }
</style>
@endpush
