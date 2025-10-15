@extends('admin.other_users.apps.sales_management.app')
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
                            <li class="breadcrumb-item"><a href="{{url('admin/sales-management')}}" style="font-family: 'Cairo', sans-serif;">الرئيســــية</a></li>
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
                            <div>
                             </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;"> اســــم المنتـــج</th>
                                        <th style="font-family: 'Cairo', sans-serif;">التفاصـــــيل</th>
                                        <th style="font-family: 'Cairo', sans-serif;">السعر الحالى</th>
                                        <th style="font-family: 'Cairo', sans-serif;">السعر القديم</th>
                                        <th style="font-family: 'Cairo', sans-serif;">      العمولة</th>

                                         <th style="font-family: 'Cairo', sans-serif;">الكـــــمية</th>
                                        <th style="font-family: 'Cairo', sans-serif;">القســم الرئــيسي</th>
                                         <th  style="font-family: 'Cairo', sans-serif;">الصــــــورة</th>
                                     </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                     @foreach($products as $product)
                                         <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$product->name}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{Str::words($product->details, 20)}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$product->current_price}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$product->old_price}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">
                                                @if( auth()->user()->class === 'A' )
                                                {{ $product->commission_class_A}}
                                                @elseif(auth()->user()->class === 'B')
                                                {{ $product->commission_class_B}}

                                                @elseif(auth()->user()->class === 'C')

                                                {{ $product->commission_class_C}}
                                                @endif
                                             </td>

                                             <td style="font-family: 'Cairo', sans-serif;">{{$product->quantity}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{App\Models\Category::where('id',$product->category_id)->first()->name ?? ''}}</td>
                                             <td><img width="70" src="{{asset('public/'.$product->image)}}" alt="product"> </td>
                                         </tr>

                                        <!-- edit_modal_Grade -->

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
@endsection
@section('toaster')
    @toastr_js
    @toastr_render
@endsection
