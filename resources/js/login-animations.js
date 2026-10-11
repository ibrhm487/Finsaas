import { gsap } from 'gsap';

window.addEventListener('load', () => {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    const brandOrb1 = document.querySelector('.brand-orb');
    const brandOrb2 = document.querySelector('.brand-orb-2');

    if (brandOrb1) {
        gsap.to(brandOrb1, {
            x: '12vw',
            y: '8vh',
            scale: 1.15,
            duration: 14,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
        });
    }
    if (brandOrb2) {
        gsap.to(brandOrb2, {
            x: '-8vw',
            y: '-6vh',
            scale: 1.2,
            duration: 18,
            repeat: -1,
            yoyo: true,
            ease: 'sine.inOut',
        });
    }

    gsap.set('.brand-title', { opacity: 0, y: -40, rotationX: 15 });
    gsap.set('.brand-sub', { opacity: 0, y: 15 });
    gsap.set('.brand-desc', { opacity: 0, y: 10 });
    gsap.set('.brand-feature', { opacity: 0, x: -20 });
    gsap.set('.form-container', { opacity: 0, x: 40 });
    gsap.set('.auth-title', { opacity: 0, x: -30 });
    gsap.set('.auth-subtitle', { opacity: 0, y: 10 });
    gsap.set('.glass-field', { opacity: 0, y: 20 });
    gsap.set('.auth-options', { opacity: 0, y: 10 });
    gsap.set('.login-gradient-btn', { opacity: 0, y: 15 });

    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

    tl
        .to('.brand-title', { opacity: 1, y: 0, rotationX: 0, duration: 0.9, ease: 'back.out(1.7)' })
        .to('.brand-sub', { opacity: 1, y: 0, duration: 0.5 }, '-=0.4')
        .to('.brand-desc', { opacity: 1, y: 0, duration: 0.5 }, '-=0.2')
        .to('.brand-feature', { opacity: 1, x: 0, duration: 0.4, stagger: 0.12 }, '-=0.2')
        .to('.form-container', { opacity: 1, x: 0, duration: 0.8, ease: 'back.out(1.4)' }, '-=0.3')
        .to('.auth-title', { opacity: 1, x: 0, duration: 0.4 }, '-=0.5')
        .to('.auth-subtitle', { opacity: 1, y: 0, duration: 0.35 }, '-=0.2')
        .to('.glass-field', { opacity: 1, y: 0, duration: 0.45, stagger: 0.12 }, '-=0.15')
        .to('.auth-options', { opacity: 1, y: 0, duration: 0.35 }, '-=0.15')
        .to('.login-gradient-btn', { opacity: 1, y: 0, duration: 0.4, ease: 'back.out(1.6)' }, '-=0.1');

    const btn = document.querySelector('.login-gradient-btn');
    if (btn) {
        btn.addEventListener('mouseenter', () => {
            gsap.to(btn, { scale: 1.02, duration: 0.3, ease: 'power2.out' });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn, { scale: 1, duration: 0.3, ease: 'power2.out' });
        });
    }

    if (document.querySelector('.has-error')) {
        gsap.to('.form-container', { x: [-8, 8, -6, 6, -3, 3, 0], duration: 0.5, ease: 'power2.out' });
    }
});
