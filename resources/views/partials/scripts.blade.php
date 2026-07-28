<script>
    const siteHeader = document.getElementById('siteHeader');
    const menuButton = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const modal = document.getElementById('modal');
    const modalBody = document.getElementById('modalBody');

    window.addEventListener('scroll', () => siteHeader?.classList.toggle('is-scrolled', window.scrollY > 12), { passive: true });

    menuButton?.addEventListener('click', () => {
        const willOpen = mobileMenu.hasAttribute('hidden');
        mobileMenu.toggleAttribute('hidden');
        menuButton.setAttribute('aria-expanded', String(willOpen));
        menuButton.querySelector('.material-symbols-rounded').textContent = willOpen ? 'close' : 'menu';
        document.body.classList.toggle('menu-open', willOpen);
    });
    mobileMenu?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
        mobileMenu.setAttribute('hidden', '');
        menuButton?.setAttribute('aria-expanded', 'false');
        menuButton?.querySelector('.material-symbols-rounded') && (menuButton.querySelector('.material-symbols-rounded').textContent = 'menu');
        document.body.classList.remove('menu-open');
    }));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && mobileMenu && !mobileMenu.hasAttribute('hidden')) {
            mobileMenu.setAttribute('hidden', '');
            menuButton?.setAttribute('aria-expanded', 'false');
            if (menuButton) menuButton.querySelector('.material-symbols-rounded').textContent = 'menu';
            document.body.classList.remove('menu-open');
            menuButton?.focus();
        }
    });

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });
    document.querySelectorAll('.reveal').forEach(element => revealObserver.observe(element));

    let lastFocusedElement = null;
    function openModal(templateId) {
        const template = document.getElementById(templateId);
        if (!modal || !modalBody || !template) return;
        lastFocusedElement = document.activeElement;
        modalBody.replaceChildren(template.content.cloneNode(true));
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        modal.querySelector('.modal-close')?.focus();
    }
    function closeModal() {
        modal?.classList.remove('active');
        modal?.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        lastFocusedElement?.focus();
    }
    modal?.addEventListener('click', event => { if (event.target === modal) closeModal(); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') closeModal(); });

    function toggleAccordion(button) {
        const item = button.closest('.faq-item');
        const expanded = item.classList.toggle('active');
        button.setAttribute('aria-expanded', String(expanded));
    }

    let currentTestimonial = 0;
    const testimonialSlides = [...document.querySelectorAll('.testimonial-slide')];
    function changeTestimonial(direction) {
        if (!testimonialSlides.length) return;
        testimonialSlides[currentTestimonial].classList.remove('active');
        currentTestimonial = (currentTestimonial + direction + testimonialSlides.length) % testimonialSlides.length;
        testimonialSlides[currentTestimonial].classList.add('active');
        const counter = document.getElementById('testimonialCurrent');
        if (counter) counter.textContent = String(currentTestimonial + 1).padStart(2, '0');
    }

    function scrollTeam(direction) {
        const track = document.getElementById('teamTrack');
        const card = track?.querySelector('.team-card');
        if (!track || !card) return;
        const gap = parseFloat(getComputedStyle(track).gap) || 20;
        track.scrollBy({ left: direction * (card.getBoundingClientRect().width + gap), behavior: 'smooth' });
    }
</script>
