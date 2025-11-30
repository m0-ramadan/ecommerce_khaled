@extends('Admin.layout.master')

@section('title')
    طرق الدفع
@endsection

@section('css')
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="https://seda.codeella.com/home">الرئيسية</a>
                </li>
                <li class="breadcrumb-item active">طرق الدفع</li>



            </ol>
        </nav>


        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="p-3 d-flex justify-content-between">
                <div>
                    <form>
                        <div class="input-group">
                            <input value="" name="key_words" type="text" class="form-control" placeholder="الاسم"
                                aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button"
                                id="button-addon1">ابحث<i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="btn-group" role="group" aria-label="Basic example">

                        <a href="https://seda.codeella.com/admin/payment-methods/create"
                            class="btn btn-outline-primary waves-effect text-primary">اضافة<i
                                class="fa-solid fa-circle-plus"></i></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>ملاحظات</th>
                            <th>الاحداث</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">

                        <tr>
                            <th>1</th>


                            <td>Instapay</td>
                            <td></td>

                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="https://seda.codeella.com/admin/payment-methods/2/edit"><i
                                                class="ti ti-pencil "></i>تعديل</a>


                                        <a data-url="https://seda.codeella.com/admin/payment-methods/2"
                                            data-text_btn_confirm="تأكيد" data-text_btn_cancel="الغاء" data-method="delete"
                                            data-message="هل أنت متأكد من الحذف" class="dropdown-item btn-action"
                                            href="javascript:void(0);"><i class="ti ti-trash "></i> حذف</a>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <th>2</th>


                            <td>cash</td>
                            <td>برجاء التأكد من ان الرقم المدخل به فودافون كاش</td>

                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item"
                                            href="https://seda.codeella.com/admin/payment-methods/1/edit"><i
                                                class="ti ti-pencil "></i>تعديل</a>


                                        <a data-url="https://seda.codeella.com/admin/payment-methods/1"
                                            data-text_btn_confirm="تأكيد" data-text_btn_cancel="الغاء" data-method="delete"
                                            data-message="هل أنت متأكد من الحذف" class="dropdown-item btn-action"
                                            href="javascript:void(0);"><i class="ti ti-trash "></i> حذف</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="m-3">

                </div>

            </div>
        </div>
        <!--/ Basic Bootstrap Table -->

    </div>
@endsection


@section('js')
@endsection
