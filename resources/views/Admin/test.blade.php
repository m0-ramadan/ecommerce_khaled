<link rel="stylesheet" href="https://seda.codeella.com/assets/vendor/libs/rateyo/rateyo.css" />
<style>
    #map {
        width: 100%;
        height: 400px;
        /* يمكنك تعديل الارتفاع حسب الحاجة */
        margin-top: 20px;
    }
</style>

<style>
    body {
        font-family: "Cairo", sans-serif !important;
    }

    .layout-navbar-fixed body:not(.modal-open) .layout-content-navbar .layout-navbar,
    .layout-menu-fixed body:not(.modal-open) .layout-content-navbar .layout-navbar,
    .layout-menu-fixed-offcanvas body:not(.modal-open) .layout-content-navbar .layout-navbar {
        z-index: 1043;
    }

    .layout-navbar-fixed body:not(.modal-open) .layout-content-navbar .layout-menu,
    .layout-menu-fixed body:not(.modal-open) .layout-content-navbar .layout-menu,
    .layout-menu-fixed-offcanvas body:not(.modal-open) .layout-content-navbar .layout-menu {
        z-index: 1043;
    }

    i {
        margin: 0px 5px 0px 5px
    }

    textarea {
        height: 100px;
    }
</style>


<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->



            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="https://seda.codeella.com/home">الرئيسية</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="javascript:void(0);"></a>
                                </li>
                                <li class="breadcrumb-item active"></li>


                            </ol>
                        </nav>

                        <div class="col-xl-12 mb-4 col-lg-7 col-12">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="card-title mb-0">اللإحصائيات</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-3">
                                        <div class="col-md-3 col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                                    <i class="ti ti-user-pin ti-md"></i>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">851</h5>
                                                    <small>عدد السائقين</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="badge rounded-pill bg-label-success me-3 p-2">
                                                    <i class="ti ti-user-bolt ti-md"></i>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">8</h5>
                                                    <small>Online</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="d-flex align-items-center">
                                                <div class="badge rounded-pill bg-label-warning me-3 p-2">
                                                    <i class="ti ti-user-question ti-md"></i>
                                                </div>
                                                <div class="card-info">
                                                    <h5 class="mb-0">926</h5>
                                                    <small>عدد السائقين المعلقين</small>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <div>
                                <div class="row">
                                    <div class="p-3 d-flex justify-content-between">
                                        <div>
                                            <form method="GET" action="https://seda.codeella.com/admin/users/drivers">
                                                <div class="input-group">
                                                    <input value="" name="key_words" type="text"
                                                        class="form-control" placeholder="ابحث بالاسم أو رقم الموبايل"
                                                        aria-label="Example text with button addon"
                                                        aria-describedby="button-addon1">
                                                    <button type="submit" class="btn btn-outline-primary waves-effect"
                                                        id="button-addon1">ابحث <i
                                                            class="fa-solid fa-magnifying-glass"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                        <div>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <a href="https://seda.codeella.com/admin/users/drivers/export"
                                                    class="btn btn-outline-primary waves-effect text-primary">تصدير
                                                    xls<i class="fa-solid fa-file-export"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class=" mb-4">
                                            <div class="card-body">
                                                <div class="">


                                                </div>
                                                <p class="demo-inline-spacing">
                                                <div class="p-3 d-flex justify-content-between">
                                                    <a class="btn btn-primary me-1" data-bs-toggle="collapse"
                                                        href="#collapseExample" role="button" aria-expanded="false"
                                                        aria-controls="collapseExample">
                                                        <i class="ti ti-adjustments-horizontal"></i>
                                                    </a>


                                                    <div class="btn-group" role="group" aria-label="Basic example">
                                                        <a href="https://seda.codeella.com/admin/users/drivers/create"
                                                            class="btn btn-outline-primary waves-effect text-primary">اضافة<i
                                                                class="fa-solid fa-circle-plus"></i></a>
                                                    </div>
                                                </div>

                                                </p>
                                                <div class="collapse border border-dark rounded p-4"
                                                    id="collapseExample">

                                                    <form id="form_filter">
                                                        <div class="row">


                                                            <div class=" col-6 mt-4">
                                                                <label class="mb-1">السائق</label>
                                                                <select name="driver_id"
                                                                    class="form-control select2 select_driver_id">
                                                                    <option value="">--------</option>
                                                                    <option value="232">
                                                                        محمد عبد الرحمن متولي طاحون</option>
                                                                    <option value="233">
                                                                        علاء عبد الحميد</option>
                                                                </select>
                                                            </div>
                                                            <div class=" col-6 mt-4">
                                                                <label class="mb-1">المحافظة</label>
                                                                <select name="governorate_id"
                                                                    class="form-control select2 select_governorate_id">
                                                                    <option value="">--------</option>
                                                                    <option value="12">
                                                                        المنوفية</option>
                                                                </select>
                                                            </div>

                                                            <div class=" col-6 mt-4">
                                                                <label class="mb-1">انواع المركبات</label>
                                                                <select name="vehicle_type_id"
                                                                    class="form-control select2 select_vehicle_type_id">
                                                                    <option value="">--------</option>
                                                                    <option value="10">
                                                                        تاكسي</option>
                                                                    <option value="9">
                                                                        توكتوك</option>
                                                                    <option value="11">
                                                                        ملاكي</option>
                                                                </select>
                                                            </div>


                                                            <div class=" col-6 mt-4">
                                                                <label class="mb-1">الحالة</label>
                                                                <select name="online_status"
                                                                    class="form-control select2 select_online_status">
                                                                    <option value="">--------</option>
                                                                    <option value="online">Online</option>

                                                                    <option value="offline">
                                                                        Offline</option>
                                                                </select>
                                                            </div>

                                                            <div class=" col-6 mt-4">
                                                                <label class="mb-1">التقييم</label>
                                                                <select name="rating"
                                                                    class="form-control select2 select_rating">
                                                                    <option value="">--------</option>
                                                                    <option value="1_to_2">
                                                                        من 1 إلى 2 نجوم</option>
                                                                    <option value="2_to_3">
                                                                        من 2 إلى 3 نجوم</option>
                                                                    <option value="3_to_4">
                                                                        من 3 إلى 4 نجوم</option>
                                                                    <option value="+4">
                                                                        اكثر من 4 نجوم</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-6 mt-4">
                                                                <label class="mb-1">التحقق من رقم المحمول</label>
                                                                <select name="mobile_verified_at"
                                                                    class="form-control select2 select_mobile_verified">
                                                                    <option value="">--------</option>
                                                                    <option value="verified">تم التحقق</option>
                                                                    <option value="not_verified">لم يتم التحقق
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-4">
                                                            <div class="col-12">
                                                                <button type="submit"
                                                                    class="btn btn-primary me-1 w-100">
                                                                    تصفية<i class="ti ti-adjustments-horizontal"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </form>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end px-5">

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary waves-effect waves-light">Select
                                        Actions</button>
                                    <button type="button"
                                        class="btn btn-primary dropdown-toggle dropdown-toggle-split waves-effect waves-light"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden"> </span>
                                    </button>
                                    <ul class="dropdown-menu" style="">

                                        <li>
                                            <a data-url="" data-text_btn_confirm="تأكيد" id="delete_select"
                                                data-text_btn_cancel="الغاء" data-method="post"
                                                data-message="هل أنت متأكد من حذف العناصر المحددة حذف نهائي ؟ مع العلم انه لايمكنك التراجع عن هذا الاجراء  ؟ "
                                                class="dropdown-item btn-action btn-check-delete"
                                                href="javascript:void(0);">حذف</a>

                                        </li>

                                    </ul>
                                </div>

                            </div>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>

                                            <th>
                                                <div class="form-check form-check-primary mt-3">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="selectAll">
                                                    <label class="form-check-label" for="customCheckPrimary"></label>
                                                </div>
                                            </th>

                                            <th>#</th>

                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">

                                        <tr>
                                            <th>
                                                <div class="form-check form-check-primary mt-3">
                                                    <input class="form-check-input rowCheckbox" type="checkbox"
                                                        value="1776">
                                                    <label class="form-check-label" for="customCheckPrimary"></label>
                                                </div>
                                            </th>
                                            <th>1</th>

                                            <td>
                                                <div class="me-3">
                                                    <img src="https://seda.codeella.com/uploads/drivers/AVzGMe9CXnXsZOiTIMBH1754920413.jpg"
                                                        alt="User" class="rounded" width="46">
                                                </div>
                                            </td>
                                            <td width="100px">
                                                <div class="flex-grow-1 row">
                                                    <h6 class="mb-0">
                                                        <a href="https://seda.codeella.com/admin/users/drivers/1776">soliman
                                                            Mohamed</a>

                                                    </h6>
                                                    <small class="text-muted">
                                                        <a
                                                            href="https://seda.codeella.com/admin/users/drivers/1776">+201100688222</a>
                                                        <span class="badge bg-label-success me-1"><i
                                                                class="fa-solid fa-circle-check"></i></span>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                المنوفية
                                            </td>

                                            <td>
                                                <span class="badge bg-label-danger me-1">Offline<i style=""
                                                        class="fa-solid fa-circle-xmark"></i></span>
                                            </td>
                                            <td>
                                                -----
                                            </td>
                                            <td>

                                                <span class="badge bg-label-warning me-1" style="width: 175px">بانتظار
                                                    مراجعة المستندات</span>
                                            </td>

                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- زرار القبول -->
                                                    <button type="button" class="btn btn-sm btn-success accept-doc"
                                                        data-id="1776" data-toggle="modal"
                                                        data-target="#actionModal">قبول المستند</button>

                                                    <!-- زرار الرفض -->
                                                    <button type="button" class="btn btn-sm btn-danger reject-doc"
                                                        data-id="1776" data-toggle="modal"
                                                        data-target="#actionModal">رفض المستند</button>
                                                </div>
                                            </td>

                                            <td>
                                                0.00 EGP
                                            </td>

                                            <td>
                                                -----
                                            </td>
                                            <td>
                                                2025-08-11
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                        data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu">

                                                        <a class="dropdown-item"
                                                            href="https://seda.codeella.com/admin/users/drivers/1776"><i
                                                                class="ti ti-eye "></i>عرض</a>

                                                        <a class="dropdown-item"
                                                            href="https://seda.codeella.com/admin/users/drivers/1776/edit"><i
                                                                class="ti ti-pencil "></i>تعديل</a>

                                                        <a class="dropdown-item"
                                                            href="https://seda.codeella.com/admin/users/drivers/1776/orders"><i
                                                                class="ti ti-car "></i>الرحلات</a>


                                                        <a data-url="https://seda.codeella.com/admin/users/drivers/1776/block"
                                                            data-text_btn_confirm="تأكيد" data-text_btn_cancel="الغاء"
                                                            data-method="post"
                                                            data-message="هل أنت متأكد من حظر الحساب ؟"
                                                            class="dropdown-item btn-action"
                                                            href="javascript:void(0);"><i
                                                                class="fa-solid fa-ban "></i> حظر الحساب</a>
                                                        <a data-url="https://seda.codeella.com/admin/users/drivers/1776"
                                                            data-text_btn_confirm="تأكيد" data-text_btn_cancel="الغاء"
                                                            data-method="delete" data-message="هل أنت متأكد من الحذف"
                                                            class="dropdown-item btn-action"
                                                            href="javascript:void(0);"><i class="ti ti-trash "></i>
                                                            حذف</a>
                                                        <a data-url="https://seda.codeella.com/admin/users/drivers/1776/unverify"
                                                            data-text_btn_confirm="تأكيد" data-text_btn_cancel="الغاء"
                                                            data-method="post"
                                                            data-message=" هل انت متأكد من الغاء تفعيل الحساب ؟"
                                                            class="dropdown-item btn-action"
                                                            href="javascript:void(0);"><i
                                                                class="fa-solid fa-ban "></i>
                                                            إلغاء تفعيل الحساب</a>


                                                        <a class="dropdown-item"
                                                            href="https://seda.codeella.com/admin/users/drivers/1776/notify"><i
                                                                class="ti ti-bell "></i>إرسال إشعار</a>


                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                    </tbody>
                                </table>
                                <div class="m-3">
                                    <nav>
                                        <ul class="pagination">

                                            <li class="page-item disabled" aria-disabled="true"
                                                aria-label="&laquo; Previous">
                                                <span class="page-link" aria-hidden="true">&lsaquo;</span>
                                            </li>

                                            <li class="page-item active" aria-current="page"><span
                                                    class="page-link">1</span></li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=2">2</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=3">3</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=4">4</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=5">5</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=6">6</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=7">7</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=8">8</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=9">9</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=10">10</a>
                                            </li>

                                            <li class="page-item disabled" aria-disabled="true"><span
                                                    class="page-link">...</span></li>


                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=56">56</a>
                                            </li>
                                            <li class="page-item"><a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=57">57</a>
                                            </li>


                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="https://seda.codeella.com/admin/users/drivers?page=2"
                                                    rel="next" aria-label="Next &raquo;">&rsaquo;</a>
                                            </li>
                                        </ul>
                                    </nav>

                                </div>

                            </div>
                        </div>
                        <!--/ Basic Bootstrap Table -->

                    </div>


                    <!-- / Content -->



                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>

</body>

</html>
