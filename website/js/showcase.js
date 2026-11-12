// Showcase sections: team entrance and phone dots, philosophy arches, footer sketch
(() => {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ---------- Team: people rise in when the section is reached ----------
  const crew = document.querySelector('.crew');
  const stage = document.querySelector('[data-crew]');
  if (crew && stage) {
    new IntersectionObserver(([entry], obs) => {
      if (!entry.isIntersecting) return;
      crew.classList.add('crew-in');
      obs.disconnect();
    }, { threshold: 0.15 }).observe(crew);

    // phones: dot for the person closest to the centre of the row
    const members = [...stage.querySelectorAll('.crew-member')];
    const dotsWrap = document.querySelector('[data-crew-dots]');
    const dots = members.map(() => dotsWrap.appendChild(document.createElement('i')));
    const syncDots = () => {
      const row = stage.getBoundingClientRect();
      const centre = row.left + row.width / 2;
      let best = 0;
      let bestGap = Infinity;
      members.forEach((m, k) => {
        const box = m.getBoundingClientRect();
        const gap = Math.abs(box.left + box.width / 2 - centre);
        if (gap < bestGap) { bestGap = gap; best = k; }
      });
      dots.forEach((d, k) => d.classList.toggle('is-active', k === best));
    };
    stage.addEventListener('scroll', syncDots, { passive: true });
    syncDots();
  }

  // reveal once, when a fifth of the element is on screen
  const revealOnce = (el, cls, threshold = 0.2) => {
    new IntersectionObserver(([entry], obs) => {
      if (!entry.isIntersecting) return;
      el.classList.add(cls);
      obs.disconnect();
    }, { threshold }).observe(el);
  };

  // ---------- Philosophy arches: windows open upward, photos drift inside them ----------
  const arches = document.querySelector('[data-arches]');
  if (arches) {
    revealOnce(arches, 'is-in', 0.25);
    const imgs = [...arches.querySelectorAll('.arch-frame img')];
    if (!reducedMotion) {
      let ticking = false;
      const drift = () => {
        ticking = false;
        const box = arches.getBoundingClientRect();
        if (box.bottom < 0 || box.top > innerHeight) return;
        const progress = (box.top + box.height / 2 - innerHeight / 2) / innerHeight; // about -1 … 1
        imgs.forEach((img, i) => img.style.setProperty('--py', `${(progress * (i % 2 ? -18 : 26)).toFixed(1)}px`));
      };
      addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(drift); } }, { passive: true });
      drift();
    }

    // phones: one arch stands tall in the middle; when the next one arrives the previous shrinks to the side
    const items = [...arches.querySelectorAll('.arch')];
    const phone = window.matchMedia('(max-width: 800px)');
    const positions = ['pos-mid', 'pos-prev', 'pos-next', 'pos-out-left', 'pos-out-right'];
    let current = Math.floor(items.length / 2);
    let inView = false;
    let lastMove = 0;
    const place = () => {
      items.forEach((item, i) => {
        item.classList.remove(...positions);
        if (!phone.matches) return;
        let offset = i - current; // shortest way round the loop
        if (offset > items.length / 2) offset -= items.length;
        if (offset < -items.length / 2) offset += items.length;
        item.classList.add(offset === 0 ? 'pos-mid' : offset === -1 ? 'pos-prev' : offset === 1 ? 'pos-next' : offset < 0 ? 'pos-out-left' : 'pos-out-right');
      });
    };
    const go = (step) => { current = (current + step + items.length) % items.length; lastMove = Date.now(); place(); };
    place();
    phone.addEventListener('change', place);
    new IntersectionObserver(([entry]) => { inView = entry.isIntersecting; }, { threshold: 0.3 }).observe(arches);
    if (!reducedMotion) {
      setInterval(() => { if (phone.matches && inView && !document.hidden && Date.now() - lastMove > 2600) go(1); }, 2800);
    }
    // swipe left/right to move by hand
    let startX = null;
    arches.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; }, { passive: true });
    arches.addEventListener('touchend', (e) => {
      if (startX === null || !phone.matches) return;
      const dx = e.changedTouches[0].clientX - startX;
      startX = null;
      if (Math.abs(dx) > 40) go(dx < 0 ? 1 : -1);
    });
  }

  // ---------- Footer sketch: the lamp switches on while the cursor is anywhere over it (tap on phones) ----------
  const sketch = document.querySelector('.footer-sketch');
  if (sketch) {
    sketch.addEventListener('mouseenter', () => sketch.classList.add('lamp-on'));
    sketch.addEventListener('mouseleave', () => sketch.classList.remove('lamp-on'));
    sketch.addEventListener('click', () => { if (window.matchMedia('(hover: none)').matches) sketch.classList.toggle('lamp-on'); });
  }
})();
