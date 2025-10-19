@extends('website.layouts.master')
@section('css')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@endsection

@section('content')
    <section class="s-block s-block--photos-slider ">
        <salla-slider type="carousel" class="photos-slider" centered auto-play loop pagination id="photos-0-slider">
            <div slot="items">
                <div class="swiper-slide">
                    <a href="">
                        <img loading="eager" src="{{ asset('website/images/1.jpeg') }}"
                            class="w-full object-contain rounded-md"></img>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="">
                        <img loading="eager" src="{{ asset('website/images/2.jpeg') }}"
                            class="w-full object-contain rounded-md"></img>
                    </a>
                </div>
                <div class="swiper-slide">
                    <a href="">
                        <img loading="eager" src="{{ asset('website/images/3.jpeg') }}"
                            class="w-full object-contain rounded-md"></img>
                    </a>
                </div>
            </div>
        </salla-slider>
    </section>
    <section id="main-links-1" class="s-block s-block--categories  ">
        <div class="container">
            <salla-slider type="carousel" block-title="الأقسام الرئيسية"
                block-subtitle="حرك القائمة لليسار لعرض جميع الاقسام" show-controls="true" id="main-links-1">
                <div slot="items">
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/PD53rr1LUNb19Hj3je9ULKSfhYUGg0o3TOBdz1T7.png)"></i>
                            <h4>العروض و البكجات</h4>
                            <span></span>
                        </a>
                    </div>
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/lnB9NCS8slVsu7xfA6j6Ka37ZIEGCnEu9de9JiR3.png)"></i>
                            <h4>طباعة بوكسات</h4>
                            <span></span>
                        </a>
                    </div>
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/iVj6NojrRm6YOmBH6CTKeHbUWUfrpoYsK3D8hqB9.png)"></i>
                            <h4>طباعة الأكياس</h4>
                            <span></span>
                        </a>
                    </div>
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/pIbiSL3zp7u4cSqnsrhMcq0D0fQPT2pVkcK72f6g.png)"></i>
                            <h4>طباعة الملصقات</h4>
                            <span></span>
                        </a>
                    </div>
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/KDhBRdujxvsDIH6szhkOTvZAh8FjHjp92CXZTG9H.png)"></i>
                            <h4>مطبوعات ورقية</h4>
                            <span></span>
                        </a>
                    </div>
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/JIKjiR5SpebGdpgTMZlrNw45BhbWSI84nz4j8vAR.png)"></i>
                            <h4>التغليف والتعبئة</h4>
                            <span></span>
                        </a>
                    </div>
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/Ig09NZmIFTJOmq1idDnGS8VGPpfu6xoIdzvblMzm.png)"></i>
                            <h4>المطاعم والمقاهي</h4>
                            <span></span>
                        </a>
                    </div>
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/eBFLEKHgLMVfUDBGEyZjqvOahjEB0r3nkiNsEpd6.jpg)"></i>
                            <h4>طباعة كراتين الشحن</h4>
                            <span></span>
                        </a>
                    </div>
                    <div class="swiper-slide slide--one-sixth">
                        <a href="{{ route('website.product', 4) }}" class="slide--cat-entry" data-emergence="hidden">
                            <i class="bg-cover flex-center "
                                style="background-image: url(https://cdn.salla.sa/form-builder/BLnLOMwEaF2kJLLWLhx24QNjwqfaBgzQM9jRmQgI.jpg)"></i>
                            <h4>طباعة انواع اكياس اخرى</h4>
                            <span></span>
                        </a>
                    </div>
                </div>
            </salla-slider>
        </div>
    </section>

    <section id="best-offers-4-slider" class="s-block s-block--best-offers container overflow-hidden">
        <div class="s-products-slider-wrapper">
            <div class="s-slider-wrapper carousel-slider s-slider-horizontal" id="slider-with-bg-4" dir="rtl">
                <div class="s-slider-block__title">
                    <div class="s-slider-block__title-right">
                        <h2>طباعة كراتين اشحن</h2>
                    </div>
                    <div class="s-slider-block__title-left">
                        <a href="{{ route('website.product', 4) }}" class="s-slider-block__display-all">عرض الكل</a>
                        <div class="s-slider-block__title-nav" dir="rtl">
                            <button aria-label="Previous slide"
                                class="s-slider-prev s-slider-nav-arrow swiper-button-disabled swiper-button-lock" disabled
                                tabindex="-1" aria-controls="swiper-wrapper-3dda6d06b396c2a3" aria-disabled="true">
                                <span class="s-slider-button-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <title>keyboard_arrow_right</title>
                                        <path d="M11.438 22.479l6.125-6.125-6.125-6.125 1.875-1.875 8 8-8 8z"></path>
                                    </svg>
                                </span>
                            </button>
                            <button aria-label="Next slide"
                                class="s-slider-next s-slider-nav-arrow swiper-button-disabled swiper-button-lock" disabled
                                tabindex="-1" aria-controls="swiper-wrapper-3dda6d06b396c2a3" aria-disabled="true">
                                <span class="s-slider-button-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <title>keyboard_arrow_left</title>
                                        <path d="M20.563 22.104l-1.875 1.875-8-8 8-8 1.875 1.875-6.125 6.125z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="swiper s-slider-container swiper-initialized swiper-horizontal swiper-pointer-events swiper-rtl swiper-backface-hidden"
                    dir="rtl">
                    <div class="swiper-wrapper s-slider-swiper-wrapper" id="swiper-wrapper-3dda6d06b396c2a3"
                        aria-live="off">
                        <!-- Product 1 -->
                        <div class="s-products-slider-card swiper-slide swiper-slide-active" role="group"
                            aria-label="1 / 4">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="1991041004">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكر مستطيل 2.png') }}"
                                            alt="بوكسات بيتزا - مقاسات متعددة"
                                            data-src="{{ asset('website/images/استيكر مستطيل 2.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 1.62 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1991041004)"
                                        data-id="1991041004" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">بوكسات
                                                بيتزا - مقاسات متعددة</a>
                                        </h3>
                                        <p class="s-product-card-content-subtitle">بوكس ورق كرتون مع طباعة بجوده عاليه</p>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(1991041004)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product 2 -->
                        <div class="s-products-slider-card swiper-slide swiper-slide-next" role="group"
                            aria-label="2 / 4">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="550221170">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكر مستطيل1.png') }}"
                                            alt="كرتون شحن 33x19x12"
                                            data-src="https://cdn.salla.sa/rDvVQ/1b2a8d2c-66d0-4946-b569-59f4938878f1-500x342.06008583691-vTouQoAXpAoAeSVP31vzlkiYzg6Mv03NnLStGnnf.png"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 3.2 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(550221170)"
                                        data-id="550221170" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">كرتون شحن
                                                33x19x12</a>
                                        </h3>
                                        <p class="s-product-card-content-subtitle">بوكس ورق مقوى مع طباعة عالية الجودة</p>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(550221170)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product 3 -->
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="3 / 4">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="786214664">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكر مستطيل3.png') }}"
                                            alt="كرتون شحن 29x20x14"
                                            data-src="{{ asset('website/images/استيكر مستطيل3.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 3.2 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(786214664)"
                                        data-id="786214664" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">كرتون شحن
                                                29x20x14</a>
                                        </h3>
                                        <p class="s-product-card-content-subtitle">بوكس ورق مقوى مع طباعة عالية الجودة</p>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(786214664)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product 4 -->
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="4 / 4">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="2085684514">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكرات بشكل محدد.png') }}"
                                            alt="كرتون شحن 29x22x10"
                                            data-src="{{ asset('website/images/استيكرات بشكل محدد.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 3.2 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(2085684514)"
                                        data-id="2085684514" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">كرتون شحن
                                                29x22x10</a>
                                        </h3>
                                        <p class="s-product-card-content-subtitle">بوكس ورق مقوى مع طباعة عالية الجودة</p>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(2085684514)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>
        </div>
    </section>

    <section id="best-offers-5-slider" class="s-block s-block--best-offers container overflow-hidden">
        <div class="s-products-slider-wrapper">
            <div class="s-slider-wrapper carousel-slider s-slider-horizontal" id="slider-with-bg-5" dir="rtl">
                <div class="s-slider-block__title">
                    <div class="s-slider-block__title-right">
                        <h2>طباعة على الأكياس</h2>
                    </div>
                    <div class="s-slider-block__title-left">
                        <a href="{{ route('website.product', 4) }}" class="s-slider-block__display-all">عرض الكل</a>
                        <div class="s-slider-block__title-nav" dir="rtl">
                            <button aria-label="Previous slide" class="s-slider-prev s-slider-nav-arrow" tabindex="0"
                                aria-controls="swiper-wrapper-1689cd3409991f8e" aria-disabled="false">
                                <span class="s-slider-button-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <title>keyboard_arrow_right</title>
                                        <path d="M11.438 22.479l6.125-6.125-6.125-6.125 1.875-1.875 8 8-8 8z"></path>
                                    </svg>
                                </span>
                            </button>
                            <button aria-label="Next slide" class="s-slider-next s-slider-nav-arrow" tabindex="0"
                                aria-controls="swiper-wrapper-1689cd3409991f8e" aria-disabled="false">
                                <span class="s-slider-button-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <title>keyboard_arrow_left</title>
                                        <path d="M20.563 22.104l-1.875 1.875-8-8 8-8 1.875 1.875-6.125 6.125z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="swiper s-slider-container swiper-initialized swiper-horizontal swiper-pointer-events swiper-rtl swiper-backface-hidden"
                    dir="rtl">
                    <div class="swiper-wrapper s-slider-swiper-wrapper" id="swiper-wrapper-1689cd3409991f8e"
                        aria-live="off" style="transition-duration: 0ms; transform: translate3d(303.5px, 0px, 0px);">
                        <!-- Product 1 -->
                        <div class="s-products-slider-card swiper-slide swiper-slide-prev" role="group"
                            aria-label="1 / 8">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="690395453">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكرات بشكل محدد.png') }}"
                                            alt="30x11x15 كيس ورد طولي"
                                            data-src="{{ asset('website/images/استيكرات بشكل محدد.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 7.5 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(690395453)"
                                        data-id="690395453" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">30x11x15 كيس ورد
                                                طولي</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(690395453)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product 2 -->
                        <div class="s-products-slider-card swiper-slide swiper-slide-active" role="group"
                            aria-label="2 / 8">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="961636360">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكرات مربعة 1.png') }}"
                                            alt="كيس كرتوني فاخر مقاس 35x19x8 (حامل طويل)"
                                            data-src="{{ asset('website/images/استيكرات مربعة 1.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 9 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(961636360)"
                                        data-id="961636360" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">كيس
                                                كرتوني فاخر مقاس 35x19x8 (حامل طويل)</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(961636360)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product 3 -->
                        <div class="s-products-slider-card swiper-slide swiper-slide-next" role="group"
                            aria-label="3 / 8">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="706543849">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكرات مربعة2.png') }}"
                                            alt="كيس كرتوني فاخر مقاس 30x20x8"
                                            data-src="{{ asset('website/images/استيكرات مربعة2.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 7.5 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(706543849)"
                                        data-id="706543849" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">كيس
                                                كرتوني فاخر مقاس 30x20x8</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(706543849)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product 4 -->
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="4 / 8">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="1564264544">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكرات مربعة3.png') }}"
                                            alt="كيس ورد بشكل قمع 39x30"
                                            data-src="{{ asset('website/images/استيكرات مربعة3.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 7.5 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1564264544)"
                                        data-id="1564264544" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">كيس ورد بشكل
                                                قمع 39x30</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(1564264544)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product 5 -->
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="5 / 8">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="1744837838">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/استيكرات مربعة4.png') }}"
                                            alt="كيس كرتوني فاخر مقاس 28x28x15"
                                            data-src="{{ asset('website/images/استيكرات مربعة4.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 9 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1744837838)"
                                        data-id="1744837838" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                        <div class="s-product-card-rating">
                                            <i class="sicon-star2 before:text-orange-300"></i>
                                            <span>5</span>
                                        </div>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">كيس
                                                كرتوني فاخر مقاس 28x28x15</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(1744837838)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Product 6 -->
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="6 / 8">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="1685083272">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/بكج التغليف المثالي.png') }}"
                                            alt="كيس كرتوني فاخر مقاس 40x20x20"
                                            data-src="{{ asset('website/images/بكج التغليف المثالي.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 9 ريال</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1685083272)"
                                        data-id="1685083272" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">كيس
                                                كرتوني فاخر مقاس 40x20x20</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(1685083272)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>
        </div>
    </section>

    <section class="s-block s-block--fixed-banner wide-placeholder">
        <div class="container">
            <a href="{{ route('website.product', 4) }}" class="banner banner--fixed" aria-label="Banner ">
                <img class="lazy-load w-full object-cover" src="{{ route('website.product', 4) }}"
                    data-src="{{ asset('website/images/1.png') }}" alt="" />
            </a>
        </div>
    </section>


    <section id="best-offers-17-slider" class="s-block s-block--best-offers container overflow-hidden">
        <div class="s-products-slider-wrapper">
            <div class="s-slider-wrapper carousel-slider s-slider-horizontal" id="slider-with-bg-17" dir="rtl">
                <div class="s-slider-block__title">
                    <div class="s-slider-block__title-right">
                        <h2>مطبوعات موسمية</h2>
                    </div>
                    <div class="s-slider-block__title-left">
                        <a href="{{ route('website.product', 4) }}" class="s-slider-block__display-all">عرض الكل</a>
                        <div class="s-slider-block__title-nav" dir="rtl">
                            <button aria-label="Previous slide" class="s-slider-prev s-slider-nav-arrow" tabindex="0"
                                aria-controls="swiper-wrapper-f17f4c44e22d049b" aria-disabled="false">
                                <span class="s-slider-button-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <title>keyboard_arrow_right</title>
                                        <path d="M11.438 22.479l6.125-6.125-6.125-6.125 1.875-1.875 8 8-8 8z"></path>
                                    </svg>
                                </span>
                            </button>
                            <button aria-label="Next slide" class="s-slider-next s-slider-nav-arrow" tabindex="0"
                                aria-controls="swiper-wrapper-f17f4c44e22d049b" aria-disabled="false">
                                <span class="s-slider-button-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <title>keyboard_arrow_left</title>
                                        <path d="M20.563 22.104l-1.875 1.875-8-8 8-8 1.875 1.875-6.125 6.125z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="swiper s-slider-container swiper-initialized swiper-horizontal swiper-rtl swiper-backface-hidden"
                    dir="rtl">
                    <div class="swiper-wrapper s-slider-swiper-wrapper" id="swiper-wrapper-f17f4c44e22d049b"
                        aria-live="off">
                        <div class="s-products-slider-card swiper-slide swiper-slide-active" role="group"
                            aria-label="1 / 4">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="316874631">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/box2.png') }}"
                                            alt="بوكس توزيعات مربع 10x10x10 (اليوم الوطني 95) - نموذج 3"
                                            data-src="{{ asset('website/images/box2.png') }}" data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 0.73 ريال للحبة</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(316874631)"
                                        data-id="316874631" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">بوكس
                                                توزيعات مربع 10x10x10 (اليوم الوطني 95) - نموذج 3</a>
                                        </h3>
                                        <p class="s-product-card-content-subtitle">بوكس ورق مقوى مع طباعة عالية الجودة</p>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(316874631)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="s-products-slider-card swiper-slide swiper-slide-next" role="group"
                            aria-label="2 / 4">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="34760105">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/box1.png') }}"
                                            alt="بوكس مربع 4x4x4 (اليوم الوطني 95) - نموذج 3"
                                            data-src="{{ asset('website/images/بكج مشاريع العبايات.png') }}"
                                            data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 0.27 ريال للحبة</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(34760105)"
                                        data-id="34760105" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">بوكس
                                                مربع 4x4x4 (اليوم الوطني 95) - نموذج 3</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(34760105)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="3 / 4">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="481050998">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/box4.png') }}"
                                            alt="بوكس مربع 4x4x4 (اليوم الوطني 95) - نموذج 2"
                                            data-src="{{ asset('website/images/box4.png') }}" data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 0.27 ريال للحبة</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(481050998)"
                                        data-id="481050998" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">بوكس
                                                مربع 4x4x4 (اليوم الوطني 95) - نموذج 2</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(481050998)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="4 / 4">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="339016803">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/box3.png') }}"
                                            alt="بوكس توزيعات مربع 10x10x10 (اليوم الوطني 95) - نموذج 2"
                                            data-src="{{ asset('website/images/box3.png') }}" data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">وصول إلى 0.73 ريال للحبة</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(339016803)"
                                        data-id="339016803" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">بوكس
                                                توزيعات مربع 10x10x10 (اليوم الوطني 95) - نموذج 2</a>
                                        </h3>
                                        <p class="s-product-card-content-subtitle">بوكس ورق مقوى مع طباعة عالية الجودة</p>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(339016803)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>
        </div>
    </section>

    <section id="best-offers-18-slider" class="s-block s-block--best-offers container overflow-hidden">
        <div class="s-products-slider-wrapper">
            <div class="s-slider-wrapper carousel-slider s-slider-horizontal" id="slider-with-bg-18" dir="rtl">
                <div class="s-slider-block__title">
                    <div class="s-slider-block__title-right">
                        <h2>خدمات التصميم</h2>
                    </div>
                    <div class="s-slider-block__title-left">
                        <a href="https://printnes.co/category/vAxKbz" class="s-slider-block__display-all">عرض الكل</a>
                        <div class="s-slider-block__title-nav" dir="rtl">
                            <button aria-label="Previous slide" class="s-slider-prev s-slider-nav-arrow" tabindex="0"
                                aria-controls="swiper-wrapper-c6733bbe174fcbe7" aria-disabled="false">
                                <span class="s-slider-button-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <title>keyboard_arrow_right</title>
                                        <path d="M11.438 22.479l6.125-6.125-6.125-6.125 1.875-1.875 8 8-8 8z"></path>
                                    </svg>
                                </span>
                            </button>
                            <button aria-label="Next slide" class="s-slider-next s-slider-nav-arrow" tabindex="0"
                                aria-controls="swiper-wrapper-c6733bbe174fcbe7" aria-disabled="false">
                                <span class="s-slider-button-icon">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        viewBox="0 0 32 32">
                                        <title>keyboard_arrow_left</title>
                                        <path d="M20.563 22.104l-1.875 1.875-8-8 8-8 1.875 1.875-6.125 6.125z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="swiper s-slider-container swiper-initialized swiper-horizontal swiper-rtl swiper-backface-hidden"
                    dir="rtl">
                    <div class="swiper-wrapper s-slider-swiper-wrapper" id="swiper-wrapper-c6733bbe174fcbe7"
                        aria-live="off">
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="1 / 5">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="1896683158">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/box3.png') }}" alt="undefined"
                                            data-src="{{ asset('website/images/box3.png') }}" data-ll-status="loaded">
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1896683158)"
                                        data-id="1896683158" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">٣٥٠ <i class="sicon-sar"></i></h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">خدمة تصميم - دفتر</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(1896683158)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="2 / 5">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="363956245">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/5.png') }}" alt="undefined"
                                            data-src="{{ asset('website/images/5.png') }}" data-ll-status="loaded">
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(363956245)"
                                        data-id="363956245" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">٣٥٠ <i class="sicon-sar"></i></h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">خدمة تصميم - كرت
                                                اعمال</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(363956245)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="3 / 5">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="507083012">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/6.png') }}" alt="undefined"
                                            data-src="{{ asset('website/images/6.png') }}" data-ll-status="loaded">
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(507083012)"
                                        data-id="507083012" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">٢٥٠٠٫١ <i class="sicon-sar"></i></h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">خدمة تصميم - شعار</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(507083012)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="4 / 5">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="585592751">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/7.png') }}" alt="undefined"
                                            data-src="{{ asset('website/images/7.png') }}" data-ll-status="loaded">
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(585592751)"
                                        data-id="585592751" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">٣٥٠ <i class="sicon-sar"></i></h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">خدمة تصميم - ورق
                                                رسمي</a>
                                        </h3>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(585592751)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="s-products-slider-card swiper-slide" role="group" aria-label="5 / 5">
                            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height"
                                id="1280979836">
                                <div class="relative s-product-card-image">
                                    <a href="{{ route('website.product', 4) }}">
                                        <img class="s-product-card-image-contain lazy loaded"
                                            src="{{ asset('website/images/8.png') }}" alt="undefined"
                                            data-src="{{ asset('website/images/8.png') }}" data-ll-status="loaded">
                                        <div class="s-product-card-promotion-title">صمم هويتك البصرية</div>
                                    </a>
                                    <button
                                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1280979836)"
                                        data-id="1280979836" type="button">
                                        <span class="s-button-text">
                                            <i class="sicon-heart"></i>
                                        </span>
                                    </button>
                                    <div class="triangle-overlay"></div>
                                </div>
                                <div class="s-product-card-content">
                                    <div class="s-product-card-content-sub">
                                        <h4 class="s-product-card-price">-</h4>
                                    </div>
                                    <div class="s-product-card-content-main">
                                        <h3 class="s-product-card-content-title">
                                            <a href="{{ route('website.product', 4) }}">خدمة
                                                تصميم - هوية بصرية متكاملة</a>
                                        </h3>
                                        <p class="s-product-card-content-subtitle">اسلوب فريد وابداعي</p>
                                    </div>
                                    <div class="s-product-card-content-footer gap-2">
                                        <div class="w-full">
                                            <button
                                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                                type="button" onclick="addToCart(1280979836)">
                                                <span class="s-button-text">
                                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                                    <span>إضافة للسلة</span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                </div>
            </div>
        </div>
    </section>

    <section class="s-block s-block--fixed-banner wide-placeholder">
        <div class="container">
            <a href="{{ route('website.product', 4) }}" class="banner banner--fixed" aria-label="Banner ">
                <img class="lazy-load w-full object-cover loaded" src="{{ asset('website/images/2.png') }}"
                    data-src="{{ asset('website/images/2.png') }}" alt="">
            </a>
        </div>
    </section>


    <section class="s-block container fixed-products" dir="rtl">
        <div class="s-block__title">
            <div class="right-side">
                <h2>مطبوعات ورقية</h2>
            </div>
        </div>
        <div class="s-products-list-wrapper s-products-list-vertical-cards">
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1087229601">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded" src="{{ asset('website/images/3.jpeg') }}"
                            alt="undefined" data-src="{{ asset('website/images/3.jpeg') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">ابتداء من 5.5 ريال</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1087229601)" data-id="1087229601"
                        type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">استاند طاولة منيو
                                مقاس
                                18X12 CM</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">منيو استاند طاولة</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1087229601)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1307351044">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded" src="{{ asset('website/images/5.png') }}"
                            alt="undefined" data-src="{{ asset('website/images/5.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">تاق مربع</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1307351044)" data-id="1307351044"
                        type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">تاق مربع مقاس 5.5X5.5 CM</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">ابتدا من 0.75 ريال</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1307351044)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="111868769">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/4.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/4.png') }}" loading="lazy" data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">ابتداء من 2 ريال</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(111868769)" data-id="111868769"
                        type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">استاند طاولة ورقي
                                مقاس
                                10X10 CM</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">استاند طاولة ورقي</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(111868769)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1829569867">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/6.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/6.png') }}" loading="lazy" data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">ابتدأ من 85 ريال 100 كرت</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1829569867)"
                        data-id="1829569867" type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                        <div class="s-product-card-rating">
                            <i class="sicon-star2 before:text-orange-300"></i>
                            <span>5</span>
                        </div>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">كرت اهداء مطوى عرضي</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">كارت مطوي مع طبقه حماية (سولفان)</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1829569867)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1709487047">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/box4.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/box4.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">ابتدأ من 0.6 ريال للكرت</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1709487047)"
                        data-id="1709487047" type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">كروت دائرية مقاس 10x10</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">كروت دائرية مقاس 10 سم</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1709487047)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1919025862">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/box3.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/box3.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">ابتدا من 0.75 ريال</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1919025862)"
                        data-id="1919025862" type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">تاق دائري مقاس 5 سم</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">تاق دائري 5 سم</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1919025862)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1245654704">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/box1.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/box1.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">تاق مستطيل</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1245654704)"
                        data-id="1245654704" type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                        <div class="s-product-card-rating">
                            <i class="sicon-star2 before:text-orange-300"></i>
                            <span>5</span>
                        </div>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">تاق 9x5 سم</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">وصول إلى 0.15 ريال للحبة</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1245654704)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1405780725">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/box2.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/box2.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">ابتدأ من 60 ريال 25 حبات</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1405780725)"
                        data-id="1405780725" type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">شهادة شكر</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">شهادات شكر وتقدير</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1405780725)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="s-block container fixed-products" dir="rtl">
        <div class="s-block__title">
            <div class="right-side">
                <h2>منتجات دعائية</h2>
            </div>
            <a href="{{ route('website.product', 4) }}" class="s-block__display-all">عرض الكل</a>
        </div>
        <div class="s-products-list-wrapper s-products-list-vertical-cards">
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="566088917">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/استيكرات مربعة2.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/استيكرات مربعة2.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">وصول ل 0.37 ريال للكميات</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(566088917)" data-id="566088917"
                        type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">علب البودينق والاسكريم</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">اكواب القهوة مع الطباعة</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(566088917)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1232616434">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/استيكرات بشكل محدد.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/استيكرات بشكل محدد.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">طباعة UV عالية الجودة</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1232616434)"
                        data-id="1232616434" type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">حافظ مشروب mug
                                بالطباعة
                                (نموذج 4)</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">الطباعة الحرارية على الأكواب</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1232616434)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1669662201">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/استيكر مستطيل4.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/استيكر مستطيل4.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">طباعة UV عالية الجودة</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1669662201)"
                        data-id="1669662201" type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">حافظ مشروب mug
                                بالطباعة
                                (نموذج 3)</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">الطباعة الحرارية على الأكواب</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1669662201)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="s-product-card-entry s-product-card-vertical s-product-card-fit-height" id="1120923799">
                <div class="relative s-product-card-image">
                    <a href="{{ route('website.product', 4) }}">
                        <img class="s-product-card-image-contain lazy loaded"
                            src="{{ asset('website/images/استيكر مستطيل3.png') }}" alt="undefined"
                            data-src="{{ asset('website/images/استيكر مستطيل3.png') }}" loading="lazy"
                            data-ll-status="loaded">
                        <div class="s-product-card-promotion-title">ابتداء من 10 حبات</div>
                    </a>
                    <button
                        class="s-product-card-wishlist-btn animated not-added un-favorited s-button-element s-button-icon s-button-outline s-button-light-outline s-button-loader-center"
                        aria-label="Add or remove to wishlist" onclick="toggleWishlist(1120923799)"
                        data-id="1120923799" type="button">
                        <span class="s-button-text">
                            <i class="sicon-heart"></i>
                        </span>
                    </button>
                    <div class="triangle-overlay"></div>
                </div>
                <div class="s-product-card-content">
                    <div class="s-product-card-content-sub">
                        <h4 class="s-product-card-price">-</h4>
                    </div>
                    <div class="s-product-card-content-main">
                        <h3 class="s-product-card-content-title">
                            <a href="{{ route('website.product', 4) }}">نوت مع مسطرة وقلم</a>
                        </h3>
                        <p class="s-product-card-content-subtitle">نوت مع مسطره وقلم</p>
                    </div>
                    <div class="s-product-card-content-footer gap-2">
                        <div class="w-full">
                            <button
                                class="s-button-element s-button-btn s-button-outline s-button-wide s-button-primary-outline s-button-loader-center"
                                type="button" onclick="addToCart(1120923799)">
                                <span class="s-button-text">
                                    <i class="text-sm rtl:ml-1.5 ltr:mr-1.5 sicon-shopping"></i>
                                    <span>إضافة للسلة</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="s-block s-block--fixed-banner wide-placeholder">
        <div class="container">
            <a href="{{ route('website.product', 4) }}" class="banner banner--fixed" aria-label="Banner ">
                <img class="lazy-load w-full object-cover loaded" src="{{ asset('website/images/2.png') }}"
                    data-src="{{ asset('website/images/1.png') }}" alt="">
            </a>
        </div>
    </section>

    <section class="s-block s-block--statistics overflow-hidden ">
        <div class="container">
            <div class="s-block__title">
                <div class="right-side">
                    <h2>لماذا تالا الجزيرة ؟!</h2>
                </div>
            </div>

            <div class="statistics-list grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="statistics-list__item flex-col flex-center">
                    <span class="sicon-user-circle flex-center text-4xl rounded-full w-20 h-20 text-white mb-4"
                        style="background-color: #1f3a78"></span>
                    <strong class="text-4xl">8000+</strong>
                    <small class="text-xs text-gray-400 mt-1">عميل جرب منتجاتنا</small>
                </div>
                <div class="statistics-list__item flex-col flex-center">
                    <span class="sicon-location flex-center text-4xl rounded-full w-20 h-20 text-white mb-4"
                        style="background-color: #1f3a78"></span>
                    <strong class="text-4xl">500+</strong>
                    <small class="text-xs text-gray-400 mt-1">شحنة دولية خلال العام الماضي</small>
                </div>
                <div class="statistics-list__item flex-col flex-center">
                    <span class="sicon-mail flex-center text-4xl rounded-full w-20 h-20 text-white mb-4"
                        style="background-color: #1f3a78"></span>
                    <strong class="text-4xl">160000+</strong>
                    <small class="text-xs text-gray-400 mt-1">رسالة رددنا عليها خلال العام الماضي</small>
                </div>
                <div class="statistics-list__item flex-col flex-center">
                    <span class="sicon-store flex-center text-4xl rounded-full w-20 h-20 text-white mb-4"
                        style="background-color: #1f3a78"></span>
                    <strong class="text-4xl">700+</strong>
                    <small class="text-xs text-gray-400 mt-1">متجر يستفيدون من خدمات تالا الجزيرة بشكل شهري</small>
                </div>

            </div>

        </div>
    </section>

    @include('website.layouts.faq')

    <section id="block-blog-21" class="s-block s-block--blog overflow-hidden ">
        <div class="container">
            <salla-slider type="carousel" block-title="اعرف اكثر عن الطباعة" block-subTitle="" id="blog-21-slider">
                <div slot="items">
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="space-y-3 p-4 rounded transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 h-full border border-gray-100 ">
                            <div class="h-[120px] rounded bg-gray-50 overflow-hidden">
                                <a href="{{ route('website.product', 4) }}"><img
                                        src="https://cdn.salla.sa/rDvVQ/1pKR8dtK4Hd9UoYFf1hqwqPQJMn5YFEqyOyJngiZ.jpg"
                                        class="object-cover w-full h-full hover:opacity-90 transition-opacity" /></a>
                            </div>
                            <h2><a href="{{ route('website.product', 4) }}"
                                    class="text-sm line-clamp-2 hover:text-primary transition-colors">كيفية
                                    إعداد
                                    ملف للطباعة</a></h2>
                            <p class="text-sm text-gray-500 line-clamp-2">كيف اسوي ملف طباعة صحيح ؟</p>
                            <div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="space-y-3 p-4 rounded transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 h-full border border-gray-100 ">
                            <div class="h-[120px] rounded bg-gray-50 overflow-hidden">
                                <a href="{{ route('website.product', 4) }}"><img
                                        src="https://cdn.salla.sa/rDvVQ/0Wn6S8lHqsRtMdMLRUfqsmtbIGHj2A43pb0E6OWW.jpg"
                                        class="object-cover w-full h-full hover:opacity-90 transition-opacity" /></a>
                            </div>
                            <h2><a href="{{ route('website.product', 4) }}"
                                    class="text-sm line-clamp-2 hover:text-primary transition-colors">كيف
                                    تعرف نوع
                                    الورق الذي يناسب عملك بشكل أفضل</a></h2>
                            <p class="text-sm text-gray-500 line-clamp-2">اختر مواصفات الطباعة بعنايه</p>
                            <div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="space-y-3 p-4 rounded transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 h-full border border-gray-100 ">
                            <div class="h-[120px] rounded bg-gray-50 overflow-hidden">
                                <a href="{{ route('website.product', 4) }}"><img
                                        src="https://cdn.salla.sa/rDvVQ/OQxmT3F9y8bS0SrRQOIZVusIvSyRHalGpw0j7rJX.png"
                                        class="object-cover w-full h-full hover:opacity-90 transition-opacity" /></a>
                            </div>
                            <h2><a href="{{ route('website.product', 4) }}"
                                    class="text-sm line-clamp-2 hover:text-primary transition-colors">الاختلافات
                                    في اللون: RGB و CMYK</a></h2>
                            <p class="text-sm text-gray-500 line-clamp-2">اختلاف الالوان بين شاشة الكمبيوتر و
                                الطباعة</p>
                            <div>
                            </div>
                        </div>
                    </div>
                </div>
            </salla-slider>
        </div>
    </section>

    <section class="s-block s-block--features container">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5 lg:gap-6 xl:gap-8 md:divide-x rtl:divide-x-reverse">
            <div class="s-block--features__item">
                <i class="feature-icon sicon-full-wallet"></i>
                <h4>الدفع</h4>
                <p>خدمات الدفع المتعددة :( .Apple pay. mada.التحويل البنكي)</p>
            </div>
            <div class="s-block--features__item">
                <i class="feature-icon sicon-headset"></i>
                <h4>الفريق</h4>
                <p>فريق عمل رائع في خدمتكم</p>
            </div>
            <div class="s-block--features__item">
                <i class="feature-icon sicon-shipping"></i>
                <h4>الشحن</h4>
                <p>شحن لجميع مناطق المملكة</p>
            </div>
        </div>
    </section>

    <section class="s-block s-block--fixed-banner wide-placeholder">
        <div class="container">
            <a href="" class="banner banner--fixed" aria-label="Banner ">
                <img class="lazy-load w-full object-cover" src="https://cdn.salla.network/images/s-empty.png?v=2.0.5"
                    data-src="{{ asset('website/images/welcom text.png') }}" alt="" />
            </a>
        </div>
    </section>
    <section class="s-block s-block--testimonials overflow-hidden s-block--full-bg bg-gray-50 py-5 sm:py-12"
        id="testimonials-slider-24">
        <div class="container">
            <salla-slider type="carousel" class="testimonials-slider" auto-play
                block-title="آراء العملاء حول تالا الجزيرة" display-all-url="https://talaaljazira.co/testimonials"
                id="testimonials-24-slider">
                <div slot="items">
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="bg-white rounded p-5 h-full flex flex-col transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 hover:-translate-y-1">
                            <div class="mb-2">
                                <salla-rating-stars value="5"></salla-rating-stars>
                            </div>
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">هذا كان طلبي الثاني للستيكر شيت من تالا
                                الجزيرة
                                والثالث بالطريق بإذن الله 😻</p>
                            <div class="mt-auto">
                                <header
                                    class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_male.png"
                                        alt="Maha Habeeb" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">Maha Habeeb</h4>
                                    <i
                                        class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
                                </header>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="bg-white rounded p-5 h-full flex flex-col transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 hover:-translate-y-1">
                            <div class="mb-2">
                                <salla-rating-stars value="5"></salla-rating-stars>
                            </div>
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">جودة الطباعة على الكراتين من تالا الجزيرة
                                عالية
                                وسرعة
                                في الرد</p>
                            <div class="mt-auto">
                                <header
                                    class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_female.png"
                                        alt="Zainab Aldawood" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">Zainab Aldawood</h4>
                                    <i
                                        class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
                                </header>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="bg-white rounded p-5 h-full flex flex-col transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 hover:-translate-y-1">
                            <div class="mb-2">
                                <salla-rating-stars value="5"></salla-rating-stars>
                            </div>
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">ممتاز من تالا الجزيرة</p>
                            <div class="mt-auto">
                                <header
                                    class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_female.png"
                                        alt="Sakinah Abdullah" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">Sakinah Abdullah</h4>
                                    <i
                                        class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
                                </header>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="bg-white rounded p-5 h-full flex flex-col transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 hover:-translate-y-1">
                            <div class="mb-2">
                                <salla-rating-stars value="5"></salla-rating-stars>
                            </div>
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">خدمة رائعة من تالا الجزيرة</p>
                            <div class="mt-auto">
                                <header
                                    class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_female.png"
                                        alt="Wasayef Khalid" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">Wasayef Khalid</h4>
                                    <i
                                        class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
                                </header>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="bg-white rounded p-5 h-full flex flex-col transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 hover:-translate-y-1">
                            <div class="mb-2">
                                <salla-rating-stars value="5"></salla-rating-stars>
                            </div>
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">الطباعة من تالا الجزيرة ماعليها أي كلام، صراحة
                                أحلى
                                مكان طبعت فيه ستيكر شيت</p>
                            <div class="mt-auto">
                                <header
                                    class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_male.png"
                                        alt="Maha Habeeb" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">Maha Habeeb</h4>
                                    <i
                                        class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
                                </header>
                            </div>
                        </div>
                    </div>
                </div>
            </salla-slider>
        </div>
    </section>
