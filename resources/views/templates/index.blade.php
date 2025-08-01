<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0 text-dark">
                <i class="fas fa-file-alt me-2"></i>
                {{ __('Template Management') }}
            </h2>
            @can('create templates')
            <a href="{{ route('templates.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Create New Template
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list me-2"></i>
                                All Templates
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($templates->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover" id="templatesTable">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Default</th>
                                                <th>Variables</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($templates as $template)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $template->name }}</strong>
                                                        @if($template->is_default)
                                                            <span class="badge bg-success ms-2">DEFAULT</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ $template->status === 'active' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($template->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($template->is_default)
                                                            <i class="fas fa-star text-warning"></i>
                                                            <span class="text-success">Yes</span>
                                                        @else
                                                            @can('edit templates')
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-warning set-default-btn"
                                                                        data-template-id="{{ $template->id }}"
                                                                        data-template-name="{{ $template->name }}">
                                                                    <i class="fas fa-star"></i>
                                                                    Set Default
                                                                </button>
                                                            @else
                                                                <span class="text-muted">No</span>
                                                            @endcan
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-info view-variables-btn"
                                                                data-template-id="{{ $template->id }}"
                                                                data-template-name="{{ $template->name }}">
                                                            <i class="fas fa-eye"></i>
                                                            View Variables
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">
                                                            {{ $template->created_at->format('M d, Y') }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-success preview-btn"
                                                                    data-template-id="{{ $template->id }}"
                                                                    data-template-name="{{ $template->name }}">
                                                                <i class="fas fa-eye"></i>
                                                                Preview
                                                            </button>
                                                            
                                                            @can('edit templates')
                                                                <a href="{{ route('templates.edit', $template) }}" 
                                                                   class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-edit"></i>
                                                                    Edit
                                                                </a>
                                                            @endcan
                                                            
                                                            @can('delete templates')
                                                                @if(!$template->is_default)
                                                                    <button type="button" 
                                                                            class="btn btn-sm btn-danger delete-btn"
                                                                            data-template-id="{{ $template->id }}"
                                                                            data-template-name="{{ $template->name }}">
                                                                        <i class="fas fa-trash"></i>
                                                                        Delete
                                                                    </button>
                                                                @endif
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No Templates Found</h5>
                                    <p class="text-muted">Create your first template to get started with AI recommendations.</p>
                                    @can('create templates')
                                        <a href="{{ route('templates.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Create First Template
                                        </a>
                                    @endcan
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Variables Modal -->
    <div class="modal fade" id="variablesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-code me-2"></i>
                        Template Variables
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="variablesContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
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
                    <div id="previewContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize DataTables
            $('#templatesTable').DataTable({
                pageLength: 10,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [5] }
                ]
            });

            // Set Default Template
            document.querySelectorAll('.set-default-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const templateId = this.dataset.templateId;
                    const templateName = this.dataset.templateName;
                    
                    if (confirm(`Set "${templateName}" as the default template?`)) {
                        fetch(`/templates/${templateId}/set-default`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error setting default template');
                            console.error(error);
                        });
                    }
                });
            });

            // View Variables
            document.querySelectorAll('.view-variables-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const templateId = this.dataset.templateId;
                    const templateName = this.dataset.templateName;
                    
                    document.querySelector('#variablesModal .modal-title').innerHTML = 
                        `<i class="fas fa-code me-2"></i>Variables in "${templateName}"`;
                    
                    fetch(`/templates/${templateId}/variables`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let html = '';
                                if (data.variables.length > 0) {
                                    html = '<div class="row">';
                                    data.variables.forEach(variable => {
                                        html += `
                                            <div class="col-md-6 mb-2">
                                                <code class="badge bg-primary">[${variable}]</code>
                                            </div>
                                        `;
                                    });
                                    html += '</div>';
                                    html += `<div class="alert alert-info mt-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Found ${data.variables.length} variable(s) in this template.
                                    </div>`;
                                } else {
                                    html = '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>No variables found in this template.</div>';
                                }
                                document.getElementById('variablesContent').innerHTML = html;
                            } else {
                                document.getElementById('variablesContent').innerHTML = 
                                    '<div class="alert alert-danger">Error loading variables</div>';
                            }
                        })
                        .catch(error => {
                            document.getElementById('variablesContent').innerHTML = 
                                '<div class="alert alert-danger">Error loading variables</div>';
                        });
                    
                    new bootstrap.Modal(document.getElementById('variablesModal')).show();
                });
            });

            // Preview Template
            document.querySelectorAll('.preview-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const templateId = this.dataset.templateId;
                    const templateName = this.dataset.templateName;
                    
                    document.querySelector('#previewModal .modal-title').innerHTML = 
                        `<i class="fas fa-eye me-2"></i>Preview: "${templateName}"`;
                    
                    fetch(`/templates/${templateId}/preview`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const html = `
                                    <div class="row">
                                        <div class="col-12">
                                            <h6><i class="fas fa-file-alt me-2"></i>Populated Template (with sample data):</h6>
                                            <div class="border rounded p-3 bg-light">
                                                <pre style="white-space: pre-wrap; font-size: 0.9em;">${data.populated_content}</pre>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                document.getElementById('previewContent').innerHTML = html;
                            } else {
                                document.getElementById('previewContent').innerHTML = 
                                    '<div class="alert alert-danger">Error loading preview</div>';
                            }
                        })
                        .catch(error => {
                            document.getElementById('previewContent').innerHTML = 
                                '<div class="alert alert-danger">Error loading preview</div>';
                        });
                    
                    new bootstrap.Modal(document.getElementById('previewModal')).show();
                });
            });

            // Delete Template
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const templateId = this.dataset.templateId;
                    const templateName = this.dataset.templateName;
                    
                    if (confirm(`Are you sure you want to delete "${templateName}"?`)) {
                        fetch(`/templates/${templateId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        })
                        .catch(error => {
                            alert('Error deleting template');
                            console.error(error);
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>