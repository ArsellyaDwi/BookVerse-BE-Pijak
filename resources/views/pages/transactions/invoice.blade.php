<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px 20px;
        }
        
        .invoice-box {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .invoice-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .invoice-header p {
            opacity: 0.9;
            font-size: 14px;
        }
        
        .invoice-body {
            padding: 30px;
        }
        
        .company-info {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .company-details {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .col {
            flex: 1;
            min-width: 250px;
        }
        
        .invoice-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .invoice-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .label {
            font-weight: 600;
            color: #555;
            margin-right: 10px;
        }
        
        .customer-info, .delivery-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            height: 100%;
        }
        
        .customer-info h3, .delivery-info h3 {
            font-size: 16px;
            margin-bottom: 12px;
            color: #333;
            border-left: 3px solid #667eea;
            padding-left: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #555;
            font-size: 13px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
            color: #666;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-row {
            background: #f8f9fa;
        }
        
        .total-row td {
            font-weight: bold;
            color: #333;
        }
        
        .shipping-row td {
            color: #0284c7;
        }
        
        .grand-total {
            font-size: 18px;
            color: #059669;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-waiting_payment { background: #fef3c7; color: #92400e; }
        .status-shipped { background: #dbeafe; color: #1e40af; }
        .status-done { background: #d1fae5; color: #065f46; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
        
        .pickup-info {
            background: #e0f2fe;
            border-left: 3px solid #0284c7;
        }
        
        .delivery-method-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #e0e7ff;
            color: #4338ca;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            margin-top: 8px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .invoice-box {
                box-shadow: none;
                border-radius: 0;
            }
            
            .invoice-header {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .status-badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Thank you for your purchase</p>
        </div>
        
        <div class="invoice-body">
            <!-- Company Information from Store Settings -->
            <div class="company-info">
                <div class="company-name">{{ $storeSettings->store_name ?? config('app.name', 'Book Store') }}</div>
                <div class="company-details">
                    @if($storeSettings && $storeSettings->store_address)
                        {{ $storeSettings->store_address }}<br>
                    @endif
                    @if($storeSettings && $storeSettings->store_contact)
                        Contact: {{ $storeSettings->store_contact }}
                    @endif
                </div>
            </div>
            
            <!-- Invoice Info -->
            <div class="invoice-info">
                <p><span class="label">Invoice Number:</span> #{{ $transaction->id }}</p>
                <p><span class="label">Invoice Date:</span> {{ $transaction->date->format('F d, Y') }}</p>
                <p><span class="label">Payment Status:</span> 
                    <span class="status-badge status-{{ $transaction->status }}">
                        {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                    </span>
                </p>
            </div>
            
            <div class="row">
                <!-- Customer Information -->
                <div class="col">
                    <div class="customer-info">
                        <h3>Bill To:</h3>
                        <p><strong>{{ $transaction->user->name }}</strong></p>
                        <p>{{ $transaction->user->email }}</p>
                    </div>
                </div>
                
                <!-- Delivery Information -->
                <div class="col">
                    <div class="delivery-info {{ !$transaction->deliveryAddress ? 'pickup-info' : '' }}">
                        <h3>Delivery Method:</h3>
                        @if($transaction->deliveryAddress)
                            <p><strong>🚚 Shipping Address:</strong></p>
                            <p>{{ $transaction->deliveryAddress->address }}</p>
                            <p>{{ $transaction->deliveryAddress->village }}, {{ $transaction->deliveryAddress->district }}</p>
                            <p>{{ $transaction->deliveryAddress->city }}, {{ $transaction->deliveryAddress->province }}</p>
                            @if($transaction->deliveryMethod)
                                <div class="delivery-method-badge">
                                    {{ $transaction->deliveryMethod->name }}
                                    @if($transaction->deliveryMethod->estimated_days_min && $transaction->deliveryMethod->estimated_days_max)
                                        (Est. {{ $transaction->deliveryMethod->estimated_days_min }}-{{ $transaction->deliveryMethod->estimated_days_max }} days)
                                    @endif
                                </div>
                            @endif
                        @else
                            <p><strong>🏪 Pickup at Store</strong></p>
                            <p>Please pick up your order at our store location</p>
                            <p style="margin-top: 8px; font-size: 13px; color: #0284c7;">No shipping cost</p>
                            @if($storeSettings)
                                <p style="margin-top: 10px; font-size: 13px;">
                                    <strong>Store Address:</strong><br>
                                    {{ $storeSettings->store_address }}
                                </p>
                                @if($storeSettings->store_contact)
                                    <p style="font-size: 12px; margin-top: 5px;">Contact: {{ $storeSettings->store_contact }}</p>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Order Items Table -->
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Unit Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->book->title }}</strong><br>
                            <small style="color: #999;">{{ $item->book->author }}</small>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                        <td class="text-right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                    </tr>
                    @if($transaction->shipping_cost > 0)
                    <tr class="shipping-row">
                        <td colspan="3" class="text-right"><strong>Shipping Cost:</strong></td>
                        <td class="text-right">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="3" class="text-right"><strong>Grand Total:</strong></td>
                        <td class="text-right grand-total">
                            <strong>Rp {{ number_format($transaction->total + $transaction->shipping_cost, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
            
            <!-- Payment Method -->
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                <p><span class="label">💳 Payment Method:</span> {{ $transaction->paymentMethod->name ?? 'N/A' }}</p>
                @if($transaction->payment_proof)
                    <p><span class="label">📎 Payment Proof:</span> Uploaded</p>
                @endif
                @if($transaction->deliveryMethod && $transaction->shipping_cost > 0)
                    <p style="margin-top: 8px;"><span class="label">🚚 Shipping Method:</span> {{ $transaction->deliveryMethod->name }}</p>
                @endif
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>Thank you for shopping with {{ $storeSettings->store_name ?? config('app.name', 'Book Store') }}!</p>
                <p>For any inquiries, please contact us at {{ $storeSettings->store_contact ?? 'customer@bookstore.com' }}</p>
                <p style="margin-top: 10px;">This is a computer-generated invoice. No signature is required.</p>
            </div>
        </div>
    </div>
</body>
</html>