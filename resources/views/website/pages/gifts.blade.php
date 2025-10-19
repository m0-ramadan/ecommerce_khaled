@extends('website.layouts.master')
@section('css')
@endsection

@section('content')
    <div class="h-56 -mb-52 w-full bg-primary start-0 relative -z-1 bg-cover bg-no-repeat bg-center has-overlay--before"
        style="background-image:url(https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fnljtgp.png)">
    </div>
    <div class="container page-container space-y-8 md:space-y-16">

        <nav class="breadcrumbs w-full py-5">
            <ol class="flex items-center flex-wrap text-sm">
                <li class="whitespace-nowrap"><a href="https://printnes.co/" class="fix-align text-primary">الرئيسية</a></li>
                <li><i class="sicon-keyboard_arrow_left ltr:-scale-x-100 inline-block arrow mx-2"></i></li>
                <li><span class="fix-align opacity-70">نظام المكافآت</span></li>
            </ol>
        </nav>


        <div class="loyalty__banner">
            <div class="loyalty__banner-inner">
                <i class="sicon-star2 star-anime text-amber-400 text-7xl"></i>
                <div class="loyalty__banner-content">
                    <div class="info">
                        <h1>نظام المكافآت</h1>
                        <p>تعرف على طرق تجمع فيها اكبر قدر ممكن من النقاط واستبدلها بمنتجات من متجرنا</p>
                    </div>

                    <salla-loyalty customer-points="0">
                        <salla-button onclick="salla.event.dispatch('loyalty::open')" slot="widget">
                            إستبدال النقاط
                        </salla-button>
                    </salla-loyalty>
                </div>
            </div>

            <!-- background big stars -->
            <div class="loyalty-star loyalty-star--first">
                <i class="sicon-star2"></i>
            </div>

            <div class="loyalty-star loyalty-star--second">
                <i class="sicon-star2"></i>
            </div>
        </div>

        <section class="points-ways py-6">
            <div class="s-block__title">
                <h2>طرق الحصول على النقاط</h2>
            </div>
            <div class="points-ways__list">
                <div class="way-item">
                    <div class="flex rtl:space-x-reverse space-x-4">
                        <span class="way-item__icon sicon-store" style="color: #000000">
                            <span style="background-color: #000000"></span>
                        </span>
                        <div class="way-item__content">
                            <h4 style="color: #000000">
                                10
                                نقطة
                            </h4>
                            <p>شارك أصدقائك</p>
                        </div>
                    </div>

                </div>
                <div class="way-item">
                    <div class="flex rtl:space-x-reverse space-x-4">
                        <span class="way-item__icon sicon-store" style="color: #000000">
                            <span style="background-color: #000000"></span>
                        </span>
                        <div class="way-item__content">
                            <h4 style="color: #000000">
                                175
                                نقطة
                            </h4>
                            <p>أضف بياناتك</p>
                        </div>
                    </div>

                </div>
                <div class="way-item">
                    <div class="flex rtl:space-x-reverse space-x-4">
                        <span class="way-item__icon sicon-store" style="color: #000000">
                            <span style="background-color: #000000"></span>
                        </span>
                        <div class="way-item__content">
                            <h4 style="color: #000000">
                                2
                                نقطة
                            </h4>
                            <p>تسوق الآن</p>
                        </div>
                    </div>

                </div>
                <div class="way-item">
                    <div class="flex rtl:space-x-reverse space-x-4">
                        <span class="way-item__icon sicon-store" style="color: #000000">
                            <span style="background-color: #000000"></span>
                        </span>
                        <div class="way-item__content">
                            <h4 style="color: #000000">
                                50
                                نقطة
                            </h4>
                            <p>قيّم طلباتك</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <div>
            <salla-slider type="carousel" block-title="الشحن المجاني" id="loyalty-slider-free_shipping">
                <div slot="items">

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://cdn.salla.sa/ydZbx/OUM3eThdVHIIA3yAdPhNWuqNOOq1IdG63fxaZUWA.png"
                                alt="شحن مجاني" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    شحن مجاني
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس بشحن مجاني</p>
                            </div>
                        </div>
                    </div>
                </div>
            </salla-slider>
        </div>
        <div>
            <salla-slider type="carousel" block-title="الخصومات" id="loyalty-slider-coupon_discount">
                <div slot="items">

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fvldhhnf.png"
                                alt="كوبون خصم 30 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 30 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 30
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fwdlb.png"
                                alt="كوبون خصم 60 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 60 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 60
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fjoahx.png"
                                alt="كوبون خصم 120 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 120 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 120
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fdhdjejcu.png"
                                alt="كوبون خصم 180 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 180 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 180
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fgid.png"
                                alt="كوبون خصم 240 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 240 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 240
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fsjjk.png"
                                alt="كوبون خصم 300 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 300 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 300
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fbdkzzv.png"
                                alt="كوبون خصم 360 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 360 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 360
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fgvxzarm.png"
                                alt="كوبون خصم 420 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 420 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 420
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fdqkqplfh.png"
                                alt="كوبون خصم 480 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 480 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 480
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fwnozdsa.png"
                                alt="كوبون خصم 540 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 540 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 540
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fhnpbwtdf.png"
                                alt="كوبون خصم 600 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 600 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 600
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fgfudbl.png"
                                alt="كوبون خصم 900 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 900 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة 900
                                    ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fhab.png"
                                alt="كوبون خصم 1200 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 1200 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة
                                    1200 ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fpwgs.png"
                                alt="كوبون خصم 3000 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 3000 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة
                                    3000 ريال</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                data-src="https://s3.eu-central-1.amazonaws.com/cdn.salla.sa/tmp%2Fimages%2Fckslpsr.png"
                                alt="كوبون خصم 6000 <i class=sicon-sar></i>" />

                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    كوبون خصم 6000 <i class=sicon-sar></i>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاط مكافآت برنتنس الخاصة بك الآن بكوبون خصم بقيمة
                                    6000 ريال</p>
                            </div>
                        </div>
                    </div>
                </div>
            </salla-slider>
        </div>
        <div>
            <salla-slider type="carousel" block-title="المنتجات المجانية" id="loyalty-slider-free_product">
                <div slot="items">

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <a href="https://printnes.co/كروت-شخصية-بزنس-كارد-تصميم/p1003268229"
                                class='product-entry__image h-40'>
                                <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                    src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                    data-src="https://cdn.salla.sa/rDvVQ/z1njcUnSQz0HJtUYjVKcTXbEAHAasD5rp8rkAPCx.jpg"
                                    alt="منتج مجاني: كروت شخصية ورق مقوي 300 جرام" />

                            </a>
                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    <a href="https://printnes.co/كروت-شخصية-بزنس-كارد-تصميم/p1003268229">منتج مجاني: كروت
                                        شخصية ورق مقوي 300 جرام</a>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاطك بكروت شخصية مجانا</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <a href="https://printnes.co/كروت-شخصية-بزنس-كارد-تصميم/p2117796307"
                                class='product-entry__image h-40'>
                                <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                    src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                    data-src="https://cdn.salla.sa/rDvVQ/yCcPns2mLQKBr3JVy7Bn837MepoFaMVc0DYXonPd.jpg"
                                    alt="منتج مجاني: كروت شخصية ورق مقمش" />

                            </a>
                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    <a href="https://printnes.co/كروت-شخصية-بزنس-كارد-تصميم/p2117796307">منتج مجاني: كروت
                                        شخصية ورق مقمش</a>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاطك بكروت شخصية مجانا</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <a href="https://printnes.co/شرائط-ورقية-لاختبار-العطور-85x3-سم-بجودة-عالية-برنتنس-للطباعه/p730183340"
                                class='product-entry__image h-40'>
                                <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                    src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                    data-src="https://cdn.salla.sa/rDvVQ/tnP1yiwTUYvuAMkBz1BuLiHlA1sSlBHgSQbbQ5HU.jpg"
                                    alt="منتج مجاني: شرائط ورقية لاختبار العطور 8.5 في 3 سم" />

                            </a>
                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    <a
                                        href="https://printnes.co/شرائط-ورقية-لاختبار-العطور-85x3-سم-بجودة-عالية-برنتنس-للطباعه/p730183340">منتج
                                        مجاني: شرائط ورقية لاختبار العطور 8.5 في 3 سم</a>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاطك بشرائط ورقية لاختبار العطور 8.5 في 3 سم</p>
                            </div>
                        </div>
                    </div>

                    <div class="slide--one-fourth">
                        <div class='product-entry flex-col'>
                            <a href="https://printnes.co/استيكر-باقات-ورد-6x4-cm/p302223700"
                                class='product-entry__image h-40'>
                                <img class="lazy sm:h-full w-full object-cover rounded-t-md"
                                    src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                                    data-src="https://cdn.salla.sa/rDvVQ/Z69XkW0ExkImeowODqs557ZyfN52jG7PdWkLB2jw.jpg"
                                    alt="منتج مجاني: استيكر باقات ورد 6x4 cm" />

                            </a>
                            <div class="flex-1 p-5">
                                <h3 class="product-entry__title leading-6 mb-2.5">
                                    <a href="https://printnes.co/استيكر-باقات-ورد-6x4-cm/p302223700">منتج مجاني: استيكر
                                        باقات ورد 6x4 cm</a>
                                </h3>
                                <p class="text-sm leading-6 ">استبدل نقاطك بستيكر ورد مجانا</p>
                            </div>
                        </div>
                    </div>
                </div>
            </salla-slider>
        </div>
    </div>
@endsection

@section('js')
@endsection
