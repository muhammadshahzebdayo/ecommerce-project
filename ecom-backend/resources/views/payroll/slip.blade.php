<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary_Slip_{{ str_replace(' ', '_', $payroll->employee->name) }}_{{ $payroll->month }}_{{ $payroll->year }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-color: #0f172a;
            --secondary-color: #475569;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
            --success-color: #15803d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 40px 20px;
            color: #1e293b;
        }

        .slip-container {
            max-width: 800px;
            background: #ffffff;
            margin: 0 auto;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            position: relative;
            border-top: 8px solid var(--primary-color);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 24px;
            margin-bottom: 30px;
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            fill: var(--primary-color);
        }

        .brand-name {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--primary-color);
            margin: 0;
        }

        .brand-tagline {
            font-size: 11px;
            color: var(--secondary-color);
            margin: 2px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .slip-title {
            text-align: right;
        }

        .slip-title h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-color);
            margin: 0;
            text-transform: uppercase;
        }

        .slip-title p {
            font-size: 13px;
            color: var(--secondary-color);
            margin: 4px 0 0 0;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 30px;
        }

        .details-block {
            background-color: var(--bg-light);
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .details-block h3 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--secondary-color);
            margin-top: 0;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 6px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            color: var(--secondary-color);
            font-weight: 500;
        }

        .detail-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .salary-table th {
            background-color: var(--primary-color);
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            text-align: left;
        }

        .salary-table th.amount-col {
            text-align: right;
        }

        .salary-table td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
            color: #334155;
        }

        .salary-table td.amount-col {
            text-align: right;
            font-weight: 600;
        }

        .salary-table tr.total-row td {
            font-weight: 700;
            background-color: var(--bg-light);
            border-bottom: 2px solid var(--border-color);
        }

        .payout-summary {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 40px;
        }

        .payout-box {
            background-color: #f0fdf4;
            border: 2px solid #bbf7d0;
            border-radius: 8px;
            padding: 20px;
            min-width: 250px;
            text-align: right;
        }

        .payout-box .payout-label {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--success-color);
            margin: 0;
        }

        .payout-box .payout-amount {
            font-size: 28px;
            font-weight: 800;
            color: var(--success-color);
            margin: 6px 0 0 0;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            padding-top: 20px;
        }

        .signature-line {
            width: 200px;
            text-align: center;
        }

        .signature-line p {
            margin: 0;
            font-size: 13px;
            color: var(--secondary-color);
        }

        .signature-line .line {
            border-top: 1px solid var(--secondary-color);
            margin-bottom: 8px;
        }

        .action-bar {
            max-width: 800px;
            margin: 20px auto 0 auto;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #1e293b;
        }

        .btn-secondary {
            background-color: #ffffff;
            color: var(--primary-color);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background-color: var(--bg-light);
        }

        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .slip-container {
                box-shadow: none;
                padding: 0;
                border: none;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="slip-container">
        <!-- Header -->
        <div class="header">
            <div class="brand-info">
                <svg class="brand-logo" viewBox="0 0 24 24">
                    <path d="M12 2L2 22h20L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z"/>
                </svg>
                <div>
                    <h2 class="brand-name">J.G.D FASHION STORE</h2>
                    <p class="brand-tagline">Premium Apparel & Footwear</p>
                </div>
            </div>
            <div class="slip-title">
                <h1>Salary Slip</h1>
                <p>Period: {{ date("F Y", mktime(0, 0, 0, $payroll->month, 10, $payroll->year)) }}</p>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="details-grid">
            <div class="details-block">
                <h3>Employee Details</h3>
                <div class="detail-row">
                    <span class="detail-label">Employee ID:</span>
                    <span class="detail-value">EMP-{{ str_pad($payroll->employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $payroll->employee->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Designation / Role:</span>
                    <span class="detail-value">{{ $payroll->employee->role }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Type:</span>
                    <span class="detail-value">{{ $payroll->employee->employee_type }}</span>
                </div>
                @if($payroll->employee->store)
                <div class="detail-row">
                    <span class="detail-label">Store Location:</span>
                    <span class="detail-value">{{ $payroll->employee->store->name }}</span>
                </div>
                @endif
            </div>
            
            <div class="details-block">
                <h3>Slip Metadata</h3>
                <div class="detail-row">
                    <span class="detail-label">Slip Reference:</span>
                    <span class="detail-value">PAY-{{ str_pad($payroll->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Print Date:</span>
                    <span class="detail-value">{{ now()->format('d-M-Y H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Purpose:</span>
                    <span class="detail-value">{{ $payroll->purpose ?? 'Monthly Salary Payout' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: {{ $payroll->payment_status === 'Paid' ? 'var(--success-color)' : '#b91c1c' }}">{{ $payroll->payment_status }}</span>
                </div>
                @if($payroll->payment_status === 'Paid')
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">{{ $payroll->payment_method }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Salary Breakdown Table -->
        <table class="salary-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount-col">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td class="amount-col">Rs. {{ number_format($payroll->basic_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>Allowances / Bonus</td>
                    <td class="amount-col">Rs. {{ number_format($payroll->allowances, 2) }}</td>
                </tr>
                <tr>
                    <td>Deductions (Taxes / Leaves)</td>
                    <td class="amount-col" style="color: #b91c1c;">- Rs. {{ number_format($payroll->deductions, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Net Payable Salary</td>
                    <td class="amount-col">Rs. {{ number_format($payroll->net_paid, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Grand Payout Box -->
        <div class="payout-summary">
            <div class="payout-box">
                <p class="payout-label">Net Disbursed Amount</p>
                <h2 class="payout-amount">Rs. {{ number_format($payroll->net_paid, 2) }}</h2>
            </div>
        </div>

        <!-- Notes / Comments -->
        @if($payroll->notes)
        <div class="details-block" style="margin-bottom: 40px;">
            <h3 style="margin-bottom: 8px;">Admin Notes</h3>
            <p style="margin: 0; font-size: 13px; color: var(--secondary-color);">{{ $payroll->notes }}</p>
        </div>
        @endif

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-line">
                <div class="line"></div>
                <p>Employee Signature</p>
            </div>
            <div class="signature-line">
                <div class="line"></div>
                <p>Authorized Signature</p>
            </div>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="action-bar no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 4px;">
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm7 0a2 2 0 1 1-4 0 2 2 0 0 1 4 0z"/>
            </svg>
            Print Slip / PDF
        </button>
        <a href="{{ route('payroll.slip.word', $payroll) }}" class="btn btn-secondary">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16" style="margin-right: 4px;">
                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 1.5 13 5h-3.5V1.5zM4.5 9h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1zm0 2h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z"/>
            </svg>
            Download Word
        </a>
    </div>

</body>
</html>
