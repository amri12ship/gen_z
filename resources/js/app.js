import './bootstrap';
import initBackground from './three-background';

document.addEventListener('DOMContentLoaded', () => {
    initBackground();

    // Scroll reveal
    const revealEls = document.querySelectorAll('.reveal');

    if ('IntersectionObserver' in window && revealEls.length) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12 },
        );

        revealEls.forEach((el) => observer.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('is-visible'));
    }

    // Efek tilt 3D pada kartu produk
    document.querySelectorAll('[data-tilt]').forEach((card) => {
        card.addEventListener('mousemove', (event) => {
            const rect = card.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width - 0.5;
            const y = (event.clientY - rect.top) / rect.height - 0.5;

            card.style.transform = `perspective(900px) rotateY(${x * 10}deg) rotateX(${-y * 10}deg) translateY(-8px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
});
