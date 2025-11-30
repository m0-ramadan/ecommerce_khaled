   <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
       <div class="app-brand demo">
           <a href="{{ route('admin.index') }}" class="app-brand-link">
               <span style="width: 75px !important ; height:75px !important" class="app-brand-logo demo"></span>
               <span class="app-brand-text demo menu-text fw-bold"style="font-size: 0.90rem">{{ env('APP_NAME') }}</span>
           </a>

           <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
               <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
               <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
           </a>
       </div>

       <div class="menu-inner-shadow"></div>

       <ul class="menu-inner py-1">

           <li class="menu-item ">
               <a href="https://seda.codeella.com/home" class="menu-link">
                   <i class="menu-icon tf-icons ti ti-home"></i>
                   <div>الرئيسية</div>
               </a>
           </li>

           <li class="menu-item ">
               <a href="javascript:void(0);" class="menu-link menu-toggle">
                   <i class="menu-icon tf-icons ti ti-world"></i>
                   <div>المناطق</div>

               </a>
               <ul class="menu-sub">

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/zones/countries" class="menu-link">
                           <div>الدول</div>
                       </a>
                   </li>

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/zones/governorates" class="menu-link">
                           <div>المحافظات</div>
                       </a>
                   </li>

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/zones" class="menu-link">
                           <div>المناطق</div>
                       </a>
                   </li>

               </ul>
           </li>
           <li class="menu-item ">
               <a href="javascript:void(0);" class="menu-link menu-toggle">
                   <i class="menu-icon tf-icons ti ti-user-bolt"></i>
                   <div>المستخدمين</div>

               </a>
               <ul class="menu-sub">

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/admins" class="menu-link">
                           <div>مديرين الموقع</div>
                       </a>
                   </li>

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/users/clients" class="menu-link">
                           <div>العملاء</div>
                       </a>
                   </li>
               </ul>
           </li>


           <li class="menu-item ">
               <a href="https://seda.codeella.com/admin/payment-methods" class="menu-link">
                   <i class="menua-icon ti ti-credit-card"></i>
                   <div>طرق الدفع</div>
               </a>
           </li>


           <li class="menu-item ">
               <a href="javascript:void(0);" class="menu-link menu-toggle">
                   <i class="menu-icon ti ti-package"></i>

                   <div>الطلبات</div>
                   <div class="badge bg-primary rounded-pill ms-auto">5</div>
               </a>

               <ul class="menu-sub">

                   <li class="menu-item ">
                       <a href="#" class="menu-link">
                           <i class="menu-icon ti ti-shopping-cart"></i>
                           <div>الجميع</div>
                       </a>
                   </li>


                   <li class="menu-item ">
                       <a href="#" class="menu-link">
                           <i class="menu-icon ti ti-shopping-cart"></i>
                           <div>الملغية</div>
                       </a>
                   </li>
               </ul>
           </li>



           <li class="menu-item ">
               <a href="https://seda.codeella.com/admin/problems" class="menu-link">
                   <i class="menu-icon ti ti-category-2"></i>
                   <div>المشكلات</div>
               </a>
           </li>

           <li class="menu-item ">
               <a href="https://seda.codeella.com/admin/coupons" class="menu-link">
                   <i class="menu-icon ti ti-discount"></i>
                   <div>الكوبونات</div>
               </a>
           </li>


           <li class="menu-item ">
               <a href="https://seda.codeella.com/admin/contact-us" class="menu-link">
                   <i class="menu-icon ti ti-messages"></i>
                   <div>تواصل معنا</div>
               </a>
           </li>

           <li class="menu-item ">
               <a href="https://seda.codeella.com/admin/order-reports" class="menu-link">
                   <i class="menu-icon ti ti-message-report"></i>
                   <div>الشكاوي</div>
               </a>
           </li>


           <li class="menu-item ">
               <a href="javascript:void(0);" class="menu-link menu-toggle">
                   <i class="menu-icon tf-icons ti ti-info-circle"></i>
                   <div>عنا</div>

               </a>
               <ul class="menu-sub">

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/about/policy-customer" class="menu-link">
                           <i class="menu-icon ti ti-file-text"></i>
                           <div> سياسة الخصوصية للعميل
                           </div>
                       </a>
                   </li>

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/about/terms-customer" class="menu-link">
                           <i class="menu-icon ti ti-info-circle"></i>
                           <div> من نحن للعميل
                           </div>
                       </a>
                   </li>

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/about/aboutus-customer" class="menu-link">
                           <i class="menu-icon ti ti-file-description"></i>
                           <div> الشروط والأحكام للعميل
                           </div>
                       </a>
                   </li>
               </ul>
           </li>

           <li class="menu-item ">
               <a href="javascript:void(0);" class="menu-link menu-toggle">
                   <i class="menu-icon tf-icons ti ti-file-pencil "></i>
                   <div>السجلات</div>

               </a>
               <ul class="menu-sub">

                   <li class="menu-item ">
                       <a href="https://seda.codeella.com/admin/logs/errors" class="menu-link">
                           <div>الاخطاء البرمجية</div>
                       </a>
                   </li>

               </ul>
           </li>


           <li class="menu-item">
               <a href="javascript:void(0);" class="menu-link menu-toggle">
                   <i class="menu-icon tf-icons ti ti-settings"></i>
                   <div>الإعدادات</div>
               </a>

               <ul class="menu-sub">
                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/languages" class="menu-link">
                           <div>اللغات</div>
                       </a>
                   </li>

                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/api" class="menu-link">
                           <div>واجهة الـ API</div>
                       </a>
                   </li>

                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/pages/Orders" class="menu-link">
                           <div>الطلبات</div>
                       </a>
                   </li>

                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/pages/SMTP" class="menu-link">
                           <div>إعدادات البريد (SMTP)</div>
                       </a>
                   </li>

                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/pages/General" class="menu-link">
                           <div>الإعدادات العامة</div>
                       </a>
                   </li>

                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/pages/Payment" class="menu-link">
                           <div>وسائل الدفع</div>
                       </a>
                   </li>

                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/pages/Communication" class="menu-link">
                           <div>إعدادات التواصل</div>
                       </a>
                   </li>

                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/pages/Realtime" class="menu-link">
                           <div>التحديث اللحظي (Realtime)</div>
                       </a>
                   </li>

                   <li class="menu-item">
                       <a href="https://seda.codeella.com/admin/settings/pages/Files" class="menu-link">
                           <div>إدارة الملفات</div>
                       </a>
                   </li>
               </ul>
           </li>


       </ul>
   </aside>
