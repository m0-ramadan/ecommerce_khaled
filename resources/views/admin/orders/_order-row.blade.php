<tr>
    <?php $i++; ?>
    <td>{{ $i }}</td>
    <td>{{ $order->code_order }}</td>

       <td>
        @switch($order->status)
            @case(0)
                طلبات منتظرة الموافقة
                @break
            @case(1)
                طلبات مقبولة
                @break
            @case(2)
                طلبات مرفوضة
                @break
            @case(3)
                طلبات جاري توصيلها
                @break
            @case(4)
                طلبات تم توصيلها
                @break
            @case(5)
                طلبات معلقة
                @break
            @case(6)
                طلبات في مرحلة التجهيز
                @break
             @case(7)
                طلبات ملغاه
                @break
            @default
                حالة غير معروفة
        @endswitch
    </td>

    <td>{{ $order->user_phone }}</td>
    <td>{{ $order->user_name }}</td>
    <td>{{ $order->address }}</td>
    <td>{{ $order->total }}</td>
    <td>{{ $order->created_at }}</td>
    <td>
        <button class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#exampleModal{{ $order->id }}" title="حذف الطلب"><i class="fa fa-remove"></i>
        </button>
        <button class="btn btn-info" type="button" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#details{{ $order->id }}" title="بيانات الطلب"><i class="fa fa-info"></i>
        </button>
        <button class="btn btn-info" type="button" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#order-status-{{ $order->id }}" title="تغيير حالة الطلب"><i class="fa fa-check"></i>
        </button>
        <a href="{{ route('emails.create') }}?client_email={{ $order->user_email }}&client_id={{ $order->client_id }}"
            class="btn btn-sm btn-info" title="ارسال ايميل">
            <i class="fa fa-envelope"></i>
        </a>
    </td>
</tr>

<!-- confirmed_modal_Grade -->
<div class="modal fade" id="confirmed{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">تاكيد الاوردر
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.update', $order->id) }}" method="post">
                {{ method_field('patch') }}
                @csrf
                <input id="id" type="hidden" name="id" class="form-control" value="{{ $order->id }}">
                <div class="modal-body">هل أنت متاكد من تاكيد الاوردر</div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                    <button class="btn btn-secondary" type="submit">تاكيد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- order status -->
<div class="modal fade" id="order-status-{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="order-status" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="order-status">تغيير حالة الطلب
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.update', $order) }}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <select class="form-select" aria-label="Select order status" name="status">
                        @foreach ($orderStatuses as $name => $value)
                            @continue($value == $order->status)
                            <option value="{{ $value }}">{{ strtolower($name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                    <button class="btn btn-secondary" type="submit">تأكيد</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- delete_modal_Grade -->
<div class="modal fade" id="exampleModal{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">حذف الاوردر</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('orders.destroy', $order->id) }}" method="post">
                {{ method_field('Delete') }}
                @csrf
                <input id="id" type="hidden" name="id" class="form-control"
                    value="{{ $order->id }}">
                <div class="modal-body">هل أنت متاكد من حذف الاوردر</div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                    <button class="btn btn-secondary" type="submit">حذف</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- details_modal_Grade -->
<div class="modal fade" id="details{{ $order->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content p-5">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">بيانات الاوردر
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom01">اسم
                            العميل</label>
                        <input style="border:solid 1px #555" class="form-control" disabled
                            value="{{ $order->client?->name ?? $order->user_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom01">رقم
                            هاتف العميل</label>
                        <input style="border:solid 1px #555" class="form-control" disabled
                            value="{{ $order->client?->phone ?? $order->user_name }}">
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom01">البريد
                            الالكتروني</label>
                        <input style="border:solid 1px #555" class="form-control" disabled
                            value="{{ $order->client?->email  ?? $order->email}}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom01">عنوان العميل</label>
                        <input style="border:solid 1px #555" class="form-control" disabled
                            value="{{ $order->client?->address ?? $order->address }}">
                    </div>
                    @if($order->PromoCode)
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom01"> كود الخصم </label>
                        <input style="border:solid 1px #555" class="form-control" disabled
                            value="{{ $order->PromoCode?->code ??'NULL' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="validationCustom01">(%) قيمة الخصم </label>
                        <input style="border:solid 1px #555" class="form-control" disabled
                            value="{{ $order->PromoCode?->mount ??'NULL'}}">
                    </div>
                    @endif
              
                    <hr>


                    @foreach ($order->orderitem as $item)
                        <div class="col">
                            <label class="form-label" for="validationCustom01">اسم المنتج</label>
                            <input class="form-control" disabled value="{{ $item->product->name ??"" }}">
                        </div>
                        <div class="col">
                            <label class="form-label" for="validationCustom01">الكمية</label>
                            <input class="form-control" disabled value="{{ $item->quantity }}">
                        </div>
                        <div class="col">
                            <label class="form-label" for="validationCustom02">سعر القطعة</label>
                            <input class="form-control" disabled value="{{ $item->product->current_price }}">
                        </div>
                        <div class="col">
                            <img width="70" src="{{ asset('public/' . $item->product->image) }}">
                        </div>
                        <div class="col">
                            <a href="{{ $item->product->aliexpress }}" target="_blank" rel="noopener noreferrer"
                                class="btn btn-info">Link</a>
                        </div>

                        <br>
                        <hr>
                    @endforeach
                </div>

                <br>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                </div>
            </div>
        </div>
    </div>
</div>
