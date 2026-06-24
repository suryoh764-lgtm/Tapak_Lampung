// ===== THEME ENGINE =====
const html = document.documentElement;

function getPreferredTheme() {
    const saved = localStorage.getItem('tapak-theme');
    if (saved) return saved;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function setTheme(theme) {
    html.setAttribute('data-theme', theme);
    localStorage.setItem('tapak-theme', theme);
}

function toggleTheme() {
    const current = html.getAttribute('data-theme');
    setTheme(current === 'light' ? 'dark' : 'light');
}

// Init theme
setTheme(getPreferredTheme());

// Listen for system changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('tapak-theme')) {
        setTheme(e.matches ? 'dark' : 'light');
    }
});

// ===== NAV SCROLL =====
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('scrolled', scrollY > 40);
});

// ===== MOBILE =====
function mob() {
    document.getElementById('mobNav').classList.toggle('open');
}

// ===== FILTER CHIPS =====
document.querySelectorAll('.filter-chip').forEach(c => {
    c.addEventListener('click', () => {
        document.querySelectorAll('.filter-chip').forEach(x => x.classList.remove('active'));
        c.classList.add('active');
    });
});

// ===== REVEAL ON SCROLL =====
const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) e.target.classList.add('visible');
    });
}, { threshold: 0.06, rootMargin: '0px 0px -30px 0px' });

document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

// ===== SMOOTH SCROLL =====
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
        e.preventDefault();
        const t = document.querySelector(this.getAttribute('href'));
        if (t) t.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});
