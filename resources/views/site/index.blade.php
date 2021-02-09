@extends('site.site')

@section('content')
    <!-- <section class="main-menu">
            <div class="wrapper">
                <div class="main-menu__inner">
                    <ul class="main-menu__list">
                        <li class="main-menu__item">
                            <a href="/about.html" class="main-menu__link">
                                о нас
                            </a>
                        </li>


                            <li class="main-menu__item">
                                <a href="/woman.html" class="main-menu__link">
                                    женская парфюмерия
                                </a>
                            </li>
                            <li class="main-menu__item">
                                <a href="/man.html" class="main-menu__link">
                                    мужская парфюмерия
                                </a>
                            </li>


                        <li class="main-menu__item">
                            <a href="/samples-preview.html" class="main-menu__link">
                                пробники
                            </a>
                        </li>


                        <li class="main-menu__item">
                            <a href="/contact.html" class="main-menu__link">
                                контакты
                            </a>
                        </li>
                        <li class="main-menu__item">
                            <a @click="showInfo()" href="#footer" class="main-menu__link">
                                информация
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </section> -->
    <a href="#adv">
        <img class="main-image" src="{{ asset('template_site/images/header-banner.jpg') }}" alt="">
        <img class="mobile-image" src="{{ asset('template_site/images/header-banner_mobile.jpg') }}" alt="">
        <!-- <section class="header-banner">
            <div class="wrapper">
                <div class="header-banner__inner">
                    <div class="header-banner__info">



                    </div>
                </div>
            </div>
        </section> -->
    </a>

    <div style="position:relative;">
        <div style="position:absolute; top:-40px;" id="adv"></div>
    </div>

    <section class="advantages">
        <div class="wrapper">
            <div class="advantages__inner">
                <div class="text">
                    <p style="text-align:center">
                        МЫ ОЧЕНЬ ХОТИМ ПОЗНАКОМИТЬ ВАС С НАШЕЙ ПАРФЮМЕРИЕЙ, УВЕРЕНЫ – ВЫ В НЕЕ ВЛЮБИТЕСЬ, ПОЭТОМУ ДАЕМ ТАКУЮ СИМВОЛИЧЕСКУЮ ЦЕНУ!
                        <br/>
                        <br/>
                        Выберите <strong style="color:#fb200d;">3 любых</strong> пробника по&nbsp;2,5 мл
                        <!-- Мы используем <strong>только оригинальные</strong> компоненты для создания красивых, безопасных и гипоаллергенных ароматов. Наши аналоги - это уникальные формулы, которые максимально приближены к своим брендовым форматам, но незначительно отличается от них своеобразной изюминкой в аромате. -->
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section class="product">
        <div class="product__header" id="woman">
            <div class="wrapper sample-title">
                <h2>
                    Женские &nbsp; &nbsp;

                    <a href="#man" class="product-card__button sex_button">
                        перейти к мужским
                    </a>
                </h2>
            </div>
        </div>

        <div class="product__list">
            <div class="wrapper">
                <div class="product-list">

                    <div v-cloak v-for="product in products"
                         v-if="product.show == 1 && product.woman == 1"
                         class="product-list__col">


                        <div class="product-card">

                            <div v-if="product.new == 1" class="product-card__label-new">
                                    <span>
                                        new
                                    </span>
                            </div>
                            <div v-if="product.best == 1" class="product-card__label-bestseller">
                                BESTSELLER
                            </div>
                            <div v-if="product.hit == 1" class="product-card__label-niche">
                                niche
                            </div>
                            <div class="product-card__img">
                                <img v-if="product.img" :src="['/assets/img/landing_good/' + product.img]" :alt="product.analog">
                            </div>
                            <div class="product-card__info-wrapper">
                                <div class="product-card__info">
                                    <div class="product-card__name">
                                        <strong>{{-- product.bname --}}</strong><br/>
                                        {{-- product.name --}}
                                    </div>
                                    <div class="product-card__number">
                                        <div class="product-card__number-icon">
                                            <span>
                                                {{-- product.name --}}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="product-card__price">
                                        <div class="product-card__price-col active">
                                                <span>
                                                    {{-- product.price --}}.
                                                     <sup>00</sup>
                                                </span>
                                        </div>
                                        <span class="product-card__price-text">&nbsp; &nbsp; грн.</span>

                                    </div>

                                    <div class="product-card__volume">2,5 мл</div>

                                    <div class="product-card__description">

                                        Основные аккорды: <b>{{-- product.filter2 --}}</b>
                                    </div>
                                </div>
                                <form class="product-card__controllers">
                                    <button @click="addToCart(product, $event)" type="submit" class="product-card__button">
                                        {{-- hasInBasket(product.art) ? 'Добавлено в корзину' : 'В корзину' --}}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="product">
        <div class="product__header" id="man">
            <div class="wrapper sample-title">
                <h2>
                    Мужские &nbsp; &nbsp;
                    <a href="#woman" class="product-card__button sex_button">
                        вернуться к женским
                    </a>
                </h2>
            </div>
        </div>

        <div class="product__list">
            <div class="wrapper">
                <div class="product-list">

                    <div v-cloak v-for="product in products"
                         v-if="product.show == 1 && product.man == 1"
                         class="product-list__col">


                        <div class="product-card">

                            <div v-if="product.new == 1" class="product-card__label-new">
                                    <span>
                                        new
                                    </span>
                            </div>
                            <div v-if="product.best == 1" class="product-card__label-bestseller">
                                BESTSELLER
                            </div>
                            <div v-if="product.hit == 1" class="product-card__label-niche">
                                niche
                            </div>
                            <div class="product-card__img">
                                <img v-if="product.img" :src="['/assets/img/landing_good/' + product.img]" :alt="product.analog">
                            </div>
                            <div class="product-card__info-wrapper">
                                <div class="product-card__info">
                                    <div class="product-card__name">
                                        <strong>{{-- product.bname --}}</strong><br/>
                                        {{-- product.name --}}
                                    </div>
                                    <div class="product-card__number">
                                        <div class="product-card__number-icon">
                                            <span>
                                                {{-- product.name --}}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="product-card__price">
                                        <div class="product-card__price-col active">
                                                <span>
                                                    {{-- product.price --}}.
                                                     <sup>00</sup>
                                                </span>
                                        </div>
                                        <span class="product-card__price-text">&nbsp; &nbsp; грн.</span>

                                    </div>

                                    <div class="product-card__volume">2,5 мл</div>

                                    <div class="product-card__description">

                                        Основные аккорды: <b>{{-- product.filter2 --}}</b>
                                    </div>
                                </div>
                                <form class="product-card__controllers">
                                    <button @click="addToCart(product, $event)" type="submit" class="product-card__button">
                                        {{-- hasInBasket(product.art) ? 'Добавлено в корзину' : 'В корзину' --}}
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="advantages">
        <div class="wrapper">
            <div class="advantages__inner">
                <div class="advantages__row">
                    <div class="advantages__col">
                        <div class="advantages-card">
                            <div class="advantages-card__img">
                                <img src="{{ asset('template_site/images/advantages_1.png') }}" alt="" class="desktop">
                                <img src="{{ asset('template_site/images/advantages_1_mobile.png') }}" alt="" class="mobile">
                            </div>
                            <div class="advantages-card__info">
                                <div class="advantages-card__text">
                                    Французские эссенции и масла высочайшего качества.
                                </div>
                                <!-- <a target="_blank" href="/about.html" class="advantages-card__btn">
                                    Подробнее
                                </a> -->
                            </div>
                        </div>
                    </div>
                    <div class="advantages__col">
                        <div class="advantages-card">
                            <div class="advantages-card__img">
                                <img src="{{ asset('template_site/images/advantages_2.png') }}" alt="" class="desktop">
                                <img src="{{ asset('template_site/images/advantages_2_mobile.png') }}" alt="" class="mobile">
                            </div>
                            <div class="advantages-card__info">
                                <div class="advantages-card__text">
                                    Проработанные формулы для максимального сходства ароматов.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="advantages__col">
                        <div class="advantages-card">
                            <div class="advantages-card__img">
                                <img src="{{ asset('template_site/images/advantages_3.png') }}" alt="" class="desktop">
                                <img src="{{ asset('template_site/images/advantages_3_mobile.png') }}" alt="" class="mobile">
                            </div>
                            <div class="advantages-card__info">
                                <div class="advantages-card__text">
                                    Без парабенов, парафинов, красителей, фталатов, силиконов, минеральных масел, и других опасных веществ.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="advantages__col">
                        <div class="advantages-card">
                            <div class="advantages-card__img">
                                <img src="{{ asset('template_site/images/advantages_4.png') }}" alt="" class="desktop">
                                <img src="{{ asset('template_site/images/advantages_4_mobile.png') }}" alt="" class="mobile">
                            </div>
                            <div class="advantages-card__info">
                                <div class="advantages-card__text">
                                    Быстрая Доставка "Укрпочтой" за 2-4 дня в любую точку Украины.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="info">
        <div class="wrapper">
            <div class="info__inner">
                <div class="text">
                    <p>
                        Сайт создан в соответствии с Директивами Европейского Союза 2006/114/ЕС и 2005/29/ЕС, а также законом Украины «О рекламе».
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 	<section class="viber viber--main-page">
            <div class="wrapper">
                <div class="viber__inner viber__inner--main-page">
                    <div class="viber__sticker-mobile"></div>
                    <div class="viber__title-main">
                        Лучшие акции и распродажи для подписчиков
                    </div>
                    <div class="viber__logo viber__logo--main-page">
                        Viber
                    </div>
                    <form class="viber__form" name="viber-form">
                        <ul class="viber__list">
                            <li class="viber__list-item">
                                <span>-</span> Акции и скидки
                            </li>
                            <li class="viber__list-item">
                                <span>-</span> Подарочный наборы
                            </li>
                            <li class="viber__list-item">
                                <span>-</span> Специальные предложения
                            </li>
                        </ul>
                        <a href="#" class="viber__btn">
                            Хочу Скидки!
                        </a>
                        <label class="viber-checkbox__radiobutton">
                            <input class="viber-checkbox__input" type="checkbox" name="viber" checked>
                            <div class="viber-checkbox__box"></div>
                            <div class="viber-checkbox__text">
                                Вы соглашаетесь с <a target="_blank" href="/policy.html"><u>Политикой
                                конфиденциальности</u></a>
                            </div>
                        </label>
                    </form>
                </div>
            </div>
        </section> -->


    <section class="instagram-box">
        <div class="wrapper">
            <div class="instagram-box__inner">
                <div class="instagram-box__header">
                    <div class="instagram-box__icon"></div>
                    <div class="instagram-box__title">
                        #PDPARIS
                    </div>
                    <div class="instagram-box__text">
                        Следите за новинками в нашем Instagram
                    </div>
                </div>


                <div class="instagram-box__slider">
                    <img style="width:285px;" src="{{ asset('template_site/images/instagram-2.jpg') }}" alt="">
                    <img style="width:285px;" src="{{ asset('template_site/images/instagram-1.jpg') }}" alt="">
                    <img style="width:285px;" src="{{ asset('template_site/images/instagram-3.jpg') }}" alt="">
                    <img style="width:285px;" src="{{ asset('template_site/images/instagram-4.jpg') }}" alt="">
                    <img style="width:285px;" src="{{ asset('template_site/images/instagram-5.jpg') }}" alt="">
                    <img style="width:285px;" src="{{ asset('template_site/images/instagram-6.jpg') }}" alt="">
                    <img style="width:285px;" src="{{ asset('template_site/images/instagram-7.jpg') }}" alt="">
                    <img style="width:285px;" src="{{ asset('template_site/images/instagram-9.jpg') }}" alt="">
                </div>

                <a target="_blank" href="https://www.instagram.com/pd_paris/" class="instagram-box__btn">
                    ОТКРЫТЬ InSTAGRAM
                </a>

            </div>


        </div>
    </section>
@endsection
