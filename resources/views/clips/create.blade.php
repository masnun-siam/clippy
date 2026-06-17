@extends('layouts.app')

@section('title', 'Create New Link - Clippy')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="h2 mb-2">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Create New Short Link
                </h1>
                <p class="text-muted">Transform your long URLs into short, shareable links</p>
            </div>

            <!-- Create Form -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('clips.store') }}" id="createClipForm">
                        @csrf
                        <input type="hidden" name="type" id="clipType" value="url">

                        <!-- Type Tabs -->
                        <ul class="nav nav-tabs mb-4" id="clipTypeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="link-tab" data-bs-toggle="tab" data-bs-target="#linkPane" type="button" role="tab">
                                    <i class="fas fa-link me-1"></i>Link
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="html-tab" data-bs-toggle="tab" data-bs-target="#htmlPane" type="button" role="tab">
                                    <i class="fas fa-code me-1"></i>HTML
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Link Tab -->
                            <div class="tab-pane fade show active" id="linkPane" role="tabpanel">
                                <div class="mb-4">
                                    <label for="url" class="form-label fw-bold">
                                        <i class="fas fa-link me-2 text-primary"></i>URL to Shorten
                                    </label>
                                    <input type="url"
                                           class="form-control form-control-lg @error('url') is-invalid @enderror"
                                           id="url"
                                           name="url"
                                           value="{{ old('url') }}"
                                           required
                                           placeholder="https://example.com/very-long-url"
                                           autocomplete="url">
                                    @error('url')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- HTML Tab -->
                            <div class="tab-pane fade" id="htmlPane" role="tabpanel">
                                <div class="mb-4">
                                    <label for="htmlSource" class="form-label fw-bold">
                                        <i class="fas fa-code me-2 text-primary"></i>HTML Content
                                    </label>
                                    <textarea class="form-control @error('html') is-invalid @enderror"
                                              id="htmlSource"
                                              name="html"
                                              rows="12"
                                              placeholder="<h1>Hello World</h1>">{{ old('html') }}</textarea>
                                    @error('html')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Custom Alias -->
                        <div class="mb-4">
                            <label for="custom_alias" class="form-label fw-bold">
                                <i class="fas fa-edit me-2 text-primary"></i>Custom Alias (Optional)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">{{ url('/') }}/</span>
                                <input type="text"
                                       class="form-control @error('custom_alias') is-invalid @enderror"
                                       id="custom_alias"
                                       name="custom_alias"
                                       value="{{ old('custom_alias') }}"
                                       placeholder="my-custom-link"
                                       pattern="[a-zA-Z0-9-_]+"
                                       title="Only letters, numbers, hyphens, and underscores are allowed">
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Leave empty for auto-generated alias. Only letters, numbers, hyphens, and underscores allowed.
                            </small>
                            @error('custom_alias')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password Protection -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="enablePassword" name="enable_password" value="1" {{ old('enable_password') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="enablePassword">
                                    <i class="fas fa-shield-alt me-2 text-warning"></i>Password Protection
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Add password protection to your short link
                            </small>
                        </div>

                        <!-- Password Fields -->
                        <div id="passwordFields" class="mb-4" style="display: {{ old('enable_password') ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Enter password">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Confirm password">
                                </div>
                            </div>
                        </div>

                        <!-- Expiration -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="enableExpiration" name="enable_expiration" value="1" {{ old('enable_expiration') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="enableExpiration">
                                    <i class="fas fa-clock me-2 text-info"></i>Set Expiration Date
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Automatically expire this link after a specific date
                            </small>
                        </div>

                        <!-- Expiration Date -->
                        <div id="expirationFields" class="mb-4" style="display: {{ old('enable_expiration') ? 'block' : 'none' }};">
                            <label for="expires_at" class="form-label">Expiration Date</label>
                            <input type="datetime-local"
                                   class="form-control @error('expires_at') is-invalid @enderror"
                                   id="expires_at"
                                   name="expires_at"
                                   value="{{ old('expires_at') }}"
                                   min="{{ date('Y-m-d\TH:i') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center">
                                <i class="fas fa-magic me-2"></i>Create Short Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="card mt-4" id="previewCard" style="display: none;">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-eye me-2 text-success"></i>Preview
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Original URL:</label>
                            <div class="text-muted" id="previewOriginal"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Short URL:</label>
                            <div class="text-primary" id="previewShort"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
<script>
    // Init CodeMirror
    const htmlEditor = CodeMirror.fromTextArea(document.getElementById('htmlSource'), {
        mode: 'htmlmixed',
        lineNumbers: true,
        lineWrapping: true,
    });

    // Tab switching: update hidden type input + toggle required
    const typeInput = document.getElementById('clipType');
    const urlInput = document.getElementById('url');
    const linkTab = document.getElementById('link-tab');
    const htmlTab = document.getElementById('html-tab');

    linkTab.addEventListener('shown.bs.tab', function() {
        typeInput.value = 'url';
        urlInput.required = true;
    });
    htmlTab.addEventListener('shown.bs.tab', function() {
        typeInput.value = 'html';
        urlInput.required = false;
    });

    // Toggle password fields
    document.getElementById('enablePassword').addEventListener('change', function() {
        const passwordFields = document.getElementById('passwordFields');
        const passwordInput = document.getElementById('password');

        if (this.checked) {
            passwordFields.style.display = 'block';
            passwordInput.required = true;
        } else {
            passwordFields.style.display = 'none';
            passwordInput.required = false;
            passwordInput.value = '';
            document.getElementById('password_confirmation').value = '';
        }
    });

    // Toggle expiration fields
    document.getElementById('enableExpiration').addEventListener('change', function() {
        const expirationFields = document.getElementById('expirationFields');
        const expirationInput = document.getElementById('expires_at');

        if (this.checked) {
            expirationFields.style.display = 'block';
            expirationInput.required = true;
        } else {
            expirationFields.style.display = 'none';
            expirationInput.required = false;
            expirationInput.value = '';
        }
    });

    // Live preview
    function updatePreview() {
        const url = document.getElementById('url').value;
        const customAlias = document.getElementById('custom_alias').value;
        const previewCard = document.getElementById('previewCard');
        const previewOriginal = document.getElementById('previewOriginal');
        const previewShort = document.getElementById('previewShort');

        if (url) {
            previewCard.style.display = 'block';
            previewOriginal.textContent = url;

            if (customAlias) {
                previewShort.textContent = `{{ url('/') }}/${customAlias}`;
            } else {
                previewShort.textContent = `{{ url('/') }}/[auto-generated]`;
            }
        } else {
            previewCard.style.display = 'none';
        }
    }

    // Add event listeners for preview
    document.getElementById('url').addEventListener('input', updatePreview);
    document.getElementById('custom_alias').addEventListener('input', updatePreview);

    // Auto-focus on URL input
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('url').focus();
    });

    // Custom alias validation
    document.getElementById('custom_alias').addEventListener('input', function() {
        const value = this.value;
        const pattern = /^[a-zA-Z0-9-_]*$/;

        if (value && !pattern.test(value)) {
            this.setCustomValidity('Only letters, numbers, hyphens, and underscores are allowed');
        } else {
            this.setCustomValidity('');
        }
    });

    // Form validation and UTC conversion
    document.getElementById('createClipForm').addEventListener('submit', function(e) {
        // Sync CodeMirror back to textarea
        htmlEditor.save();

        const expirationEnabled = document.getElementById('enableExpiration').checked;
        const expiresAtInput = document.getElementById('expires_at');

        // Convert local datetime to UTC before submission
        if (expirationEnabled && expiresAtInput.value) {
            const localDateTime = new Date(expiresAtInput.value);
            const utcDateTime = localDateTime.toISOString().slice(0, 19).replace('T', ' ');

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'expires_at';
            hiddenInput.value = utcDateTime;

            expiresAtInput.removeAttribute('name');
            this.appendChild(hiddenInput);
        }
    });
</script>
@endpush
