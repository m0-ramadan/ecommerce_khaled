<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الشحنة #{{ $shipment->code ?? '-' }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Amiri', 'Arial', sans-serif;
            margin: 0;
            padding: 4mm;
            direction: rtl;
            text-align: center;
            font-size: 24pt;
            unicode-bidi: embed;
            -webkit-font-feature-settings: "liga", "rlig";
            font-feature-settings: "liga", "rlig";
            box-sizing: border-box;
            width: 100%;
            min-height: 100vh;
        }

        .company-header {
            text-align: center;
            margin-bottom: 4mm;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .company-logo {
            width: 40mm;
            height: auto;
            max-width: 100%;
            max-height: 40mm;
            object-fit: contain;
            margin-bottom: 2mm;
        }

        .company-name {
            font-size: 32pt;
            color: #333;
            font-weight: bold;
        }

        h1 {
            color: #333;
            text-align: center;
            margin: 4mm 0;
            font-size: 40pt;
        }

        .section-title {
            margin-top: 6mm;
            margin-bottom: 2mm;
            font-size: 30pt;
            border-bottom: 2px solid #333;
            page-break-after: avoid;
        }

        table {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 6mm;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #000;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4mm;
            text-align: center;
            font-size: 20pt;
            word-wrap: break-word;
            max-width: 0;
        }

        th {
            font-weight: bold;
        }

        /* Ensure rounded corners only on outer edges */
        table tr:first-child th:first-child,
        table tr:first-child td:first-child {
            border-top-right-radius: 8px;
        }

        table tr:first-child th:last-child,
        table tr:first-child td:last-child {
            border-top-left-radius: 8px;
        }

        table tr:last-child th:first-child,
        table tr:last-child td:first-child {
            border-bottom-right-radius: 8px;
        }

        table tr:last-child th:last-child,
        table tr:last-child td:last-child {
            border-bottom-left-radius: 8px;
        }

        @media print {
            body {
                margin: 0;
                padding: 3mm;
                font-size: 22pt;
            }

            .company-header {
                margin-bottom: 3mm;
            }

            .company-logo {
                width: 35mm;
                max-height: 35mm;
            }

            .company-name {
                font-size: 30pt;
            }

            h1 {
                font-size: 36pt;
                margin: 3mm 0;
            }

            .section-title {
                font-size: 28pt;
                margin-bottom: 1.5mm;
            }

            table {
                width: 85%;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 5mm;
                border-collapse: separate;
                border-spacing: 0;
                border: 1px solid #000;
                border-radius: 8px;
                page-break-inside: avoid;
            }

            th,
            td {
                padding: 3mm;
                font-size: 18pt;
                border: 1px solid #000;
            }

            /* Maintain rounded corners in print */
            table tr:first-child th:first-child,
            table tr:first-child td:first-child {
                border-top-right-radius: 8px;
            }

            table tr:first-child th:last-child,
            table tr:first-child td:last-child {
                border-top-left-radius: 8px;
            }

            table tr:last-child th:first-child,
            table tr:last-child td:first-child {
                border-bottom-right-radius: 8px;
            }

            table tr:last-child th:last-child,
            table tr:last-child td:last-child {
                border-bottom-left-radius: 8px;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>
    <div class="company-header">
        <img src="{{ asset('public/images/dark.png') }}" alt="شعار شركة أويا" class="company-logo">
    </div>

    <table>
        <tr>
            <th>كود الشحنة</th>
            <td>{{ $shipment->code ?? '-' }}</td>
        </tr>
        <tr>
            <th>السعر</th>
            <td>
                @php
                    $currencyLabel = '';
                    if ($shipment->currency_id) {
                        $currencyLabel = match ($shipment->currency_id) {
                            1 => 'LYD',
                            2 => 'EGP',
                            3 => '$',
                            4 => 'TRY',
                            default => 'غير معروف',
                        };
                    } elseif (isset($shipment->client) && $shipment->client->country_id) {
                        $currencyLabel = match ($shipment->client->country_id) {
                            1 => 'LYD',
                            2 => 'EGP',
                            3 => '$',
                            4 => 'TRY',
                            default => 'غير معروف',
                        };
                    }
                @endphp
                {{ ($shipment->price ?? 0) .
                    // +($shipment->shipping_cost ?? 0) +
                    //     ($shipment->expense_code ?? 0) +
                    //     ($shipment->packaging_cost ?? 0)
                    ' ' .
                    $currencyLabel }}
            </td>
        </tr>
        <tr>
            <th>سعر التوصيل {{ $shipment->type == 1 ? 'الداخلي' : '' }}</th>
            <td>
                {{ $shipment->shipping_cost . ' ' . $currencyLabel }}
            </td>
        </tr>
        <tr>
            <th>التغليف</th>
            <td>{{ $shipment->packaging_cost ?? '0' . ' ' . $currencyLabel }}</td>
        </tr>
        <tr>
            <th>العنوان</th>
            <td>{{ $shipment->address_received ?? '-' }}</td>
        </tr>
        <tr>
            <th>من فرع</th>
            <td>{{ optional($shipment->branchFrom)->name ?? '-' }}</td>
        </tr>
        @if ($shipment->type == 1)
            <tr>
                <th>إلى فرع</th>
                <td>{{ optional($shipment->branchTo)->name ?? '-' }}</td>
            </tr>
        @else
            <tr>
                <th>إلى مدينة</th>
                <td>{{ optional($shipment->region)->region_ar ?? '-' }}</td>
            </tr>
        @endif
        @if ($shipment->type == 1)
            <tr>
                <th>السداد</th>
                <td>{{ $shipment->paymentType?->name ?? 'غير خالص' }}</td>
            </tr>
            <tr>
                <th>المصروف</th>
                <td>{{ $shipment->expense_code ?? '0' . ' ' . $currencyLabel }}</td>
            </tr>
            <tr>
                <th>المدفوع</th>
                <td>{{ $shipment->refund_code ?? '0' . ' ' . $currencyLabel }}</td>
            </tr>
            <tr>
                <th>المتبقي</th>
                <td> {{ ($shipment->price ?? 0) +
                    ($shipment->shipping_cost ?? 0) +
                    ($shipment->expense_code ?? 0) +
                    ($shipment->packaging_cost ?? 0) -
                    ($shipment->refund_code ?? 0) .
                    ' ' .
                    $currencyLabel }}
                </td>
            </tr>
            <tr>
                <th> الجمرك</th>
                <td>
                    @if ($shipment->customs_included === '0' || $shipment->customs_included === 0)
                        شامل الجمرك
                    @elseif ($shipment->customs_included === '1' || $shipment->customs_included === 1)
                        غير شامل الجمرك
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endif
        <tr>
            <th>الملاحظات</th>
            <td>{{ $shipment->describe_shipments ?? '-' }}</td>
        </tr>
        <tr>
            <th>العدد</th>

            <td>
                @if ($shipment->type == 1)
                    {{ $shipment->contents ? $shipment->contents->sum('quantity') : 0 }}
                @else
                    {{ $shipment->products_in ? $shipment->products_in->sum('quantity') : $shipment->quantity ?? 0 }}
                @endif

            </td>

        </tr>

        <tr>
            <th>الوزن</th>
            <td>{{ $shipment->weight ? $shipment->weight . ' كجم' : '-' }}</td>
        </tr>
        <tr>
            <th>رقم المستلم</th>
            <td>{{ $shipment->phone_received ?? '--' }}</td>
        </tr>
        <tr>
            <th>اسم المستلم</th>
            <td>{{ $shipment->name_received ?? '--' }}</td>
        </tr>
        @if (0)
            <tr>
                <th>ملاحظات</th>
                <td>{{ $shipment->notes ?? '-' }}</td>
            </tr>
        @endif

    </table>
    <div class="barcode">
        @if ($shipment->barcode && \Illuminate\Support\Facades\Storage::disk('public')->exists($shipment->barcode))
            <img src="{{ asset('public/storage/' . $shipment->barcode) }}" alt="Barcode for {{ $shipment->code }}">
        @else
            <p>الباركود غير متوفر</p>
        @endif
    </div>
</body>

</html>
