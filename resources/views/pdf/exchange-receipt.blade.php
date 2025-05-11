<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Exchange Receipt #{{ $exchange->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .receipt-box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
        }
        .details-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .details-row {
            display: table-row;
        }
        .details-label {
            display: table-cell;
            padding: 5px;
            font-weight: bold;
            width: 150px;
        }
        .details-value {
            display: table-cell;
            padding: 5px;
        }
        .total-price {
            background: #f3f4f6;
            padding: 10px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Exchange Receipt</h1>
            <p>Receipt #{{ $exchange->id }}</p>
            <p>{{ now()->format('F d, Y') }}</p>
        </div>

        <div class="receipt-box">
            <h2>Exchange Details</h2>
            <div class="details-grid">
                <div class="details-row">
                    <div class="details-label">Textile Waste:</div>
                    <div class="details-value">{{ $exchange->textileWaste->title }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Type:</div>
                    <div class="details-value">{{ $exchange->textileWaste->waste_type }} - {{ $exchange->textileWaste->material_type }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Quantity:</div>
                    <div class="details-value">{{ number_format($exchange->quantity, 2) }} {{ $exchange->textileWaste->unit }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Price per Unit:</div>
                    <div class="details-value">${{ number_format($exchange->textileWaste->price_per_unit, 2) }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Status:</div>
                    <div class="details-value">{{ ucfirst($exchange->status) }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Exchange Date:</div>
                    <div class="details-value">{{ $exchange->exchange_date ? $exchange->exchange_date->format('M d, Y h:i A') : 'N/A' }}</div>
                </div>
            </div>

            <h2>Parties Involved</h2>
            <div class="details-grid">
                <div class="details-row">
                    <div class="details-label">Supplier:</div>
                    <div class="details-value">{{ $exchange->supplierCompany->company_name }}</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Receiver:</div>
                    <div class="details-value">{{ $exchange->receiverCompany->company_name }}</div>
                </div>
            </div>

            <div class="total-price">
                Total Price: ${{ number_format($exchange->quantity * $exchange->textileWaste->price_per_unit, 2) }}
            </div>
        </div>

        <div class="footer">
            <p>This is an official receipt for the textile waste exchange transaction.</p>
            <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
        </div>
    </div>
</body>
</html>

