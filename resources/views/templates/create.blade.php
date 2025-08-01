<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-plus me-2"></i>
                {{ __('Create New Template') }}
            </h2>
            <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Templates
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-alt me-2"></i>
                                Template Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('templates.store') }}">
                                @csrf

                                <!-- Template Name -->
                                <div class="mb-4">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-tag me-2"></i>
                                        Template Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter a descriptive name for this template"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Choose a unique, descriptive name for easy identification.
                                    </small>
                                </div>

                                <!-- Template Content -->
                                <div class="mb-4">
                                    <label for="content" class="form-label">
                                        <i class="fas fa-code me-2"></i>
                                        Template Content <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              rows="20" 
                                              placeholder="Enter your template content with variables"
                                              required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Use variables in the format <code>[VARIABLE_NAME]</code>. 
                                        Available variables include: PRODUCT_NAME, COUNTRY, IBPP, LAST_CIF, FREIGHT, MARGIN, EXPORTS_DATA, COMPETITORS_DATA, etc.
                                    </small>
                                </div>

                                <!-- Status and Default Options -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">
                                            <i class="fas fa-toggle-on me-2"></i>
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status" 
                                                required>
                                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-star me-2"></i>
                                            Default Template
                                        </label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_default" 
                                                   name="is_default" 
                                                   value="1" 
                                                   {{ old('is_default') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_default">
                                                Set as default template
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">
                                            The default template will be used automatically for AI requests.
                                        </small>
                                    </div>
                                </div>

                                <!-- Variable Helper -->
                                <div class="mb-4">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Available Variables
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Basic Information:</h6>
                                                    <ul class="list-unstyled">
                                                        <li><code>[PRODUCT_NAME]</code></li>
                                                        <li><code>[COUNTRY]</code></li>
                                                        <li><code>[AUTHORITY]</code></li>
                                                        <li><code>[TENDER_QUANTITY]</code></li>
                                                        <li><code>[REGISTRATION]</code></li>
                                                        <li><code>[GRADE]</code></li>
                                                        <li><code>[DEPARTMENT]</code></li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Pricing Data:</h6>
                                                    <ul class="list-unstyled">
                                                        <li><code>[IBPP]</code> - IB Purchase Price</li>
                                                        <li><code>[LAST_CIF]</code> - Last VRL CIF Price</li>
                                                        <li><code>[LAST_PRICE]</code> - Last Year Winning Prize</li>
                                                        <li><code>[WINNER]</code></li>
                                                        <li><code>[YEAR]</code> - Last Quoted Year</li>
                                                        <li><code>[LAST_QTY]</code> - Last Quantity</li>
                                                        <li><code>[FREIGHT]</code> - Tentative Freight</li>
                                                        <li><code>[MARGIN]</code> - Client Margin</li>
                                                        <li><code>[EXPENSES]</code> - Client Expenses</li>
                                                        <li><code>[BATCH_SIZE]</code></li>
                                                        <li><code>[BATCH_COST]</code></li>
                                                        <li><code>[LOCAL_PREF]</code> - Local Preference</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6>Dynamic Data:</h6>
                                                    <ul class="list-unstyled">
                                                        <li><code>[EXPORTS_DATA]</code> - Automatically formatted export information</li>
                                                        <li><code>[COMPETITORS_DATA]</code> - List of registered competitors</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6>Additional Fields:</h6>
                                                    <ul class="list-unstyled">
                                                        <li><code>[FOC]</code> - FOC</li>
                                                        <li><code>[WAS_WINNER_LOCAL]</code> - Was Winner Local</li>
                                                        <li><code>[SUPPLY_REMARKS]</code> - Supply Remarks</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('templates.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-info me-2" id="previewBtn">
                                            <i class="fas fa-eye me-2"></i>
                                            Preview
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            Create Template
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>
                        Template Preview
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="previewContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview functionality
            document.getElementById('previewBtn').addEventListener('click', function() {
                const content = document.getElementById('content').value;
                
                if (!content.trim()) {
                    alert('Please enter template content first.');
                    return;
                }

                // Sample data for preview (using CAPS variable names)
                const sampleData = {
                    'PRODUCT_NAME': 'AZITHROMYCIN - 500MG',
                    'COUNTRY': 'ETHIOPIA',
                    'AUTHORITY': 'Ministry of Health',
                    'TENDER_QUANTITY': '1000',
                    'IBPP': '4.50',
                    'LAST_CIF': '5.80',
                    'LAST_PRICE': '6.25',
                    'WINNER': 'Previous Winner',
                    'YEAR': '2017',
                    'LAST_QTY': '100',
                    'REGISTRATION': 'Registered',
                    'GRADE': 'EPN',
                    'DEPARTMENT': 'IB VRL LATAM',
                    'FREIGHT': '100',
                    'MARGIN': '100',
                    'EXPENSES': '100',
                    'BATCH_SIZE': '1000',
                    'BATCH_COST': '50',
                    'LOCAL_PREF': '100',
                    'FOC': '100',
                    'WAS_WINNER_LOCAL': 'Yes',
                    'SUPPLY_REMARKS': 'Sample remarks',
                    'EXPORTS_DATA': '- Venus → India → 1000 @ $100\n- Venus → India → 2000 @ $100',
                    'COMPETITORS_DATA': '- Venus (India) → Venus (India) → CIF: $100\n- Venus (India) → Venus (India) → CIF: $1200'
                };

                // Replace variables in content
                let previewContent = content;
                Object.keys(sampleData).forEach(key => {
                    const regex = new RegExp('\\[' + key + '\\]', 'g');
                    previewContent = previewContent.replace(regex, sampleData[key]);
                });

                document.getElementById('previewContent').innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This preview uses sample data to show how your template will look when populated.
                    </div>
                    <div class="border rounded p-3 bg-light">
                        <pre style="white-space: pre-wrap; font-size: 0.9em;">${previewContent}</pre>
                    </div>
                `;

                new bootstrap.Modal(document.getElementById('previewModal')).show();
            });
        });
    </script>
</x-app-layout>