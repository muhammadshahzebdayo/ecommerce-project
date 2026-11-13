<x-filament-panels::page>
    <div class="inv-wrapper">
        <style>
            /* Design variables */
            :root {
                --inv-bg-card: #ffffff;
                --inv-border-color: #e4e4e7;
                --inv-text-primary: #09090b;
                --inv-text-muted: #71717a;
                --inv-bg-badge-success: #d1fae5;
                --inv-text-badge-success: #065f46;
                --inv-bg-badge-warning: #fef3c7;
                --inv-text-badge-warning: #92400e;
                --inv-bg-badge-danger: #fee2e2;
                --inv-text-badge-danger: #991b1b;
                --inv-bg-stat-item: #f4f4f5;
                --inv-table-header-bg: #fafafa;
                --inv-row-hover: #fcfcfc;
            }

            .dark {
                --inv-bg-card: #18181b;
                --inv-border-color: #27272a;
                --inv-text-primary: #ffffff;
                --inv-text-muted: #a1a1aa;
                --inv-bg-badge-success: rgba(16, 185, 129, 0.15);
                --inv-text-badge-success: #34d399;
                --inv-bg-badge-warning: rgba(245, 158, 11, 0.15);
                --inv-text-badge-warning: #fbbf24;
                --inv-bg-badge-danger: rgba(239, 68, 68, 0.15);
                --inv-text-badge-danger: #f87171;
                --inv-bg-stat-item: rgba(39, 39, 42, 0.5);
                --inv-table-header-bg: #1c1c1f;
                --inv-row-hover: rgba(39, 39, 42, 0.3);
            }

            /* Container and Grid */
            .inv-wrapper {
                color: var(--inv-text-primary);
                font-family: system-ui, -apple-system, sans-serif;
            }
            .inv-header {
                background-color: var(--inv-bg-card);
                border: 1px solid var(--inv-border-color);
                border-radius: 16px;
                padding: 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
            .inv-header-title {
                font-size: 22px;
                font-weight: 700;
                color: var(--inv-text-primary);
            }
            .inv-header-subtitle {
                font-size: 13px;
                color: var(--inv-text-muted);
                margin-top: 4px;
            }

            /* Filters Bar */
            .inv-filters-row {
                background-color: var(--inv-bg-card);
                border: 1px solid var(--inv-border-color);
                border-radius: 16px;
                padding: 16px 24px;
                margin-top: 20px;
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                align-items: flex-end;
            }
            .inv-filter-group {
                display: flex;
                flex-direction: column;
                gap: 6px;
            }
            .inv-filter-label {
                font-size: 12px;
                font-weight: 700;
                color: var(--inv-text-muted);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .inv-filter-input {
                background-color: var(--inv-bg-stat-item);
                border: 1px solid var(--inv-border-color);
                color: var(--inv-text-primary);
                padding: 8px 12px;
                border-radius: 10px;
                font-size: 14px;
                outline: none;
                width: 180px;
            }

            /* Actions Bar */
            .inv-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 20px;
            }
            .inv-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                border: 1px solid var(--inv-border-color);
                transition: all 0.2s ease;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            }
            .inv-btn:active {
                transform: scale(0.98);
            }
            .inv-btn-primary {
                background-color: #3b82f6;
                color: #ffffff;
                border-color: #2563eb;
            }
            .inv-btn-primary:hover {
                background-color: #2563eb;
            }
            .inv-btn-success {
                background-color: #10b981;
                color: #ffffff;
                border-color: #059669;
            }
            .inv-btn-success:hover {
                background-color: #059669;
            }
            .inv-btn-secondary {
                background-color: var(--inv-bg-stat-item);
                color: var(--inv-text-primary);
            }

            /* Responsive KPI grid */
            .inv-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-top: 24px;
            }
            @media (max-width: 768px) {
                .inv-grid { grid-template-columns: 1fr; }
            }

            .inv-card {
                background-color: var(--inv-bg-card);
                border: 1px solid var(--inv-border-color);
                border-radius: 16px;
                padding: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                position: relative;
                overflow: hidden;
            }
            .inv-card-stripe {
                position: absolute;
                top: 0;
                left: 0;
                height: 4px;
                width: 100%;
            }
            .inv-card-stripe.emerald { background-color: #10b981; }
            .inv-card-stripe.rose { background-color: #f43f5e; }
            .inv-card-stripe.blue { background-color: #3b82f6; }

            .inv-card-label {
                font-size: 11px;
                font-weight: 700;
                color: var(--inv-text-muted);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .inv-card-value {
                font-size: 26px;
                font-weight: 800;
                color: var(--inv-text-primary);
                margin-top: 8px;
                font-family: monospace;
            }
            .inv-card-desc {
                font-size: 12px;
                color: var(--inv-text-muted);
                margin-top: 6px;
            }

            /* Table Styles */
            .inv-table-card {
                background-color: var(--inv-bg-card);
                border: 1px solid var(--inv-border-color);
                border-radius: 16px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                margin-top: 24px;
                overflow: hidden;
            }
            .inv-table-header {
                padding: 18px 24px;
                border-bottom: 1px solid var(--inv-border-color);
                background-color: var(--inv-table-header-bg);
            }
            .inv-table-title {
                font-size: 16px;
                font-weight: 700;
                color: var(--inv-text-primary);
            }
            .inv-table-sub {
                font-size: 12px;
                color: var(--inv-text-muted);
                margin-top: 4px;
            }
            .inv-table-wrapper {
                width: 100%;
                overflow-x: auto;
            }
            .inv-table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }
            .inv-table th {
                padding: 14px 24px;
                font-size: 11px;
                font-weight: 700;
                color: var(--inv-text-muted);
                text-transform: uppercase;
                letter-spacing: 0.05em;
                border-bottom: 1px solid var(--inv-border-color);
                background-color: var(--inv-table-header-bg);
            }
            .inv-table td {
                padding: 14px 24px;
                font-size: 13.5px;
                border-bottom: 1px solid var(--inv-border-color);
                color: var(--inv-text-primary);
                vertical-align: middle;
            }
            .inv-table tr:last-child td {
                border-bottom: none;
            }
            .inv-table tbody tr:hover td {
                background-color: var(--inv-row-hover);
            }

            /* Print Stylesheet Overrides - Crucially sets Portrait mode */
            @media print {
                @page {
                    size: portrait; /* Forces Portrait layout in browser print dialog */
                    margin: 15mm 12mm 15mm 12mm;
                }
                body {
                    background: #ffffff !important;
                    color: #000000 !important;
                }
                .no-print, 
                header, 
                nav, 
                aside, 
                .fi-sidebar, 
                .fi-topbar, 
                .fi-main-ctn > header,
                .inv-actions,
                .inv-filters-row {
                    display: none !important;
                }
                .inv-wrapper {
                    padding: 0 !important;
                    margin: 0 !important;
                    width: 100% !important;
                }
                .inv-card, .inv-table-card {
                    border: 1px solid #000000 !important;
                    box-shadow: none !important;
                    page-break-inside: avoid;
                    background: #ffffff !important;
                    color: #000000 !important;
                }
                .inv-card-stripe {
                    display: none !important;
                }
            }
        </style>

        <!-- 1. Header Banner -->
        <div class="inv-header">
            <div>
                <h2 class="inv-header-title">Brand Ledger</h2>
                <p class="inv-header-subtitle">Chronological ledger detailing cash inflows, outflows, and net margins</p>
            </div>
            <div style="font-size: 28px;" class="no-print">💼</div>
        </div>

        <!-- 2. Date Filters Row (No-Print) -->
        <div class="inv-filters-row no-print">
            <div class="inv-filter-group">
                <span class="inv-filter-label">Start Date</span>
                <input type="date" wire:model.live="startDate" class="inv-filter-input" />
            </div>
            <div class="inv-filter-group">
                <span class="inv-filter-label">End Date</span>
                <input type="date" wire:model.live="endDate" class="inv-filter-input" />
            </div>
            <button wire:click="resetFilters" class="inv-btn inv-btn-secondary">
                🔄 Reset Range
            </button>
        </div>

        <!-- 3. Export Controls (No-Print) -->
        <div class="inv-actions no-print">
            <button onclick="window.print()" class="inv-btn inv-btn-primary">
                🖨️ Print Ledger / Save PDF (Portrait)
            </button>
            <button onclick="window.exportLedgerToCSV('ledger-table', 'Brand_Financial_Ledger.csv')" class="inv-btn inv-btn-success">
                📊 Export Ledger to Excel (CSV)
            </button>
        </div>

        <!-- 4. Financial Summaries Row -->
        <div class="inv-grid">
            <div class="inv-card">
                <div class="inv-card-stripe emerald"></div>
                <span class="inv-card-label">Total Sales (Revenue)</span>
                <div class="inv-card-value">Rs. {{ number_format($summary['total_sales'], 2) }}</div>
                <p class="inv-card-desc">Credits from completed checkouts</p>
            </div>

            <div class="inv-card">
                <div class="inv-card-stripe rose"></div>
                <span class="inv-card-label">Total Expenses</span>
                <div class="inv-card-value">Rs. {{ number_format($summary['total_expenses'], 2) }}</div>
                <p class="inv-card-desc">Debits from recorded overheads</p>
            </div>

            <div class="inv-card">
                <div class="inv-card-stripe blue"></div>
                <span class="inv-card-label">Net Profit / Loss</span>
                <div class="inv-card-value" style="color: {{ $summary['net_profit'] >= 0 ? '#10b981' : '#f43f5e' }};">
                    Rs. {{ number_format($summary['net_profit'], 2) }}
                </div>
                <p class="inv-card-desc">Remaining net brand earnings</p>
            </div>
        </div>

        <!-- 5. Ledger Statement Details Table -->
        <div class="inv-table-card">
            <div class="inv-table-header">
                <h4 class="inv-table-title">📈 Brand Financial Transactions Statement</h4>
                <p class="inv-table-sub">Showing transactions from {{ $startDate ?? 'Beginning' }} to {{ $endDate ?? 'Today' }}</p>
            </div>
            <div class="inv-table-wrapper">
                <table class="inv-table" id="ledger-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th style="text-align: right; color: #f43f5e;">Debit (Expense)</th>
                            <th style="text-align: right; color: #10b981;">Credit (Sale)</th>
                            <th style="text-align: right;">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ledgerEntries as $entry)
                            <tr>
                                <td style="font-family: monospace;">{{ $entry['date'] }}</td>
                                <td>
                                    <span class="inv-badge {{ str_contains($entry['type'], 'Sale') ? 'success' : 'danger' }}">
                                        {{ $entry['type'] }}
                                    </span>
                                </td>
                                <td>{{ $entry['description'] }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: 600; color: #f43f5e;">
                                    {{ $entry['debit'] > 0 ? 'Rs. ' . number_format($entry['debit'], 2) : '-' }}
                                </td>
                                <td style="text-align: right; font-family: monospace; font-weight: 600; color: #10b981;">
                                    {{ $entry['credit'] > 0 ? 'Rs. ' . number_format($entry['credit'], 2) : '-' }}
                                </td>
                                <td style="text-align: right; font-family: monospace; font-weight: 700; color: {{ $entry['balance'] >= 0 ? 'inherit' : '#f43f5e' }}">
                                    Rs. {{ number_format($entry['balance'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--inv-text-muted); padding: 32px;">
                                    No ledger entries found for the selected date range.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Client-side CSV/Excel exporter for ledger -->
    <script>
        window.exportLedgerToCSV = function(tableId, filename) {
            const table = document.getElementById(tableId);
            if (!table) return;
            
            let csv = [];
            const rows = table.querySelectorAll("tr");
            
            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll("td, th");
                
                for (let j = 0; j < cols.length; j++) {
                    let data = cols[j].innerText.trim();
                    // Clean symbols, commas, and line breaks
                    data = data.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, " ");
                    data = data.replace(/"/g, '""');
                    row.push('"' + data + '"');
                }
                csv.push(row.join(","));
            }
            
            const csvContent = "data:text/csv;charset=utf-8,\uFEFF" + csv.join("\n");
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</x-filament-panels::page>
