const menuToggle = document.getElementById('menu-toggle');
const mobileMenu = document.getElementById('mobile-menu');

if (menuToggle && mobileMenu) {
  menuToggle.addEventListener('click', () => {
    const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!expanded));
    mobileMenu.classList.toggle('open');
    mobileMenu.setAttribute('aria-hidden', String(expanded));
  });

  mobileMenu.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
      menuToggle.setAttribute('aria-expanded', 'false');
      mobileMenu.setAttribute('aria-hidden', 'true');
    });
  });
}

document.addEventListener('click', (event) => {
  const target = event.target;
  if (!mobileMenu || !menuToggle) return;
  if (mobileMenu.contains(target) || menuToggle.contains(target)) return;
  mobileMenu.classList.remove('open');
  menuToggle.setAttribute('aria-expanded', 'false');
  mobileMenu.setAttribute('aria-hidden', 'true');
});

window.addEventListener('scroll', () => {
  document.body.classList.toggle('nav-scrolled', window.scrollY > 24);
});

const heroCarousel = document.getElementById('home-carousel');
if (heroCarousel) {
  const slides = Array.from(heroCarousel.querySelectorAll('.carousel-slide'));
  const controls = heroCarousel.querySelectorAll('.carousel-control');
  let index = 0;

  const updateCarousel = () => {
    slides.forEach((slide, slideIndex) => {
      slide.classList.toggle('active', slideIndex === index);
    });
  };

  const goTo = (nextIndex) => {
    index = (nextIndex + slides.length) % slides.length;
    heroCarousel.querySelector('.carousel-track').style.transform = `translateX(-${index * 100}%)`;
    updateCarousel();
  };

  controls.forEach((button) => {
    button.addEventListener('click', () => {
      const direction = button.classList.contains('next') ? 1 : -1;
      goTo(index + direction);
    });
  });

  setInterval(() => {
    goTo(index + 1);
  }, 5000);

  updateCarousel();
}
