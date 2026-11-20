import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;

document.documentElement.classList.add('js');

// Ask before destructive admin actions: <form data-confirm="Delete this listing?">.
document.addEventListener('submit', (e) => {
    const message = e.target.getAttribute('data-confirm');
    if (message && !window.confirm(message)) e.preventDefault();
});

// Auto-submit filter forms when a select changes: <select data-autosubmit>.
document.addEventListener('change', (e) => {
    if (e.target.matches('[data-autosubmit]')) e.target.form?.requestSubmit();
});

// Fade elements in as they scroll into view: <div data-reveal style="--delay: 100ms">.
const revealer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('revealed');
        revealer.unobserve(entry.target);
    });
}, { rootMargin: '0px 0px -6% 0px' });
document.querySelectorAll('[data-reveal]').forEach((el) => revealer.observe(el));

// Saved properties live in the visitor's browser (no account needed).
Alpine.store('saved', {
    ids: JSON.parse(localStorage.getItem('saved-properties') || '[]'),
    has(id) { return this.ids.includes(id); },
    toggle(id) {
        this.ids = this.has(id) ? this.ids.filter((x) => x !== id) : [id, ...this.ids];
        localStorage.setItem('saved-properties', JSON.stringify(this.ids));
    },
    get count() { return this.ids.length; },
    get url() { return `/properties/saved?ids=${this.ids.join(',')}`; },
});

// Mortgage / payment calculator on sale listings.
Alpine.data('mortgage', (price) => ({
    price,
    deposit: 20,
    rate: 22,
    years: 15,
    get loan() { return this.price * (1 - this.deposit / 100); },
    get monthly() {
        const r = this.rate / 100 / 12;
        const n = this.years * 12;
        if (!r) return this.loan / n;
        return (this.loan * r) / (1 - Math.pow(1 + r, -n));
    },
    money(v) { return 'GH₵ ' + Math.round(v).toLocaleString(); },
}));

// Photo gallery with a full-screen viewer (arrow keys and swipe).
Alpine.data('gallery', (count) => ({
    open: false,
    index: 0,
    startX: null,
    show(i) { this.index = i; this.open = true; document.body.style.overflow = 'hidden'; },
    close() { this.open = false; document.body.style.overflow = ''; },
    next() { this.index = (this.index + 1) % count; },
    prev() { this.index = (this.index - 1 + count) % count; },
    touchStart(e) { this.startX = e.touches[0].clientX; },
    touchEnd(e) {
        if (this.startX === null) return;
        const dx = e.changedTouches[0].clientX - this.startX;
        if (Math.abs(dx) > 40) (dx < 0 ? this.next() : this.prev());
        this.startX = null;
    },
}));

// Share a listing: native share sheet on phones, copy link elsewhere.
Alpine.data('share', (title, url) => ({
    copied: false,
    async go() {
        if (navigator.share) {
            try { await navigator.share({ title, url }); } catch { /* cancelled */ }
            return;
        }
        await navigator.clipboard.writeText(url);
        this.copied = true;
        setTimeout(() => (this.copied = false), 2000);
    },
}));

Alpine.start();
