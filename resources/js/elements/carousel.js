import Glide from '@glidejs/glide';

document.addEventListener("DOMContentLoaded", function () {
    let homeSlider = document.querySelector('.slider-homepage');
    if (homeSlider) {
        new Glide('.slider-homepage', {
            type: 'carousel',
            rewind: true,
        }).mount();
    }
});