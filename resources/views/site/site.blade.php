<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="windows-1251">

    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Parfum de Paris</title>
    <meta name="description" content="Parfum de Paris">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/png">

    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display"> -->
    <link rel="stylesheet" href="{{ asset('template_site/css/main.css') }}">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-166764330-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-166764330-1');
    </script>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '3329530043937779');
        fbq('track', 'PageView');


    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=3329530043937779&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '1013168769101972');
        fbq('track', 'PageView');


    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=1013168769101972&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->


    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '3090275101009692');
        fbq('track', 'PageView');

    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=3090275101009692&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://cdn.jsdelivr.net/npm/yandex-metrica-watch/tag.js", "ym");

        ym(66758551, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/66758551" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->


    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '202630431467283');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=202630431467283&ev=PageView
              &noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

</head>
<body>
<div class="vue">
<!-- header with cart -->
@include('site.layouts.header')

    <!-- main content -->
    @yield('content')

    <!-- footer -->
@include('site.layouts.footer')


<!--Модалки-->

    <!--Корзина промокод-->
    <div class="modal modal__cart-promocode">
        <div class="modal__wrapper modal-promocode">
            <div class="modal__close modal-promocode__close"></div>

            <div class="modal-promocode__header">
                <div class="modal-promocode__title">
                    Ваша корзина
                </div>
            </div>

            <div v-if="basket.length === 0">
                <div class="modal-promocode__title">
                    Ваша корзина пуста
                </div>
                <br/>
                <br/>
            </div>
            <div v-else>
                <div class="modal-promocode__stock">
                    <div class="modal-promocode__stock-title">
                        Минимальный заказ 3 пробника
                    </div>

                </div>
                <div class="modal-promocode-table">
                    <div class="modal-promocode-table__header">
                        <div class="modal-promocode-table__col-img"></div>
                        <div class="modal-promocode-table__col-product">
                            товар
                        </div>
                        <div class="modal-promocode-table__col-price">
                            цена
                        </div>
                        <div class="modal-promocode-table__col-volume">
                            объем
                        </div>
                        <div class="modal-promocode-table__col-amount">
                            сумма
                        </div>
                        <div class="modal-promocode-table__col-close"></div>
                    </div>
                    <div class="modal-promocode-table__content">


                        <div v-cloak v-for="(product, index) in basketVisible" class="modal-promocode-table__row">


                            <!-- <div v-cloak v-for="(product, index) in basket" class="modal-promocode-table__row"> -->
                            <div class="modal-promocode-table__col-img">
                                <img v-if="product.img" :src="['/assets/img/landing_good/' + product.img]" alt="">
                            </div>
                            <div class="modal-promocode-table__col-product">
                                <span>{{-- product.name --}}</span>
                            <!-- <div class="modal-promocode-table__text-small">{{-- product.art --}}</div> -->
                                <!--Дубль объема для мобильной версии-->
                                <div class="modal-promocode-table__mobile-volume">
                                    <span>2,5</span> мл
                                </div>
                                <!--Дубль объема для мобильной версии конец-->
                                <div class="discount" v-if="product.discount">({{-- product.discount --}})</div>
                            </div>
                            <div class="modal-promocode-table__col-price">
                                <span>{{-- product.sale --}}</span>  грн.

                            </div>
                            <div class="modal-promocode-table__col-volume">
                                <span>2,5</span> мл
                            </div>
                            <div class="modal-promocode-table__col-amount">
                                <span>{{-- product.sale --}}</span> грн.
                            <!-- <div class="discount" v-if="product.discount">({{-- product.discount --}})</div> -->
                            </div>
                            <div class="modal-promocode-table__col-close">

                                <a @click="removeFromCart(product.art)" href="javascript:void(0)" class="modal-promocode-table__close">&nbsp;</a>
                            </div>
                        </div>

                    </div>
                    <div class="modal-promocode-table__total">
                        <!--Строки для мобильной версии-->
                        <div class="modal-promocode-table__total-row-mobile">
                            <div class="modal-promocode-table__text-mobile">
                                Товаров на сумму:
                            </div>
                            <div class="modal-promocode-table__text-mobile">
                                {{-- total --}} грн.
                            </div>
                        </div>
                        <div class="modal-promocode-table__total-row-mobile">
                            <div class="modal-promocode-table__text-mobile">
                                Доставка:
                            </div>
                            <div class="modal-promocode-table__text-mobile">
                                на следующем шаге
                            </div>
                        </div>


                        <div class="modal-promocode-table__total-row">
                            <div class="modal-promocode-table__total-text">
                                ИТОГО:
                            </div>
                            <div class="modal-promocode-table__total-text-big">
                                {{-- total --}} грн.
                                <div class="discount" v-if="order.promocodeInfo">{{-- order.promocodeInfo --}}</div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-promocode-table__footer">
                        <div class="modal-promocode-table__footer-col-link">
                            <a @click="closeBasket()" href="javascript:void(0)" class="modal-promocode-table__footer-link">
                                Вернуться к выбору
                            </a>
                        </div>

                        <div v-if="! order.promocodeAccepted" class="modal-promocode-table__footer-col-promo">

                        </div>
                        <div class="modal-promocode-table__footer-col-btn">
                            <button v-if="basket.length < 3" @click="closeBasket()"  class="modal-promocode-table__btn">
                                {{-- 'Добавьте еще ' + (3 - basket.length) --}}
                            </button>

                            <button v-if="basket.length >= 3" @click="checkout()"  class="modal-promocode-table__btn">
                                Перейти к оформлению
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Корзина промокод конец-->

    <!--Оформление заказа-->
    <div class="modal modal__order">
        <div class="modal__wrapper modal-order" style="max-width:600px;">
            <div class="modal__close modal-order__close"></div>
            <div class="modal-order__inner">
                <div class="modal-order__header">
                    <h1>
                        Оформление заказа
                    </h1>
                </div>

                <div class="modal-order__content">
                    <form class="modal-order__row" name="modal-order-form">

                        <div v-if="step == 1" class="modal-order__col-payment width-100">

                            <div class="modal-order__payment-box">

                                <div>

                                    <div v-if="order.kindpay == 1" style="font-size:14px; line-height: 18px; border: 2px solid #54256e; margin-bottom:20px; padding: 10px; color:#7b419b;">
                                        <strong>ВНИМАНИЕ!</strong> На следующей странице Вам нужно будет оплатить <strong>только</strong> за товар, доставку мы берем на себя.
                                    </div>

                                    <center>
                                        <img style="width:150px; margin-bottom:10px;" src="/images/800px-Ukrposhta-ua.svg.png">
                                        <br/>
                                    </center>

                                    <div style="font-size:14px; line-height: 18px; margin-bottom:10px;">Выберите способ оплаты:</div>

                                    <label v-if="order.kindpay != 2" class="modal-order__radiobutton">
                                        <input @click="clearIssue()" v-model="order.kindpay" class="modal-order__radiobutton-input" type="radio" name="kindpay" value="1" selected>
                                        <div class="modal-order__box"></div>
                                        <div class="modal-order__text">
                                            <b>Оплата онлайн</b> &nbsp;

                                            <div style="font-size:9px; text-transform:uppercase; border:1px solid #5676d5; padding:3px 5px; padding-bottom:1px; border-radius:10px; display:inline-block; color:#5676d5; font-weight:bold;">Выбор клиентов</div>
                                            <br/>

                                            <div v-if="!order.kindpay" style="color:green; font-weight:bold;">Бесплатная доставка при оплате онлайн</div>

                                        </div>
                                    </label>

                                    <label v-if="order.kindpay != 1"  class="modal-order__radiobutton">
                                        <input @click="clearIssue()" v-model="order.kindpay" class="modal-order__radiobutton-input" type="radio" name="kindpay" value="2">
                                        <div class="modal-order__box"></div>
                                        <div class="modal-order__text">
                                            <b>Оплата при получении</b><br/>
                                        </div>
                                    </label>

                                    <a
                                        v-if="order.kindpay"
                                        @click="clearKindPay();"
                                        href="javascript:void(0)"
                                        style="font-size:13px; text-decoration: underline; color:#333; padding:0px 35px;">
                                        Изменить выбор
                                    </a>

                                    <div
                                        v-if="!order.kindpay"
                                        style="font-size:14px; line-height: 18px; border: 2px solid rgb(133 84 160); margin-bottom:20px; margin-top:40px; padding: 10px; color:rgb(133 84 160);">
                                        Рекомендуем выбрать самый популярный среди клиентов способ «<strong>Оплата онлайн</strong>», чтобы сэкономить на доставке
                                    </div>



                                    <div v-if="order.kindpay">

                                        <div style="border-bottom: solid 1px #e6e6e6; width: 100%; margin-bottom: 20px; margin-top:20px;"></div>

                                        <div style="font-size:14px; line-height: 18px; margin-bottom:10px;">Выберите способ доставки:</div>

                                        <label v-if="order.pay != 'Курьером'" class="modal-order__radiobutton">
                                            <input @click="clearIssue(); setStep(2)" v-model="order.pay" class="modal-order__radiobutton-input" type="radio" name="modal-order-payment" value="Отделение" selected>
                                            <div class="modal-order__box"></div>
                                            <div class="modal-order__text">
                                                <b>На отделение </b><br/>
                                                Доставка на отделение УКРПОЧТА в Вашем населенном пункте.
                                            </div>
                                        </label>

                                        <label v-if="order.pay != 'Отделение'" class="modal-order__radiobutton">
                                            <input @click="clearIssue(); setStep(2)" v-model="order.pay" class="modal-order__radiobutton-input" type="radio" name="modal-order-payment" value="Курьером">
                                            <div class="modal-order__box"></div>
                                            <div class="modal-order__text">
                                                <b>Курьером</b><br/>
                                                Адресная доставка курьером УКРПОЧТА
                                            </div>
                                        </label>

                                        <a v-if="order.pay" @click="clearPay();" href="javascript:void(0)"  style="font-size:13px; text-decoration: underline; color:#333; padding:0px 35px; ">
                                            Изменить выбор
                                        </a>

                                    </div>

                                    <br/>


                                    <div class="modal-order__confirm-box">
                                        <button v-if="order.pay && order.kindpay && step == 1" @click="step = 2" type="submit" class="modal-order__btn feedback__btn">
                                            Далее
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>


                        <div v-if="step == 2" class="modal-order__col-contacts" style="width:100%;">

                            <br/>

                            <a @click="step = 1" href="javascript:void(0)"  style="font-size:15px; text-decoration: underline; color:#333;">
                                <center>Изменить способ доставки</center>
                            </a>

                            <br/>

                            <div style="font-size:14px; line-height: 18px; margin-bottom:10px;">Введите ваши контактные данные: </div>

                            <input v-if="order.pay" v-model="order.name" class="modal-order__input feedback__input" placeholder="Имя" name="name">

                            <input v-if="order.pay" v-model="order.lastname" class="modal-order__input feedback__input" placeholder="Фамилия" name="lastname">

                            <div v-if="order.lastname">

                                <input type="tel" v-mask="'+38 (###) ###-##-##'" v-model="order.phone" class="modal-order__input feedback__input" placeholder="Ваш телефон" name="modal-order-phone">

                                <div class="vue-suggestion">
                                    <input
                                        v-if="order.pay == 'Оплата онлайн' || order.pay == 'Отделение' || order.pay == 'Курьером'"
                                        v-model="order.city"
                                        placeholder="Город или населенный пункт"
                                        name="city"
                                        class="city"
                                        @input='evt => searchCities(order.city=evt.target.value)'
                                        @focus="showCities = true"
                                    >
                                    <div v-if="order.city && showCities && citiesFiltered.length > 0" class="vs__list">
                                        <div @click="setCity(row)" v-for="row in citiesFiltered" class="vs__list-item">{{-- row.name --}}</div>
                                    </div>
                                </div>

                                <div style="color:red;" class="city-issue"></div>

                                <div class="vue-suggestion">
                                    <input
                                        v-if="order.pay == 'Отделение' && order.cityId"
                                        v-model="order.office"
                                        placeholder="Отделение 'УКРПОЧТА'"
                                        name="office"
                                        class="office"
                                        @input='evt => searchOffices(order.office=evt.target.value)'
                                        @focus="showOffices = true"
                                    >
                                    <div v-if="showOffices && officesFiltered.length > 0" class="vs__list">
                                        <div @click="setOffice(row)" v-for="row in officesFiltered" class="vs__list-item">№{{-- row.POSTINDEX --}}, {{-- row.ADDRESS --}}</div>
                                    </div>

                                    <div v-if="offices.length == 0 && order.cityId && order.pay == 'Отделение'" class="vs__list">
                                        <div class="vs__list-item">Нет отделений</div>
                                    </div>
                                </div>

                                <input v-if="order.pay == 'Оплата онлайн'" v-model="order.office" class="modal-order__input feedback__input" placeholder="Отделение 'Новой Почты'" name="office">


                                <div class="vue-suggestion">
                                    <input
                                        v-if="order.pay == 'Курьером' && order.cityId"
                                        v-model="order.street"
                                        placeholder="Улица"
                                        name="street"
                                        class="street"
                                        @input='evt => searchStreets(order.street=evt.target.value)'
                                        @focus="showStreets = true"
                                    >
                                    <div v-if="showStreets && streetsFiltered.length > 0" class="vs__list">
                                        <div @click="setStreet(row)" v-for="row in streetsFiltered" class="vs__list-item">{{-- row.STREET_UA --}}</div>
                                    </div>
                                </div>

                                <div class="vue-suggestion">
                                    <input
                                        v-if="order.pay == 'Курьером' && order.streetId"
                                        v-model="order.house"
                                        placeholder="Дом"
                                        name="house"
                                        class="house"
                                        @input='evt => searchHouses(order.house=evt.target.value)'
                                        @focus="showHouses = true"
                                    >
                                    <div v-if="showHouses && housesFiltered.length > 0" class="vs__list">
                                        <div @click="setHouse(row)" v-for="row in housesFiltered" class="vs__list-item">{{-- row.HOUSENUMBER_UA --}}</div>
                                    </div>
                                </div>

                                <div style="color:red;" class="postindex-issue"></div>


                                <input v-if="order.pay == 'Курьером' && order.postindex" v-model="order.flat" class="modal-order__input feedback__input" placeholder="Квартира" name="flat">

                            </div>

                            <!-- <textarea v-if="order.pay == 'Наложенный платеж'" v-model="order.comment" class="modal-order__textarea feedback__textarea" placeholder="Комментарии к заказу" name="modal-order-message"></textarea> -->

                            <br/>

                            <div class="modal-order__confirm-box">
                                <!-- <div class="modal-order__text-small">
                                    Нажимая на кнопку “Подтвердить”, вы даете согласие на обработку своих <a target="_blank" href="/policy.html">персональных данных</a>
                                </div> -->
                                <button :disabled="loading" @click="acceptOrder($event)" type="submit" class="modal-order__btn feedback__btn">
                                    {{-- loading ? 'Пожалуйста, подождите...' : 'Подтвердить' --}}
                                </button>
                            </div>
                        </div>


                    </form>
                </div>
                <!-- <div class="modal-order__footer">
                    <a target="_blank" href="/delivery.html" class="modal-order__footer-link">
                        Доставка и оплата
                    </a>
                    <a target="_blank" href="/terms.html" class="modal-order__footer-link">
                        Условия использования
                    </a>
                </div> -->
            </div>
        </div>
    </div>
    <!--Оформление заказа конец-->



    <!--Карточка товара-->
    <div class="modal modal__product">
        <div class="modal__wrapper modal-product">
            <div class="modal__close modal-product__close"></div>
            <!--Название товара только для мобильной версии-->
            <div class="modal-product__mobile-header">
                {{-- product.bname --}} № {{-- product.name --}}
            </div>
            <!--Название товара только для мобильной версии конец-->
            <div class="modal-product__inner">
                <div class="modal-product__col-img">
                    <div class="modal-product-card">
                        <div class="modal-product-card__img">
                            <img v-if="product.img" :src="['/assets/img/landing_good/' + product.img]" :alt="product.analog">
                        </div>
                        <div class="modal-product-card__content">
                            <div class="modal-product-card__title">
                                {{-- product.bname --}} № {{-- product.name --}}
                            </div>
                            <div class="modal-product-card__description">
                                Парфюмированная вода
                            </div>
                            <div class="modal-product-card__price">
                                <div class="product-card__price-col active" >
									<span>
										{{-- product.active50 ? product.price50 : product.price100 --}}.00
									</span>
                                </div>
                                <span class="product-card__price-text">
									грн.
								</span>
                            </div>
                            <form class="product-card__controllers" name="modal-product-card">
                                <div v-if="product.samples != 1" class="radiobuttons">
                                    <div class="radiobuttons__col">
                                        <label class="radiobutton">
                                            <input class="radiobutton__input" name="card1" type="radio" :checked="product.active50" >
                                            <div @click="setProductVolume(product, 50)" class="radiobutton__box">
                                                50мл
                                            </div>
                                        </label>
                                    </div>
                                    <div class="radiobuttons__col">
                                        <label class="radiobutton">
                                            <input class="radiobutton__input" name="card1" type="radio" :checked="product.active100">
                                            <div @click="setProductVolume(product, 100)" class="radiobutton__box">
                                                100мл
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <button @click="addToCart(product, $event)" type="submit" class="product-card__button">
                                    В корзину
                                </button>
                            </form>
                            <div class="modal-product-card__info">
                                <div class="modal-product-card__info-code">
                                    Код товара: <span>{{-- product.art100 --}}</span>
                                </div>
                                <div class="modal-product-card__info-availability">
                                    В наличии!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-product__col-info">
                    <div class="modal-product-card-info">
                        <div class="modal-product-card-info__options">
                            <p>
                                <b>
                                    Тип аромата:
                                </b>
                                <span>
									{{-- product.filter --}}
								</span>
                            </p>
                            <p>
                                <b>
                                    Схож с ароматом:
                                </b>
                                <span>
									{{-- product.analog --}}
								</span>
                            </p>
                            <p>
                                <b>
                                    Верхние ноты:
                                </b>
                                <span>
									{{-- product.note_high --}}
								</span>
                            </p>
                            <p>
                                <b>
                                    Ноты сердца:
                                </b>
                                <span>
									{{-- product.note_heart --}}
								</span>
                            </p>
                            <p>
                                <b>
                                    Базовая нота:
                                </b>
                                <span>
									{{-- product.note_base --}}
								</span>
                            </p>

                            <p>{{-- product.text --}}</p>
                        </div>



                        <br/>

                        <!-- <div class="modal-product-card-info__text" v-html="product.text"></div> -->
                        <div class="modal-product-card-indicators">
                            <div class="modal-product-card-indicators__row">
                                <div class="modal-product-card-indicators__title">
                                    Сходство с аналогом
                                </div>
                                <div class="modal-product-card-indicators__inner-row">
                                    <!--шкала оценки-->
                                    <!--Управлять шкалой оценки можно при помощи изменения ширины modal-product-card-indicators__scale-inner-->
                                    <div class="modal-product-card-indicators__scale">
                                        <div class="modal-product-card-indicators__scale-inner" :style="{ width: product.similarity + '0%' }"></div>
                                    </div>
                                    <!--шкала оценки конец-->
                                    <div class="modal-product-card-indicators__label">
                                        <span>{{-- product.similarity --}}</span>/<span>10</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-product-card-indicators__row">
                                <div class="modal-product-card-indicators__title">
                                    Стойкость
                                </div>
                                <div class="modal-product-card-indicators__inner-row">
                                    <!--шкала оценки-->
                                    <!--Управлять шкалой оценки можно при помощи изменения ширины modal-product-card-indicators__scale-inner-->
                                    <div class="modal-product-card-indicators__scale">
                                        <div class="modal-product-card-indicators__scale-inner" :style="{ width: product.stamina + '0%' }"></div>
                                    </div>
                                    <!--шкала оценки конец-->
                                    <div class="modal-product-card-indicators__label">
                                        <span>{{-- product.stamina --}}</span>/<span>10</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-product-card-indicators__row">
                                <div class="modal-product-card-indicators__title">
                                    Шлейф
                                </div>
                                <div class="modal-product-card-indicators__inner-row">
                                    <!--шкала оценки-->
                                    <!--Управлять шкалой оценки можно при помощи изменения ширины modal-product-card-indicators__scale-inner-->
                                    <div class="modal-product-card-indicators__scale">
                                        <div class="modal-product-card-indicators__scale-inner" :style="{ width: product.plume + '0%' }"></div>
                                    </div>
                                    <!--шкала оценки конец-->
                                    <div class="modal-product-card-indicators__label">
                                        <span>{{-- product.plume --}}</span>/<span>10</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div v-if="!compare" class="modal-product-analog">
                <div class="modal-product-analog-wrapper">
                    <div class="product-analog-img"></div>
                    <div class="product-analog-text">
                        <p>Ищите аналог?</p>
                        Просматривайте каталог в удобном формате
                        <button @click="openAnalog(product.woman == 1 ? '/woman-compare.html' : '/man-compare.html')">показать</button>
                    </div>
                </div>
            </div>

            <div class="modal-product__mobile-footer">
                <a href="javascript:void(0)" @click="closeModal('modal__product')">
                    Закрыть
                </a>
            </div>
        </div>
    </div>
    <!--Карточка товара конец-->

    <!--Предупреждение-->
    <div class="modal modal__warning">
        <div class="modal__wrapper modal-warning">
            <div class="modal__close modal-warning__close"></div>
            <div class="modal-warning__header">
                <div class="modal-warning__header-row">
                    <div class="modal-warning__header-col modal-warning__header-col--left">
                        <div class="modal-warning__header-text">
                            Парфюм <br>
                            с похожим <br>
                            ароматом
                        </div>
                    </div>
                    <div class="modal-warning__header-img">
                        <img src="/images/modal-warning_img.png" alt="">
                    </div>
                    <div class="modal-warning__header-col modal-warning__header-col--right">
                        <div class="modal-warning__header-text">
                            Парфюм<br/>
                            PDPARIS
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-warning__info">
                Обратите внимание
            </div>
            <div class="modal-warning__content">
                <p>
                    Изображение не должно вводить вас в заблуждение!

                </p>
                <p>
                    Вы покупаете оригинальный парфюм <b>PDPARIS <sup>TM</sup></b>, аромат которого имеет схожие ноты и “пирамиду”, но никак не связан с указанным брендом\дизайнером или производителем.

                </p>
            </div>
            <div class="modal-warning__footer">
                <a href="javascript:void(0)" @click="acceptAnalog(link)" id="modal-warning-agreement" class="modal-warning__btn">
                    Показать каталог
                </a>
                <div class="modal-warning__small-text">
                    Нажимая кнопку «Я понимаю», вы принимаете <a target="_blank" href="/terms.html">Условия использования сайта</a>
                </div>
            </div>
            <div class="modal-warning__exit">
                Не то, что вы искали? <a href="/">ПОКИНУТЬ САЙТ</a>
            </div>

        </div>
    </div>
    <!--Предупреждение конец-->

    <!--Предупреждение пробники-->
    <div class="modal modal__warning modal__warning__samples">
        <div class="modal__wrapper modal-warning">
            <div class="modal__close modal-warning__close"></div>
            <div class="modal-warning__header">
                <h1>Схожі але різні</h1>
                <div class="modal-warning__header-row">

                    <!-- <div class="modal-warning__header-col modal-warning__header-col--left">
                        <div class="modal-warning__header-text">
                            Схожі але різні
                        </div>
                    </div> -->
                    <div class="modal-warning__header-img">
                        <img src="/images/modal-warning_img.png" alt="">
                    </div>
                    <!-- <div class="modal-warning__header-col modal-warning__header-col--right">
                        <div class="modal-warning__header-text">
                            Парфюм<br/>
                            PDPARIS
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="modal-warning__info">
                Звернііть увагу!
            </div>
            <div class="modal-warning__content">
                <p>
                    Зображення не повинно вводити вас в оману!

                </p>
                <p>
                    Ви отримаєте оригінальний парфум <b>PDPARIS <sup>TM</sup></b>, аромат якого має схожу композицію, але ніяк не пов'язаний з зазначеним брендом \ дизайнером або виробником.

                </p>
            </div>
            <div class="modal-warning__footer">
                <a href="javascript:void(0)" @click="acceptSamples()" id="modal-warning-agreement" class="modal-warning__btn">
                    OK
                </a>
                <div class="modal-warning__small-text">
                    Натискаючи кнопку «ОК», ви погоджуєтесь із Інструкціями щодо сайту.
                </div>
            </div>
        </div>
    </div>
    <!--Предупреждение пробники конец-->

</div>


<!--Модалки конец-->

<script src="{{ asset('template_site/js/libs/vue.min.js') }}"></script>

<!-- <script src="/js/libs/vue-suggestion.min.js"></script> -->

<script src="{{ asset('template_site/js/libs/vue-cookies.js') }}"></script>
<script src="{{ asset('template_site/js/libs/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('template_site/js/libs/slick.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script>
<script src="{{ asset('template_site/js/libs/select2.min.js') }}"></script>

<script src="{{ asset('template_site/js/main2.js') }}"></script>

<script async defer crossorigin="anonymous" src="https://connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v6.0"></script>

</body>
</html>

