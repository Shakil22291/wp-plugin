const sliderView = document.querySelector('.myplugin-slider-view > ul'),
  slides = document.querySelectorAll(' .myplugin-slider-view .single-slide'),
  arrowLeft = document.querySelector(
    '.myplugin-slider-view .slider-arrows .arrow-left'
  ),
  arrowRight = document.querySelector(
    '.myplugin-slider-view .slider-arrows .arrow-right'
  ),
  slideLength = slides.length;

const slideMe = (sliderViewItems, isActiveItem, currentItem) => {
  // update the  calasses
  isActiveItem.classList.remove('is-active');
  sliderViewItems.classList.add('is-active');

  // css transform the active slide position
  sliderView.setAttribute(
    'style',
    `transform:translateX(-${sliderViewItems.offsetLeft}px)`
  );
};

const beforeSliding = (i) => {
  let isActiveItem = document.querySelector('.single-slide.is-active'),
    currentItem = Array.from(slides).indexOf(isActiveItem) + i,
    nextItem = currentItem + i,
    sliderViewItems = document.querySelector(
      '.single-slide:nth-child(' + nextItem + ')'
    );

  if (nextItem > slideLength) {
    sliderViewItems = document.querySelector('.single-slide:nth-child(1)');
  }

  if (nextItem === 0) {
    sliderViewItems = document.querySelector('.single-slide:nth-child(1)');
  }

  slideMe(sliderViewItems, isActiveItem, currentItem);
};

arrowRight.addEventListener('click', () => beforeSliding(1));
arrowLeft.addEventListener('click', () => beforeSliding(0));
