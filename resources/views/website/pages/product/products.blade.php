@extends('website.layouts.master')
@section('css')
@endsection

@section('content')

    <div class="container my-10">
        <div class="flex items-start flex-col md:flex-row">

            <div class="main-content flex-1 w-full ">
                <div class="mb-4 sm:mb-6 flex justify-between items-center">
                    <h1 class="font-bold text-xl rtl:pl-3 ltr:pr-3">كل المنتجات</h1>
                    <div class="center-between">
                        <div class="flex gap-6 md:gap-8 items-center">

                            <div class="flex items-center">
                                <label class="hidden sm:block rtl:ml-3 ltr:mr-3 whitespace-nowrap"
                                    for="product-filter">ترتيب</label>
                                <select id="product-filter" class="form-input pt-0 pb-1 rtl:pl-10 ltr:pr-10">
                                    <option value="ourSuggest">مقترحاتنا</option>
                                    <option value="bestSell">الاكثر مبيعاً</option>
                                    <option value="topRated">الاعلى تقييماً</option>
                                    <option value="priceFromTopToLow">السعر من الاعلى إلى الاقل</option>
                                    <option value="priceFromLowToTop">السعر من الاقل إلى الاعلى</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="flex">
                    <salla-products-list class="flex-1 min-w-0 overflow-auto " source="product.index"
                        source-value="368078986" autoload="false"></salla-products-list>
                </div>

            </div>
        </div>
    </div>


    <section class="s-block s-block--testimonials overflow-hidden s-block--full-bg bg-gray-50 py-5 sm:py-12"
        id="testimonials-slider-">
        <div class="container">
            <salla-slider type="carousel" class="testimonials-slider" auto-play block-title="آراء العملاء"
                id="testimonials--slider">
                <div slot="items">
                    <div class="swiper-slide slide--one-fourth">
                        <div
                            class="bg-white rounded p-5 h-full flex flex-col transition-all duration-300 shadow-md shadow-gray-100 hover:shadow-lg hover:shadow-gray-200 hover:-translate-y-1">
                            <div class="mb-2">
                                <salla-rating-stars value="5"></salla-rating-stars>
                            </div>
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">الجوده ولا اروع
                                بالاضافه لرُقي تعاملهم
                                وصلني المنتج بوقت قياسي يشكرون عليه❤️</p>
                            <div class="mt-auto">
                                <header class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_male.png"
                                        alt="فيّ المطيري" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">فيّ المطيري</h4>
                                    <i class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
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
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">افضل مطبعه سرعه في التنفيذ طباعه جميله جدا</p>
                            <div class="mt-auto">
                                <header class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.salla.sa/customer_profiles/VoqteRTCZY5tZbbWpUml5hy1Xc6NwcsET7oD7jQ3.jpg"
                                        alt="عبدالعزيز العمري" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">عبدالعزيز العمري</h4>
                                    <i class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
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
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">حلو اشكركم</p>
                            <div class="mt-auto">
                                <header class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_male.png"
                                        alt="حنان الحربي" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">حنان الحربي</h4>
                                    <i class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
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
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">جداً جميله البوكسات وجودة الطباعه ولا غلطه</p>
                            <div class="mt-auto">
                                <header class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_female.png"
                                        alt="فاطمة العصيمي" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">فاطمة العصيمي</h4>
                                    <i class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
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
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">متجر ممتاز ومتعاون وطباعة رائعة 🔥🔥🔥🔥</p>
                            <div class="mt-auto">
                                <header class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_male.png"
                                        alt="رزان Aa" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">رزان Aa</h4>
                                    <i class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
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
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">مرره عجبني البوكس مو اخر مره اطلب منهم</p>
                            <div class="mt-auto">
                                <header class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_male.png"
                                        alt="يارا العنزي" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">يارا العنزي</h4>
                                    <i class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
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
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">جودتهم ممتازززة والتوصيل عن طريق ريد بوكس سرييع
                                + رخيص ❤️</p>
                            <div class="mt-auto">
                                <header class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_male.png"
                                        alt="محمد البوعينين" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">محمد البوعينين</h4>
                                    <i class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
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
                            <p class="leading-6 mt-4 sm:mt-0 text-gray-600">تجربة رائعة والجودة ممتازة وبإذن الله لي تعامل
                                ثاني معاكم 🤍</p>
                            <div class="mt-auto">
                                <header class="flex items-center space-x-2 rtl:space-x-reverse border-t mt-4 pt-4 relative">
                                    <img src="https://cdn.assets.salla.network/prod/stores/themes/default/assets/images/avatar_female.png"
                                        alt="Artkitchen 👩🏼‍🍳" class="w-9 h-9 rounded-full object-cover">
                                    <h4 class="font-bold text-xs sm:text-sm">Artkitchen 👩🏼‍🍳</h4>
                                    <i class="sicon-quote-open absolute text-6xl text-gray-100 rtl:left-0 ltr:right-0"></i>
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
@endsection