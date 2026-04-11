@extends('layouts.app')
@section('title', 'API Documentation')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5/swagger-ui.css">
<style>
    #swagger-wrapper { margin: -24px; }
    .swagger-ui .topbar { display: none; }
    .swagger-ui .info { padding: 20px 24px 0; }
    .swagger-ui .scheme-container { padding: 10px 24px; }
    .swagger-ui .wrapper { padding: 0 24px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">API Documentation</h5>
    <div class="d-flex gap-2">
        <a href="{{ asset('api-docs/openapi.json') }}" download="parkly-openapi.json"
           class="btn btn-sm btn-outline-primary">
            <i class="bi bi-download me-1"></i>Download JSON
        </a>
        <a href="https://www.postman.com/imports/api?url={{ urlencode(asset('api-docs/openapi.json')) }}"
           target="_blank" class="btn btn-sm btn-warning text-dark">
            <i class="bi bi-box-arrow-up-right me-1"></i>Import to Postman
        </a>
    </div>
</div>
<div class="alert alert-info py-2 small mb-3">
    <i class="bi bi-info-circle me-1"></i>
    To import into Postman: <strong>Open Postman → Import → Link or File → paste/upload the JSON</strong>.
    Use the <strong>Authorize</strong> button in the docs below to test protected endpoints.
</div>

<div id="swagger-wrapper">
    <div id="swagger-ui"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
<script>
    SwaggerUIBundle({
        url: "{{ asset('api-docs/openapi.json') }}",
        dom_id: '#swagger-ui',
        presets: [SwaggerUIBundle.presets.apis, SwaggerUIBundle.SwaggerUIStandalonePreset],
        layout: 'BaseLayout',
        deepLinking: true,
        tryItOutEnabled: true,
        displayRequestDuration: true,
        filter: true,
        persistAuthorization: true,
        requestInterceptor: function (request) {
            request.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            return request;
        },
    });
</script>
@endpush
