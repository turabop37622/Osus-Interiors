(function () {
  const root = document.documentElement;
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Split the hero headline into masked words for the reveal animation; a <br> stays a line break
  document.querySelectorAll('[data-word-reveal]').forEach((heading) => {
    const lines = heading.innerHTML.split(/<br\s*\/?>/i).map((line) => line.trim().split(/\s+/));
    const wrapper = document.createElement('span');
    wrapper.className = 'word-reveal';
    wrapper.setAttribute('aria-label', lines.map((words) => words.join(' ')).join(' '));
    let i = 0;
    lines.forEach((words, l) => {
      if (l > 0) wrapper.appendChild(document.createElement('br'));
      words.forEach((word) => {
        const mask = document.createElement('span');
        mask.className = 'word-mask';
        mask.setAttribute('aria-hidden', 'true');
        const inner = document.createElement('span');
        inner.style.animationDelay = 150 + i++ * 65 + 'ms';
        inner.innerHTML = word;
        mask.appendChild(inner);
        wrapper.appendChild(mask);
        // the space sits between masks; inside an inline-block it would collapse
        wrapper.appendChild(document.createTextNode(' '));
      });
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
  // only in-page anchors; a WordPress menu may also link to other pages
  const navLinks = [...document.querySelectorAll('.nav-links a')].filter((link) => /^#[\w-]+$/.test(link.getAttribute('href')));
  const sections = navLinks
    .map((link) => document.querySelector(link.getAttribute('href')))
    .filter(Boolean)
    .sort((a, b) => a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1);

  // Scroll progress bar + image parallax
  const parallaxImages = document.querySelectorAll('.hero-image img, .project-image img');
  const detailFrames = document.querySelectorAll('.detail-card .project-image');
  const header = document.querySelector('.site-header');
  let lastScrollY = window.scrollY;
  let ticking = false;

  function onScroll() {
    const max = root.scrollHeight - window.innerHeight;
    root.style.setProperty('--scroll-progress', max > 0 ? (window.scrollY / max).toFixed(4) : 0);
    updateScrollFill();

    // Hide the header while scrolling down, bring it back on any upward scroll
    const y = window.scrollY;
    if (header && Math.abs(y - lastScrollY) > 4) {
      const menuOpen = nav && nav.classList.contains('open');
      header.classList.toggle('is-hidden', y > lastScrollY && y > 160 && !menuOpen);
      lastScrollY = y;
    }

    if (!reducedMotion) {
      const vh = window.innerHeight;
      parallaxImages.forEach((img) => {
        const box = img.parentElement.getBoundingClientRect();
        if (box.bottom < 0 || box.top > vh) return;
        const offset = ((box.top + box.height / 2) - vh / 2) / vh; // -1 .. 1
        img.style.setProperty('--parallax', (offset * -40).toFixed(1) + 'px');
      });
    }

    // Room-by-room photos grow from a small frame to full size as they rise into view
    if (!reducedMotion) {
      const vh = window.innerHeight;
      detailFrames.forEach((frame) => {
        const top = frame.getBoundingClientRect().top;
        const grow = Math.min(Math.max((vh - top) / (vh * 0.6), 0), 1);
        frame.style.setProperty('--grow', grow.toFixed(3));
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
    const cursor = stage.querySelector('.reel-cursor');
    const progress = reel.querySelector('[data-reel-progress]');
    const total = cards.length;
    let active = 0;
    let inView = false;

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

  // Hero slideshow: other photos load after the page, then rotate every 5s while the hero is visible
  const heroSlides = document.querySelector('[data-hero-slides]');
  if (heroSlides) {
    const slides = [...heroSlides.querySelectorAll('img')];
    const heroDots = [...heroSlides.querySelectorAll('.hero-dots i')];
    const heroCaption = heroSlides.querySelector('[data-hero-caption]');
    let heroIndex = 0;
    let heroInView = true;
    const showHero = (i) => {
      heroIndex = (i + slides.length) % slides.length;
      slides.forEach((slide, s) => slide.classList.toggle('is-active', s === heroIndex));
      heroDots.forEach((dot, d) => dot.classList.toggle('is-active', d === heroIndex));
      heroCaption.textContent = slides[heroIndex].dataset.caption;
    };
    showHero(0);
    window.addEventListener('load', () => slides.forEach((slide) => {
      if (!slide.dataset.src) return;
      slide.srcset = slide.dataset.srcset;
      slide.src = slide.dataset.src;
    }));
    setInterval(() => {
      const next = slides[(heroIndex + 1) % slides.length];
      if (heroInView && !document.hidden && !reducedMotion && next.complete && next.naturalWidth) showHero(heroIndex + 1);
    }, 5000);
    new IntersectionObserver(([entry]) => { heroInView = entry.isIntersecting; }).observe(heroSlides);
  }

  // Background videos play only while on screen
  const inviewVideos = document.querySelectorAll('[data-inview-video]');
  if (inviewVideos.length && !reducedMotion) {
    const videoObserver = new IntersectionObserver((entries) => entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.preload = 'auto';
        const attempt = entry.target.play();
        if (attempt) attempt.catch(() => {});
      } else {
        entry.target.pause();
      }
    }), { threshold: 0.2 });
    inviewVideos.forEach((video) => videoObserver.observe(video));
  }

  // Reviews marquee: repeat the cards until one set is wider than the screen, then double it so translateX(-50%) loops seamlessly
  const reviewRow = document.querySelector('[data-reviews] .reviews-row');
  if (reviewRow && !reducedMotion) {
    const originals = [...reviewRow.children];
    const addCopies = () => originals.forEach((card) => {
      const copy = card.cloneNode(true);
      copy.setAttribute('aria-hidden', 'true');
      reviewRow.appendChild(copy);
    });
    while (reviewRow.scrollWidth < window.innerWidth * 1.1) addCopies();
    [...reviewRow.children].forEach((card) => {
      const copy = card.cloneNode(true);
      copy.setAttribute('aria-hidden', 'true');
      reviewRow.appendChild(copy);
    });
    reviewRow.style.animationDuration = Math.round(reviewRow.scrollWidth / 2 / 45) + 's';
  }

  // Enquiry and review forms post to Web3Forms, which emails each submission to the studio's Gmail
  document.querySelectorAll('[data-web3form]').forEach((form) => {
    const status = form.querySelector('.form-status');
    const submit = form.querySelector('.form-submit');
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (form.access_key.value.startsWith('YOUR_')) {
        status.textContent = 'The form is not connected yet. Please email info@osusinteriors.com.';
        return;
      }
      submit.disabled = true;
      status.textContent = 'Sending…';
      try {
        const response = await fetch(form.action, { method: 'POST', headers: { Accept: 'application/json' }, body: new FormData(form) });
        const result = await response.json();
        if (!result.success) throw new Error(result.message);
        form.reset();
        status.textContent = form.dataset.success;
      } catch {
        status.textContent = 'Something went wrong. Please try again or email info@osusinteriors.com.';
      } finally {
        submit.disabled = false;
      }
    });
  });

  // Services accordion: one service open at a time
  const serviceButtons = [...document.querySelectorAll('.service-row[aria-controls]')];
  const setService = (button, open) => {
    button.setAttribute('aria-expanded', String(open));
    document.getElementById(button.getAttribute('aria-controls')).classList.toggle('is-open', open);
  };
  serviceButtons.forEach((button) => button.addEventListener('click', () => {
    const open = button.getAttribute('aria-expanded') !== 'true';
    serviceButtons.forEach((other) => setService(other, other === button && open));
  }));
  if (serviceButtons[0]) setService(serviceButtons[0], true);

  // Desktop: a closed service's photo trails the cursor while hovering its row
  if (serviceButtons.length && window.matchMedia('(hover: hover) and (pointer: fine)').matches && !reducedMotion) {
    const floater = document.createElement('img');
    floater.className = 'service-float';
    floater.alt = '';
    floater.setAttribute('aria-hidden', 'true');
    document.body.appendChild(floater);
    let targetX = 0, targetY = 0, x = 0, y = 0, following = false;
    const follow = () => {
      x += (targetX - x) * 0.18;
      y += (targetY - y) * 0.18;
      floater.style.transform = `translate3d(${x}px, ${y}px, 0) translate(-50%, -60%) rotate(${(targetX - x) * 0.04}deg)`;
      if (following) requestAnimationFrame(follow);
    };
    serviceButtons.forEach((button) => {
      const photo = document.getElementById(button.getAttribute('aria-controls')).querySelector('img');
      button.addEventListener('mouseenter', (e) => {
        if (button.getAttribute('aria-expanded') === 'true') return;
        floater.src = photo.currentSrc || photo.src;
        targetX = x = e.clientX;
        targetY = y = e.clientY;
        floater.classList.add('is-visible');
        if (!following) { following = true; requestAnimationFrame(follow); }
      });
      button.addEventListener('mousemove', (e) => { targetX = e.clientX; targetY = e.clientY; });
      const hide = () => { floater.classList.remove('is-visible'); following = false; };
      button.addEventListener('mouseleave', hide);
      button.addEventListener('click', hide);
    });
  }

  // Project carousel: photos load on demand, each holds for DURATION, then the next wipes in
  const pcar = document.querySelector('[data-pcar]');
  if (pcar) {
    const DURATION = 3500;
    const stage = pcar.querySelector('[data-pcar-stage]');
    const slides = [...stage.querySelectorAll('img')];
    const buttons = [...pcar.querySelectorAll('[data-pcar-project]')];
    const bars = buttons.map((button) => button.querySelector('b'));
    const total = slides.length;
    let index = -1;
    let elapsed = 0;
    let last = 0;
    let inView = false;
    let hovering = false;

    const load = (i) => {
      const img = slides[(i + total) % total];
      if (img.dataset.src) {
        img.srcset = img.dataset.srcset;
        img.src = img.dataset.src;
        delete img.dataset.src;
      }
    };

    function show(i) {
      const next = (i + total) % total;
      if (next === index) return;
      const previous = slides[index];
      slides.forEach((slide) => slide.classList.remove('was-active'));
      if (previous) {
        previous.classList.remove('is-active');
        previous.classList.add('was-active');
        setTimeout(() => previous.classList.remove('was-active'), 1300);
      }
      load(next);
      load(next + 1);
      slides[next].classList.add('is-active');
      index = next;
      elapsed = 0;

      const project = slides[next].dataset.project;
      buttons.forEach((button) => button.classList.toggle('is-active', button.dataset.pcarProject === project));
    }

    function tick(now) {
      const running = inView && !hovering && !document.hidden && !reducedMotion;
      if (running && last) elapsed += now - last;
      last = now;

      const project = slides[index].dataset.project;
      const group = slides.filter((slide) => slide.dataset.project === project);
      const progress = (group.indexOf(slides[index]) + Math.min(elapsed / DURATION, 1)) / group.length;
      bars.forEach((bar, b) => bar.style.setProperty('--p', String(b) === project ? progress.toFixed(3) : 0));

      if (elapsed >= DURATION) show(index + 1);
      requestAnimationFrame(tick);
    }

    buttons.forEach((button) => button.addEventListener('click', () => {
      show(slides.findIndex((slide) => slide.dataset.project === button.dataset.pcarProject));
    }));
    stage.addEventListener('mouseenter', () => { hovering = true; });
    stage.addEventListener('mouseleave', () => { hovering = false; });

    let swipeX = null;
    stage.addEventListener('pointerdown', (e) => { swipeX = e.clientX; });
    stage.addEventListener('pointerup', (e) => {
      if (swipeX !== null && Math.abs(e.clientX - swipeX) > 50) show(index + (e.clientX < swipeX ? 1 : -1));
      swipeX = null;
    });

    new IntersectionObserver(([entry]) => { inView = entry.isIntersecting; }, { threshold: 0.2 }).observe(stage);
    show(0);
    requestAnimationFrame(tick);
  }

  // Intro curtain: lift once the page has loaded and the logo has had a moment
  if (root.classList.contains('has-intro')) {
    const loaded = new Promise((resolve) => (document.readyState === 'complete' ? resolve() : window.addEventListener('load', resolve)));
    const minimum = new Promise((resolve) => setTimeout(resolve, 1300));
    const cap = new Promise((resolve) => setTimeout(resolve, 3000));
    Promise.race([Promise.all([loaded, minimum]), cap]).then(() => {
      root.classList.add('intro-done');
      setTimeout(() => document.querySelector('.intro')?.remove(), 1200);
    });
  }

  // Current year in footer
  document.querySelectorAll('[data-year]').forEach((el) => { el.textContent = new Date().getFullYear(); });
})();
