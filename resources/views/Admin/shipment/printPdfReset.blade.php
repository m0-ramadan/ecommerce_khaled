<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوليصة الشحن #{{ $shipment->code ?? '-' }}</title>
    <style>
        body {
            font-family: 'Amiri', serif;
            margin: 0;
            padding: 5mm;
            direction: rtl;
            text-align: center;
            font-size: 18px;
            line-height: 1.5;
            color: #000;
            width: 80mm;
            max-width: 80mm;
            unicode-bidi: embed;
            box-sizing: border-box;
            page-break-after: never;
        }

        .company-header {
            text-align: center;
            margin-bottom: 3px;
            display: flex;
            flex-direction: column;
            align-items: center;
            page-break-inside: avoid;
        }

        .company-logo {
            width: 40mm;
            height: auto;
            margin-bottom: 2px;
        }

        .company-name {
            font-size: 24px;
            color: #333;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
            margin: 0;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0 3px;
            border-bottom: 1px solid #333;
            page-break-inside: avoid;
        }

        .field {
            display: block;
            margin-bottom: 3px;
            word-wrap: break-word;
            page-break-inside: avoid;
            text-align: center;
        }

        .field-label {
            font-weight: bold;
            display: inline;
            margin-right: 4px;
        }

        .field-value {
            display: inline;
        }

        .barcode-container {
            text-align: center;
            margin: 6mm 0;
        }

        .barcode-container img {
            width: 60mm;
            height: auto;
            border: 1px solid #ddd;
            padding: 2mm;
            background-color: #fff;
        }

        .barcode-container p {
            color: #dc3545;
            font-weight: bold;
            font-size: 14px;
            margin-top: 3mm;
        }

        * {
            page-break-before: avoid;
            page-break-after: avoid;
            page-break-inside: avoid;
        }

        @media print {
            body {
                margin: 0;
                padding: 4mm;
                font-size: 16px;
            }

            .company-header {
                margin-bottom: 2px;
            }

            .company-logo {
                width: 35mm !important;
                height: auto !important;
                filter: grayscale(100%) brightness(0%) invert(100%) !important;
            }

            .company-name {
                font-size: 22px;
            }

            .header h1 {
                font-size: 22px;
            }

            .section-title {
                font-size: 18px;
            }

            .field {
                font-size: 16px;
            }

            .barcode-container img {
                width: 50mm;
            }

            .barcode-container p {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="company-header">
        <img src="{{ asset('public/images/dark.png') }}" alt="شعار شركة أويا" class="company-logo">
    </div>

    <div class="section-title">تفاصيل بوليصة الشحن</div>
    <div class="section-title">
        {{ \Carbon\Carbon::parse($shipment->created_at)->locale('ar')->translatedFormat('d F Y
                                                                                                                                                                                                                                        H:i') }}
    </div>



    <div class="field">
        <span class="field-label">كود الشحنة:</span>
        <span class="field-value">{{ $shipment->code ?? '-' }}</span>
    </div>
    <div class="field">
        <span class="field-label">السعر:</span>
        <span class="field-value">
            @php
                $currencyLabel = null;
                if ($shipment->currency_id) {
                    switch ($shipment->currency_id) {
                        case 1:
                            $currencyLabel = 'LYD';
                            break;
                        case 2:
                            $currencyLabel = 'EGP';
                            break;
                        case 3:
                            $currencyLabel = '$';
                            break;
                        case 4:
                            $currencyLabel = 'TRY';
                            break;
                    }
                } elseif (optional($shipment->client)->country_id) {
                    switch ($shipment->client->country_id) {
                        case 1:
                            $currencyLabel = 'LYD';
                            break;
                        case 2:
                            $currencyLabel = 'EGP';
                            break;
                        case 3:
                            $currencyLabel = '$';
                            break;
                        case 4:
                            $currencyLabel = 'TRY';
                            break;
                        default:
                            $currencyLabel = 'لم يتم التعرف على العملة';
                    }
                }
            @endphp
            @if ($shipment->price)
                {{ $shipment->price }} {{ $currencyLabel }}
            @else
                -
            @endif
        </span>
    </div>

    <div class="field">
        <span class="field-label">سعر التوصيل {{ $shipment->type == 1 ? 'الداخلي' : '' }}</span>
        <span class="field-value">
            {{ $shipment->shipping_cost ?? '0' }} {{ $currencyLabel }}</span>
    </div>

    <div class="field">
        <span class="field-label">التغليف:</span>
        <span class="field-value">{{ $shipment->packaging_cost ?? 0 }} {{ $currencyLabel }}</span>
    </div>
    <div class="field">
        <span class="field-label">الجمرك:</span>
        <span class="field-value">
            @if ($shipment->customs_included === '0' || $shipment->customs_included === 0)
                شامل الجمرك
            @elseif ($shipment->customs_included === '1' || $shipment->customs_included === 1)
                غير شامل الجمرك
            @else
                -
            @endif
        </span>
    </div>


    <div class="field">
        <span class="field-label">العنوان:</span>
        <span class="field-value">{{ Str::limit($shipment->address_received ?? ' --', 50) }}</span>
    </div>

    <div class="field">
        <span class="field-label">من فرع:</span>
        <span class="field-value">{{ optional($shipment->branchFrom)->name ?? '-' }}</span>
    </div>
    @if ($shipment->type == 1)
        <div class="field">
            <span class="field-label">الي فرع:</span>
            <span class="field-value">{{ optional($shipment->branchTo)->name ?? '-' }}</span>
        </div>
    @endif

    @if ($shipment->type == 2)
        <div class="field">
            <span class="field-label">الي مدينة:</span>
            <span class="field-value">{{ optional($shipment->region)->region_ar ?? '-' }}</span>
        </div>
    @endif
    @if ($shipment->type == 1)
        <div class="field">
            <span class="field-label">نوع الدفع:</span>
            <span class="field-value">
                @switch($shipment->payment_type_id)
                    @case(1)
                        خالص
                    @break

                    @case(2)
                        غير خالص
                    @break

                    @case(4)
                        متبقي جزء
                    @break

                    @default
                        غير محدد
                @endswitch
            </span>
        </div>

        <div class="field">
            <span class="field-label">المصروف:</span>
            <span class="field-value">{{ $shipment->expense_code ?? '-' }} {{ $currencyLabel }}</span>
        </div>
        <div class="field">
            <span class="field-label">المدفوع:</span>
            <span class="field-value">{{ $shipment->refund_code ?? '-' }} {{ $currencyLabel }}</span>
        </div>
        <div class="field">
            <span class="field-label">المتبقي:</span>
            <span
                class="field-value">{{ ($shipment->price ?? 0) +
                    ($shipment->shipping_cost ?? 0) +
                    ($shipment->expense_code ?? 0) +
                    ($shipment->packaging_cost ?? 0) -
                    ($shipment->refund_code ?? 0) }}
                {{ $currencyLabel }}</span>
        </div>
    @endif

    <div class="field">
        <span class="field-label">الملاحظات:</span>
        <span class="field-value">{{ Str::limit($shipment->describe_shipments ?? '-', 50) }}</span>
    </div>

    <div class="field">
        <span class="field-label">العدد:</span>
        <span class="field-value">{{ $shipment->contents ? $shipment->contents->sum('quantity') : 0 }}</span>
    </div>
    <div class="field">
        <span class="field-label">الوزن:</span>
        <span class="field-value">{{ $shipment->weight ?? '-' }}</span>
    </div>

    <div class="field">
        <span class="field-label">رقم المستلم:</span>
        <span class="field-value">{{ $shipment->phone_received ?? '--' }}</span>
    </div>
    <div class="field">
        <span class="field-label">اسم المستلم:</span>
        <span class="field-value">{{ $shipment->name_received ?? '--' }}</span>
    </div>


    <div class="field">
        <span class="field-label">ملاحظات:</span>
        <span class="field-value">{{ Str::limit($shipment->notes ?? '-', 50) }}</span>
    </div>

    @if (0)
        <div class="barcode-container">
            @if ($shipment->barcode && file_exists(storage_path('app/public/' . $shipment->barcode)))
                <img src="{{ asset('public/storage/' . $shipment->barcode) }}"
                    alt="باركود الشحنة {{ $shipment->code ?? '-' }}">
            @else
                <p>باركود الشحنة غير متوفر</p>
            @endif
        </div>
    @endif

</body>

</html>


</html>
