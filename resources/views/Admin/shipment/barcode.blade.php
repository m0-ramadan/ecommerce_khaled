<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إيصال الشحنة - {{ $shipment->code }}</title>
    <style>
        body {
            font-family: 'Amiri', sans-serif;
            font-size: 12px;
            direction: rtl;
            text-align: right;
            margin: 0;
            padding: 3mm;
            width: 80mm;
            color: #000;
        }

        .container {
            width: 100%;
            padding: 0;
        }

        .logo {
            text-align: center;
            margin-bottom: 2mm;
        }

        .logo img {
            width: 40mm;
            height: auto;
            max-height: 15mm;
        }

        .barcode {
            text-align: center;
            margin: 3mm 0;
        }

        .barcode img {
            width: 60mm;
            height: auto;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 3mm;
            border-top: 1px solid #000;
            padding-top: 1mm;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="https://oya-ly.com/images/setting/1745413422.png" alt="Company Logo">

        </div>
        <div class="barcode">
            @if ($shipment->barcode && \Illuminate\Support\Facades\Storage::disk('public')->exists($shipment->barcode))
            <img src="{{ asset('public/storage/' . $shipment->barcode) }}" alt="Barcode for {{ $shipment->code }}">
            @else
            <p>الباركود غير متوفر</p>
            @endif
        </div>
        <div class="footer">
            <p>شكرًا لاستخدام خدماتنا</p>
            <p>تاريخ الإصدار: {{ now()->format('Y-m-d H:i') }}</p>
        </div>
    </div>
</body>

</html>