@extends('layouts.app')

@section('title', 'Clippy - Simple & Elegant URL Shortener')

@section('content')
<!-- Hero Section -->
<div class="hero-section position-relative overflow-hidden">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-title display-1 fw-bold mb-4">
                        Make Your Links
                        <span class="text-warning">Memorable</span>
                    </h1>
                    <p class="hero-subtitle lead mb-4">
                        Transform long, complicated URLs into short, shareable links that are easy to remember and track.
                        Perfect for social media, email campaigns, and professional presentations.
                    </p>
                    <div class="hero-stats mb-4">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number h3 text-warning mb-0">1M+</div>
                                    <div class="stat-label small text-light opacity-75">Links Created</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number h3 text-warning mb-0">50K+</div>
                                    <div class="stat-label small text-light opacity-75">Happy Users</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat-item">
                                    <div class="stat-number h3 text-warning mb-0">99.9%</div>
                                    <div class="stat-label small text-light opacity-75">Uptime</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-actions d-flex flex-wrap gap-3">
                        @if($registrationEnabled)
                            <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4 py-3 fw-bold d-flex align-items-center justify-content-center">
                                <i class="fas fa-rocket me-2"></i>Start Shortening - Free
                            </a>
                        @endif
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 py-3 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual text-center position-relative">
                    <div class="floating-elements">
                        <div class="floating-card card shadow-lg position-absolute" style="top: 20%; left: 10%; transform: rotate(-5deg);">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-link text-primary me-2"></i>
                                    <code class="text-muted">clippy.msiamn.dev/social-promo</code>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card card shadow-lg position-absolute" style="top: 40%; right: 10%; transform: rotate(5deg);">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-chart-line text-success me-2"></i>
                                    <span class="text-muted">+247% clicks</span>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card card shadow-lg position-absolute" style="bottom: 30%; left: 20%; transform: rotate(-3deg);">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-shield-alt text-warning me-2"></i>
                                    <span class="text-muted">Password Protected</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-icon">
                        <i class="fas fa-link text-warning opacity-25" style="font-size: 12rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Background decoration -->
    <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: -1;">
        <div class="position-absolute" style="top: 20%; left: 10%; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%; filter: blur(40px);"></div>
        <div class="position-absolute" style="bottom: 20%; right: 10%; width: 150px; height: 150px; background: rgba(255, 193, 7, 0.1); border-radius: 50%; filter: blur(60px);"></div>
    </div>
</div>

<!-- Features Section -->
<div class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-4 fw-bold mb-3">Why Choose Clippy?</h2>
                <p class="lead text-muted">Powerful features designed for modern link management</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-bolt text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Lightning Fast</h4>
                    <p class="text-muted">Create short URLs instantly with our optimized platform. No waiting, no delays - just fast, reliable link shortening.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Secure & Private</h4>
                    <p class="text-muted">Password protect your links, set expiration dates, and control access. Your privacy and security are our top priorities.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-chart-line text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Smart Analytics</h4>
                    <p class="text-muted">Track clicks, monitor performance, and gain insights into your link engagement with detailed analytics.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt text-info" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Mobile Optimized</h4>
                    <p class="text-muted">Perfect experience on any device. Manage your links on the go with our responsive design.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-cog text-secondary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Customizable</h4>
                    <p class="text-muted">Create custom aliases, set branded domains, and personalize your short links to match your brand.</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-code text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Developer API</h4>
                    <p class="text-muted">Powerful REST API for developers. Integrate Clippy into your applications with our comprehensive API documentation.</p>
                    <div class="mt-3">
                        <a href="/docs" target="_blank" class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center">
                            <i class="fas fa-book me-1"></i>View API Docs
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card h-100 text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-users text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="h5 fw-bold mb-3">Team Friendly</h4>
                    <p class="text-muted">Built for teams and individuals alike. Share, collaborate, and manage links efficiently across your organization.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="py-5 bg-white">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-4 fw-bold mb-3">How It Works</h2>
                <p class="lead text-muted">Get started in just 3 simple steps</p>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-lg-4 mb-4">
                <div class="process-step text-center">
                    <div class="step-number mx-auto mb-3">
                        <span class="h2 fw-bold text-white">1</span>
                    </div>
                    <h5 class="fw-bold mb-3">Paste Your URL</h5>
                    <p class="text-muted">Simply paste your long URL into our shortener. We support all types of links - websites, documents, social media, and more.</p>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="process-step text-center">
                    <div class="step-number mx-auto mb-3">
                        <span class="h2 fw-bold text-white">2</span>
                    </div>
                    <h5 class="fw-bold mb-3">Customize & Secure</h5>
                    <p class="text-muted">Add custom aliases, set passwords, configure expiration dates, and choose your preferred settings for maximum control.</p>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="process-step text-center">
                    <div class="step-number mx-auto mb-3">
                        <span class="h2 fw-bold text-white">3</span>
                    </div>
                    <h5 class="fw-bold mb-3">Share & Track</h5>
                    <p class="text-muted">Share your short URL anywhere and track its performance with real-time analytics and detailed insights.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-5" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));">
    <div class="container text-center text-white">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="display-4 fw-bold mb-4">Ready to Transform Your Links?</h2>
                <p class="lead mb-4 opacity-90">Join thousands of users who trust Clippy for their URL shortening needs. Start creating memorable, trackable links today.</p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    @if($registrationEnabled)
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4 py-3 fw-bold d-flex align-items-center justify-content-center">
                            <i class="fas fa-rocket me-2"></i>Get Started Free
                        </a>
                    @endif
                    <a href="#features" class="btn btn-outline-light btn-lg px-4 py-3 d-flex align-items-center justify-content-center">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .floating-card {
        animation: float 6s ease-in-out infinite;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.9);
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(var(--rotation, 0deg)); }
        50% { transform: translateY(-20px) rotate(var(--rotation, 0deg)); }
    }

    .floating-card:nth-child(1) { animation-delay: 0s; --rotation: -5deg; }
    .floating-card:nth-child(2) { animation-delay: 2s; --rotation: 5deg; }
    .floating-card:nth-child(3) { animation-delay: 4s; --rotation: -3deg; }

    .feature-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .step-number {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
    }

    .process-step {
        position: relative;
    }

    .process-step::after {
        content: '';
        position: absolute;
        top: 40px;
        left: 50%;
        width: 100px;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color), transparent);
        transform: translateX(-50%);
    }

    .process-step:last-child::after {
        display: none;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .floating-card {
            display: none;
        }

        .process-step::after {
            display: none;
        }
    }
</style>
@endpush
