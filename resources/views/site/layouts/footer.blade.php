<section id="footer" class="footer">
    <div class="footer-menu">
        <div class="wrapper">
            <div class="footer-menu__inner">
                <ul class="footer-menu__list">
                    <!-- <li class="footer-menu__item">
                        <a href="/contact.html" class="footer-menu__link">
                            Контакты
                        </a>
                    </li> -->
                    <!-- <li class="footer-menu__item">
                        <a href="/delivery.html" class="footer-menu__link">
                            Доставка и оплата
                        </a>
                    </li> -->
                    <li class="footer-menu__item">
                        <a href="{{ route('site.policy') }}" class="footer-menu__link">
                            Конфиденциальность
                        </a>
                    </li>
                    <li class="footer-menu__item">
                        <a href="{{ route('site.terms') }}" class="footer-menu__link">
                            Условия использования
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-info">
        <div class="wrapper">
            <div class="footer-info__inner">
                <!-- 	<div class="footer-info__button">
                        горячая линия
                    </div>
                    <a href="tel:0800334869" class="footer-info__phone">
                        0 800 33 48 69
                    </a> -->
                <div class="footer-info__time">
                    <!-- 	c <b>9:00</b> до <b>21:00</b> <span>• БЕСПЛАТНО ПО УКРАИНЕ</span>
                        <br/>
                        <br/> -->
                </div>
                <div class="footer-description">
                    <!-- <div class="footer-description__text">
                        ПОДПИСКА НА СПЕЦИАЛЬНОЕ ПРЕДЛОЖЕНИЕ <b>PDPARIS</b>
                    </div>
                    <form class="footer-description__wrapper">
                        <input v-model="email" name="subscribe" class="footer-description__input" placeholder="Введите Ваш е-мейл">
                        <button @click="subscribe($event)" type="submit" class="footer-description__button"></button>
                    </form> -->

                    <br/>
                    <br/>

                    <div class="footer-description__text" style="text-align:center;">ООО "ИЗИМАРКЕТ" ИНН 42026686<br/>ул Криворожская 24-А,кв142</div>
                </div>




                <div class="footer-socials">
                    <div class="footer-socials__col">
                        <!-- <a target="_blank" href="https://www.facebook.com/PD-Paris-%D0%A3%D0%BA%D1%80%D0%B0%D0%B8%D0%BD%D0%B0-104450154563865/" class="footer-socials__icon footer-socials__icon--facebook">
                            <img src="/images/svg/sprite.svg#facebook" alt="facebook">
                        </a> -->
                    </div>
                    <div class="footer-socials__col">
                        <!-- <a target="_blank" href="https://twitter.com" class="footer-socials__icon footer-socials__icon--twitter">
                            <img src="/images/svg/sprite.svg#twitter" alt="twitter">
                        </a> -->
                    </div>
                    <div target="_blank" class="footer-socials__col">

                        <a target="_blank" href="https://www.instagram.com/pd_paris/" class="footer-socials__icon footer-socials__icon--instagram">
                            <img src="{{ asset('template_site/images/svg/sprite.svg#instagram-white') }}" alt="instagram">
                        </a>
                    </div>
                    <div target="_blank" class="footer-socials__col">
                        <!-- <a target="_blank" href="https://youtube.com" class="footer-socials__icon footer-socials__icon--youtube">
                            <img src="/images/svg/sprite.svg#youtube" alt="youtube">
                        </a> -->
                    </div>
                    <div target="_blank" class="footer-socials__col">
                        <!-- <a target="_blank" href="https://vk.com" class="footer-socials__icon footer-socials__icon--vk">
                            <img src="/images/svg/sprite.svg#vk" alt="vk">
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
