@extends('layouts.app')

@section('title', 'Page Not Found - Clippy')

@section('content')
<div class="error-page">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-8 col-xl-7">
                <div class="error-content text-center">
                    <!-- Animated 404 -->
                    <div class="error-number mb-4">
                        <div class="error-digit error-4-1">4</div>
                        <div class="error-digit error-0">
                            <div class="error-zero">
                                <div class="error-zero-inner">
                                    <i class="fas fa-unlink"></i>
                                </div>
                            </div>
                        </div>
                        <div class="error-digit error-4-2">4</div>
                    </div>

                    <!-- Error Message -->
                    <div class="error-message mb-5">
                        <h1 class="error-title mb-3">Oops! Link Not Found</h1>
                        <p class="error-subtitle text-muted mb-4">
                            The short link you're looking for seems to have vanished into the digital void.
                            It might have expired, been deleted, or never existed in the first place.
                        </p>

                        <!-- Possible reasons -->
                        <div class="error-reasons mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="reason-card">
                                        <i class="fas fa-clock text-warning mb-2"></i>
                                        <h6>Expired Link</h6>
                                        <small class="text-muted">The link may have reached its expiration date</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="reason-card">
                                        <i class="fas fa-trash text-danger mb-2"></i>
                                        <h6>Deleted Link</h6>
                                        <small class="text-muted">The creator might have removed this link</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="reason-card">
                                        <i class="fas fa-question text-info mb-2"></i>
                                        <h6>Invalid URL</h6>
                                        <small class="text-muted">The link address might be incorrect</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="error-actions">
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="{{ route('welcome') }}" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center">
                                <i class="fas fa-home me-2"></i>Go Home
                            </a>
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a>
                                <a href="{{ route('clips.create') }}" class="btn btn-outline-success btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-plus me-2"></i>Create New Link
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-center">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Fun Animation -->
                    <div class="error-animation mt-5">
                        <div class="floating-links">
                            <div class="floating-link" style="--delay: 0s;">
                                <i class="fas fa-link"></i>
                            </div>
                            <div class="floating-link" style="--delay: 0.5s;">
                                <i class="fas fa-chain-broken"></i>
                            </div>
                            <div class="floating-link" style="--delay: 1s;">
                                <i class="fas fa-unlink"></i>
                            </div>
                            <div class="floating-link" style="--delay: 1.5s;">
                                <i class="fas fa-external-link-alt"></i>
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

    /* Animated 404 Number */
    .error-number {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .error-digit {
        font-size: 6rem;
        font-weight: 900;
        color: var(--bs-primary);
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        animation: bounce 2s ease-in-out infinite;
    }

    .error-4-1 {
        animation-delay: 0s;
    }

    .error-4-2 {
        animation-delay: 0.4s;
    }

    .error-zero {
        position: relative;
        width: 6rem;
        height: 6rem;
        border: 8px solid var(--bs-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: spin 3s linear infinite;
    }

    .error-zero-inner {
        font-size: 2rem;
        color: var(--bs-primary);
        animation: spin-reverse 3s linear infinite;
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

    /* Reason Cards */
    .reason-card {
        padding: 1.5rem 1rem;
        border-radius: 12px;
        background: rgba(108, 117, 125, 0.05);
        border: 1px solid rgba(108, 117, 125, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .reason-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        background: rgba(108, 117, 125, 0.08);
    }

    .reason-card i {
        font-size: 2rem;
        display: block;
    }

    .reason-card h6 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    /* Floating Animation */
    .floating-links {
        position: relative;
        height: 100px;
        margin-top: 2rem;
    }

    .floating-link {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.5rem;
        color: rgba(var(--bs-primary-rgb), 0.3);
        animation: float-around 4s ease-in-out infinite;
        animation-delay: var(--delay);
    }

    .floating-link:nth-child(1) {
        left: 20%;
    }

    .floating-link:nth-child(2) {
        left: 40%;
    }

    .floating-link:nth-child(3) {
        left: 60%;
    }

    .floating-link:nth-child(4) {
        left: 80%;
    }

    /* Animations */
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

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes spin-reverse {
        from {
            transform: rotate(360deg);
        }
        to {
            transform: rotate(0deg);
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

    @keyframes float-around {
        0%, 100% {
            transform: translate(-50%, -50%) translateY(0px);
            opacity: 0.3;
        }
        50% {
            transform: translate(-50%, -50%) translateY(-30px);
            opacity: 0.7;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .error-content {
            padding: 2rem 1.5rem;
            margin: 1rem;
        }

        .error-digit {
            font-size: 4rem;
        }

        .error-zero {
            width: 4rem;
            height: 4rem;
        }

        .error-zero-inner {
            font-size: 1.5rem;
        }

        .error-title {
            font-size: 2rem;
        }

        .error-subtitle {
            font-size: 1rem;
        }

        .floating-links {
            display: none;
        }
    }

    @media (max-width: 576px) {
        .error-number {
            gap: 0.5rem;
        }

        .error-digit {
            font-size: 3rem;
        }

        .error-zero {
            width: 3rem;
            height: 3rem;
            border-width: 4px;
        }

        .error-zero-inner {
            font-size: 1rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Add some interactive elements
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to reason cards
        const reasonCards = document.querySelectorAll('.reason-card');
        reasonCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add click effect to the 404 number
        const errorDigits = document.querySelectorAll('.error-digit, .error-zero');
        errorDigits.forEach(digit => {
            digit.addEventListener('click', function() {
                this.style.animation = 'none';
                setTimeout(() => {
                    this.style.animation = '';
                }, 10);
            });
        });
    });
</script>
@endpush
