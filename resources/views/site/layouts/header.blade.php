<header class="header">
    <div class="wrapper">
        <div class="header__inner">
            <div class="header__mobile-menu"></div>
            <a href="/" class="header__logo">
                <!-- <img src="/images/logo.png" alt="logo"> -->
            </a>
            <div class="header-info">
                <a href="/"><img class="mobile-logo"  src="{{ asset('template_site/images/logo.png') }}" alt="logo"></a>
                <!-- <div class="header-info__time">
                    С 9:00 до 18:00
                </div>
                <a href="tel:0800334869" class="header-info__phone">
                    0 800 33 48 69
                </a>
                <div class="header-info__text">
                    Бесплатно по Украине
                </div> -->
            </div>
            <a @click="openBasket()" href="javascript:void(0)" class="header-basket">
                <div class="header-basket__counter">
						<span v-cloak>
							{{-- basket.length --}}
						</span>
                </div>
            </a>
        </div>
    </div>
</header>
