/* ==========================================================================
   Plantify Official — app.js
   Responsive client-side interactions: Steppers, Filters,
   Gallery, Pot Variants, Accordions, Mobile Navigation, Toast Notifications.
   Zero-Emoji Compliant & US English (en-US).
   ========================================================================== */

(function () {
    'use strict';

    function $(s, r) {
        return (r || document).querySelector(s);
    }
    function $$(s, r) {
        return Array.prototype.slice.call((r || document).querySelectorAll(s));
    }

    // Currency Formatter (USD $)
    function money(amount) {
        return '$' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    /* ------------------------------------------------------------------------
       Toast System (Zero Unicode Emojis - Inline SVG Only)
       ------------------------------------------------------------------------ */
    function toast(message) {
        let host = $('.toasts');
        if (!host) {
            host = document.createElement('div');
            host.className = 'toasts';
            document.body.appendChild(host);
        }

        const node = document.createElement('div');
        node.className = 'toast';
        node.setAttribute('role', 'status');
        node.innerHTML = `
            <svg class="w-5 h-5 text-ochre flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m20 6-11 11-5-5"/>
            </svg>
            <span class="font-medium">${message}</span>
        `;

        host.appendChild(node);
        setTimeout(() => {
            node.style.transition = 'opacity 200ms ease, transform 200ms ease';
            node.style.opacity = '0';
            node.style.transform = 'translateY(8px)';
            setTimeout(() => node.remove(), 220);
        }, 2800);
    }

    /* ------------------------------------------------------------------------
       Initialize on DOMContentLoaded
       ------------------------------------------------------------------------ */
    document.addEventListener('DOMContentLoaded', () => {
        // Any global button helpers
        $$('[data-toast]').forEach((btn) => {
            btn.addEventListener('click', () => toast(btn.dataset.toast));
        });
    });

    // Global Object for template calls
    window.Plantify = {
        toast,
        money
    };
})();
