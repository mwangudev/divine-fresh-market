<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->id }} - Divine Fresh Market</title>
    <style>
        /* Make it look like a physical receipt */
        body { background-color: #555; display: flex; flex-direction: column; align-items: center; padding: 40px; font-family: 'Courier New', Courier, monospace; }
        .receipt-card { background: white; width: 300px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); color: black; }
        .text-center { text-align: center; }
        .dashed-line { border-top: 1px dashed black; margin: 10px 0; }
        .item-row { display: flex; justify-content: space-between; font-size: 14px; margin-bottom: 5px; }
        .action-buttons { margin-top: 20px; display: flex; gap: 10px; width: 340px; }
        .btn { flex: 1; padding: 15px; text-align: center; text-decoration: none; color: white; font-family: Arial, sans-serif; font-weight: bold; border-radius: 5px; cursor: pointer; border: none; }
        .btn-print { background-color: #2196F3; }
        .btn-back { background-color: #4CAF50; }

        /* When the printer actually prints, hide the background and buttons */
        @media print {
            body { background: white; padding: 0; align-items: flex-start; }
            .receipt-card { box-shadow: none; width: 100%; max-width: 300px; padding: 0; margin: 0; }
            .action-buttons { display: none; }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <div class="text-center">
            <h2 style="margin: 0;">DIVINE FRESH MARKET</h2>
            <p style="margin: 5px 0; font-size: 12px;">Dodoma, Tanzania</p>
            <p style="margin: 5px 0; font-size: 12px;">Tel: +255 123 456 789</p>
        </div>

        <div class="dashed-line"></div>

        <p style="margin: 5px 0; font-size: 12px;">Date: {{ $sale->created_at->format('d/m/Y H:i') }}</p>
        <p style="margin: 5px 0; font-size: 12px;">Receipt #: {{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</p>
        <p style="margin: 5px 0; font-size: 12px;">Cashier: {{ $sale->user->name }}</p>

        <div class="dashed-line"></div>

        <div class="item-row" style="font-weight: bold;">
            <span style="width: 50%;">Item</span>
            <span style="width: 15%; text-align: center;">Qty</span>
            <span style="width: 35%; text-align: right;">Total</span>
        </div>

        <div class="dashed-line"></div>

        @foreach($sale->items as $item)
            <div class="item-row">
                <span style="width: 50%;">{{ $item->product->name }}</span>
                <span style="width: 15%; text-align: center;">{{ $item->quantity }}</span>
                <span style="width: 35%; text-align: right;">{{ number_format($item->subtotal, 2) }}</span>
            </div>
        @endforeach

        <div class="dashed-line"></div>

        <div class="item-row" style="font-weight: bold; font-size: 16px;">
            <span>GRAND TOTAL:</span>
            <span>Tsh {{ number_format($sale->total_amount, 2) }}</span>
        </div>
        <div class="item-row">
            <span>Paid By:</span>
            <span>{{ $sale->payment_method }}</span>
        </div>

        <div class="dashed-line"></div>

        <div class="text-center" style="margin-top: 15px; font-size: 12px;">
            <p>Thank you for shopping with us!</p>
            <p>Welcome Again.</p>
        </div>
    </div>

    <div class="action-buttons">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print Receipt</button>
        <a href="{{ route('pos.index') }}" class="btn btn-back">🛒 Next Customer</a>
    </div>

</body>
</html>
