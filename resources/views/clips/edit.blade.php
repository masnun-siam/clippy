@extends('layouts.app')

@section('title', 'Edit Link - Clippy')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-4">
                <h1 class="h2 mb-2">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Short Link
                </h1>
                <p class="text-muted">Update your link settings and configuration</p>
            </div>

            <!-- Edit Form -->
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-link me-2"></i>Link Details
                    </h5>
                    <div class="badge bg-primary">
                        {{ url('/' . $clip->slug) }}
                    </div>
                </div>
                <div class="card-body">
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

                    <form method="POST" action="{{ route('clips.update', $clip->id) }}" id="clipForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="type" id="clipType" value="{{ $clip->type ?? 'url' }}">

                        <!-- Type Tabs -->
                        <ul class="nav nav-tabs mb-4" id="clipTypeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ ($clip->type ?? 'url') === 'url' ? 'active' : '' }}" id="link-tab" data-bs-toggle="tab" data-bs-target="#linkPane" type="button" role="tab">
                                    <i class="fas fa-link me-1"></i>Link
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ ($clip->type ?? 'url') === 'html' ? 'active' : '' }}" id="html-tab" data-bs-toggle="tab" data-bs-target="#htmlPane" type="button" role="tab">
                                    <i class="fas fa-code me-1"></i>HTML
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Link Tab -->
                            <div class="tab-pane fade {{ ($clip->type ?? 'url') === 'url' ? 'show active' : '' }}" id="linkPane" role="tabpanel">
                                <div class="mb-4">
                                    <label for="url" class="form-label fw-bold">
                                        <i class="fas fa-globe me-2"></i>Original URL *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-link"></i>
                                        </span>
                                        <input type="url"
                                               class="form-control @error('url') is-invalid @enderror"
                                               id="url"
                                               name="url"
                                               value="{{ old('url', $clip->url) }}"
                                               {{ ($clip->type ?? 'url') === 'url' ? 'required' : '' }}
                                               placeholder="https://example.com/your-long-url"
                                               autocomplete="url">
                                        <button type="button" class="btn btn-outline-secondary" id="validateUrl">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                    @error('url')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Update the destination URL where users will be redirected
                                    </div>
                                </div>
                            </div>

                            <!-- HTML Tab -->
                            <div class="tab-pane fade {{ ($clip->type ?? 'url') === 'html' ? 'show active' : '' }}" id="htmlPane" role="tabpanel">
                                <div class="mb-4">
                                    <label for="htmlSource" class="form-label fw-bold">
                                        <i class="fas fa-code me-2 text-primary"></i>HTML Content
                                    </label>
                                    <textarea class="form-control @error('html') is-invalid @enderror"
                                              id="htmlSource"
                                              name="html"
                                              rows="12"
                                              placeholder="<h1>Hello World</h1>">{{ old('html', $clip->html) }}</textarea>
                                    @error('html')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Current Short URL Display -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-link me-2"></i>Short URL
                            </label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       value="{{ url('/' . $clip->slug) }}"
                                       readonly>
                                <button type="button" class="btn btn-outline-secondary" onclick="copyToClipboard('{{ url('/' . $clip->slug) }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <a href="{{ url('/' . $clip->slug) }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                This short URL cannot be changed once created
                            </div>
                        </div>

                        <!-- Password Protection -->
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="enablePassword" name="enable_password" {{ $clip->password ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="enablePassword">
                                    <i class="fas fa-lock me-2"></i>Password Protection
                                </label>
                            </div>

                            <div id="passwordFields" style="display: {{ $clip->password ? 'block' : 'none' }};">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-key"></i>
                                    </span>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="{{ $clip->password ? 'Enter new password (leave empty to keep current)' : 'Enter password' }}"
                                           minlength="4">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePasswordEdit">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    @if($clip->password)
                                        Leave empty to keep the current password, or enter a new one to change it
                                    @else
                                        Users will need this password to access the link
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Expiration Date -->
                        <div class="mb-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="enableExpiration" name="enable_expiration" {{ $clip->expires_at ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="enableExpiration">
                                    <i class="fas fa-clock me-2"></i>Set Expiration Date
                                </label>
                            </div>

                            <div id="expirationFields" style="display: {{ $clip->expires_at ? 'block' : 'none' }};">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input type="datetime-local"
                                           class="form-control @error('expires_at') is-invalid @enderror"
                                           id="expires_at"
                                           name="expires_at"
                                           value="{{ old('expires_at', $clip->expires_at ? \Carbon\Carbon::parse($clip->expires_at)->format('Y-m-d\TH:i') : '') }}"
                                           min="{{ now()->format('Y-m-d\TH:i') }}">
                                </div>
                                @error('expires_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    The link will automatically expire after this date
                                </div>
                            </div>
                        </div>

                        <!-- Link Stats -->
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-chart-line me-2"></i>Link Statistics
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <div class="h4 text-primary">{{ $clip->created_at->diffForHumans() }}</div>
                                                <div class="text-muted">Created</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <div class="h4 text-success">
                                                    @if($clip->expires_at && $clip->expires_at < now())
                                                        <span class="text-danger">Expired</span>
                                                    @else
                                                        Active
                                                    @endif
                                                </div>
                                                <div class="text-muted">Status</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center">
                                                <div class="h4 text-info">
                                                    {{ $clip->password ? 'Protected' : 'Public' }}
                                                </div>
                                                <div class="text-muted">Access</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                <i class="fas fa-save me-2"></i>Update Link
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Once you delete this link, it cannot be recovered. All access to this short URL will be permanently lost.
                    </p>
                    <form method="POST" action="{{ route('clips.destroy', $clip->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger d-flex align-items-center justify-content-center"
                                onclick="return confirm('Are you sure you want to delete this link? This action cannot be undone.')">
                            <i class="fas fa-trash me-2"></i>Delete Link
                        </button>
                    </form>
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

    // Copy to clipboard functionality with fallback
    function copyToClipboard(text) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showCopySuccess();
            }, function(err) {
                console.error('Clipboard API failed, trying fallback: ', err);
                fallbackCopyToClipboard(text);
            });
        } else {
            fallbackCopyToClipboard(text);
        }
    }

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

    function showCopySuccess() {
        const toast = document.createElement('div');
        toast.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <i class="fas fa-check me-2"></i>Link copied to clipboard!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 3000);
    }

    function showCopyError() {
        const toast = document.createElement('div');
        toast.className = 'alert alert-warning alert-dismissible fade show position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>Unable to copy automatically. Please copy manually.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);
        setTimeout(() => { toast.remove(); }, 5000);
    }

    // Toggle password fields
    document.getElementById('enablePassword').addEventListener('change', function() {
        const passwordFields = document.getElementById('passwordFields');
        const passwordInput = document.getElementById('password');

        if (this.checked) {
            passwordFields.style.display = 'block';
        } else {
            passwordFields.style.display = 'none';
            passwordInput.value = '';
        }
    });

    // Toggle expiration fields
    document.getElementById('enableExpiration').addEventListener('change', function() {
        const expirationFields = document.getElementById('expirationFields');
        const expirationInput = document.getElementById('expires_at');

        if (this.checked) {
            expirationFields.style.display = 'block';
        } else {
            expirationFields.style.display = 'none';
            expirationInput.value = '';
        }
    });

    // Toggle password visibility
    document.getElementById('togglePasswordEdit').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const toggleIcon = this.querySelector('i');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    });

    // URL validation
    document.getElementById('validateUrl').addEventListener('click', function() {
        const urlInput = document.getElementById('url');
        const url = urlInput.value;

        if (url) {
            try {
                new URL(url);
                this.innerHTML = '<i class="fas fa-check text-success"></i>';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-outline-success');
            } catch (e) {
                this.innerHTML = '<i class="fas fa-times text-danger"></i>';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-outline-danger');
            }
        }
    });

    // Form validation
    document.getElementById('clipForm').addEventListener('submit', function(e) {
        // Sync CodeMirror back to textarea
        htmlEditor.save();

        // Convert local datetime to UTC before submission
        const expirationEnabled = document.getElementById('enableExpiration').checked;
        const expiresAtInput = document.getElementById('expires_at');

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

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    });
</script>
@endpush
