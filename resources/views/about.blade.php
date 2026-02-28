@extends('layout')

@section('title', 'О нас')

@section('content')
    <section class="about__section">
        <div class="container about__container">
            <h1>О компании МэриФлер</h1>

            <div class="about__content">
                <div class="about__text">
                    <p>МэриФлер - это интернет-магазин цветов и подарков в Уфе. Мы предлагаем широкий ассортимент свежих букетов, композиций и подарков для любых случаев.</p>

                    <p>Наша миссия - дарить людям радость и эмоции через красивые цветы и качественные подарки. Мы работаем только с проверенными поставщиками и гарантируем свежесть каждого букета.</p>

                    <h2>Наши преимущества</h2>
                    <ul>
                        <li>Свежие цветы от лучших поставщиков</li>
                        <li>Быстрая доставка по Уфе</li>
                        <li>Широкий выбор подарков</li>
                        <li>Профессиональные флористы</li>
                    </ul>

                    <h2>Контакты</h2>
                    <div class="contacts">
                        <div class="contact-item">
                            <i class="fa-solid fa-house"></i>
                            <span>Первомайская, 2/30</span>
                        </div>
                        <div class="contact-item">
                            <i class="fa-solid fa-clock"></i>
                            <span>10.00 - 21.00 ч.</span>
                        </div>
                        <div class="contact-item">
                            <i class="fa-solid fa-phone"></i>
                            <a href="tel:+79872425251">+7 (987) 258 72-67</a>
                        </div>
                    </div>
                </div>

                <div class="about__image">
                    <img src="{{ asset('img/logo_bg.png') }}" alt="О нас">
                </div>
            </div>
        </div>
    </section>
@endsection
