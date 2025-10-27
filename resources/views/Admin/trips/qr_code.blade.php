<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز QR للرحلة #{{ $trip->code ?? 'غير محدد' }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Amiri', 'DejaVu Sans', serif;
            direction: rtl;
            text-align: center;
            margin: 0;
            padding: 6mm;
            box-sizing: border-box;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .company-header {
            margin-bottom: 6mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            page-break-inside: avoid;
        }

        .company-logo {
            width: 30mm;
            height: auto;
            margin-bottom: 3mm;
        }

        .qr-code-container {
            text-align: center;
            margin: 6mm 0;
        }

        .qr-code-container img {
            width: 60mm;
            height: auto;
            border: 1px solid #ddd;
            padding: 2mm;
            background-color: #fff;
        }

        .qr-code-container p {
            color: #dc3545;
            font-weight: bold;
            font-size: 14pt;
            margin-top: 3mm;
        }

        @media print {
            body {
                margin: 0;
                padding: 4mm;
            }

            .company-logo {
                width: 25mm;
            }

            .qr-code-container img {
                width: 50mm;
            }

            .qr-code-container p {
                font-size: 12pt;
            }
        }
    </style>
</head>

<body>
    <div class="company-header">
        <img src="{{ asset('public/images/logo.png') }}" alt="شعار شركة أويا" class="company-logo">
    </div>

    <div class="qr-code-container">
        @if ($trip->qr_code && file_exists(storage_path('app/public/' . $trip->qr_code)))
        <img src="{{ asset('public/storage/' . $trip->qr_code) }}" alt="رمز QR للرحلة {{ $trip->code ?? 'غير محدد' }}">
        @else
        <p>رمز QR غير متوفر</p>
        @endif
    </div>
</body>

</html>