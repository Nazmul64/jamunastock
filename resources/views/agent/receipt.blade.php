<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <title>বাজার কালেকশন ও আর্থিক ক্যাশমেমো রশিদ - {{ $delivery->delivery_no }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Hind Siliguri', 'Plus Jakarta Sans', Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #1e293b;
        }
        .receipt-card {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 18px;
            margin-bottom: 20px;
        }
        .receipt-header h2 {
            margin: 0 0 4px 0;
            color: #0f172a;
            font-size: 22px;
            font-weight: 800;
        }
        .receipt-header p {
            margin: 0;
            font-size: 13.5px;
            color: #64748b;
        }
        .voucher-badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            padding: 4px 16px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 800;
            margin-top: 8px;
            font-family: monospace;
        }
        .meta-table {
            width: 100%;
            font-size: 13.5px;
            margin-bottom: 18px;
        }
        .meta-table td {
            padding: 4px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            margin-bottom: 18px;
        }
        .items-table th {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 10px;
            font-weight: 700;
            color: #475569;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .items-table tfoot td {
            border-top: 2px solid #cbd5e1;
            padding: 12px 10px;
            font-weight: 800;
        }
        .footer-sign {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
        }
        .sign-line {
            border-top: 1px dashed #94a3b8;
            padding-top: 6px;
            width: 150px;
            text-align: center;
        }
        @media print {
            body { padding: 0; background: #fff; }
            .receipt-card { border: none; box-shadow: none; padding: 10px; width: 100%; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="max-width: 620px; margin: 0 auto 16px auto; display: flex; justify-content: space-between; align-items: center;">
        <a href="javascript:window.close()" style="color: #64748b; text-decoration: none; font-size: 13px;">&larr; বন্ধ করুন</a>
        <button onclick="window.print()" style="padding: 9px 22px; background: #0c5c2d; color: #fff; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 14px;">
            🖨️ রশিদ প্রিন্ট করুন (Print Receipt)
        </button>
    </div>

    <div class="receipt-card">
        <div class="receipt-header">
            <h2>{{ setting('title') ?: 'JAMUNA STOCK MARKET PLC' }}</h2>
            <p>মাসিক বাজার বিতরণ ও আর্থিক সংগ্রহ রশিদ (Market Collection & Financial Voucher)</p>
            <div class="voucher-badge">রশিদ নং: {{ $delivery->delivery_no }}</div>
        </div>

        <table class="meta-table">
            <tr>
                <td style="color: #64748b; width: 20%;">সদস্যের নাম:</td>
                <td style="width: 30%;"><strong>{{ $delivery->member_name }}</strong></td>
                <td style="color: #64748b; width: 20%;">তারিখ:</td>
                <td style="width: 30%;"><strong>{{ date('d M, Y', strtotime($delivery->delivery_date)) }}</strong></td>
            </tr>
            <tr>
                <td style="color: #64748b;">কার্ড নম্বর:</td>
                <td><strong style="font-family: monospace;">{{ $delivery->card_number }}</strong></td>
                <td style="color: #64748b;">সময়:</td>
                <td><strong>{{ date('h:i A', strtotime($delivery->delivery_time)) }}</strong></td>
            </tr>
            <tr>
                <td style="color: #64748b;">এজেন্ট পয়েন্ট:</td>
                <td><strong>{{ $delivery->agentPoint->name ?? '-' }}</strong></td>
                <td style="color: #64748b;">এলাকা:</td>
                <td><strong>{{ $delivery->area ?: ($delivery->agentPoint->area ?? 'N/A') }}</strong></td>
            </tr>
            <tr>
                <td style="color: #64748b;">বাজার মাস (Cycle):</td>
                <td colspan="3"><strong>{{ $delivery->market_month ?: date('Y-m', strtotime($delivery->delivery_date)) }}</strong></td>
            </tr>
        </table>

        <!-- Section 33.6: Financial & Rate Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>পণ্য (Product)</th>
                    <th style="text-align: right;">পরিমাণ (Qty)</th>
                    <th style="text-align: right;">দর (Unit Rate)</th>
                    <th style="text-align: right;">মোট মূল্য (Amount)</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $calcTotalAmount = 0; 
                    $calcTotalQty = 0;
                @endphp
                @foreach($delivery->items as $idx => $it)
                @php
                    $itemAmount = $it->total_amount > 0 ? $it->total_amount : ($it->quantity * $it->unit_price);
                    $calcTotalAmount += $itemAmount;
                    $calcTotalQty += $it->quantity;
                @endphp
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><strong>{{ $it->product_name }}</strong></td>
                    <td style="text-align: right; font-weight: 600;">{{ $it->quantity }} {{ $it->unit }}</td>
                    <td style="text-align: right; color: #475569;">৳ {{ number_format($it->unit_price, 2) }}</td>
                    <td style="text-align: right; font-weight: 700; color: #0c5c2d;">৳ {{ number_format($itemAmount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right;">সর্বমোট (Total):</td>
                    <td style="text-align: right;">{{ number_format($calcTotalQty, 1) }}</td>
                    <td></td>
                    <td style="text-align: right; color: #0c5c2d; font-size: 16px;">
                        ৳ {{ number_format($delivery->total_amount > 0 ? $delivery->total_amount : $calcTotalAmount, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px; border-radius: 8px; font-size: 13px; color: #166534; margin-bottom: 20px;">
            <div class="d-flex justify-content-between">
                <span><strong>বিতরণ অবস্থা:</strong> {{ $delivery->status === 'completed' ? '✓ সম্পূর্ণ বাজার বুঝিয়ে দেওয়া হয়েছে।' : '⚠️ আংশিক বাজার প্রদান করা হয়েছে।' }}</span>
                <span><strong>পদ্ধতি:</strong> কার্ডহোল্ডার ভেরিফাইড</span>
            </div>
            <div class="mt-1 small text-muted">
                <strong>মন্তব্য:</strong> {{ $delivery->remarks ?: 'পণ্য অক্ষত অবস্থায় সদস্যের নিকট হস্তান্তর সম্পন্ন হয়েছে।' }}
            </div>
        </div>

        <div class="footer-sign">
            <div class="sign-line">গ্রাহক / সদস্যের স্বাক্ষর</div>
            <div class="sign-line">এজেন্ট অফিসারের স্বাক্ষর ও সিল</div>
        </div>
    </div>

</body>
</html>
