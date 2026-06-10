/* ===========================================================
   MEGALOC :: CLIENT HEADER INTERACTIONS
   Handles: category mega menu, profile dropdown, mobile toggle.
   =========================================================== */

(function () {
    'use strict';

    const $ = (id) => document.getElementById(id);

    const catTrigger  = $('categoryTrigger');
    const catMenu     = $('categoryMenu');
    const profTrigger = $('profileTrigger');
    const profMenu    = $('profileMenu');
    const mobileBtn   = $('mobileToggle');
    const headerNav   = document.querySelector('.header-nav');

    function closeAll() {
        if (catMenu)  catMenu.classList.remove('show');
        if (profMenu) profMenu.classList.remove('show');
        document.querySelector('.category-dropdown')?.classList.remove('open');
    }

    if (catTrigger && catMenu) {
        catTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const wasOpen = catMenu.classList.contains('show');
            closeAll();
            if (!wasOpen) {
                catMenu.classList.add('show');
                catTrigger.parentElement.classList.add('open');
            }
        });
    }

    if (profTrigger && profMenu) {
        profTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const wasOpen = profMenu.classList.contains('show');
            closeAll();
            if (!wasOpen) profMenu.classList.add('show');
        });
    }

    document.addEventListener('click', closeAll);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeAll(); });

    if (mobileBtn && headerNav) {
        const icon = mobileBtn.querySelector('i');
        const setIcon = (open) => {
            if (!icon) return;
            icon.classList.toggle('fa-bars',  !open);
            icon.classList.toggle('fa-xmark',  open);
        };

        mobileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const willOpen = !headerNav.classList.contains('mobile-open');
            headerNav.classList.toggle('mobile-open', willOpen);
            setIcon(willOpen);
        });

        /* Close drawer when any nav link is tapped. */
        headerNav.querySelectorAll('.nav-link, .mega-menu-item').forEach((el) => {
            el.addEventListener('click', () => {
                headerNav.classList.remove('mobile-open');
                setIcon(false);
            });
        });

        /* Close drawer on outside click / Escape. */
        document.addEventListener('click', (e) => {
            if (!headerNav.contains(e.target) && !mobileBtn.contains(e.target)) {
                headerNav.classList.remove('mobile-open');
                setIcon(false);
            }
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                headerNav.classList.remove('mobile-open');
                setIcon(false);
            }
        });
    }
})();
