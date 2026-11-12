(function () {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Stats: count up from zero the first time each number scrolls into view
  const counters = document.querySelectorAll('[data-count]');
  const showFinal = (el) => { el.textContent = el.dataset.count + el.dataset.suffix; el.parentElement.classList.add("counted"); };
  if (reducedMotion || !('IntersectionObserver' in window)) {
    counters.forEach(showFinal);
  } else {
    const countObserver = new IntersectionObserver((entries) => entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      countObserver.unobserve(entry.target);
      const el = entry.target;
      el.parentElement.classList.add("counted");
      const target = Number(el.dataset.count);
      const start = performance.now();
      const step = (now) => {
        const t = Math.min((now - start) / 1600, 1);
        el.textContent = Math.round(target * (1 - Math.pow(1 - t, 3))) + el.dataset.suffix;
        if (t < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    }), { threshold: 0.6 });
    counters.forEach((el) => countObserver.observe(el));
  }

  // Before / after: the range input drives where the "before" photo is cut
  document.querySelectorAll('[data-compare]').forEach((frame) => {
    const range = frame.querySelector('input[type="range"]');
    range.addEventListener('input', () => frame.style.setProperty('--pos', range.value + '%'));
  });

  // FAQ: opening one question closes the others
  const faqs = [...document.querySelectorAll('.faq-list details')];
  faqs.forEach((item) => item.addEventListener('toggle', () => {
    if (item.open) faqs.forEach((other) => { if (other !== item) other.open = false; });
  }));

  // Brands: repeat the row once so the marquee loops without a gap
  const brandRow = document.querySelector('.brands-row');
  if (brandRow) [...brandRow.children].forEach((logo) => {
    const copy = logo.cloneNode(true);
    copy.setAttribute('aria-hidden', 'true');
    brandRow.appendChild(copy);
  });

  // Lightbox: the project carousel and case-study galleries open their photos full screen
  const stage = document.querySelector('[data-pcar-stage]');
  const box = document.querySelector('[data-lightbox]');
  if (!box) return;
  const boxImg = box.querySelector('[data-lightbox-img]');
  const boxCaption = box.querySelector('[data-lightbox-caption]');
  const dotsWrap = box.querySelector('[data-lightbox-dots]');
  let photos = [];
  let dots = [];
  let current = 0;
  let opener = null;
  let downX = 0;

  const show = (i) => {
    current = (i + photos.length) % photos.length;
    const photo = photos[current];
    boxImg.src = photo.dataset.src || photo.src;
    boxImg.alt = photo.alt;
    boxCaption.textContent = photo.alt;
    dots.forEach((dot, d) => dot.classList.toggle('is-active', d === current));
  };
  const openWith = (list, index) => {
    photos = list;
    // one dot per photo shows where you are in the set
    dotsWrap.textContent = '';
    dots = photos.map((photo, d) => {
      const dot = document.createElement('button');
      dot.type = 'button';
      dot.setAttribute('aria-label', 'Photo ' + (d + 1) + ' of ' + photos.length);
      dot.addEventListener('click', () => show(d));
      dotsWrap.appendChild(dot);
      return dot;
    });
    opener = document.activeElement;
    box.hidden = false;
    document.body.classList.add('has-lightbox');
    show(index);
    box.querySelector('[data-lightbox-close]').focus();
  };
  const close = () => {
    box.hidden = true;
    document.body.classList.remove('has-lightbox');
    if (opener) opener.focus();
  };

  // a swipe on the carousel changes photo; only a still tap opens the lightbox
  if (stage) {
    stage.addEventListener('pointerdown', (e) => { downX = e.clientX; });
    stage.addEventListener('click', (e) => {
      if (Math.abs(e.clientX - downX) >= 10) return;
      const active = stage.querySelector('img.is-active') || stage.querySelector('img');
      const list = [...stage.querySelectorAll('img')].filter((img) => img.dataset.project === active.dataset.project);
      openWith(list, list.indexOf(active));
    });
  }
  document.querySelectorAll('[data-gallery]').forEach((gallery) => {
    const list = [...gallery.querySelectorAll('img')];
    list.forEach((img, i) => {
      img.tabIndex = 0;
      img.addEventListener('click', () => openWith(list, i));
      img.addEventListener('keydown', (e) => { if (e.key === 'Enter') openWith(list, i); });
    });
  });
  box.querySelector('[data-lightbox-close]').addEventListener('click', close);
  box.querySelector('[data-lightbox-prev]').addEventListener('click', () => show(current - 1));
  box.querySelector('[data-lightbox-next]').addEventListener('click', () => show(current + 1));
  box.addEventListener('click', (e) => { if (e.target === box) close(); });
  document.addEventListener('keydown', (e) => {
    if (box.hidden) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowRight') show(current + 1);
    if (e.key === 'ArrowLeft') show(current - 1);
  });
  // swipe: touch events on phones (pointer events get cancelled when the browser starts a gesture), mouse drag on desktop
  let boxX = null;
  const swipeEnd = (x) => {
    if (boxX !== null && Math.abs(x - boxX) > 40) show(current + (x < boxX ? 1 : -1));
    boxX = null;
  };
  box.addEventListener('touchstart', (e) => { boxX = e.touches[0].clientX; }, { passive: true });
  box.addEventListener('touchend', (e) => swipeEnd(e.changedTouches[0].clientX));
  box.addEventListener('pointerdown', (e) => { if (e.pointerType === 'mouse') boxX = e.clientX; });
  box.addEventListener('pointerup', (e) => { if (e.pointerType === 'mouse') swipeEnd(e.clientX); });
})();
