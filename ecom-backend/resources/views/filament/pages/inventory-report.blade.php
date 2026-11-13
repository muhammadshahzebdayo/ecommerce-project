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
            .inv-header-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                background-color: var(--inv-bg-badge-success);
                border: 1px solid rgba(16, 185, 129, 0.2);
                border-radius: 9999px;
                padding: 6px 14px;
                font-size: 12px;
                font-weight: 600;
                color: var(--inv-text-badge-success);
            }
            .inv-pulse {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background-color: #10b981;
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: .4; }
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

            /* Responsive KPI grid */
            .inv-grid {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 16px;
                margin-top: 24px;
            }
            @media (max-width: 1400px) {
                .inv-grid { grid-template-columns: repeat(3, 1fr); }
            }
            @media (max-width: 900px) {
                .inv-grid { grid-template-columns: repeat(2, 1fr); }
            }
            @media (max-width: 600px) {
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
            .inv-card-stripe.amber { background-color: #f59e0b; }
            .inv-card-stripe.blue { background-color: #3b82f6; }
            .inv-card-stripe.zinc { background-color: #71717a; }
            .inv-card-stripe.purple { background-color: #8b5cf6; }

            .inv-card-label {
                font-size: 11px;
                font-weight: 700;
                color: var(--inv-text-muted);
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .inv-card-value {
                font-size: 24px;
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

            /* Comparison section */
            .inv-comparison-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 24px;
                margin-top: 24px;
            }
            @media (max-width: 768px) {
                .inv-comparison-grid { grid-template-columns: 1fr; }
            }
            .inv-comp-card {
                background-color: var(--inv-bg-card);
                border: 1px solid var(--inv-border-color);
                border-radius: 16px;
                padding: 24px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            }
            .inv-comp-card.shoes { border-left: 5px solid #f59e0b; }
            .inv-comp-card.undergarments { border-left: 5px solid #a855f7; }

            .inv-comp-header {
                display: flex;
                align-items: center;
                gap: 12px;
                border-bottom: 1px solid var(--inv-border-color);
                padding-bottom: 16px;
                margin-bottom: 20px;
            }
            .inv-comp-title {
                font-size: 18px;
                font-weight: 700;
                color: var(--inv-text-primary);
            }
            .inv-comp-sub {
                font-size: 11px;
                color: var(--inv-text-muted);
                text-transform: uppercase;
                font-family: monospace;
                letter-spacing: 0.05em;
            }

            .inv-comp-grid-stats {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }
            .inv-stat-item {
                background-color: var(--inv-bg-stat-item);
                padding: 16px;
                border-radius: 12px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                border: 1px solid var(--inv-border-color);
            }
            .inv-stat-label {
                font-size: 11px;
                font-weight: 700;
                color: var(--inv-text-muted);
                text-transform: uppercase;
            }
            .inv-stat-value {
                font-size: 18px;
                font-weight: 800;
                color: var(--inv-text-primary);
                margin-top: 6px;
                font-family: monospace;
            }

            /* Progress Bar */
            .inv-progress-container {
                grid-column: span 2;
                margin-top: 8px;
            }
            .inv-progress-text {
                display: flex;
                justify-content: space-between;
                font-size: 12px;
                font-weight: 600;
                color: var(--inv-text-muted);
                margin-bottom: 6px;
            }
            .inv-progress-bg {
                background-color: var(--inv-border-color);
                height: 8px;
                border-radius: 9999px;
                overflow: hidden;
            }
            .inv-progress-bar {
                height: 100%;
                border-radius: 9999px;
            }
            .inv-progress-bar.shoes { background-color: #f59e0b; }
            .inv-progress-bar.undergarments { background-color: #a855f7; }

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
                font-size: 13px;
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

            /* Badges */
            .inv-badge {
                display: inline-flex;
                align-items: center;
                border-radius: 9999px;
                padding: 4px 10px;
                font-size: 11px;
                font-weight: 700;
                line-height: 1;
            }
            .inv-badge.success {
                background-color: var(--inv-bg-badge-success);
                color: var(--inv-text-badge-success);
            }
            .inv-badge.warning {
                background-color: var(--inv-bg-badge-warning);
                color: var(--inv-text-badge-warning);
            }
            .inv-badge.danger {
                background-color: var(--inv-bg-badge-danger);
                color: var(--inv-text-badge-danger);
            }

            /* Print Stylesheet Overrides */
            @media print {
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
                .inv-actions {
                    display: none !important;
                }
                .inv-wrapper {
                    padding: 0 !important;
                    margin: 0 !important;
                    width: 100% !important;
                }
                .inv-card, .inv-comp-card, .inv-table-card {
                    border: 1px solid #000000 !important;
                    box-shadow: none !important;
                    page-break-inside: avoid;
                    background: #ffffff !important;
                    color: #000000 !important;
                }
                .inv-stat-item {
                    border: 1px solid #cccccc !important;
                    background: #ffffff !important;
                    color: #000000 !important;
                }
                .inv-card-stripe {
                    display: none !important;
                }
            }
        </style>

        <!-- 1. Header Banner -->
        <div class="inv-header" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 class="inv-header-title">Inventory Insights & Analytics</h2>
                <p class="inv-header-subtitle">Real-time stock valuation and category sales breakdown</p>
            </div>
            <div class="inv-header-badge">
                <span class="inv-pulse"></span>
                <span>Live Database Synced</span>
            </div>
        </div>

        <!-- Action Controls (No-Print) -->
        <div class="inv-actions no-print">
            <button onclick="window.print()" class="inv-btn inv-btn-primary">
                🖨️ Print Report / Save PDF
            </button>
            <button onclick="window.exportTableToCSV('sku-table', 'Warehouse_SKU_Inventory_Report.csv')" class="inv-btn inv-btn-success">
                📊 Export SKU details to Excel (CSV)
            </button>
            <button onclick="window.exportTableToCSV('subcat-table', 'Undergarments_Subcategory_Report.csv')" class="inv-btn inv-btn-success">
                👙 Export Subcategory breakdown to Excel (CSV)
            </button>
        </div>

        <!-- 2. Overall Summary Cards Grid -->
        <div class="inv-grid">
            <div class="inv-card">
                <div class="inv-card-stripe emerald"></div>
                <span class="inv-card-label">Total Inventory Worth</span>
                <div class="inv-card-value">Rs. {{ number_format($overallStats['worth']) }}</div>
                <p class="inv-card-desc">Value of remaining stock</p>
            </div>

            <div class="inv-card">
                <div class="inv-card-stripe amber"></div>
                <span class="inv-card-label">In-Stock Units</span>
                <div class="inv-card-value">{{ number_format($overallStats['remaining_units']) }} Units</div>
                <p class="inv-card-desc">Currently in warehouse</p>
            </div>

            <div class="inv-card">
                <div class="inv-card-stripe blue"></div>
                <span class="inv-card-label">Units Sold (Delivered)</span>
                <div class="inv-card-value">{{ number_format($overallStats['sold_units']) }} Units</div>
                <p class="inv-card-desc">Delivered to customers</p>
            </div>

            <div class="inv-card">
                <div class="inv-card-stripe zinc"></div>
                <span class="inv-card-label">Total Sourced (Purchased)</span>
                <div class="inv-card-value">{{ number_format($overallStats['purchased_units']) }} Units</div>
                <p class="inv-card-desc">Total stock units purchased</p>
            </div>

            <div class="inv-card">
                <div class="inv-card-stripe purple"></div>
                <span class="inv-card-label">Stock Health Index</span>
                <div class="inv-card-value">{{ $stockHealth['health_ratio'] }}%</div>
                <p class="inv-card-desc">{{ $stockHealth['in_stock'] }} / {{ $stockHealth['total'] }} SKUs in stock</p>
            </div>
        </div>

        <!-- 3. Advanced Insights: Critical Alerts & Top Sellers -->
        <div class="inv-comparison-grid">
            <!-- Critical Low Stock Alerts -->
            <div class="inv-table-card" style="margin-top: 0;">
                <div class="inv-table-header" style="background-color: rgba(239, 68, 68, 0.05);">
                    <h4 class="inv-table-title" style="color: #f87171;">⚠️ Critical Inventory Alerts</h4>
                    <p class="inv-table-sub">Items with low stock (5 or less) needing replenishment</p>
                </div>
                <div class="inv-table-wrapper">
                    <table class="inv-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th style="text-align: right;">Stock</th>
                                <th style="text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockAlerts as $alert)
                                <tr>
                                    <td style="font-weight: 600;">{{ $alert['product'] }}</td>
                                    <td style="font-family: monospace; font-size: 11px; color: var(--inv-text-muted);">{{ $alert['sku'] }}</td>
                                    <td style="text-align: right; font-family: monospace; font-weight: 700; color: #f87171;">{{ number_format($alert['stock']) }}</td>
                                    <td style="text-align: center;">
                                        <span class="inv-badge {{ $alert['stock'] == 0 ? 'danger' : 'warning' }}">
                                            {{ $alert['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; color: var(--inv-text-muted); padding: 24px;">
                                        🎉 All items are fully stocked! No critical alerts.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Performing Products (Best Sellers) -->
            <div class="inv-table-card" style="margin-top: 0;">
                <div class="inv-table-header" style="background-color: rgba(16, 185, 129, 0.05);">
                    <h4 class="inv-table-title" style="color: #34d399;">🏆 Top Performing Products (Best Sellers)</h4>
                    <p class="inv-table-sub">Top 5 variants based on total units sold</p>
                </div>
                <div class="inv-table-wrapper">
                    <table class="inv-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th style="text-align: right;">Units Sold</th>
                                <th style="text-align: right;">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSellingProducts as $bestSeller)
                                <tr>
                                    <td style="font-weight: 600;">{{ $bestSeller['product'] }}</td>
                                    <td style="font-family: monospace; font-size: 11px; color: var(--inv-text-muted);">{{ $bestSeller['sku'] }}</td>
                                    <td style="text-align: right; font-family: monospace; font-weight: 700; color: #34d399;">{{ number_format($bestSeller['sold']) }}</td>
                                    <td style="text-align: right; font-family: monospace; font-weight: 700;">Rs. {{ number_format($bestSeller['revenue']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align: center; color: var(--inv-text-muted); padding: 24px;">
                                        No sales data available yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 4. Shoes vs Undergarments Comparison Section -->
        <div class="inv-comparison-grid">
            <!-- Footwear Card -->
            <div class="inv-comp-card shoes">
                <div class="inv-comp-header">
                    <span style="font-size: 24px;">👞</span>
                    <div>
                        <h4 class="inv-comp-title">Gents Footwear Inventory</h4>
                        <p class="inv-comp-sub">Category Totals</p>
                    </div>
                </div>
                <div class="inv-comp-grid-stats">
                    <div class="inv-stat-item">
                        <span class="inv-stat-label">In-Stock</span>
                        <span class="inv-stat-value">{{ number_format($footwearStats['remaining']) }} Units</span>
                    </div>
                    <div class="inv-stat-item">
                        <span class="inv-stat-label">Sold</span>
                        <span class="inv-stat-value">{{ number_format($footwearStats['sold']) }} Units</span>
                    </div>
                    <div class="inv-stat-item">
                        <span class="inv-stat-label">Total Sourced</span>
                        <span class="inv-stat-value">{{ number_format($footwearStats['purchased']) }} Units</span>
                    </div>
                    <div class="inv-stat-item">
                        <span class="inv-stat-label">Stock Value</span>
                        <span class="inv-stat-value" style="color: #fbbf24;">Rs. {{ number_format($footwearStats['worth']) }}</span>
                    </div>
                    <div class="inv-progress-container">
                        <div class="inv-progress-text">
                            <span>Sell-Through Rate</span>
                            <span>{{ $footwearStats['purchased'] > 0 ? round(($footwearStats['sold'] / $footwearStats['purchased']) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="inv-progress-bg">
                            <div class="inv-progress-bar shoes" style="width: {{ $footwearStats['purchased'] > 0 ? ($footwearStats['sold'] / $footwearStats['purchased']) * 100 : 0 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Undergarments Card -->
            <div class="inv-comp-card undergarments">
                <div class="inv-comp-header">
                    <span style="font-size: 24px;">👙</span>
                    <div>
                        <h4 class="inv-comp-title">Ladies Undergarments</h4>
                        <p class="inv-comp-sub">Category Totals</p>
                    </div>
                </div>
                <div class="inv-comp-grid-stats">
                    <div class="inv-stat-item">
                        <span class="inv-stat-label">In-Stock</span>
                        <span class="inv-stat-value">{{ number_format($undergarmentsStats['remaining']) }} Units</span>
                    </div>
                    <div class="inv-stat-item">
                        <span class="inv-stat-label">Sold</span>
                        <span class="inv-stat-value">{{ number_format($undergarmentsStats['sold']) }} Units</span>
                    </div>
                    <div class="inv-stat-item">
                        <span class="inv-stat-label">Total Sourced</span>
                        <span class="inv-stat-value">{{ number_format($undergarmentsStats['purchased']) }} Units</span>
                    </div>
                    <div class="inv-stat-item">
                        <span class="inv-stat-label">Stock Value</span>
                        <span class="inv-stat-value" style="color: #a855f7;">Rs. {{ number_format($undergarmentsStats['worth']) }}</span>
                    </div>
                    <div class="inv-progress-container">
                        <div class="inv-progress-text">
                            <span>Sell-Through Rate</span>
                            <span>{{ $undergarmentsStats['purchased'] > 0 ? round(($undergarmentsStats['sold'] / $undergarmentsStats['purchased']) * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="inv-progress-bg">
                            <div class="inv-progress-bar undergarments" style="width: {{ $undergarmentsStats['purchased'] > 0 ? ($undergarmentsStats['sold'] / $undergarmentsStats['purchased']) * 100 : 0 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Undergarments Subcategory Breakdown Table -->
        <div class="inv-table-card">
            <div class="inv-table-header">
                <h4 class="inv-table-title">👙 Ladies Undergarments Subcategory Breakdown</h4>
                <p class="inv-table-sub">Granular summary matching all underlying subcategories</p>
            </div>
            <div class="inv-table-wrapper">
                <table class="inv-table" id="subcat-table">
                    <thead>
                        <tr>
                            <th>Subcategory</th>
                            <th>SKU Prefix</th>
                            <th style="text-align: right;">In-Stock (Left)</th>
                            <th style="text-align: right;">Units Sold</th>
                            <th style="text-align: right;">Total Sourced</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subcategoryBreakdown as $row)
                            <tr>
                                <td style="font-weight: 600;">{{ $row['name'] }}</td>
                                <td style="font-family: monospace; font-size: 12px; color: var(--inv-text-muted);">{{ $row['sku_prefix'] }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: 700; color: #fbbf24;">{{ number_format($row['remaining']) }}</td>
                                <td style="text-align: right; font-family: monospace; color: var(--inv-text-muted);">{{ number_format($row['sold']) }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: 700;">{{ number_format($row['purchased']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 6. Granular SKU Inventory Details Table -->
        <div class="inv-table-card">
            <div class="inv-table-header">
                <h4 class="inv-table-title">🔍 Granular SKU Inventory Details (All Items)</h4>
                <p class="inv-table-sub">Sizing and coloring variants mapped to stocks</p>
            </div>
            <div class="inv-table-wrapper">
                <table class="inv-table" id="sku-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th style="text-align: right;">Price</th>
                            <th style="text-align: right;">In-Stock</th>
                            <th style="text-align: right;">Sold</th>
                            <th style="text-align: right;">Total Sourced</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($variantDetails as $row)
                            <tr>
                                <td style="font-weight: 600;">{{ $row['product'] }}</td>
                                <td style="font-size: 13px; color: var(--inv-text-muted);">{{ $row['category'] }}</td>
                                <td style="font-family: monospace; font-weight: 600;">{{ $row['sku'] }}</td>
                                <td style="text-align: right; font-family: monospace;">Rs. {{ number_format($row['price']) }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: 700; color: {{ $row['stock'] <= 5 ? '#f87171' : 'inherit' }}">
                                    {{ number_format($row['stock']) }}
                                </td>
                                <td style="text-align: right; font-family: monospace; color: var(--inv-text-muted);">{{ number_format($row['sold']) }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: 700;">{{ number_format($row['purchased']) }}</td>
                                <td style="text-align: center;">
                                    @if($row['stock'] == 0)
                                        <span class="inv-badge danger">Out of Stock</span>
                                    @elseif($row['stock'] <= 5)
                                        <span class="inv-badge warning">Low Stock</span>
                                    @else
                                        <span class="inv-badge success">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Client-side CSV/Excel export function -->
    <script>
        window.exportTableToCSV = function(tableId, filename) {
            const table = document.getElementById(tableId);
            if (!table) return;
            
            let csv = [];
            const rows = table.querySelectorAll("tr");
            
            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll("td, th");
                
                for (let j = 0; j < cols.length; j++) {
                    let data = cols[j].innerText.trim();
                    // Clean comma characters and handle line breaks
                    data = data.replace(/(\r\n|\n|\r)/gm, " ").replace(/\s+/g, " ");
                    data = data.replace(/"/g, '""');
                    row.push('"' + data + '"');
                }
                csv.push(row.join(","));
            }
            
            // Download process
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
