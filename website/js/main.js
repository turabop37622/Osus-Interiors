(function () {
  const root = document.documentElement;
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Split the hero headline into masked words for the reveal animation
  document.querySelectorAll('[data-word-reveal]').forEach((heading) => {
    const text = heading.textContent.trim();
    const wrapper = document.createElement('span');
    wrapper.className = 'word-reveal';
    wrapper.setAttribute('aria-label', text);
    text.split(/\s+/).forEach((word, i) => {
      const mask = document.createElement('span');
      mask.className = 'word-mask';
      mask.setAttribute('aria-hidden', 'true');
      const inner = document.createElement('span');
      inner.style.animationDelay = 150 + i * 65 + 'ms';
      inner.textContent = word + ' ';
      mask.appendChild(inner);
      wrapper.appendChild(mask);
    });
    heading.textContent = '';
    heading.appendChild(wrapper);
  });

  // Split scroll-fill headings into words; their opacity follows scroll position
  const fillHeadings = [...document.querySelectorAll('[data-scroll-fill]')].map((heading) => {
    const words = heading.textContent.trim().split(/\s+/);
    heading.setAttribute('aria-label', words.join(' '));
    heading.innerHTML = words
      .map((word) => '<span class="fill-word" aria-hidden="true">' + word + '</span>')
      .join(' ');
    return { el: heading, words: [...heading.querySelectorAll('.fill-word')] };
  });

  function updateScrollFill() {
    const vh = window.innerHeight;
    fillHeadings.forEach(({ el, words }) => {
      const box = el.getBoundingClientRect();
      // 0 when the heading enters the lower part of the screen, 1 once it reaches the upper third
      const start = vh * 0.85;
      const end = vh * 0.3;
      const progress = Math.min(Math.max((start - box.top) / (start - end + box.height * 0.5), 0), 1);
      const lit = progress * words.length;
      words.forEach((word, i) => {
        word.style.opacity = (0.14 + 0.86 * Math.min(Math.max(lit - i, 0), 1)).toFixed(3);
      });
    });
  }

  // Mobile menu
  const menuButton = document.querySelector('.menu-button');
  const nav = document.querySelector('.nav-links');
  if (menuButton && nav) {
    const setOpen = (open) => {
      nav.classList.toggle('open', open);
      menuButton.setAttribute('aria-expanded', String(open));
      menuButton.textContent = open ? 'Close' : 'Menu';
    };
    menuButton.addEventListener('click', () => setOpen(!nav.classList.contains('open')));
    nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
  }

  // Reveal sections as they enter the viewport
  const revealItems = document.querySelectorAll('.scroll-reveal');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    revealItems.forEach((el) => observer.observe(el));
  } else {
    revealItems.forEach((el) => el.classList.add('is-visible'));
  }

  // Highlight the nav link of the section in view
  const navLinks = [...document.querySelectorAll('.nav-links a')];
  const sections = navLinks
    .map((link) => document.querySelector(link.getAttribute('href')))
    .filter(Boolean)
    .sort((a, b) => a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1);

  // Scroll progress bar + image parallax
  const parallaxImages = document.querySelectorAll('.hero-image img, .project-image img');
  let ticking = false;

  function onScroll() {
    const max = root.scrollHeight - window.innerHeight;
    root.style.setProperty('--scroll-progress', max > 0 ? (window.scrollY / max).toFixed(4) : 0);
    updateScrollFill();

    if (!reducedMotion) {
      const vh = window.innerHeight;
      parallaxImages.forEach((img) => {
        const box = img.parentElement.getBoundingClientRect();
        if (box.bottom < 0 || box.top > vh) return;
        const offset = ((box.top + box.height / 2) - vh / 2) / vh; // -1 .. 1
        img.style.setProperty('--parallax', (offset * -40).toFixed(1) + 'px');
      });
    }

    let current = sections[0];
    sections.forEach((section) => {
      if (section.getBoundingClientRect().top <= 140) current = section;
    });
    navLinks.forEach((link) => {
      if (current && link.getAttribute('href') === '#' + current.id) link.setAttribute('aria-current', 'page');
      else link.removeAttribute('aria-current');
    });

    ticking = false;
  }

  window.addEventListener('scroll', () => {
    if (!ticking) {
      ticking = true;
      requestAnimationFrame(onScroll);
    }
  }, { passive: true });
  window.addEventListener('resize', onScroll);
  onScroll();

  // Video reel: 3D coverflow carousel, the centre video plays and advances when it ends
  const reel = document.querySelector('.reel');
  if (reel) {
    const stage = reel.querySelector('[data-reel-stage]');
    const cards = [...stage.querySelectorAll('.reel-card')];
    const videos = cards.map((card) => card.querySelector('video'));
    const ambient = reel.querySelector('.reel-ambient');
    const caption = reel.querySelector('.reel-caption');
    const titleEl = reel.querySelector('[data-reel-title]');
    const metaEl = reel.querySelector('[data-reel-meta]');
    const currentEl = reel.querySelector('[data-reel-current]');
    const cursor = stage.querySelector('.reel-cursor');
    const progress = reel.querySelector('[data-reel-progress]');
    const pad = (n) => String(n).padStart(2, '0');
    const total = cards.length;
    let active = 0;
    let inView = false;

    reel.querySelector('[data-reel-total]').textContent = pad(total);

    const bars = cards.map((card, i) => {
      const bar = document.createElement('button');
      bar.type = 'button';
      bar.setAttribute('aria-label', 'Play ' + card.dataset.title);
      bar.appendChild(document.createElement('span'));
      bar.addEventListener('click', () => goTo(i));
      progress.appendChild(bar);
      return bar;
    });

    videos.forEach((video, i) => {
      video.loop = false;
      video.addEventListener('ended', () => { if (i === active) goTo(active + 1); });
      video.addEventListener('timeupdate', () => {
        if (i === active && video.duration) bars[i].style.setProperty('--p', (video.currentTime / video.duration).toFixed(3));
      });
    });

    function layout() {
      const cardWidth = cards[0].offsetWidth;
      const narrow = window.innerWidth <= 800;
      cards.forEach((card, i) => {
        // shortest signed distance from the active card, so the ring wraps around
        let offset = (i - active + total) % total;
        if (offset > total / 2) offset -= total;
        const dist = Math.abs(offset);
        const x = offset * cardWidth * (narrow ? 0.72 : 0.86);
        const z = -dist * (narrow ? 160 : 240);
        const rotate = offset * (narrow ? -18 : -26);
        const scale = 1 - dist * 0.06;
        card.style.transform =
          'translateX(-50%) translateX(' + x + 'px) translateZ(' + z + 'px) rotateY(' + rotate + 'deg) scale(' + scale + ')';
        card.style.zIndex = String(10 - dist);
        card.style.opacity = dist > 2 ? '0' : '1';
        card.style.pointerEvents = dist > 2 ? 'none' : '';
        card.classList.toggle('is-active', offset === 0);
        card.setAttribute('aria-hidden', String(offset !== 0));
      });
    }

    function playActive() {
      videos.forEach((video, i) => {
        if (i === active && inView && !reducedMotion) {
          video.preload = 'auto';
          const attempt = video.play();
          if (attempt) attempt.catch(() => {});
        } else {
          video.pause();
        }
      });
      // warm up the next clip so the switch is seamless
      videos[(active + 1) % total].preload = 'auto';
    }

    function goTo(index) {
      const next = (index + total) % total;
      if (next !== active) {
        videos[active].pause();
        bars[active].style.setProperty('--p', 0);
        videos[next].currentTime = 0;
      }
      active = next;
      bars.forEach((bar, i) => { if (i < active) bar.style.setProperty('--p', 1); else if (i > active) bar.style.setProperty('--p', 0); });

      const card = cards[active];
      currentEl.textContent = pad(active + 1);
      titleEl.textContent = card.dataset.title;
      metaEl.textContent = card.dataset.meta;
      caption.classList.remove('is-changing');
      void caption.offsetWidth; // restart the caption animation
      caption.classList.add('is-changing');

      ambient.classList.add('is-swapping');
      setTimeout(() => {
        ambient.style.backgroundImage = 'url("' + videos[active].getAttribute('poster') + '")';
        ambient.classList.remove('is-swapping');
      }, 350);

      layout();
      playActive();
    }

    reel.querySelector('[data-reel-prev]').addEventListener('click', () => goTo(active - 1));
    reel.querySelector('[data-reel-next]').addEventListener('click', () => goTo(active + 1));
    cards.forEach((card, i) => card.addEventListener('click', () => { if (!dragged && i !== active) goTo(i); }));

    // Drag / swipe
    let startX = null;
    let dragged = false;
    stage.addEventListener('pointerdown', (e) => {
      startX = e.clientX;
      dragged = false;
      stage.classList.add('is-dragging');
    });
    window.addEventListener('pointerup', (e) => {
      if (startX === null) return;
      const dx = e.clientX - startX;
      if (Math.abs(dx) > 50) {
        dragged = true;
        goTo(active + (dx < 0 ? 1 : -1));
      }
      startX = null;
      stage.classList.remove('is-dragging');
    });

    // Custom "Drag" cursor
    stage.addEventListener('pointermove', (e) => {
      const box = stage.getBoundingClientRect();
      cursor.style.setProperty('--cx', e.clientX - box.left + 'px');
      cursor.style.setProperty('--cy', e.clientY - box.top + 'px');
    });

    stage.tabIndex = 0;
    stage.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowRight') goTo(active + 1);
      if (e.key === 'ArrowLeft') goTo(active - 1);
    });

    // Start buffering the first clip before the section scrolls into view
    new IntersectionObserver(([entry], observer) => {
      if (!entry.isIntersecting) return;
      videos[active].preload = 'auto';
      observer.disconnect();
    }, { rootMargin: '800px 0px' }).observe(stage);

    // Only play while the section is on screen
    new IntersectionObserver(([entry]) => {
      inView = entry.isIntersecting;
      playActive();
    }, { threshold: 0.25 }).observe(stage);

    window.addEventListener('resize', layout);
    goTo(0);
  }

  // Current year in footer
  document.querySelectorAll('[data-year]').forEach((el) => { el.textContent = new Date().getFullYear(); });
})();
