$(document).ready(function () {

    // Scroll top line on mobiles
    var scrollTopLine = function() {
      var fromTop = $(window).scrollTop();
      $('.header').toggleClass("scrolled", (fromTop > 10));
      $('.main-menu').toggleClass("scrolled-menu", (fromTop > 10));
    }
    
    scrollTopLine();

    $(window).on("scroll", function() {
      scrollTopLine();
    });

    function initCertificateSlider() {
        if ($('#certificateSlider').length > 0) {
            $('#certificateSlider').slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                swipe: false,
                arrows: false,
                dots: false,
                responsive: [
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                            arrows: true,
                            dots: true,
                            swipe: true,
                        }
                    },
                ]
            });

        } else {
        }
    }

    function initMobileMenu() {
        $(".header__mobile-menu").on("click", function () {
            $(".main-menu").toggleClass("open");
        })
    }

    function closeModal() {
        $('.modal__close').on("click", function () {
            $(this).closest('.modal').removeClass('open');
            $('body').removeClass('hidden');
        })
    }

    function changePromocodeBoxPlaceholder() {
        $(window).resize(function () {
            if ($(window).width() < 768) {
                $('.modal-promocode-box__input').attr("placeholder", "Промокод");
            } else {
                $('.modal-promocode-box__input').attr("placeholder", "Есть промокод?");
            }
        });
    }


    function initInstagramSlider() {
        if ($('.instagram-box__slider').length > 0) {
            $('.instagram-box__slider').slick({
                slidesToShow: 4,
                arrows: true,
                responsive: [
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                            arrows: true,
                            dots: true,
                            swipe: true,
                        }
                    },
                ]
            });

        } else {
        }
    }


    function initSelect() {
        $(".select select").select2({
            "width": "100%",
            minimumResultsForSearch: -1,
            placeholder: 'Фильтровать по...'
        });
    }

    function initSelectLanguage() {
        $(".main-menu__language select").select2({
            minimumResultsForSearch: -1,
            "width": "100%",
        });
    }

    function openBasket() {
        // $('.header-basket').on("click", function () {
        //     $('.modal__cart-promocode').addClass('open');
        //     $('body').addClass('hidden');
        // })
    }

    initMobileMenu();
    closeModal();
    changePromocodeBoxPlaceholder();
    initCertificateSlider();
    initInstagramSlider();
    initSelect();
    initSelectLanguage();
    openBasket();
});

