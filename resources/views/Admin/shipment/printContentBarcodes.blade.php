<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة باركود المحتويات</title>
    <style>
        body {
            font-family: 'Amiri', sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .barcode {
            width: 70mm;
            height: 30mm;
            margin: 0 auto;
            padding: 0.5mm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }

        .barcode img {
            max-width: 40mm;
            /* صغر العرض أكتر */
            max-height: 10mm;
            /* صغر الارتفاع أكتر */
            margin-top: 10mm;
            /* خلي المسافة اللي فوق أكبر */
            margin-bottom: 1mm;
        }

        .barcode p {
            margin: 0;
            font-size: 9pt;
            /* صغر الخط شوية عشان يناسب الحجم الجديد */
            line-height: 1;
            width: 100%;
            text-align: center;
        }
    </style>
</head>

<body>
    @if ($shipment->contents->isNotEmpty())
        @foreach ($shipment->contents as $content)
            @if ($content->barcode)
                <div>كود الشحنة: {{ $shipment->code }}</div>

                <div class="barcode">
                    <img src="{{ asset('public/storage/' . $content->barcode) }}" alt="Barcode" />
                    <p>{{ $content->code ?? '--' }}</p>
                </div>
            @endif
        @endforeach
    @else
        <p>لا توجد محتويات تحتوي على باركود</p>
    @endif
</body>

</html>
