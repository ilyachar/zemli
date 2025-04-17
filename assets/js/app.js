'use strict';

const html = $('html');
const body = $('body');

const app = {
    init: function () {		
        app.linkSidebar();
        app.toggleSidebar();
        app.swiperSlider();
        app.maskPhone();
        app.whatEver();
        app.wave();
        app.clientYm();
        app.formSubmit(); // Добавляем новую функцию для отправки форм
    },
    clientYm: function() {
        let ymID;
        let ymNumber = '100734088';
        
        if (window.ym) {
            ym(ymNumber, 'getClientID', function (clientID) {
                ymID = clientID;
                console.log('clientID - ' + ymID); 
                $(".clientid").val(ymID);
            });
        } else {
            return setTimeout(function () {
                app.clientYm();
            }, 100);
        }
    },
    wave: function() {
        if ($('div').is('.player-2')) {		    
            var is_mobile = false;		   
            if ($('.player-2').css('display') == 'none') {
                is_mobile = true;
            }
            if (is_mobile == true) {
                $('#video2').addClass('video-image__mobile');
            } else {
                $(".player-2").YTPlayer();
            }
        }
        
        /*$(".wrap_snow").letItSnow();*/
        
        let dateCountdown = new Date(2024, 6, 6); 
        $('.countdown').countdown({until: dateCountdown}); 
    
        let wtw1 = $('.wtw-1').wavify({
            height: 74,
            bones: 4,
            amplitude: 37,
            color: 'rgba(255, 255, 255, .5)',
            speed: .15
        });

        let wtw2 = $('.wtw-2').wavify({
            height: 90,
            bones: 4,
            amplitude: 45,
            color: 'rgba(255, 255, 255, 1)',
            speed: .15
        });

        let wbw1 = $('.wbw-1').wavify({
            height: 74,
            bones: 5,
            amplitude: 37,
            color: 'rgba(255, 255, 255, .5)',
            speed: .1
        });

        let wbw2 = $('.wbw-2').wavify({
            height: 90,
            bones: 5,
            amplitude: 45,
            color: 'rgba(255, 255, 255, 1)',
            speed: .1
        });

        let wtp1 = $('.wtp-1').wavify({
            height: 74,
            bones: 4,
            amplitude: 37,
            color: 'rgba(239, 247, 237, .5)',
            speed: .15
        });

        let wtp2 = $('.wtp-2').wavify({
            height: 90,
            bones: 4,
            amplitude: 45,
            color: 'rgba(239, 247, 237, 1)',
            speed: .15
        });

        let wbp1 = $('.wbp-1').wavify({
            height: 74,
            bones: 5,
            amplitude: 37,
            color: 'rgba(239, 247, 237, .5)',
            speed: .1
        });

        let wbp2 = $('.wbp-2').wavify({
            height: 90,
            bones: 5,
            amplitude: 45,
            color: 'rgba(239, 247, 237, 1)',
            speed: .1
        });
    },
    linkSidebar: function() {
        const link = $('a, .btn');
        const offcanvas = $('#offcanvasSidebar');
        const bsOffcanvas = new bootstrap.Offcanvas(offcanvas);

        link.each(function () {
            $(this).on('click', function (event) {
                bsOffcanvas.hide();
            });
        });
    },
    stickyNav: function () {
        const navbar = $(".header");
        if (navbar == null) return;
        const options = {
            offset: 350,
            offsetSide: 'top',
            classes: {
                clone: 'navbar-clone',
                stick: 'navbar-stick',
                unstick: 'navbar-unstick',
            }
        };
        const stickyWrap = new Headhesive('.header', options);
    },
    toggleSidebar: function() {
        let sidebar = $('#offcanvasSidebar');		
        let button = $('.btn-toggle');
        let buttonClass = 'btn-toggle_close';
        sidebar.on('show.bs.offcanvas', function (event) {
            button.addClass(buttonClass);
        });
        sidebar.on('hide.bs.offcanvas', function (event) {
            button.removeClass(buttonClass);
        });		
    },
    swiperSlider: function() {
        $('[data-fancybox^="gallery"]').fancybox({
            backFocus: false,
            loop: false,
        });
        
        let swiperHouse = new Swiper('.swiper-house', {
            slidesPerView: 1,
            spaceBetween: 0,
            grabCursor: false,
            pagination: {
                el: ".swiper-pagination"
            }
        });

        let swiperCard = new Swiper('.swiper-card', {
            slidesPerView: "auto",
            spaceBetween: 16,
            grabCursor: false,
            pagination: {
                el: ".swiper-pagination"
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                    slidesPerGroup: 2
                },
                992: {
                    slidesPerView: 3,
                    slidesPerGroup: 3
                }
            }
        });
        let swiperGallery = new Swiper('.swiper-gallery', {
            slidesPerView: "auto",
            slidesPerGroup: 1,
            spaceBetween: 16,
            grabCursor: false,
            pagination: {
                el: ".swiper-pagination"
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                    slidesPerGroup: 2
                },
                992: {
                    slidesPerView: 3,
                    slidesPerGroup: 3
                },
                1400: {
                    slidesPerView: 4,
                    slidesPerGroup: 4,
                    spaceBetween: 16,
                }
            }
        });
    },
    whatEver: function () {	
        $('.modal').on('show.bs.modal', function (event) {
            let modal = $(this);
            let button = $(event.relatedTarget);	
            let modalTitle = button.data('title');
            let modalSource = button.data('source');
            let modalInfo = button.data('text');
            let modalBtn = button.data('btn');	
            if (modalSource) {				
                modal.find('[name="source"]').val(modalSource);
            } else if (modalTitle) {
                modal.find('[name="source"]').val(modalTitle);
            }
            modal.find('.modal__title').html(modalTitle);
            modal.find('.modal__text').html(modalInfo);
            modal.find('.modal__btn').html(modalBtn);
        });
    },
    maskPhone: function () {	
        $('[name="phone"]').mask('+7 (999) 999-99-99'); // Обновил маску для соответствия российскому формату
    },
    formSubmit: function () {
        $('form').submit(function (e) {
            e.preventDefault();
            let form = $(this);
            let btn = form.find('.btn-submit');
            let content = form.find('.form-content');
            let loader = form.find('.form-loader');
            let error = form.find('.form-error');
            let thanks = form.find('.form-thanks');
            let source = btn.data('source'); // Получаем data-source с кнопки
            if (source) {
                form.find('[name="source"]').val(source); // Записываем в поле source
            }
            let data = form.serialize();
            let required = form.find('.required');
            let checkbox = form.find('.required-check input');
            let valid = true;

            // Проверка обязательных полей
            required.each(function () {
                if ($(this).val() === '') {
                    $(this).addClass('invalid');
                    valid = false;
                } else {
                    $(this).removeClass('invalid');
                }
            });

            // Проверка чекбокса
            if (!checkbox.is(':checked')) {
                form.find('.required-check').addClass('invalid');
                valid = false;
            } else {
                form.find('.required-check').removeClass('invalid');
            }

            if (valid && !btn.hasClass('btn-disabled')) {
                btn.addClass('btn-disabled');
                content.hide();
                loader.show();

                $.ajax({
                    type: "POST",
                    url: "/send_to_telegram.php", // Путь к вашему PHP-файлу
                    data: data,
                    success: function (response) {
                        let jsonData = JSON.parse(response);
                        loader.hide();
                        if (jsonData.status === 'success') {
                            thanks.show();
                            ym(100734088, 'reachGoal', 'form'); // Отправка цели в Яндекс.Метрику
                            setTimeout(function () {
                                thanks.hide();
                                content.show();
                                form[0].reset();
                                btn.removeClass('btn-disabled');
                            }, 2000);
                        } else {
                            error.show();
                            setTimeout(function () {
                                error.hide();
                                content.show();
                                btn.removeClass('btn-disabled');
                            }, 2000);
                        }
                    },
                    error: function () {
                        loader.hide();
                        error.show();
                        setTimeout(function () {
                            error.hide();
                            content.show();
                            btn.removeClass('btn-disabled');
                        }, 2000);
                    }
                });
            }
        });

        // Активация/деактивация кнопки в зависимости от заполненности
        $('form').each(function () {
            let form = $(this);
            let btn = form.find('.btn-submit');
            setInterval(function () {
                let required = form.find('.required');
                let checkbox = form.find('.required-check input');
                let allFilled = true;
                required.each(function () {
                    if ($(this).val() === '') {
                        allFilled = false;
                    }
                });
                if (!checkbox.is(':checked')) {
                    allFilled = false;
                }
                if (allFilled) {
                    btn.removeClass('btn-disabled');
                } else {
                    btn.addClass('btn-disabled');
                }
            }, 500);
        });
    }
}

app.init();