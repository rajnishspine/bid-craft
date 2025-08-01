<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0 text-dark">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <div class="py-5">
        <div class="container-fluid">
            <div class="col-12">
                <!-- Stats Cards -->
                <div class="row mb-4" id="statsContainer">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                    <div>
                                        <h4 class="mb-0" id="totalRequests">-</h4>
                                        <small>Total Requests</small>
                        </div>
                                    <i class="fas fa-chart-bar fa-2x opacity-75"></i>
            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                    <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0" id="successRate">-</h4>
                                        <small>Success Rate</small>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0" id="totalTokens">-</h4>
                                        <small>Total Tokens</small>
                                    </div>
                                    <i class="fas fa-coins fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4 class="mb-0" id="totalCost">-</h4>
                                        <small>Total Cost</small>
                                    </div>
                                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                <!-- History Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered table-sm" id="historyTable">
                                <thead class="table-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Product</th>
                                        <th>Country</th>
                                        <th>User</th>
                                        <th>Status</th>
                                        <th>Response Time</th>
                                        <th>Tokens</th>
                                        <th>Cost</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Request Detail Modal -->
<div class="modal fade" id="requestDetailModal" tabindex="-1" aria-labelledby="requestDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="requestDetailModalLabel">
                    <i class="fas fa-info-circle"></i> Request Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body" id="requestDetailBody">
                <!-- Content will be loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <style>
    .template-before {
        background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%);
        border: 2px solid #fecaca;
        border-left: 4px solid #dc3545;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1);
    }

    .template-after {
        background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        border: 2px solid #bbf7d0;
        border-left: 4px solid #198754;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.1);
    }

    .template-system {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 2px solid #bae6fd;
        border-left: 4px solid #0dcaf0;
        box-shadow: 0 2px 8px rgba(6, 182, 212, 0.1);
    }

    .template-system-raw {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
        border-left: 4px solid #6c757d;
        box-shadow: 0 2px 8px rgba(108, 117, 125, 0.1);
    }

    .section-header {
        color: #0d6efd;
        font-weight: 600;
    }

    .template-content {
        white-space: pre-wrap;
        font-size: 0.85em;
        line-height: 1.5;
        font-family: 'Fira Code', 'Courier New', monospace;
        margin: 0;
        color: #374151;
    }

    .template-content-enhanced {
        white-space: pre-wrap;
        font-size: 0.85em;
        line-height: 1.6;
        font-family: 'Inter', sans-serif;
        color: #374151;
    }

    .template-before .template-content {
        color: #7f1d1d;
    }

    .template-after .template-content {
        color: #14532d;
    }

    .template-system-raw .template-content {
        color: #374151;
    }

    .template-system .template-content-enhanced {
        color: #0c4a6e;
    }

    .ai-response-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 20px;
        max-height: 400px;
        overflow-y: auto;
        line-height: 1.6;
    }

    .nav-tabs .nav-link {
        color: #495057 !important;
    }

    .nav-tabs .nav-link:hover {
        color: #0d6efd !important;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd !important;
    }

    /* Bootstrap DataTables Styling */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        margin-bottom: 1rem;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label {
        font-weight: normal;
        text-align: left;
        white-space: nowrap;
    }

    .dataTables_wrapper .dataTables_filter {
        text-align: right;
    }

    .dataTables_wrapper .dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: auto;
    }

    .dataTables_wrapper .dataTables_length select {
        margin: 0 0.5em;
        display: inline-block;
        width: auto;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0;
        margin: 0;
        border: none;
        background: transparent;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: transparent !important;
        border: none !important;
    }

    .dataTables_wrapper .dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 200px;
        margin-left: -100px;
        margin-top: -26px;
        text-align: center;
        padding: 20px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    #historyTable_wrapper .row {
        margin: 0;
    }

    #historyTable_wrapper .col-sm-12,
    #historyTable_wrapper .col-md-6,
    #historyTable_wrapper .col-md-5,
    #historyTable_wrapper .col-md-7 {
        padding: 0 0.75rem;
    }
    </style>

    <script>
    let historyTable;

    // Load history when page loads
    $(document).ready(function() {
        // Ensure Bootstrap dropdowns work
        const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
        const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
        // Initialize DataTable with Bootstrap styling
        historyTable = $('#historyTable').DataTable({
            processing: true,
            serverSide: false, // Client-side processing for simplicity
            ajax: {
                url: '{{ route("bid-recommendations.history") }}',
                type: 'GET',
                dataSrc: function(json) {
                    if (json.success) {
                        displayStats(json.stats);
                        // Return the actual records array
                        return json.data && json.data.data ? json.data.data : [];
                    }
                    return [];
                }
            },
            columns: [
                { data: 'id' },
                { 
                    data: null,
                    render: function(data, type, row) {
                        return row.form_data?.product_name || 'Unknown Product';
                    }
                },
                { 
                    data: null,
                    render: function(data, type, row) {
                        return row.form_data?.country || 'Unknown Country';
                    }
                },
                { 
                    data: null,
                    render: function(data, type, row) {
                        const userName = row.user?.name || 'Unknown User';
                        return `<span class="badge bg-secondary">
                            <i class="fas fa-user me-1"></i>${userName}
                        </span>`;
                    }
                },
                { 
                    data: 'success',
                    render: function(data, type, row) {
                        return data ? 
                            '<span class="badge bg-success"><i class="fas fa-check"></i> Success</span>' :
                            '<span class="badge bg-danger"><i class="fas fa-times"></i> Failed</span>';
                    }
                },
                { 
                    data: 'response_time_ms',
                    render: function(data, type, row) {
                        return data ? `${data}ms` : 'N/A';
                    }
                },
                { 
                    data: 'tokens_used',
                    render: function(data, type, row) {
                        return data ? data.toLocaleString() : 'N/A';
                    }
                },
                { 
                    data: 'cost_estimate',
                    render: function(data, type, row) {
                        return data ? `$${parseFloat(data).toFixed(4)}` : 'N/A';
                    }
                },
                { 
                    data: 'created_at',
                    render: function(data, type, row) {
                        return new Date(data).toLocaleDateString() + ' ' + new Date(data).toLocaleTimeString();
                    }
                },
                { 
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `<button class="btn btn-outline-primary btn-sm" onclick="viewDetails(${row.id})">
                            <i class="fas fa-eye"></i> View
                        </button>`;
                    }
                }
            ],
            order: [[0, 'desc']], // Order by ID descending (newest first)
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            responsive: true,
            autoWidth: false,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            language: {
                emptyTable: "No AI requests found. <a href='{{ route('bid-recommendations.index') }}' class='btn btn-primary btn-sm ms-2'><i class='fas fa-plus'></i> Create First Analysis</a>",
                zeroRecords: "No matching records found",
                search: "Search all fields:",
                lengthMenu: "Show _MENU_ entries per page",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                paginate: {
                    first: '<i class="fas fa-angle-double-left"></i>',
                    last: '<i class="fas fa-angle-double-right"></i>',
                    next: '<i class="fas fa-angle-right"></i>',
                    previous: '<i class="fas fa-angle-left"></i>'
                },
                processing: '<i class="fas fa-spinner fa-spin"></i> Loading...'
            },
            initComplete: function(settings, json) {
                // Add Bootstrap classes to DataTables controls
                $('.dataTables_length select').addClass('form-select form-select-sm');
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_paginate .pagination').addClass('pagination-sm');
            }
        });
    });

    function displayStats(stats) {
        document.getElementById('totalRequests').textContent = stats.total_requests || 0;
        document.getElementById('successRate').textContent = parseInt(stats.success_rate || 0) + '%';
        document.getElementById('totalTokens').textContent = parseInt(stats.total_tokens_used || 0).toLocaleString();
        document.getElementById('totalCost').textContent = '$' + parseFloat(stats.total_cost_estimate || 0).toFixed(4);
    }

    function viewDetails(requestId) {
        const table = $('#historyTable').DataTable();
        const request = table.rows().data().toArray().find(r => r.id === requestId);
        
        if (!request) {
            alert('Request not found');
            return;
        }

        const modalBody = document.getElementById('requestDetailBody');
        modalBody.innerHTML = `
            <ul class="nav nav-tabs" id="requestDetailTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="form-data-tab" data-bs-toggle="tab" data-bs-target="#form-data" type="button" role="tab" aria-controls="form-data" aria-selected="true">
                        <i class="fas fa-wpforms"></i> Form Data
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button" role="tab" aria-controls="templates" aria-selected="false">
                        <i class="fas fa-exchange-alt"></i> Templates
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ai-response-tab" data-bs-toggle="tab" data-bs-target="#ai-response" type="button" role="tab" aria-controls="ai-response" aria-selected="false">
                        <i class="fas fa-robot"></i> AI Response
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="requestDetailTabContent">
                <!-- Form Data Tab -->
                <div class="tab-pane fade show active" id="form-data" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6><i class="fas fa-wpforms"></i> Submitted Form Data</h6>
                        <span class="badge bg-primary fs-6">
                            <i class="fas fa-user me-1"></i>Requested by: ${request.user?.name || 'Unknown User'}
                        </span>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${Object.entries(request.form_data || {})
                                        .filter(([key, value]) => key !== 'exports_data' && key !== 'competitors_data' && !Array.isArray(value))
                                        .map(([key, value]) => `
                                        <tr>
                                            <td><strong>${formatFieldName(key)}</strong></td>
                                            <td>${value || '<span class="text-muted">Not provided</span>'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    ${request.exports_data && request.exports_data.length > 0 ? `
                    <h6 class="mt-4"><i class="fas fa-shipping-fast"></i> Exports Data</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Country</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Competitors</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${request.exports_data.map(exportItem => `
                                    <tr>
                                        <td>${exportItem.company || 'N/A'}</td>
                                        <td>${exportItem.country || 'N/A'}</td>
                                        <td>${exportItem.quantity || 'N/A'}</td>
                                        <td>$${exportItem.price || '0'}</td>
                                        <td>${exportItem.competitors || 'N/A'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    ` : '<p class="text-muted mt-3">No exports data provided</p>'}

                    ${request.competitors_data && request.competitors_data.length > 0 ? `
                    <h6 class="mt-4"><i class="fas fa-users"></i> Competitors Data</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Manufacturing Company</th>
                                    <th>Manufacturing Country</th>
                                    <th>Marketing Company</th>
                                    <th>Marketing Country</th>
                                    <th>Saudi Agent</th>
                                    <th>CIF Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${request.competitors_data.map(comp => `
                                    <tr>
                                        <td>${comp.manufacturing_company || 'N/A'}</td>
                                        <td>${comp.manufacturing_country || 'N/A'}</td>
                                        <td>${comp.marketing_company || 'N/A'}</td>
                                        <td>${comp.marketing_country || 'N/A'}</td>
                                        <td>${comp.saudi_agent || 'N/A'}</td>
                                        <td>$${comp.cif_price || '0'}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                    ` : '<p class="text-muted mt-3">No competitors data provided</p>'}

                    <div class="mt-3">
                        <button class="btn btn-outline-primary btn-sm" onclick="downloadJson(${request.id}, 'form-data')">
                            <i class="fas fa-download"></i> Download Form Data as JSON
                        </button>
                    </div>
                </div>

                <!-- Templates Tab -->
                <div class="tab-pane fade" id="templates" role="tabpanel">
                    <!-- Template Information -->
                    <div class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-info-circle text-primary me-2"></i>Template Information</h6>
                                        <p class="mb-1"><strong>Template ID:</strong> ${request.template_id || 'Legacy Request'}</p>
                                        <p class="mb-0"><strong>Template Name:</strong> ${request.template?.name || 'Legacy Template'}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-calendar text-info me-2"></i>Request Details</h6>
                                        <p class="mb-1"><strong>Created:</strong> ${new Date(request.created_at).toLocaleString()}</p>
                                        <p class="mb-0"><strong>User:</strong> ${request.user?.name || 'Unknown'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template Comparison -->
                    <div class="mb-4">
                        <h5 class="d-flex align-items-center mb-3">
                            <i class="fas fa-exchange-alt text-primary me-2"></i>
                            Template Comparison
                            <span class="badge bg-secondary ms-2 fs-6">Original vs Populated</span>
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-alt text-danger me-2"></i>
                                    <h6 class="section-header mb-0">Original Template</h6>
                                    <span class="badge bg-danger ms-2">With Variables</span>
                                </div>
                                <div class="template-before p-3 rounded-3">
                                    <pre class="template-content mb-0">${request.system_prompt_template || 'No template available'}</pre>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-check text-success me-2"></i>
                                    <h6 class="section-header mb-0">Populated Template</h6>
                                    <span class="badge bg-success ms-2">Values Inserted</span>
                                </div>
                                <div class="template-after p-3 rounded-3">
                                    <pre class="template-content mb-0">${request.populated_user_prompt || 'No populated template available'}</pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-primary btn-sm" onclick="downloadJson(${request.id}, 'templates')">
                            <i class="fas fa-download"></i> Download Template Data as JSON
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard(${request.id}, 'original-template')">
                            <i class="fas fa-copy"></i> Copy Original Template
                        </button>
                        <button class="btn btn-outline-success btn-sm" onclick="copyToClipboard(${request.id}, 'populated-template')">
                            <i class="fas fa-copy"></i> Copy Populated Template
                        </button>
                    </div>
                </div>

                <!-- AI Response Tab -->
                <div class="tab-pane fade" id="ai-response" role="tabpanel">
                    <h6><i class="fas fa-robot"></i> AI Generated Response</h6>
                    ${request.success ? `
                        <div class="ai-response-box">
                            <div style="white-space: pre-wrap;">${request.api_response || 'No response available'}</div>
                        </div>
                    ` : `
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Request Failed</h6>
                            <p class="mb-0">${request.error_message || 'Unknown error occurred'}</p>
                        </div>
                    `}

                    <div class="mt-3">
                        <button class="btn btn-outline-primary btn-sm" onclick="downloadJson(${request.id}, 'ai-response')">
                            <i class="fas fa-download"></i> Download AI Response as JSON
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('requestDetailModal'));
        modal.show();
    }

    function formatFieldName(fieldName) {
        return fieldName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function formatSystemPrompt(content) {
        if (!content) return content;
        
        return content
            .replace(/([A-Z]\. [A-Z]+[^\n]*)/g, '<strong style="color: #0d6efd;">$1</strong>')
            .replace(/(🔹[^\n]*)/g, '<span style="color: #198754; font-weight: 500;">$1</span>')
            .replace(/(📘|📊|💡|🌍|🧠)([^\n]*)/g, '<span style="color: #dc3545; font-weight: 600;">$1$2</span>')
            .replace(/(\n\n)/g, '<br><br>')
            .replace(/(\n)/g, '<br>');
    }

    function downloadJson(requestId, type) {
        const table = $('#historyTable').DataTable();
        const request = table.rows().data().toArray().find(r => r.id === requestId);
        if (!request) return;

        let data = {};
        let filename = `request_${requestId}_`;

        switch(type) {
            case 'form-data':
                data = {
                    form_data: request.form_data,
                    exports_data: request.exports_data,
                    competitors_data: request.competitors_data,
                    request_info: {
                        user_name: request.user?.name || 'Unknown User',
                        user_email: request.user?.email || 'Unknown Email',
                        request_date: request.created_at,
                        success: request.success,
                        response_time_ms: request.response_time_ms
                    }
                };
                filename += 'form_data.json';
                break;
            case 'templates':
                data = {
                    system_prompt_template: request.system_prompt_template,
                    user_prompt_template: request.user_prompt_template,
                    populated_user_prompt: request.populated_user_prompt,
                    template_versions: request.template_versions
                };
                filename += 'templates.json';
                break;
            case 'ai-response':
                data = {
                    api_response: request.api_response,
                    api_response_raw: request.api_response_raw,
                    success: request.success,
                    error_message: request.error_message
                };
                filename += 'ai_response.json';
                break;
        }

        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    function copyToClipboard(requestId, templateType) {
        const table = $('#historyTable').DataTable();
        const request = table.rows().data().toArray().find(r => r.id === requestId);
        if (!request) return;

        let textToCopy = '';
        let successMessage = '';

        switch(templateType) {
            case 'user-template':
                textToCopy = request.populated_user_prompt || request.user_prompt_template || '';
                successMessage = 'User template copied to clipboard!';
                break;
            case 'system-template':
                textToCopy = request.system_prompt_template || '';
                successMessage = 'System template copied to clipboard!';
                break;
        }

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy).then(() => {
                showToast(successMessage, 'success');
            }).catch(err => {
                console.error('Failed to copy: ', err);
                fallbackCopyTextToClipboard(textToCopy, successMessage);
            });
        } else {
            fallbackCopyTextToClipboard(textToCopy, successMessage);
        }
    }

    function fallbackCopyTextToClipboard(text, successMessage) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                showToast(successMessage, 'success');
            } else {
                showToast('Failed to copy text', 'error');
            }
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            showToast('Failed to copy text', 'error');
        }
        
        document.body.removeChild(textArea);
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            if (toast.parentNode) {
                const bsAlert = new bootstrap.Alert(toast);
                bsAlert.close();
            }
        }, 3000);
    }
    </script>
</x-app-layout>
