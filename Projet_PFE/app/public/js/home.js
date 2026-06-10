/* ===========================================================
   MEGALOC :: HOME PAGE INTERACTIONS
   - Flatpickr (start + end dates, linked range)
   - Custom city location dropdown
   - Horizontal scroll arrows (categories / others using .h-scroll)
   =========================================================== */

(function () {
    'use strict';

    /* --- Date pickers (Start & End, linked) ----------------- */
    if (typeof flatpickr === 'function') {
        const start = flatpickr('#startDatePicker', {
            minDate: 'today',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'M j, Y',
            onChange: (selectedDates) => {
                if (selectedDates[0] && endPicker) {
                    endPicker.set('minDate', selectedDates[0]);
                }
            },
        });
        const endPicker = flatpickr('#endDatePicker', {
            minDate: 'today',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'M j, Y',
        });
    }

    /* --- Custom location dropdown --------------------------- */
    const locTrigger = document.getElementById('locationTrigger');
    const locMenu    = document.getElementById('locationMenu');
    const cityInput  = document.getElementById('cityInput');
    const cityText   = document.getElementById('selectedCityText');

    if (locTrigger && locMenu) {
        locTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            locMenu.classList.toggle('show');
        });

        locMenu.querySelectorAll('.option-item').forEach((item) => {
            item.addEventListener('click', () => {
                const val  = item.getAttribute('data-value') || '';
                const text = item.textContent.trim();
                if (cityInput) cityInput.value = val;
                if (cityText)  cityText.textContent = text;
                locMenu.querySelectorAll('.option-item').forEach((i) => i.classList.remove('selected'));
                item.classList.add('selected');
                locMenu.classList.remove('show');
            });
        });

        document.addEventListener('click', (e) => {
            if (!locTrigger.contains(e.target) && !locMenu.contains(e.target)) {
                locMenu.classList.remove('show');
            }
        });
    }

    /* --- Horizontal scroll buttons -------------------------- */
    document.querySelectorAll('.scroll-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.target);
            const dir    = parseInt(btn.dataset.dir || '1', 10);
            if (!target) return;
            const card   = target.querySelector(':scope > *');
            const step   = card ? card.getBoundingClientRect().width + 20 : 260;
            target.scrollBy({ left: step * dir * 2, behavior: 'smooth' });
        });
    });
})();