@endsection

@section('js')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
        // Placeholder wishlist toggle function
        function toggleWishlist(productId) {
            console.log(`Toggling wishlist for product ID: ${productId}`);
            // Add your wishlist logic here
        }

        // Placeholder add to cart function
        function addToCart(productId) {
            console.log(`Adding product ID: ${productId} to cart`);
            // Add your cart logic here
        }
    </script>
    <script>
        // Placeholder wishlist toggle function
        function toggleWishlist(productId) {
            console.log(`Toggling wishlist for product ID: ${productId}`);
            // Add your wishlist logic here
        }

        // Placeholder add to cart function
        function addToCart(productId) {
            console.log(`Adding product ID: ${productId} to cart`);
            // Add your cart logic here
        }
        var swiper = new Swiper('.s-slider-container', {
            slidesPerView: 4,
            spaceBetween: 20,
            navigation: {
                nextEl: '.s-slider-next',
                prevEl: '.s-slider-prev',
            },
            loop: true,
            // ✅ مهم جداً دعم الشاشات الصغيرة
            breakpoints: {
                1024: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 2,
                },
                480: {
                    slidesPerView: 1,
                },
            },
        });
        var swiper = new Swiper('.s-slider-container', {
            rtl: true, // استخدم هذا بدل dir
        });
        // Initialize Product Slider (مطبوعات موسمية)
        new Swiper('#slider-with-bg-17', {
            direction: 'horizontal',
            loop: false,
            navigation: {
                nextEl: '.s-slider-next',
                prevEl: '.s-slider-prev',
            },
            slidesPerView: 'auto',
            spaceBetween: 10,
            rtl: true,
        });

        // Placeholder wishlist toggle function for Salla
        window.salla = window.salla || {};
        salla.wishlist = salla.wishlist || {};
        salla.wishlist.toggle = function(productId) {
            console.log(`Toggling wishlist for product ID: ${productId}`);
            // Add your wishlist logic here (e.g., Salla API call)
        };
    </script>
    <script>
        // Initialize Product Slider (مطبوعات موسمية)
        new Swiper('#slider-with-bg-17', {
            direction: 'horizontal',
            loop: false,
            navigation: {
                nextEl: '.s-slider-next',
                prevEl: '.s-slider-prev',
            },
            slidesPerView: 'auto',
            spaceBetween: 10,
            rtl: true,
        });

        // Placeholder wishlist toggle function
        function toggleWishlist(productId) {
            console.log(`Toggling wishlist for product ID: ${productId}`);
            // Add your wishlist logic here
        }

        // Placeholder add to cart function
        function addToCart(productId) {
            console.log(`Adding product ID: ${productId} to cart`);
            // Add your cart logic here
        }
    </script>
@endsection