if ($('.vue').length) {
  
   

    Vue.directive('mask', VueMask.VueMaskDirective);

    new Vue({
        el: '.vue',
        data: {
            step: 1,
            link: '',
            gclid: null,
            loading: false,
            email: '',
            analog: false,
            filter: '',
            filter2: '',
            basket: [],

            products: [],
            product: {},
            contact: {
                type: '',
                name: '',
                email: '',
                message: '',
                answer: '',
            },
            order: {
                streetId: '',
                street: '',
                house: '',
                flat: '',
                name: '',
                lastname: '',
                office: '',
                city: '',
                cityId: '',
                comment: '',
                phone: '+38 ',
                postindex: '',
                pay: null,
                kindpay: null,
                promocode: '',
                promocodeAccepted: false,
                promocodeInfo: '',
            },
            
            compare: false,

          

            cities:[],
            citiesFiltered: [],

            offices:[],
            officesFiltered: [],

            streets:[],
            streetsFiltered: [],

            houses:[],
            housesFiltered: [],

            showCities: false,
            showOffices: false,
            showStreets: false,
            showHouses: false,
        },



        mounted: function() {

            this.$cookies.config('30d');

            if ($('.compare').length) {
                this.compare = true;
            }

            if ($('.compare').length && ! this.$cookies.get('analog')) {
                this.openAnalog();
                $('.modal-warning__close').hide();
            }

            this.getSamples();
            
            cookie = JSON.parse(this.$cookies.get('basket'));
            if (cookie) {
                this.basket = cookie;
            }

            this.setPay();

            promocode = this.$cookies.get('promocode');
            if (promocode) {
                this.order.promocode = promocode;
                this.order.promocodeAccepted = true;
                this.order.promocodeInfo = 'Промокод: - 50 грн.';
            }

            this.countTotal();

            if ($('.thanks__inner').length) {

                this.gclid = window.gclid;

                let fcontents = [];

                $.each(this.basketVisible, function (index, product) {
                    fcontents.push({
                        'id': product.art,
                        'quantity': product.qty,
                    });
                });

                fbq('init', '2717113878386159', {
                  'extern_id': this.gclid
                });

                this.facebook('InitiateCheckout', {
                    contents: fcontents,
                    content_category: 'checkout',
                    currency: 'UAH',
                    num_items: fcontents.length,
                    value: this.total
                });

                this.basket = [];
                this.basketVisible = [];
                this.$cookies.remove('basket'); 
                this.$cookies.remove('promocode');
            }
        },

        computed: {

            total: function () {
                var total = 0;
                var that = this;
                $.each(that.basketVisible, function (index, product){
                    total += parseInt(product.price);
                });

                return total;
            }, 

            basketVisible: function () {
                var list = [];
                var that = this;
                $.each(that.basket, function (i, art){
                    $.each(that.products, function (index, product){
                        if (art == product.art100) {
                            list.push({
                                qty:      1,
                                art:      product.art100,
                                price:    product.price,
                                sale:     product.price,
                                vol:      100,
                                bname:    product.bname,
                                name:     product.name,
                                img:      product.img,
                                discount: null,
                                analog:   product.analog,
                                samples:  product.samples 
                            });
                        }
                    });
                });

                return list;
            }
        },

        watch: {
            filter: function (value) {
                this.setFilter();
            },

            filter2: function (value) {
                this.setFilter();
            },
        },

        methods: {

            clearBasker: function () {
                this.basket = [];
                this.basketVisible = [];
            },

            setStep: function (step) {
                var that = this;
                setTimeout(function(){ that.step = step; }, 100);  
            },

            setCity: function (row) {
                this.showCities = false;
                this.order.cityId = row.city_id;
                this.order.city = row.name;
                $('.city-issue').html('');

                if (this.order.pay == 'Отделение') {
                    this.searchOffices('', true);
                }

                if (this.order.pay == 'Курьером') {
                    this.searchStreets();
                }
                
            },

            searchCities: function () {
                var that = this;
                
                if ( ! that.order.city && that.order.city.length < 3) {
                    that.cities = [];
                    that.citiesFiltered = [];
                    that.offices = [];
                    that.officesFiltered = [];
                    that.showCities = false;
                    that.showOffices = false;
                    that.showStreets = false;
                    that.showHouses = false;
                    that.order.cityId = '';
                    that.order.office = '';
                    that.order.street = '';
                    that.order.house = '';
                    return false; 
                }

                if (that.order.city.length >= 3) { 
                    
                    if (that.loading) {
                        return false;
                    }

                    that.loading = true;
                    $.ajax({
                        type: 'POST',
                        url: '/api/cities',
                        data: {
                            keyword: that.order.city
                        },
                        cache: false,
                        success: function(data) {
                            that.order.postindex = '';
                            that.order.strretId = '';
                            that.order.cityId = '';
                            that.order.office = '';
                            that.offices = [];
                            that.officesFiltered = [];
                            that.streets = [];
                            that.streetsFiltered = [];
                            that.houses = [];
                            that.housesFiltered = [];

                            that.showCities = true;
                            that.showOffices = false;
                            that.showStreets = false;
                            that.showHouses = false;

                            that.cities = JSON.parse(data);
                            that.citiesFiltered = JSON.parse(data);
                            
                            // that.loading = false;
                            setTimeout(function(){ that.loading = false; }, 700);  



                        }
                    });
                
                } else {
                    
                    that.citiesFiltered = that.cities.filter(function(item){
                        return item.name.toLowerCase().includes(that.order.city.toLowerCase()) || item.name_ua.toLowerCase().includes(that.order.city.toLowerCase());
                    });
                } 
            },

            setOffice: function (row) {
                this.showOffices = false;
                this.order.postindex = row.POSTINDEX;
                this.order.office = '№' + row.POSTINDEX + ', ' + row.ADDRESS;

                $('.postindex-issue').html('');
            },

            searchOffices: function (office, force = false) {

                var that = this;

                if ( ! that.order.cityId) {
                    return false;
                }


                if (that.offices.length === 0 || force == true) {

                    if (that.loading) {
                        return false;
                    }

                    that.loading = true;

                    $.ajax({
                        type: 'POST',
                        url: '/api/offices',
                        data: {
                            cityId: that.order.cityId
                        },
                        cache: false,
                        success: function(data) {
                            that.order.postindex = '';
                            that.order.office = '';
                            that.offices = JSON.parse(data);
                            that.officesFiltered = JSON.parse(data);
                            that.showOffices = true;
                            that.loading = false;


                        }
                    });
                
                } else {
                    
                    that.officesFiltered = that.offices.filter(function(item){
                        return item.ADDRESS.toLowerCase().includes(that.order.office.toLowerCase()) || item.POSTINDEX.toLowerCase().includes(that.order.office.toLowerCase());
                    });
                } 
            },

            setStreet: function (row) {
                this.showStreets = false;
                this.order.postindex = '';
                this.order.house = '';
                this.order.flat = '';
                this.order.street = row.STREET_UA; 
                this.order.streetId = row.STREET_ID;

                this.searchHouses();
            },

            searchStreets: function () {
                var that = this;

                if (! that.order.street || that.order.street.length < 3) {
                    that.order.house = '';
                    that.houses = [];
                    that.housesFiltered = [];

                    that.order.postindex = '';
                    that.order.streetId = '';
                    that.order.flat = '';
                }

                if (! that.order.cityId) {
                    return false;
                }

                if (that.streets.length === 0) {

                    if (that.loading) {
                        return false;
                    }

                    that.loading = true;
                    

                    $.ajax({
                        type: 'POST',
                        url: '/api/streets',
                        data: {
                            cityId: that.order.cityId
                        },
                        cache: false,
                        success: function(data) {
                            that.streets = JSON.parse(data);
                            that.streetsFiltered = JSON.parse(data);
                            that.order.street = '';
                            that.order.streetId = '';
                            that.order.house = '';
                            that.order.postindex = '';
                            that.showStreets = true;
                            that.loading = false; 

                            that.houses = [];
                            that.housesFiltered = [];
                            that.order.flat = '';
                        }
                    });
                
                } else {
                    
                    that.streetsFiltered = that.streets.filter(function(item){
                        return item.STREET_UA.toLowerCase().includes(that.order.street.toLowerCase());
                    });
                } 
            },

            setHouse: function (row) {
                this.order.house = row.HOUSENUMBER_UA;
                this.order.postindex = row.POSTCODE;
                this.showHouses = false;

                $('.postindex-issue').html('');
            },

            searchHouses: function () {
                var that = this;

                if (that.houses.length === 0) {

                    if (that.loading) {
                        return false;
                    }

                    if ( ! that.order.streetId) {
                        return false;
                    }

                    that.loading = true;
                    $.ajax({
                        type: 'POST',
                        url: '/api/houses',
                        data: {
                            streetId: that.order.streetId
                        },
                        cache: false,
                        success: function(data) {
                            that.houses = JSON.parse(data);
                            that.housesFiltered = JSON.parse(data);
                            that.showHouses = true;
                            that.loading = false; 

                        }
                    });
                
                } else {
                    
                    that.housesFiltered = that.houses.filter(function(item){
                        return item.HOUSENUMBER_UA.toLowerCase().includes(that.order.house.toLowerCase());
                    });
                } 
            },

            


            setPay: function () {
                
            },

            setFilter: function () {
                var that = this;
                $.each(that.products, function (index, product){
                    product.show = 0;

                    if (that.filter && ! that.filter2) {
                        if (product.filter.indexOf(that.filter) !== -1) {
                            product.show = 1;
                        }
                    }

                    if (that.filter2 && ! that.filter) {
                        if (product.filter2.indexOf(that.filter2) !== -1) {
                            product.show = 1;
                        }
                    }

                    if (that.filter && that.filter2) {
                        if (product.filter.indexOf(that.filter) !== -1 && product.filter2.indexOf(that.filter2) !== -1) {
                            product.show = 1;
                        }
                    }

                    if (!that.filter && !that.filter2) {
                        product.show = 1;
                    }
                });
            },

            countTotal: function () {
                var that = this;
                that.total = 0;
                that.basketVisible.sort(function(a, b){return (a.price > b.price) ? 1 : -1});

                var saleCount = 0;
                // saleCount = Math.floor(that.basket.length / 4);

                $.each(that.basketVisible, function (index, product) {
                    product.sale = product.price;
                    product.discount = '';

                    if (index + 1 <= saleCount && ! product.samples) {
                        product.sale = product.price * 0.5;
                        product.discount = '50% скидка!';
                    }
                });

                that.$cookies.set('basket', JSON.stringify(that.basket));
            },

            openAnalog: function (link) {
                this.link = link;
                this.openModal('modal__warning');  
            },

            openSamples: function () {
                this.openModal('modal__warning__samples');  
            },

            hideAnalog: function () {
                $('.bottom-analog').hide();
            },

            acceptPromocode: function () {
                if (this.order.promocode === 'FIRST') {
                    this.$cookies.set('promocode', this.order.promocode);
                    this.order.promocodeAccepted = true;
                    this.order.promocodeInfo = 'Промокод: - 50 грн.';
                    this.countTotal();
                }
            },

            acceptSamples: function () {
                this.closeModal('modal__warning__samples');
                this.getSamples();
            },

            acceptAnalog: function (link) {
                this.$cookies.set('analog', true);
                this.closeModal('modal__warning');  

                gtag('event', 'change', {
                    'event_category': 'analog',
                    'event_label': 'popup'
                }); 
                
                if (this.link) {
                    location.href = this.link + '#cat';
                } else {
                    location.reload();
                }
            },

            sendMessage: function (event) { 
                var that = this;
                event.preventDefault();

                $('select[name="type"]').removeClass('issue');
                $('input[name="name"]').removeClass('issue');
                $('input[name="email"]').removeClass('issue');
                $('textarea[name="message"]').removeClass('issue');

                if ( ! that.contact.name) {
                    $('input[name="name"]').addClass('issue');
                    return false;
                }

                if ( ! that.contact.email) {
                    $('input[name="email"]').addClass('issue');
                    return false;
                }

                if ( ! that.contact.message) {
                    $('textarea[name="message"]').addClass('issue');
                    return false;
                }

                if ( ! that.contact.message) {
                    $('textarea[name="message"]').addClass('issue');
                    return false;
                }

                if ( ! that.contact.type) {
                    $('select[name="type"]').addClass('issue');
                    return false;
                }

                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if ( ! re.test(String(that.contact.email).toLowerCase())) {
                    $('input[name="email"]').addClass('issue');
                    return false;
                } 

                that.loading = true;

                $.ajax({
                    type: 'POST',
                    url: '/api/message',
                    data: {
                        type: that.contact.type,
                        name: that.contact.name,
                        email: that.contact.email,
                        message: that.contact.message
                    },
                    cache: false,
                    success: function(data){
                        that.loading = false;
                        that.contact.type = '';
                        that.contact.name = '';
                        that.contact.email = '';
                        that.contact.message = '';
                        that.contact.answer = 'Спасибо! Ваше сообщение отправлено';
                    }
                });
            },

            subscribe: function (event) { 
                var that = this;
                event.preventDefault();

                $('input[name="subscribe"]').removeClass('issue');

                if ( ! that.email) {
                    $('input[name="subscribe"]').addClass('issue');
                    return false;
                }

                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if ( ! re.test(String(that.email).toLowerCase())) {
                    $('input[name="subscribe"]').addClass('issue');
                    return false;
                } 

                $.ajax({
                    type: 'POST',
                    url: '/api/subscribe',
                    data: {
                        email: that.email,
                    },
                    cache: false,
                    success: function(data){
                        that.email = '';
                        $('input[name="subscribe"]').attr('placeholder', 'Благодарим за подписку');
                    }
                });
            },

            clearIssue: function () {

                $('.postindex-issue').html('');
                $('.city-issue').html('');

                $('input[name="modal-order-phone"]').removeClass('issue');
                $('input[name="name"]').removeClass('issue');
                $('input[name="city"]').removeClass('issue');
                $('input[name="office"]').removeClass('issue');
                $('input[name="street"]').removeClass('issue');
                $('input[name="house"]').removeClass('issue');

                this.cities = [];
                this.citiesFiltered = [];
                this.offices = [];
                this.officesFiltered = [];
                this.houses = [];
                this.housesFiltered = [];
                this.flat = '';

                this.order.city = '';
                this.order.cityId = '';
                this.order.office = '';
                this.order.street = '';
                this.order.streetId = '';
                this.order.house = '';
                this.order.flat = '';
                this.order.postindex = '';

            },

            clearKindPay: function () {
                this.order.kindpay = null; 
                this.order.pay = null; 
                this.clearIssue();
            },

            clearPay: function () {
                this.order.pay = null; 
                this.clearIssue();
            },

            acceptOrder: function (event) {
                
                event.preventDefault();
                
                var that = this;
                var error = false;


                $('.postindex-issue').html('');
                
                $('input[name="modal-order-phone"]').removeClass('issue');
                $('input[name="name"]').removeClass('issue');
                $('input[name="lastname"]').removeClass('issue');
          
                $('input.city').removeClass('issue');
                $('input.office').removeClass('issue');
                $('input.street').removeClass('issue');
                $('input.house').removeClass('issue');

                if ( ! that.order.phone || that.order.phone.replace(/[^0-9]/g,"").length < 12) {   
                    $('input[name="modal-order-phone"]').addClass('issue');
                    error = true;
                }

                if ( that.order.pay == 'Отделение' && ! that.order.name) {   
                    $('input[name="name"]').addClass('issue');
                    error = true;
                }

                if ( that.order.pay == 'Отделение' && ! that.order.city) {   
                    $('input[name="city"]').addClass('issue');
                    $('input.city').addClass('issue');
                    error = true;
                }

                if ( that.order.pay == 'Отделение' && ! that.order.office) {   
                    $('input[name="office"]').addClass('issue');
                    $('input.office').addClass('issue');
                    error = true;
                }

                if ( that.order.pay == 'Курьером' && ! that.order.city) {   
                    $('input[name="city"]').addClass('issue');
                    $('input.city').addClass('issue');
                    error = true;
                }

                if ( that.order.pay == 'Курьером' && ! that.order.street) {   
                    $('input.street').addClass('issue');
                    error = true;
                }

                if ( that.order.pay == 'Курьером' && ! that.order.house) {   
                    $('input.house').addClass('issue');
                    error = true;
                }

                if ( that.order.pay == 'Отделение' || that.order.pay == 'Курьером') {   
                    if ( ! that.order.name) {
                         $('input[name="name"]').addClass('issue');
                         error = true;
                    }

                    if ( ! that.order.lastname) {
                         $('input[name="lastname"]').addClass('issue');
                         error = true;
                    }
                }

                if ( that.order.pay == 'Отделение' && that.order.city && ! that.order.cityId) {
                    $('.city-issue').html('Выберите город из списка');
                    error = true;
                }

                if ( that.order.pay == 'Отделение' && ! that.order.postindex && that.order.cityId) {
                    $('.postindex-issue').html('Выберите Отделение из списка');
                    error = true;
                }

                if ( that.order.pay == 'Курьером' && ! that.order.postindex) {
                    $('.postindex-issue').html('Выберите номер дома из списка');
                    error = true;
                }

                if (error) {
                    return false;
                }

                    that.loading = true;

                    $.ajax({
                        type: 'POST',
                        url:  '/api/store',
                        data: {
                            postindex: that.order.postindex,
                            street: that.order.street,
                            house: that.order.house,
                            flat: that.order.flat,
                            name: that.order.name,
                            lastname: that.order.lastname,
                            tel: that.order.phone,
                            city: that.order.city,
                            office: that.order.office,
                            comment: that.order.comment,
                            pay: that.order.pay,
                            kindpay: that.order.kindpay,
                            promocode: that.order.promocode,
                            basket: JSON.stringify(that.basketVisible),
                        },
                        cache: false,
                        success: function(data) {

                            that.countTotal();

                            that.facebook('Lead', {
                                content_name: 'lead',
                                currency: 'UAH',
                                value: that.total
                            });

                            var gcontents = [];

                            $.each(that.basketVisible, function (index, product) {
                                gcontents.push({
                                    'id': product.art,
                                    'name': product.name,
                                    'variant': product.vol,
                                    'category': 'perfume',
                                    'list_position': index + 1,
                                    'quantity': product.qty,
                                    'price': product.sale,
                                });
                            });

                            gtag('event', 'checkout_progress', {
                                "checkout_step": 3,
                                "items": gcontents,
                                "coupon": that.order.promocode 
                            });
                            
                            gtag('event', 'set_checkout_option', { 
                                "checkout_step": 3 , 
                                "checkout_option": "payment_method", 
                                "value": "offline" 
                            });

                            that.$cookies.remove('basket'); 
                            that.$cookies.remove('promocode');

                            if (that.order.kindpay == 1) { 
                                setTimeout(function(){ location.href="/thanks.html?order=" + data + "&sum=" + that.total }, 500);
                                return true;
                            } 

                        
                            setTimeout(function(){ location.href="/thanks.html?after=1"; }, 500); 
                            
                            
                            // that.loading = false;
                        }
                    });
                
            },

            openModal: function (id) {
                $('.' + id).addClass('open');
                $('body').addClass('hidden');
            },

            closeModal: function (id) {
                $('.' + id).removeClass('open');
                $('body').removeClass('hidden');
            },

            openProduct: function (product, event) {
                this.product = JSON.parse(JSON.stringify(product));
                this.openModal('modal__product');

                this.facebook('ViewContent', {
                    content_ids: product.active50 ? product.art50   : product.art100,
                    content_category: product.man ? 'man' : 'woman',
                    content_name: product.name,
                    content_type: 'product'
                });
            }, 

            setProductVolume: function (product, volume) {
                if (volume == 50) {
                    product.active50 = true;
                    product.active100 = false;
                } else {
                    product.active50 = false;
                    product.active100 = true;
                }
            },

            removeFromCart: function (art) {

                const index = this.basket.indexOf(art);
                if (index > -1) {
                  this.basket.splice(index, 1);
                }

                // this.basket.splice(index, 1);
                this.countTotal();
                this.$cookies.set('basket', JSON.stringify(this.basket));
            },

            addToCart: function (product, event) {

                event.preventDefault();

                this.basket.push(product.art100);

                //this.basket.push({
                    //art:      product.art100
                    // sale:     product.active50 ? product.price50 : product.price100,
                    // vol:      product.active50 ? 50 : 100,
                    // bname:    product.bname,
                    // name:     product.name,
                    // img:      product.img,
                    // discount: null,
                    // analog:   product.analog,
                    // samples:  product.samples 
                //});
                
                this.countTotal();

                // if (this.countSamples() === 3) {
                //     this.openBasket();
                // }

                this.openBasket();

                // this.facebook('AddToCart', {
                //     content_ids: product.active50 ? product.art50   : product.art100,
                //     content_category: product.man ? 'man' : 'woman',
                //     content_name: product.name,
                //     content_type: 'product',
                //     currency: 'UAH',
                //     value: product.price,
                // });

                this.setPay();
            },

            closeBasket: function () {
                this.closeModal('modal__cart-promocode');
            },

            openBasket: function () {
                this.closeModal('modal__product');
                this.openModal('modal__cart-promocode');

                var gcontents = [];
                $.each(this.basketVisible, function (index, product) {
                    gcontents.push({
                        'id': product.art,
                        'name': product.name,
                        'variant': product.vol,
                        'category': 'perfume',
                        'list_position': index + 1,
                        'quantity': product.qty,
                        'price': product.sale,

                    });
                });

                gtag('event', 'begin_checkout', {
                  "items": gcontents
                });
            },

            checkout: function () {
                this.closeModal('modal__cart-promocode');
                this.openModal('modal__order');

                var gcontents = [];
                $.each(this.basketVisible, function (index, product) {
                    gcontents.push({
                        'id': product.art,
                        'name': product.name,
                        'variant': product.vol,
                        'category': 'perfume',
                        'list_position': index + 1,
                        'quantity': product.qty,
                        'price': product.sale,

                    });
                });

                gtag('event', 'checkout_progress', {
                "checkout_step": 2,
                  "items": gcontents
                });

            },

            getProducts: function () {
                var that = this;
                $.get('/api/products', function(data) {
                    that.products = JSON.parse(data);
                });
            },

            getSamples: function () {
                var that = this;
                $.get('/api/samples', function(data) {
                    that.products = JSON.parse(data);
                }); 
            },

            facebook: function (event, attributes) {
                 // console.log(event);
                 // console.log(attributes);
                fbq('track', event, attributes);
            },

            showPromocode: function () {
                $('.modal-promocode-table__footer-col-promo').show();
            },

            showInfo: function () {
                $('.main-menu').removeClass('open');
            },

            hasSamples: function () {
                var sample = false;
              
                this.basketVisible.forEach(function (product) {
                    if (product.samples == 1) {
                        sample = true;
                    }
                });

                return sample;
            },

            hasInBasket: function (art) {
                var has = false;
              
                this.basketVisible.forEach(function (product) {
                    if (product.art == art) {
                        has = true;
                    }
                });

                return has;
            },

            countSamples: function () {
                var count = 0;
              
                this.basketVisible.forEach(function (product) {
                    if (product.samples == 1) {
                        count++;
                    }
                });

                return count;
            }

            
        }
    });
}