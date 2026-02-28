document.addEventListener('DOMContentLoaded', function() {
    let lastScrollTop = 0;
    const header = document.querySelector('.header');
    const delta = 5;
    const headerHeight = header.offsetHeight;

    window.addEventListener('scroll', function () {
        const st = window.pageYOffset || document.documentElement.scrollTop;

        if (Math.abs(lastScrollTop - st) <= delta) return;

        if (st > lastScrollTop && st > headerHeight) {
            // Скрываем header
            header.classList.add('header-hidden');
        } else {
            // Если прокручиваем вверх, показываем header
            if (st + window.innerHeight < document.body.scrollHeight) {
                header.classList.remove('header-hidden');
            }
        }

        lastScrollTop = st;
    });


    // Получаем элементы
    const burgerMenu = document.getElementById('header__menu');
    const headerUl = document.querySelector('.header__ul');

    // Создаем элемент overlay для затемнения фона
    const overlay = document.createElement('div');
    overlay.classList.add('overlay');
    document.body.appendChild(overlay);

    // Обработчик клика на бургер-меню
    burgerMenu.addEventListener('click', function() {
        // Переключаем классы active
        burgerMenu.classList.toggle('active');
        headerUl.classList.toggle('active');
        overlay.classList.toggle('active');

        // Блокируем прокрутку страницы при открытом меню
        if (headerUl.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    });

    // Обработчик клика на overlay
    overlay.addEventListener('click', function() {
        // Убираем классы active
        burgerMenu.classList.remove('active');
        headerUl.classList.remove('active');
        overlay.classList.remove('active');

        // Возвращаем прокрутку страницы
        document.body.style.overflow = '';
    });

    // Закрываем меню при клике на пункт меню
    const menuLinks = document.querySelectorAll('.header__ul a');
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Убираем классы active
            burgerMenu.classList.remove('active');
            headerUl.classList.remove('active');
            overlay.classList.remove('active');

            // Возвращаем прокрутку страницы
            document.body.style.overflow = '';
        });
    });

    // Закрываем меню при изменении размера окна
    window.addEventListener('resize', function() {
        // Если ширина экрана больше 768px и меню открыто, закрываем его
        if (window.innerWidth > 480 && headerUl.classList.contains('active')) {
            burgerMenu.classList.remove('active');
            headerUl.classList.remove('active');
            overlay.classList.remove('active');

            // Возвращаем прокрутку страницы
            document.body.style.overflow = '';
        }
    });


    const phoneInputs = document.querySelectorAll('input[type="tel"]');

    phoneInputs.forEach(input => {
        IMask(input, {
            mask: '+{7} (000) 000 00-00',
            lazy: false,
            placeholderChar: '_'
        });
    });
});
