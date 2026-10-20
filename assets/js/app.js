/**
 * Tech Hub - Native Vanilla JavaScript
 * Smooth Interactions, Scroll Reveal, Mobile Menu & Live Search
 */

document.addEventListener('DOMContentLoaded', () => {
  // Dashboard Snort: controls scoped to the supervision view.
  const dashboard = document.querySelector('.dashboard-body');
  if (dashboard) {
    const rangeMessages = {
      '1h': '184 événements sur 60 min',
      '6h': '926 événements sur 6 h',
      '24h': '2 846 événements sur 24 h'
    };
    const summary = document.getElementById('rangeSummary');
    document.querySelectorAll('[data-range]').forEach(button => {
      button.addEventListener('click', () => {
        document.querySelectorAll('[data-range]').forEach(item => item.classList.toggle('active', item === button));
        if (summary) summary.textContent = rangeMessages[button.dataset.range] || rangeMessages['1h'];
      });
    });

    const search = document.getElementById('alertSearch');
    const rows = Array.from(document.querySelectorAll('#alertsTable tr'));
    const emptyState = document.getElementById('alertEmpty');
    if (search) {
      search.addEventListener('input', () => {
        const query = search.value.trim().toLowerCase();
        let visible = 0;
        rows.forEach(row => {
          const matches = !query || (row.dataset.search || row.textContent).toLowerCase().includes(query);
          row.hidden = !matches;
          if (matches) visible += 1;
        });
        if (emptyState) emptyState.hidden = visible !== 0;
      });

      document.addEventListener('keydown', event => {
        if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
          event.preventDefault();
          search.focus();
        }
      });
    }

    const liveToggle = document.getElementById('liveToggle');
    if (liveToggle) {
      liveToggle.addEventListener('click', () => {
        const active = liveToggle.getAttribute('aria-pressed') !== 'true';
        liveToggle.setAttribute('aria-pressed', String(active));
        liveToggle.classList.toggle('is-paused', !active);
        const label = liveToggle.querySelector('.live-toggle-label');
        if (label) label.textContent = active ? 'Live' : 'En pause';
      });
    }

    const refreshSensor = document.getElementById('refreshSensor');
    if (refreshSensor) {
      refreshSensor.addEventListener('click', () => {
        refreshSensor.classList.add('is-refreshing');
        window.setTimeout(() => refreshSensor.classList.remove('is-refreshing'), 550);
      });
    }
  }

  // 1. Sticky Header Scroll Effect
  const header = document.querySelector('.site-header');
  const handleScroll = () => {
    if (window.scrollY > 40) {
      header?.classList.add('scrolled');
    } else {
      header?.classList.remove('scrolled');
    }
  };
  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();

  // 2. Mobile Menu Toggle
  const toggleBtn = document.getElementById('mobileMenuBtn');
  const drawer = document.getElementById('mobileDrawer');
  if (toggleBtn && drawer) {
    toggleBtn.addEventListener('click', () => {
      const isOpen = drawer.classList.toggle('open');
      toggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    // Close on clicking any link inside mobile drawer
    drawer.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        drawer.classList.remove('open');
      });
    });
  }

  // 3. Scroll Reveal Animations (Intersection Observer)
  const revealElements = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          // Once animated, no need to re-observe
          observer.unobserve(entry.target);
        }
      });
    }, {
      root: null,
      rootMargin: '0px 0px -60px 0px',
      threshold: 0.1
    });

    revealElements.forEach(el => observer.observe(el));
  } else {
    // Fallback for older browsers
    revealElements.forEach(el => el.classList.add('is-visible'));
  }

  // 4. Active Nav Item on Scroll
  const sections = document.querySelectorAll('section[id], footer[id]');
  const navLinks = document.querySelectorAll('.nav-link');

  const highlightNavOnScroll = () => {
    let scrollPos = window.scrollY + 200;
    sections.forEach(section => {
      const top = section.offsetTop;
      const height = section.offsetHeight;
      const id = section.getAttribute('id');

      if (scrollPos >= top && scrollPos < top + height) {
        navLinks.forEach(link => {
          if (link.getAttribute('href') === `#${id}`) {
            link.classList.add('active');
          } else {
            link.classList.remove('active');
          }
        });
      }
    });
  };
  window.addEventListener('scroll', highlightNavOnScroll, { passive: true });

  // 5. Live Search & Filter for Technologies Section
  const techSearch = document.getElementById('techSearch');
  if (techSearch) {
    techSearch.addEventListener('input', (e) => {
      const query = e.target.value.toLowerCase().trim();
      const techCards = document.querySelectorAll('.category-card');

      techCards.forEach(card => {
        const pills = card.querySelectorAll('.tech-pill');
        let cardHasMatch = false;

        pills.forEach(pill => {
          const text = pill.textContent.toLowerCase();
          if (!query || text.includes(query)) {
            pill.style.display = 'inline-block';
            if (query && text.includes(query)) {
              pill.style.borderColor = 'var(--cyan-400)';
              pill.style.background = 'rgba(6, 182, 212, 0.25)';
              cardHasMatch = true;
            } else {
              pill.style.borderColor = '';
              pill.style.background = '';
            }
          } else {
            pill.style.display = 'none';
          }
        });

        // If searching and no match in card, dim card slightly
        if (query) {
          card.style.opacity = cardHasMatch ? '1' : '0.35';
        } else {
          card.style.opacity = '1';
        }
      });
    });
  }

  // 6. Interactive Click Feedback on Tech Badges
  document.querySelectorAll('.tech-pill').forEach(pill => {
    pill.addEventListener('click', () => {
      const originalText = pill.textContent;
      const originalBg = pill.style.background;
      pill.style.background = 'rgba(34, 211, 238, 0.35)';
      setTimeout(() => {
        pill.style.background = originalBg;
      }, 400);
    });
  });
});
