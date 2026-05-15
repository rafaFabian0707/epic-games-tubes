import './bootstrap';
import '../css/app.css';

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

document.addEventListener('DOMContentLoaded', () => {

    const swiperElement = document.querySelector('.mySwiper');
    const gameSwiperElement = document.querySelector('.gameSwiper');
    const gameSwiperElement2 = document.querySelector('.gameSwiper2');
    const popularSwiper = document.querySelector('.popularSwiper');


    // Hero Swiper
    if (swiperElement) {
        new Swiper(swiperElement, {
            loop: true,

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }


    // Game Swiper 1
    if (gameSwiperElement) {
        new Swiper(gameSwiperElement, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,

            navigation: {
                nextEl: '.swiper-btn-next',
                prevEl: '.swiper-btn-prev',
            },
        });
    }


    // Game Swiper 2
    if (gameSwiperElement2) {
        new Swiper(gameSwiperElement2, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,

            navigation: {
                nextEl: '.swiper2-btn-next',
                prevEl: '.swiper2-btn-prev',
            },
        });
    }


    // Popular Swiper
    if (popularSwiper) {
        new Swiper(popularSwiper, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,

            navigation: {
                nextEl: '.popular-btn-next',
                prevEl: '.popular-btn-prev',
            },
        });
    }

});