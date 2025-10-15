@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Modal -->
        <!-- Button trigger modal -->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">المنتجـــــات</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيســــية</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">المنتجــــــات</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <!-- DOM / jQuery  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center"> اســــم المنتـــج</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">  التصنيف</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">السعر <br> الحالى</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center"> الكـــــمية الحالية</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">الكمية المباعة</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">الكمية الكلية</th>
                                        <th  style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">قيمة المبيعات </th>
                                        <th  style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">الصــــــورة</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($products as $product)
                                        <tr>
                                                <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{mb_substr($product->name,0,20)}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{App\Models\Category::where('id',$product->category_id)->first()->name ?? ''}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{str_replace(',', '', number_format($product->current_price,2)) }}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$product->quantity}}</td>
                                                <?php $soldQuantity = App\Models\OrderItem::where('product_id', $product->id)->sum('quantity'); ?>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$soldQuantity ?? 0}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$soldQuantity +  $product->quantity}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{str_replace(',', '', number_format($soldQuantity * $product->current_price,2)) }}</td>
                                            <td><img width="70" src="{{asset('public/'.$product->image)}}" alt="product"> </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DOM / jQuery  Ends-->
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    <script src="{{ asset('admin/assets/js/editor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/assets/js/editor/ckeditor/ckeditor.custom.js') }}"></script>

@endsection

@section('toaster')
    @toastr_js
    @toastr_render
@endsection
