/* ============================================================
   中原苑 — Main JavaScript
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

  // --- Utility: Format date to Japanese style ---
  function formatDateJa(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    const y = d.getFullYear();
    const m = d.getMonth() + 1;
    const day = d.getDate();
    return `${y}年${m}月${day}日`;
  }

  function formatDateShort(dateStr) {
    const d = new Date(dateStr + 'T00:00:00');
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}.${m}.${day}`;
  }


  // --- Load Availability Data ---
  async function loadAvailability() {
    try {
      const res = await fetch('data/availability.json');
      const data = await res.json();
      const container = document.getElementById('availability-cards');
      const dateEl = document.getElementById('availability-date');

      if (!container) return;

      container.innerHTML = data.services.map((service, i) => {
        const slotsHtml = service.slots.map(slot => `
          <div class="avail-slot">
            <span class="avail-slot-label">${slot.label}</span>
            <span class="avail-slot-count">${slot.count}<span class="avail-slot-unit">名</span></span>
          </div>
        `).join('');

        return `
          <div class="avail-card fade-in" style="animation-delay: ${i * 0.1}s">
            <div class="avail-card-title">${service.name}</div>
            <div class="avail-slots">${slotsHtml}</div>
          </div>
        `;
      }).join('');

      if (dateEl) {
        dateEl.textContent = `${formatDateJa(data.updatedAt)} 現在`;
      }

      // Re-observe new fade-in elements
      observeFadeIns();
    } catch (err) {
      console.error('空き情報の読み込みに失敗しました:', err);
    }
  }


  // --- Load News Data ---
  async function loadNews() {
    try {
      const res = await fetch('data/news.json');
      const data = await res.json();
      const container = document.getElementById('news-list');

      if (!container) return;

      const items = data.items.slice(0, 5);

      container.innerHTML = items.map((item, i) => {
        const categoryClass = item.category === 'イベント'
          ? 'news-category--event'
          : 'news-category--info';

        const titleHtml = item.link
          ? `<a href="${item.link}" class="news-title">${item.title}</a>`
          : `<span class="news-title">${item.title}</span>`;

        return `
          <div class="news-item fade-in" style="animation-delay: ${i * 0.08}s">
            <span class="news-date">${formatDateShort(item.date)}</span>
            <span class="news-category ${categoryClass}">${item.category}</span>
            ${titleHtml}
          </div>
        `;
      }).join('');

      observeFadeIns();
    } catch (err) {
      console.error('お知らせの読み込みに失敗しました:', err);
    }
  }


  // --- Intersection Observer for Fade-in ---
  let observer;

  function observeFadeIns() {
    if (!observer) {
      observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, {
        threshold: 0.1,
        rootMargin: '0px 0px -40px 0px'
      });
    }

    document.querySelectorAll('.fade-in:not(.is-visible)').forEach(el => {
      observer.observe(el);
    });
  }


  // --- Sticky Header Shadow ---
  function initHeaderScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          header.classList.toggle('scrolled', window.scrollY > 20);
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }


  // --- Hamburger Menu ---
  function initHamburger() {
    const btn = document.getElementById('hamburger');
    const nav = document.getElementById('main-nav');
    if (!btn || !nav) return;

    btn.addEventListener('click', () => {
      btn.classList.toggle('active');
      nav.classList.toggle('open');
      document.body.style.overflow = nav.classList.contains('open') ? 'hidden' : '';
    });

    // Close on nav link click
    nav.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        btn.classList.remove('active');
        nav.classList.remove('open');
        document.body.style.overflow = '';
      });
    });
  }


  // --- Smooth Scroll for Anchor Links ---
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', (e) => {
        const targetId = anchor.getAttribute('href');
        if (targetId === '#') return;

        const target = document.querySelector(targetId);
        if (target) {
          e.preventDefault();
          const headerOffset = 80;
          const top = target.getBoundingClientRect().top + window.scrollY - headerOffset;
          window.scrollTo({ top, behavior: 'smooth' });
        }
      });
    });
  }


  // --- Initialize ---
  loadAvailability();
  loadNews();
  observeFadeIns();
  initHeaderScroll();
  initHamburger();
  initSmoothScroll();

});
