<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $order->order_number }}</title>
    @php
        $paper = config('receipt.paper_width', 58);
        $autoPrint = $autoPrint ?? config('receipt.auto_print', true);
    @endphp
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            width: {{ $paper }}mm;
            max-width: {{ $paper }}mm;
            font-family: 'Courier New', Courier, monospace;
            font-size: {{ $paper === 80 ? '13px' : '11px' }};
            line-height: 1.35;
            color: #000;
            background: #fff;
        }
        body { margin: 0 auto; padding: 2mm; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .line { border-top: 1px dashed #000; margin: 3mm 0; }
        .row { display: flex; justify-content: space-between; gap: 2mm; }
        .row-item { margin-bottom: 1.5mm; }
        .item-name { flex: 1; word-break: break-word; }
        .item-price { white-space: nowrap; text-align: right; }
        .item-meta { font-size: 0.9em; color: #333; padding-left: 2mm; }
        .total-row { font-weight: bold; font-size: 1.05em; margin-top: 1mm; }
        .footer { margin-top: 4mm; text-align: center; }
        .no-print {
            width: 100%;
            max-width: 320px;
            margin: 16px auto;
            padding: 12px;
            text-align: center;
            font-family: system-ui, sans-serif;
        }
        .no-print button, .no-print a {
            display: inline-block;
            margin: 4px;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 6px;
        }
        .no-print button { background: #0f172a; color: #fff; border: none; }
        .no-print a { background: #e2e8f0; color: #0f172a; }
        @media print {
            html, body { width: {{ $paper }}mm; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            @page { size: {{ $paper }}mm auto; margin: 0; }
        }
        @media screen {
            body { box-shadow: 0 0 0 1px #ddd; margin: 12px auto; }
        }
    </style>
</head>
<body>
    <div class="center bold" style="font-size:1.15em">{{ $order->outlet->name }}</div>
    @if($order->outlet->address)
        <div class="center" style="font-size:0.9em">{{ $order->outlet->address }}</div>
    @endif
    @if($order->outlet->phone)
        <div class="center" style="font-size:0.9em">Telp: {{ $order->outlet->phone }}</div>
    @endif

    <div class="line"></div>

    <div>{{ $order->order_number }}</div>
    @if($payment && $payment->paid_at)
        <div>{{ $payment->paid_at->format('d/m/Y H:i') }}</div>
    @else
        <div>{{ $order->created_at?->format('d/m/Y H:i') }}</div>
    @endif
    <div>Pelanggan: {{ $order->displayCustomer() }}</div>
    @if($order->diningTable)
        <div>Meja: {{ $order->diningTable->name }}</div>
    @endif

    <div class="line"></div>

    @foreach($items as $item)
        <div class="row-item">
            <div class="row">
                <span class="item-name">{{ $item->item_name }}</span>
                <span class="item-price">{{ number_format($item->subtotal(), 0, ',', '.') }}</span>
            </div>
            <div class="item-meta">{{ $item->qty }} × {{ number_format($item->price, 0, ',', '.') }}</div>
            @if($item->modifierSummary())
                <div class="item-meta">{{ $item->modifierSummary() }}</div>
            @endif
        </div>
    @endforeach

    <div class="line"></div>

    @php $receiptTotal = $payment ? $payment->amount : $order->total; @endphp
    <div class="row total-row">
        <span>TOTAL</span>
        <span>Rp {{ number_format($receiptTotal, 0, ',', '.') }}</span>
    </div>

    @if($payment)
        <div class="row">
            <span>Bayar ({{ strtoupper($payment->payment_method) }})</span>
            <span>{{ number_format($payment->amount_paid ?? $payment->amount, 0, ',', '.') }}</span>
        </div>
        @if($payment->change_amount)
            <div class="row"><span>Kembali</span><span>{{ number_format($payment->change_amount, 0, ',', '.') }}</span></div>
        @endif
    @elseif($order->payment_method)
        <div class="row">
            <span>Bayar ({{ strtoupper($order->payment_method) }})</span>
            <span>{{ number_format($order->amount_paid ?? $order->total, 0, ',', '.') }}</span>
        </div>
        @if($order->change_amount)
            <div class="row"><span>Kembali</span><span>{{ number_format($order->change_amount, 0, ',', '.') }}</span></div>
        @endif
    @endif

    <div class="line"></div>
    <div class="footer">Terima kasih!<br>Silakan datang kembali</div>

    <div class="no-print">
        <p style="margin-bottom:8px;color:#64748b;font-size:13px">Preview struk thermal {{ $paper }}mm</p>
        <button type="button" onclick="window.print()">🖨 Cetak Thermal</button>
        @if($order->hasUnpaidItems())
            <a href="{{ route('pos.payment', $order) }}">Bayar sisa</a>
        @else
            <a href="{{ route('pos.queue') }}">← Antrian</a>
        @endif
    </div>

    @if($autoPrint)
    <script>
        window.addEventListener('load', function () {
            setTimeout(function () { window.print(); }, 400);
        });
    </script>
    @endif
</body>
</html>
