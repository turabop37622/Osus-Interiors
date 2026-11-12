(function () {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  // viaParent: a fully clipped element never counts as visible, so watch its parent instead
  const observeOnce = (elements, threshold = 0.25, viaParent = false) => {
    if (!('IntersectionObserver' in window) || reducedMotion) {
      elements.forEach((el) => el.classList.add('rv-in'));
      return;
    }
    const io = new IntersectionObserver((entries) => entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      (entry.target.__reveal || [entry.target]).forEach((el) => el.classList.add('rv-in'));
      io.unobserve(entry.target);
    }), { threshold, rootMargin: '0px 0px -8% 0px' });
    elements.forEach((el) => {
      const watched = viaParent ? el.parentElement : el;
      (watched.__reveal = watched.__reveal || []).push(el);
      io.observe(watched);
    });
  };

  // ---------- Headings rise word by word (keeps <em>, <br> and other inline markup) ----------
  const splitWords = (heading) => {
    let i = 0;
    const walk = (node) => {
      [...node.childNodes].forEach((child) => {
        if (child.nodeType === Node.TEXT_NODE) {
          const parts = child.textContent.split(/(\s+)/);
          const frag = document.createDocumentFragment();
          parts.forEach((part) => {
            if (!part) return;
            if (/^\s+$/.test(part)) { frag.appendChild(document.createTextNode(' ')); return; }
            const outer = document.createElement('span');
            outer.className = 'rv-word';
            const inner = document.createElement('span');
            inner.style.setProperty('--i', i++);
            inner.textContent = part;
            outer.appendChild(inner);
            frag.appendChild(outer);
          });
          child.replaceWith(frag);
        } else if (child.nodeType === Node.ELEMENT_NODE && child.tagName !== 'BR') {
          walk(child);
        }
      });
    };
    walk(heading);
  };
  const headings = [...document.querySelectorAll('h2:not([data-scroll-fill]), .case-hero h1, .case-next-link strong')];
  headings.forEach(splitWords);
  observeOnce(headings, 0.4);

  // ---------- Photos open like a curtain ----------
  const reveals = [...document.querySelectorAll('.compare-frame, .founder-photo, .case-gallery img')];
  reveals.forEach((el) => el.classList.add('img-reveal'));
  observeOnce(reveals, 0.1, true);

  // ---------- Cinematic projects: title and meta rise letter by letter for each project ----------
  const pcar = document.querySelector('[data-pcar]');
  if (pcar) {
    const stage = pcar.querySelector('[data-pcar-stage]');
    const titleEl = pcar.querySelector('.pcar-overlay-title');
    const metaEl = pcar.querySelector('.pcar-overlay-meta');
    const action = pcar.querySelector('.pcar-action');
    const buttons = [...pcar.querySelectorAll('[data-pcar-project]')];
    const letters = (el, text, step) => {
      el.textContent = '';
      [...text].forEach((char, i) => {
        const span = document.createElement('span');
        span.className = 'ch';
        span.textContent = char === ' ' ? ' ' : char;
        span.style.animationDelay = i * step + 'ms';
        el.appendChild(span);
      });
    };
    let shown = null;
    const sync = () => {
      const active = buttons.find((b) => b.classList.contains('is-active')) || buttons[0];
      if (!active || active === shown) return;
      shown = active;
      letters(metaEl, String(buttons.indexOf(active) + 1).padStart(2, '0') + ' — ' + active.querySelector('em').textContent, 12);
      letters(titleEl, active.querySelector('strong').textContent, 35);
      // a project with a case study names it in data-case; the others lead to the enquiry form
      const caseUrl = active.dataset.case;
      action.href = caseUrl || '#enquiry';
      action.querySelector('span').textContent = caseUrl ? 'View case study' : 'Discuss a similar space';
    };
    sync();
    buttons.forEach((b) => new MutationObserver(sync).observe(b, { attributes: true, attributeFilter: ['class'] }));
    // the button sits on the photo; keep its click from also opening the full-screen view
    action.addEventListener('click', (e) => e.stopPropagation());
  }

  // ---------- Video carousel: the playing card tilts toward the pointer ----------
  const reelStage = document.querySelector('[data-reel-stage]');
  if (reelStage && finePointer && !reducedMotion) {
    reelStage.addEventListener('pointermove', (e) => {
      const card = reelStage.querySelector('.reel-card.is-active');
      if (!card) return;
      const box = card.getBoundingClientRect();
      const x = (e.clientX - box.left) / box.width - 0.5;
      const y = (e.clientY - box.top) / box.height - 0.5;
      card.style.setProperty('--ty', (x * 10).toFixed(2) + 'deg');
      card.style.setProperty('--tx', (-y * 8).toFixed(2) + 'deg');
    });
    reelStage.addEventListener('pointerleave', () => reelStage.querySelectorAll('.reel-card').forEach((c) => {
      c.style.removeProperty('--tx');
      c.style.removeProperty('--ty');
    }));
  }

  // ---------- Hero photo drifts gently with the pointer ----------
  const hero = document.querySelector('.hero');
  const heroImage = document.querySelector('.hero-image');
  if (hero && heroImage && finePointer && !reducedMotion) {
    hero.addEventListener('pointermove', (e) => {
      heroImage.style.setProperty('--mx', ((e.clientX / innerWidth - 0.5) * -18).toFixed(1) + 'px');
      heroImage.style.setProperty('--my', ((e.clientY / innerHeight - 0.5) * -12).toFixed(1) + 'px');
    });
  }

  // ---------- Marquees speed up with scroll velocity, then ease back ----------
  const tracks = [...document.querySelectorAll('.marquee > div, .reel-bgtext div, .brands-row')]
    .map((el) => el.getAnimations ? el.getAnimations()[0] : null).filter(Boolean);
  if (tracks.length && !reducedMotion) {
    let lastY = scrollY;
    let boost = 0;
    window.addEventListener('scroll', () => {
      boost = Math.min(boost + Math.abs(scrollY - lastY) / 30, 5);
      lastY = scrollY;
    }, { passive: true });
    const settle = () => {
      boost *= 0.92;
      tracks.forEach((a) => { a.playbackRate = 1 + boost; });
      requestAnimationFrame(settle);
    };
    requestAnimationFrame(settle);
  }

  // ---------- Our team: one panel open at a time; cycles on its own until someone hovers ----------
  const team = document.querySelector('[data-team]');
  if (team) {
    const cards = [...team.querySelectorAll('.team-card')];
    const dotsWrap = document.querySelector('[data-team-dots]');
    const dots = cards.map(() => dotsWrap.appendChild(document.createElement('i')));
    const wide = window.matchMedia('(min-width: 801px)');
    let openIndex = 0;
    let hovering = false;
    let teamInView = false;
    const openCard = (i) => {
      openIndex = i;
      cards.forEach((card, k) => card.classList.toggle('is-open', k === i));
    };
    cards.forEach((card, i) => {
      card.addEventListener('mouseenter', () => { if (wide.matches) { hovering = true; openCard(i); } });
      card.addEventListener('mouseleave', () => { hovering = false; });
      card.addEventListener('click', () => { if (wide.matches) openCard(i); });
      card.addEventListener('focus', () => { if (wide.matches) openCard(i); });
    });
    new IntersectionObserver(([entry]) => {
      teamInView = entry.isIntersecting;
      if (teamInView) team.classList.add('team-in');
    }, { threshold: 0.2 }).observe(team);
    if (!reducedMotion) {
      setInterval(() => {
        if (wide.matches && teamInView && !hovering && !document.hidden) openCard((openIndex + 1) % cards.length);
      }, 3500);
    }
    // phones: the dot under the card closest to the centre of the row is active
    const syncDots = () => {
      const row = team.getBoundingClientRect();
      const centre = row.left + row.width / 2;
      let best = 0;
      cards.forEach((card, k) => {
        const box = card.getBoundingClientRect();
        const bestBox = cards[best].getBoundingClientRect();
        if (Math.abs(box.left + box.width / 2 - centre) < Math.abs(bestBox.left + bestBox.width / 2 - centre)) best = k;
      });
      dots.forEach((dot, k) => dot.classList.toggle('is-active', k === best));
    };
    team.addEventListener('scroll', syncDots, { passive: true });
    syncDots();
  }

  if (!finePointer || reducedMotion) return;

  // ---------- Magnetic buttons ----------
  document.querySelectorAll('.header-cta, .form-submit, .whatsapp, .pcar-action').forEach((el) => {
    el.setAttribute('data-magnetic', '');
    el.addEventListener('pointermove', (e) => {
      const box = el.getBoundingClientRect();
      const x = e.clientX - (box.left + box.width / 2);
      const y = e.clientY - (box.top + box.height / 2);
      el.style.transform = `translate(${(x * 0.3).toFixed(1)}px, ${(y * 0.35).toFixed(1)}px)`;
    });
    el.addEventListener('pointerleave', () => { el.style.transform = ''; });
  });

})();
