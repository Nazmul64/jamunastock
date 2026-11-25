<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $voucher->voucher_no }} - {{ $voucher->type_label }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Hind Siliguri', sans-serif;
        }
        body {
            background-color: #f1f5f9;
            margin: 0;
            padding: 30px;
            color: #1e293b;
        }
        .print-container {
            max-width: 820px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #cbd5e1;
            position: relative;
        }
        .header-section {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .company-title {
            font-size: 26px;
            font-weight: 800;
            color: #044b20;
            margin: 0 0 5px 0;
        }
        .company-meta {
            font-size: 13px;
            color: #475569;
            margin: 0;
            line-height: 1.5;
        }
        .voucher-badge-title {
            display: inline-block;
            background: #044b20;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            padding: 8px 24px;
            border-radius: 30px;
            text-align: center;
            letter-spacing: 0.5px;
        }
        .voucher-meta-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .voucher-meta-table td {
            padding: 8px 12px;
            font-size: 14px;
        }
        .voucher-meta-table .label {
            color: #64748b;
            font-weight: 600;
            width: 18%;
        }
        .voucher-meta-table .val {
            font-weight: 700;
            color: #0f172a;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #cbd5e1;
            padding: 12px 14px;
            font-size: 14px;
        }
        .items-table th {
            background-color: #f8fafc;
            font-weight: 700;
            color: #0f172a;
            text-align: left;
        }
        .total-amount-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }
        .signatures-section {
            display: flex;
            justify-content: space-between;
            margin-top: 70px;
            padding-top: 10px;
        }
        .sig-block {
            text-align: center;
            width: 22%;
            border-top: 1px dashed #64748b;
            padding-top: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }
        .no-print-bar {
            max-width: 820px;
            margin: 0 auto 20px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background: #044b20;
            color: #ffffff;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        .btn-back {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .print-container {
                box-shadow: none;
                border: none;
                padding: 20px 0;
                max-width: 100%;
            }
            .no-print-bar {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Non-printable action buttons -->
    <div class="no-print-bar">
        <a href="javascript:history.back()" class="btn-back"><i class="fa fa-arrow-left"></i> ফিরে যান</a>
        <button onclick="window.print()" class="btn-print"><i class="fa fa-print"></i> প্রিন্ট করুন / PDF সংরক্ষণ</button>
    </div>

    <!-- Printable Container -->
    <div class="print-container">
        <!-- Header -->
        <div class="header-section">
            <div>
                <h1 class="company-title">{{ setting('title') ?: 'যমুনা স্টক মার্কেট পিএলসি' }}</h1>
                <p class="company-meta">
                    {{ setting('address1') ?: 'প্রধান কার্যালয়: হাউজিং # ১, রোড # ৪, ব্লক # বি, বসিলা, মোহাম্মদপুর, ঢাকা-১২০৭' }}<br>
                    ফোন: {{ setting('phone1') ?: '+88 01915 752724' }} | ইমেইল: {{ setting('email1') ?: 'jamunastockmarket@gmail.com' }}
                </p>
            </div>
            <div style="text-align: right;">
                <div class="voucher-badge-title">
                    {{ $voucher->type_label }}
                </div>
            </div>
        </div>

        <!-- Voucher Details Table -->
        <table class="voucher-meta-table">
            <tr>
                <td class="label">ভাউচার / মেমো নং:</td>
                <td class="val"><span style="font-size: 16px; color: #044b20;">{{ $voucher->voucher_no }}</span></td>
                <td class="label">তারিখ:</td>
                <td class="val">{{ $voucher->date ? $voucher->date->format('d/m/Y') : date('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">হিসাবের খাত:</td>
                <td class="val">{{ $voucher->category_label }}</td>
                <td class="label">পেমেন্ট মাধ্যম:</td>
                <td class="val">{{ strtoupper($voucher->payment_method) }} {{ $voucher->payment_ref ? '(' . $voucher->payment_ref . ')' : '' }}</td>
            </tr>
            <tr>
                <td class="label">{{ in_array($voucher->type, ['money_receipt', 'credit_voucher']) ? 'গৃহীত ব্যক্তি / প্রতিষ্ঠান:' : 'প্রাপক / দোকান:' }}</td>
                <td class="val" colspan="3">{{ $voucher->vendor_customer_name ?: 'অফিস ক্যাশ' }}</td>
            </tr>
            <tr>
                <td class="label">বিষয় / শিরোনাম:</td>
                <td class="val" colspan="3">{{ $voucher->title }}</td>
            </tr>
        </table>

        <!-- Itemized Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%; text-align: center;">ক্র.নং</th>
                    <th>মালামাল / সেবার বিবরণ ও হিসাবের ব্রেকডাউন</th>
                    <th style="width: 25%; text-align: right;">টাকার পরিমাণ (৳)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">১</td>
                    <td>
                        <strong style="font-size: 15px; color: #0f172a;">{{ $voucher->title }}</strong>
                        @if($voucher->item_details)
                            <div style="margin-top: 8px; font-size: 13.5px; color: #334155; line-height: 1.6; white-space: pre-line;">{!! nl2br(e($voucher->item_details)) !!}</div>
                        @endif
                        @if($voucher->description)
                            <div style="margin-top: 6px; font-size: 12.5px; color: #64748b;">নোট: {{ $voucher->description }}</div>
                        @endif
                    </td>
                    <td style="text-align: right; font-weight: 700; font-size: 16px;">
                        ৳{{ number_format($voucher->amount, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Total Box -->
        <div class="total-amount-box">
            <div>
                <span style="font-size: 13px; color: #64748b; font-weight: 600;">অনুমোদন ও স্ট্যাটাস:</span>
                <strong style="color: #044b20; text-transform: uppercase;">{{ $voucher->status }}</strong>
                @if($voucher->approved_by)
                    <span style="font-size: 12px; color: #475569;">(অনুমোদনকারী: {{ $voucher->approved_by }})</span>
                @endif
            </div>
            <div style="text-align: right;">
                <span style="font-size: 14px; font-weight: 600; color: #475569;">সর্বমোট টাকা:</span>
                <span style="font-size: 22px; font-weight: 800; color: #044b20; margin-left: 10px;">৳{{ number_format($voucher->amount, 2) }}</span>
            </div>
        </div>

        <!-- Official Signatures -->
        <div class="signatures-section">
            <div class="sig-block">
                প্রস্তুতকারী (Prepared By)
            </div>
            <div class="sig-block">
                যাচাইকারী (Checked By)
            </div>
            <div class="sig-block">
                হিসাব কর্মকর্তা (Accounts)
            </div>
            <div class="sig-block">
                ব্যবস্থাপনা পরিচালক / এমডি
            </div>
        </div>
    </div>

</body>
</html>

