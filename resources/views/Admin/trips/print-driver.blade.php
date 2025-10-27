<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الرحلة #{{ $trip->code ?? 'غير محدد' }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Amiri', 'DejaVu Sans', serif;
            direction: rtl;
            text-align: right;
            font-size: 16pt;
            unicode-bidi: bidi-override;
            margin: 0;
            padding: 6mm;
            box-sizing: border-box;
            width: 100%;
            min-height: 100vh;
        }

        .company-header {
            text-align: center;
            margin-bottom: 6mm;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .company-logo {
            width: 30mm;
            height: auto;
            margin-bottom: 3mm;
        }

        .company-name {
            font-size: 24pt;
            color: #333;
            font-weight: bold;
        }

        h1 {
            color: #333;
            text-align: center;
            font-size: 28pt;
            margin: 6mm 0;
        }

        h2 {
            color: #333;
            text-align: center;
            font-size: 22pt;
        }

        h3 {
            color: #333;
            text-align: center;
            font-size: 20pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6mm;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 3mm;
            text-align: right;
            font-size: 12pt;
            word-wrap: break-word;
            max-width: 0;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .badge {
            padding: 2mm 4mm;
            border-radius: 5px;
            color: white;
            display: inline-block;
            font-size: 10pt;
        }

        .badge-warning {
            background-color: #ffc107;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        .header {
            margin-bottom: 6mm;
            text-align: center;
        }

        .section-title {
            margin-top: 6mm;
            margin-bottom: 3mm;
            font-size: 22pt;
            border-bottom: 2px solid #333;
            page-break-after: avoid;
        }

        .qr-code-container {
            text-align: center;
            margin: 6mm 0;
        }

        .qr-code-container img {
            max-width: 70px;
            height: auto;
            border: 1px solid #ddd;
            padding: 2px;
            background-color: #fff;
        }

        .qr-code-container p {
            color: #dc3545;
            font-weight: bold;
            font-size: 12pt;
        }

        @media print {
            body {
                margin: 0;
                padding: 4mm;
                font-size: 14pt;
            }

            .company-logo {
                width: 25mm;
            }

            .company-name {
                font-size: 22pt;
            }

            h1 {
                font-size: 26pt;
            }

            h2 {
                font-size: 20pt;
            }

            h3 {
                font-size: 18pt;
            }

            .section-title {
                font-size: 20pt;
            }

            th,
            td {
                padding: 2mm;
                font-size: 10pt;
            }

            .badge {
                padding: 1.5mm 3mm;
                font-size: 8pt;
            }

            .qr-code-container img {
                max-width: 50px;
            }

            .qr-code-container p {
                font-size: 10pt;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>
    <div class="header" style="text-align: center;">
        <br style="margin: 0 !important; padding: 0 !important;">
        <br style="margin: 0 !important; padding: 0 !important;">
        <img src="{{ asset('public/images/logo.png') }}" alt="شعار شركة أويا" class="company-logo"
            style="margin-bottom: 5px !important;">
        <h1 style="margin: 5px 0 !important;">تفاصيل الرحلة</h1>
        <h3 style="margin: 5px 0 !important;">الكود: {{ $trip->code ?? 'غير محدد' }}</h3>
    </div>

    <!-- Trip Information -->
    <h2 class="section-title">
        {{ \Carbon\Carbon::parse($trip->created_at)->locale('ar')->translatedFormat('l, d F Y H:i:s') }}</h2>
    <table>
        <tr>
            <th>الاسم</th>
            <td>{{ $trip->representative?->name ?? 'غير محدد' }}</td>
            <th>الكود</th>
            <td>{{ $trip->representative?->code ?? '--' }}</td>
        </tr>
        <tr>
            <th>من فرع</th>
            <td>{{ $trip->branchFrom?->name ?? 'غير متوفر' }}</td>
            @if ($trip->type_driver == 0)
                <th>إلى فرع</th>
                <td>{{ $trip->branchTo?->name ?? 'غير متوفر' }}</td>
            @else
                <th>إلى مدينة</th>
                <td>{{ $trip->region?->region_ar ?? 'غير متوفر' }}</td>
            @endif
        </tr>
        <tr>
            <th>عدد المحتويات</th>
            <td>{{ $trip->contents->count() }}</td>
            @if ($trip->type_driver == 0)
                <th>حساب الرحلة</th>
                <td>{{ $trip->value_drive ?? '--' }}</td>
            @else
                <th>ملاحظات</th>
                <td>{{ $trip->description ?? 'غير متوفر' }}</td>
            @endif
        </tr>
        @if ($trip->type_driver == 0)
            <tr>
                <th>المدفوع</th>
                <td>{{ $trip->expense_value ?? '--' }}</td>
                <th>الباقي</th>
                <td>{{ $trip->refund_value ?? '--' }}</td>
            </tr>
        @endif
    </table>

    <!-- Contents List -->
    @if ($trip->contents->isNotEmpty())
        <h2 class="section-title">المحتويات المرتبطة</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>كود المحتوى</th>
                    <th>اسم المحتوى</th>
                    <th>كود الشحنة</th>
                    <th>الكمية</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trip->contents as $index => $content)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $content->code ?? 'غير محدد' }}</td>
                        <td>{{ $content->name ?? 'غير محدد' }}</td>
                        <td>{{ $content->shipment->code ?? 'غير محدد' }}</td>
                        <td>{{ $content->pivot->quantity ?? 'غير محدد' }}</td>
                        <td>
                            <span class="badge {{ $content->getStatusBadgeClassAttribute() }}">
                                {{ $content->getStatusLabelAttribute() }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-size: 12pt;">لا توجد محتويات مرتبطة بهذه الرحلة</p>
    @endif

    <!-- Shipments List (Optional) -->
    @if ($trip->shipments->isNotEmpty())
        <h2 class="section-title">الشحنات المرتبطة</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>كود الشحنة</th>
                    @if ($trip->type_driver == 1)
                        <th>اسم العميل</th>
                        <th>رقم المستلم</th>
                        <th>عنوان المستلم</th>
                    @endif
                    <th>العدد</th>
                    <th>الوصف</th>
                    @if ($trip->type_driver == 1)
                        <th>الوزن</th>
                        <th>التكلفة بالتوصيل</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($trip->shipments as $index => $shipment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $shipment->code ?? 'غير محدد' }}</td>
                        @if ($trip->type_driver == 1)
                            <td>{{ $shipment->name_received ?? '--' }}</td>
                            <td>{{ $shipment->phone_received ?? '--' }}</td>
                            <td>{{ $shipment->address_received ?? '--' }}</td>
                        @endif
                        <td>{{ $shipment->quantity ?? 'غير محدد' }}</td>
                        <td>{{ $shipment->notes ?? '--' }}</td>
                        @if ($trip->type_driver == 1)
                            <td>{{ $shipment->weight ?? 'غير محدد' }} كجم</td>
                            <td>{{ $shipment->calculateTotalCost() ?? 'غير محدد' }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-size: 12pt;">لا توجد شحنات مرتبطة بهذه الرحلة</p>
    @endif

</body>

</html>
