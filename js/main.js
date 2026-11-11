// Site-wide JS: nav dropdown/toggle, scroll effects, GSAP animations, and
// stat counters. Loaded once via <script defer>, cached across every page.
// Deliberately does NOT depend on Three.js (see js/bg-animation.js) so the
// header/nav stay interactive even while that much heavier library is still
// downloading -- defer scripts execute strictly in document order, so if this
// file needed THREE it would have to wait for the whole 600KB+ library first.


// Clicking anywhere on a dropdown parent (Services/Agency/Industries) opens its
// submenu instead of following the link -- at every width, not just mobile. On
// desktop the submenu was already previewed on hover, so a click landed at the
// same moment hover had it open, making it look (and behave) like the click
// itself navigated away instantly with no chance to actually use the dropdown.
// The parent page itself stays reachable via an explicit "All Services"/
// "All Industries" link placed first inside the submenu (Agency skips this
// since "About Us" already covers it).
// The onclick lives on the <li>, so this also fires for clicks that bubble up
// from the submenu's own links -- those must be left alone to navigate normally,
// only a click on the parent label/chevron itself should toggle+prevent.
function toggleDropdown(e) {
    if (e.target.closest('.dropdown-menu')) return;
    e.preventDefault();
    e.currentTarget.classList.toggle('active');
}

// .active now keeps a dropdown's submenu visible independent of :hover (see the
// matching CSS rule), so on desktop a click-opened dropdown no longer auto-closes
// just because the pointer moves away. Close it explicitly on any click outside
// the dropdown itself, the standard "click outside to dismiss" pattern.
document.addEventListener('click', (e) => {
    if (e.target.closest('.dropdown')) return;
    document.querySelectorAll('.dropdown.active').forEach(d => d.classList.remove('active'));
});

// services.php's "WHAT WE DO" flip cards (.service-card-flip) rely on :hover
// to flip on desktop, which works fine with a real pointer but is unreliable
// on touch -- there's no hover state to "move off of" to flip back, and a tap
// can't preview the back before deciding whether to follow the Learn More
// link. On devices with no real hover, use tap instead: the first tap on a
// not-yet-flipped card flips it (and un-flips any other open card) without
// following its link; once flipped, taps behave normally so the Learn More
// link and back-face content are fully reachable.
if (window.matchMedia('(hover: none)').matches) {
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (!card.classList.contains('flipped')) {
                e.preventDefault();
                document.querySelectorAll('.service-card.flipped').forEach(c => {
                    if (c !== card) c.classList.remove('flipped');
                });
                card.classList.add('flipped');
            }
        });
    });
    document.addEventListener('click', (e) => {
        if (e.target.closest('.service-card')) return;
        document.querySelectorAll('.service-card.flipped').forEach(c => c.classList.remove('flipped'));
    });
}

// Team cards reveal their description/socials on :hover, which has no
// equivalent on touch -- there's no pointer to "move off of" to close it
// again, and no way to preview the reveal before deciding whether to tap a
// social link. On devices with no real hover, tap the card to toggle
// .tapped instead (closing any other open card first), same pattern as the
// dropdown above. A tap on a link inside the card (e.g. a social icon)
// still follows that link normally rather than just toggling the reveal.
if (window.matchMedia('(hover: none)').matches) {
    document.querySelectorAll('.team-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (e.target.closest('a')) return;
            const wasOpen = card.classList.contains('tapped');
            document.querySelectorAll('.team-card.tapped').forEach(c => c.classList.remove('tapped'));
            if (!wasOpen) card.classList.add('tapped');
        });
    });
    document.addEventListener('click', (e) => {
        if (e.target.closest('.team-card')) return;
        document.querySelectorAll('.team-card.tapped').forEach(c => c.classList.remove('tapped'));
    });
}

        const progressBar = document.getElementById('scroll-progress');
        const backToTopBtn = document.getElementById('back-to-top');

        window.addEventListener('scroll', () => {
            const scrollable = document.documentElement.scrollHeight - window.innerHeight;
            const progress = scrollable > 0 ? (window.scrollY / scrollable) * 100 : 0;
            if (progressBar) progressBar.style.width = progress + '%';

            if (backToTopBtn) backToTopBtn.classList.toggle('is-visible', window.scrollY > 600);
        }, { passive: true });

        if (backToTopBtn) {
            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
            });
        }

        // Lightbox for case study proof-gallery screenshots (case-study.php) --
        // event delegation on document so it needs no per-image listeners and
        // still works regardless of how many proof images a given page has.
        const lightbox = document.getElementById('imageLightbox');
        if (lightbox) {
            const lightboxImg = lightbox.querySelector('img');
            const lightboxCaption = lightbox.querySelector('.image-lightbox-caption');
            let lightboxReturnFocus = null;

            const openLightbox = (img) => {
                lightboxReturnFocus = img;
                lightboxImg.src = img.currentSrc || img.src;
                lightboxImg.alt = img.alt || '';
                lightboxCaption.textContent = img.closest('figure')?.querySelector('figcaption')?.textContent || '';
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            };
            const closeLightbox = () => {
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
                if (lightboxReturnFocus) lightboxReturnFocus.focus({ preventScroll: true });
            };

            document.addEventListener('click', (e) => {
                const img = e.target.closest('.proof-gallery-item img');
                if (img) openLightbox(img);
            });
            document.addEventListener('keydown', (e) => {
                const img = e.target.closest('.proof-gallery-item img');
                if (img && (e.key === 'Enter' || e.key === ' ')) {
                    e.preventDefault();
                    openLightbox(img);
                }
            });
            lightbox.querySelector('.image-lightbox-close').addEventListener('click', closeLightbox);
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) closeLightbox();
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && lightbox.classList.contains('is-open')) closeLightbox();
            });
        }

        let navRollTween = null;
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            const wrapEl = document.querySelector('.nav-wrapper');
            const rollEdge = document.getElementById('navRollEdge');
            const header = document.querySelector('header');
            const toggleBtn = document.querySelector('.mobile-btn');
            const toggleIcon = document.querySelector('.mobile-btn i');
            const isOpening = !menu.classList.contains('active');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (toggleIcon) toggleIcon.className = isOpening ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', String(isOpening));
            if (navRollTween) navRollTween.kill();

            const finishClose = () => {
                menu.classList.remove('active');
                header.classList.remove('nav-open');
                // The carpet-roll animation above sets clip-path directly as an inline
                // style. At mobile/tablet widths the closed-state stylesheet rule
                // (@media max-width:1200px) sets the same value, so it's harmless -- but
                // at full desktop width nothing in the stylesheet governs clip-path at
                // all, since it's only ever meant to apply while the header is in its
                // scrolled/hamburger-driven state. Left in place, that stray inline value
                // (fully clipped) would keep clipping the nav-menu to invisible even after
                // scrolling back past the point where .scrolled is removed, permanently
                // hiding the whole desktop nav. Clearing it lets the current class/media
                // state (or lack of one) take back over cleanly.
                gsap.set(menu, { clearProps: 'clipPath' });
            };

            if (isOpening) {
                menu.classList.add('active');
                header.classList.add('nav-open');
            }

            // The toggle now drives the menu identically at every scroll position
            // and viewport width (see the header.nav-open rules in css/style.css),
            // so the carpet-roll reveal below always applies -- this guard is only
            // for genuinely missing dependencies, not a width/scroll check anymore.
            if (typeof gsap === 'undefined' || !wrapEl || !rollEdge) {
                if (!isOpening) finishClose();
                return;
            }

            if (prefersReducedMotion) {
                gsap.set(rollEdge, { autoAlpha: 0 });
                if (!isOpening) finishClose();
                return;
            }

            // Measure the dropdown panel relative to .nav-wrapper (the roll-edge's actual
            // positioning context, same as .nav-menu) so the "carpet" cylinder tracks its
            // real width/height at any breakpoint.
            const wrapRect = wrapEl.getBoundingClientRect();
            const menuRect = menu.getBoundingClientRect();
            const w = Math.max(menuRect.width, 10);
            const h = Math.max(menuRect.height, 10);
            gsap.set(rollEdge, { top: menuRect.top - wrapRect.top, left: menuRect.left - wrapRect.left, height: h, width: 16 });

            if (isOpening) {
                // Carpet starts rolled up flat against the left edge, then unrolls to the right.
                gsap.set(menu, { clipPath: 'inset(0 100% 0 0)' });
                gsap.set(rollEdge, { x: 0, autoAlpha: 1 });
                navRollTween = gsap.timeline({
                    // clip-path: inset(0 0% 0 0) reads as "no clipping", but it's still an
                    // active clip region sized to the menu's OWN (small) box -- harmless for
                    // the mobile/tablet accordion, whose dropdowns sit in normal flow inside
                    // that same box, but it silently clips away anything that deliberately
                    // overflows beyond it, like the absolutely-positioned desktop flyout
                    // dropdown reopened after scrolling. Clear it once fully open so the menu
                    // is governed purely by the class state again, same as finishClose() does.
                    onComplete: () => gsap.set(menu, { clearProps: 'clipPath' }),
                })
                    .to(menu, { clipPath: 'inset(0 0% 0 0)', duration: 0.75, ease: 'power3.out' }, 0)
                    .to(rollEdge, { x: w - 16, duration: 0.75, ease: 'power3.out' }, 0)
                    .to(rollEdge, { autoAlpha: 0, duration: 0.2 }, 0.55);
            } else {
                // Reverse: the carpet re-rolls from the right edge back up to the left.
                gsap.set(rollEdge, { x: w - 16, autoAlpha: 1 });
                navRollTween = gsap.timeline({ onComplete: finishClose })
                    .to(menu, { clipPath: 'inset(0 100% 0 0)', duration: 0.55, ease: 'power2.inOut' }, 0)
                    .to(rollEdge, { x: 0, duration: 0.55, ease: 'power2.inOut' }, 0)
                    .to(rollEdge, { autoAlpha: 0, duration: 0.15 }, 0.4);
            }
        }

        // Pause the trusted-strip marquee on touch. Real mouse/pen hover already
        // pauses it via the fine-pointer :hover rule in css/style.css -- pure CSS,
        // no JS needed, and animation-play-state:paused freezes it at its current
        // frame so nothing here has to track or restore position. Touch devices
        // have no hover state at all, so touching the marquee toggles this class
        // instead, pausing on touchstart and resuming the instant the finger lifts
        // or the touch is cancelled. Doesn't depend on GSAP, so it runs standalone
        // rather than inside the DOMContentLoaded block below.
        document.querySelectorAll('.partner-marquee').forEach((marquee) => {
            const pause = () => marquee.classList.add('is-paused');
            const resume = () => marquee.classList.remove('is-paused');
            marquee.addEventListener('touchstart', pause, { passive: true });
            marquee.addEventListener('touchend', resume);
            marquee.addEventListener('touchcancel', resume);
        });

        document.addEventListener("DOMContentLoaded", () => {
            gsap.registerPlugin(ScrollTrigger);

            // The header looks and behaves identically at the top of the page and
            // after scrolling (see css/style.css), so .scrolled no longer drives any
            // layout -- it's tracked purely as a hook for a future cosmetic touch
            // (e.g. a stronger shadow once scrolled) and, unlike before, scrolling
            // never force-closes an open nav any more, since there's no longer a
            // "collapsed-only" open state for it to drift out of sync with.
            const headerEl = document.querySelector('header');
            if (headerEl) {
                ScrollTrigger.create({
                    start: 50,
                    onEnter: () => headerEl.classList.add('scrolled'),
                    onLeaveBack: () => headerEl.classList.remove('scrolled'),
                    onRefresh: (self) => headerEl.classList.toggle('scrolled', self.scroll() > 50),
                });
            }

            // Every ScrollTrigger's start/end is calculated in absolute page pixels the
            // moment it's created. Offscreen <img loading="lazy"> elements don't download
            // until they near the viewport, so any that load AFTER that initial calc (and
            // aren't held to a fixed-size container) shift the document's real height --
            // permanently desyncing every trigger positioned below them until something
            // forces a recalculation. That's what made sections further down the page
            // (methodology, testimonials, etc.) look wrong until the user actually
            // scrolled near them. Refresh once, debounced, whenever a lazy image settles.
            let lazyImgRefreshTimer = null;
            const scheduleLazyImgRefresh = () => {
                clearTimeout(lazyImgRefreshTimer);
                lazyImgRefreshTimer = setTimeout(() => ScrollTrigger.refresh(), 120);
            };
            document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                if (img.complete) return;
                img.addEventListener('load', scheduleLazyImgRefresh, { once: true });
            });

            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            let mm = gsap.matchMedia();

            mm.add("(min-width: 769px)", () => {
                gsap.set(".laptop-container", { xPercent: -50, yPercent: 0 });
                gsap.set(".mobile-container", { xPercent: -50, yPercent: -50, scale: 1 });
                gsap.set(".scatter-item", { xPercent: -50, yPercent: -50, opacity: 1, scale: 1 });
                gsap.set(".stage-header", { opacity: 0, y: -20 });

                let masterTl = gsap.timeline({
                    scrollTrigger: {
                        trigger: ".cinematic-wrapper",
                        start: "top 80px",        
                        end: "+=3500",            
                        pin: true,                
                        scrub: 1                  
                    }
                });

                masterTl.to(".scatter-item", {
                    x: (index) => (index % 2 === 0) ? -250 : 250, 
                    y: (index) => (index < 2) ? -150 : 150, 
                    duration: 1, 
                    ease: "power2.out"
                }, 0);

                masterTl.to(".serp-screen", { y: "-55%", duration: 1.5, ease: "none" }, 0.5);
                masterTl.to(".serp-screen", { opacity: 0, duration: 0.2 }, 2.0);
                masterTl.to(".logo-screen", { opacity: 1, duration: 0.3 }, 2.2);
                masterTl.to(".scatter-item", { opacity: 0, scale: 0.8, duration: 0.3 }, 2.0);

                masterTl.to(".laptop-container", { yPercent: 0, opacity: 1, duration: 2, ease: "power3.inOut" }, 2.5); 
                masterTl.to(".mobile-container", { left: "86%", top: "70%", scale: 0.35, boxShadow: "0 20px 40px rgba(0,0,0,0.5)", duration: 2, ease: "power3.inOut" }, 2.5); 
                masterTl.to(".stage-header", { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" }, 3.0);
                
                return () => {}; 
            });

            mm.add("(max-width: 768px)", () => {
                gsap.set(".stage-header", { opacity: 1, y: 0 });
                gsap.set(".serp-screen", { y: "0%", opacity: 1 });
                gsap.set(".logo-screen", { opacity: 0 });
                
                let mobileTl = gsap.timeline({
                    scrollTrigger: {
                        trigger: ".cinematic-wrapper",
                        start: "top 85px", 
                        end: "+=800", 
                        pin: true,
                        scrub: 1
                    }
                });

                mobileTl.to(".serp-screen", { y: "-55%", duration: 2, ease: "none" });
                mobileTl.to(".serp-screen", { opacity: 0, duration: 0.3 });
                mobileTl.to(".logo-screen", { opacity: 1, duration: 0.5 });
                
                return () => {}; 
            });

            // === PREMIUM SITE-WIDE ENTRANCE ANIMATIONS ===
            // Everything below is purely decorative motion, so it's skipped
            // entirely for users who've asked for reduced motion.
            if (prefersReducedMotion) {
                gsap.set('.fade-up, .hero .fade-up', { opacity: 1, y: 0, x: 0 });
                gsap.set('.seo-chart-fill', { scaleX: 1 });
                return;
            }

            // clearProps removes the inline transform once the tween finishes instead of
            // leaving it resting at translate(0px, 0px) (still a non-"none" transform
            // value) forever after. That stray transform makes the fixed header create a
            // permanent compositing layer, which mis-paints/hides descendants that extend
            // far beyond the header's own ~100px height -- like the Industries dropdown
            // flyout reopened after scrolling, which is over 300px tall.
            gsap.fromTo('header', { y: -80, opacity: 0 }, { y: 0, opacity: 1, duration: 0.8, ease: 'power3.out', clearProps: 'transform' });

            // Wraps every word inside a heading in its own <span class="split-word">,
            // walking the DOM so existing markup (line breaks, the gradient <span>)
            // is preserved rather than clobbered by a naive textContent rebuild.
            function splitWords(container) {
                const words = [];
                function walk(node) {
                    if (node.nodeType === Node.TEXT_NODE) {
                        const parts = node.textContent.split(/(\s+)/);
                        const frag = document.createDocumentFragment();
                        parts.forEach(part => {
                            if (part === '') return;
                            if (/^\s+$/.test(part)) {
                                frag.appendChild(document.createTextNode(part));
                            } else {
                                const span = document.createElement('span');
                                span.className = 'split-word';
                                span.textContent = part;
                                frag.appendChild(span);
                                words.push(span);
                            }
                        });
                        node.parentNode.replaceChild(frag, node);
                    } else if (node.nodeType === Node.ELEMENT_NODE) {
                        // Gradient-clipped text (.text-gradient) paints via its own
                        // background with background-clip:text, which doesn't
                        // inherit to children — splitting its words into separate
                        // spans would strip the gradient off entirely (each child
                        // has no background of its own to clip). Animate it as one
                        // atomic unit instead of recursing into its text.
                        if (node.classList && node.classList.contains('text-gradient')) {
                            node.classList.add('split-word');
                            words.push(node);
                            return;
                        }
                        Array.from(node.childNodes).forEach(walk);
                    }
                }
                Array.from(container.childNodes).forEach(walk);
                return words;
            }

            // Hero copy plays immediately as a staggered sequence rather than
            // each line fading in independently at the same instant. The H1
            // gets a more dramatic word-by-word reveal instead of a flat fade.
            const heroHeading = document.querySelector('.hero-content h1');
            const heroHeadingWords = heroHeading ? splitWords(heroHeading) : [];
            if (heroHeadingWords.length) gsap.set(heroHeadingWords, { opacity: 0, y: 22 });

            const heroEls = gsap.utils.toArray('.hero .fade-up').filter(el => el.tagName !== 'H1');
            if (heroEls.length) {
                gsap.fromTo(heroEls, { opacity: 0, y: 30 }, {
                    opacity: 1, y: 0, duration: 0.7, stagger: 0.14, ease: 'power2.out', delay: 0.15
                });
            }
            if (heroHeadingWords.length) {
                gsap.to(heroHeadingWords, { opacity: 1, y: 0, duration: 0.6, stagger: 0.028, ease: 'power2.out', delay: 0.15 });
            }

            // Section headings (the h2 inside every .section-header) get the
            // same word-by-word treatment as they scroll into view.
            document.querySelectorAll('.section-header h2').forEach(h2 => {
                const words = splitWords(h2);
                if (!words.length) return;
                gsap.set(words, { opacity: 0, y: 18 });
                gsap.to(words, {
                    opacity: 1, y: 0, duration: 0.5, stagger: 0.025, ease: 'power2.out',
                    scrollTrigger: { trigger: h2, start: 'top 88%', toggleActions: 'play none none none' }
                });
            });

            // Every other .fade-up (section wrappers, CTA boxes, footer columns)
            // reveals as the user scrolls to it.
            gsap.utils.toArray('.fade-up').forEach(element => {
                if (heroEls.includes(element)) return;
                gsap.fromTo(element,
                    { opacity: 0, y: 40 },
                    { opacity: 1, y: 0, duration: 0.6, ease: "power2.out", clearProps: 'transform',
                      scrollTrigger: { trigger: element, start: "top 85%", toggleActions: "play none none none" }
                    }
                );
            });

            // Every card/list inside these grids gets its own scroll-triggered
            // reveal (rather than one trigger for the whole grid) so long grids
            // like the case studies page still animate correctly row by row.
            const gridSelectors = [
                '.case-grid', '.testimonials-grid', '.industry-tile-grid',
                '.stats-4-grid', '.pricing-grid', '.methodology-steps', '.agency-list',
                '.cta-features', '.faq-list', '.mobile-dash-cards', '.partner-strip', '.team-grid',
                '.seo-proof-grid'
            ];
            gridSelectors.forEach(selector => {
                gsap.utils.toArray(selector).forEach(grid => {
                    const items = gsap.utils.toArray(grid.children);
                    items.forEach((item, i) => {
                        gsap.fromTo(item, { opacity: 0, y: 34 }, {
                            opacity: 1, y: 0, duration: 0.55, delay: (i % 3) * 0.08, ease: 'power2.out', clearProps: 'transform',
                            scrollTrigger: { trigger: item, start: 'top 90%', toggleActions: 'play none none none' }
                        });
                    });
                });
            });

            // Homepage "Digital Services" cards: every photo is already on the card,
            // so instead of a hover/click reveal each card wipes in from the bottom
            // as the grid scrolls into view (photo settling from a zoom, then the
            // icon, title, copy and link rising in turn). clearProps hands the
            // card back to CSS afterwards so its hover lift/zoom still works.
            const svcCards = gsap.utils.toArray('.svc-card');
            if (svcCards.length) {
                const svcParts = card => card.querySelectorAll('.svc-top, .svc-body h3, .svc-body p, .svc-link');
                svcCards.forEach(card => {
                    gsap.set(card, { clipPath: 'inset(100% 0% 0% 0% round 22px)', y: 50 });
                    gsap.set(card.querySelector('.svc-media img'), { scale: 1.4 });
                    gsap.set(svcParts(card), { opacity: 0, y: 26 });
                });
                ScrollTrigger.batch(svcCards, {
                    start: 'top 90%', once: true, interval: 0.1,
                    onEnter: batch => batch.forEach((card, i) => {
                        gsap.timeline({ delay: i * 0.12 })
                            .to(card, { clipPath: 'inset(0% 0% 0% 0% round 22px)', y: 0, duration: 0.9, ease: 'power3.out', clearProps: 'clipPath,transform' }, 0)
                            .to(card.querySelector('.svc-media img'), { scale: 1.03, duration: 1.4, ease: 'power2.out', clearProps: 'transform' }, 0)
                            .to(svcParts(card), { opacity: 1, y: 0, duration: 0.6, stagger: 0.09, ease: 'power2.out', clearProps: 'opacity,transform' }, 0.35);
                    })
                });
            }

            // seo.php "Organic Clicks By Campaign" bars grow left-to-right (scaleX
            // 0 -> 1, transform-origin: left) once the chart scrolls into view --
            // one ScrollTrigger for the whole card, individual bars staggered so
            // they read top-to-bottom like a value is being counted up.
            gsap.utils.toArray('.seo-chart-card').forEach(card => {
                const bars = gsap.utils.toArray(card.querySelectorAll('.seo-chart-fill'));
                if (!bars.length) return;
                gsap.to(bars, {
                    scaleX: 1, duration: 1.1, stagger: 0.12, ease: 'power3.out',
                    scrollTrigger: { trigger: card, start: 'top 80%', toggleActions: 'play none none none' }
                });
            });

            // "OUR EXPERTISE" service cards (index.php + services.php) get a bolder
            // 3D pop-in instead of the plain fade-up every other grid uses -- a
            // little scale/rotateX plus a "back.out" overshoot ease, so the entrance
            // itself hints at the flip interaction the cards do on hover. The
            // rotateX/scale is applied to the OUTER .service-card, not
            // .service-card-inner (that element is reserved for the hover flip
            // itself), so the two transforms never fight each other.
            gsap.utils.toArray('.services-grid').forEach(grid => {
                const cards = gsap.utils.toArray(grid.children);
                cards.forEach((card, i) => {
                    gsap.fromTo(card,
                        { opacity: 0, y: 55, scale: 0.86, rotateX: -10, transformPerspective: 1200 },
                        {
                            opacity: 1, y: 0, scale: 1, rotateX: 0,
                            duration: 0.75, delay: (i % 3) * 0.1, ease: 'back.out(1.6)', clearProps: 'transform',
                            scrollTrigger: { trigger: card, start: 'top 90%', toggleActions: 'play none none none' }
                        }
                    );
                });
            });

            // === "OUR EXPERTISE": scroll-driven pinned storytelling (index.php +
            // services.php) === See the big comment above .expertise-scroll-wrap in
            // css/style.css for the full design. The CSS default is a plain,
            // always-expanded stacked list of panels -- that's what mobile, reduced
            // motion, and (if this never runs) no-JS visitors get. Only on wide
            // screens with motion allowed do we escalate to the interactive version:
            // one item's giant centered title holds, fades/slides out while its row
            // grows into an accumulating numbered list and its image panel expands,
            // then the panel collapses (row stays) as the next item's title fades in.
            document.querySelectorAll('.expertise-scroll-wrap').forEach(wrap => {
                const rows = gsap.utils.toArray(wrap.querySelectorAll('.expertise-row'));
                const heroes = gsap.utils.toArray(wrap.querySelectorAll('.expertise-hero'));
                const panels = gsap.utils.toArray(wrap.querySelectorAll('.expertise-panel'));
                const n = rows.length;
                if (!n) return;

                mm.add('(min-width: 900px)', () => {
                    const section = wrap.closest('.expertise-scroll-section');
                    section.classList.add('expertise-js-ready');
                    const pinEl = wrap.querySelector('.expertise-pin');

                    // A row's height:auto isn't tweenable, so measure its inner
                    // content's natural pixel height up front -- the row itself is
                    // already visually collapsed to 0 by CSS regardless, this just
                    // records what "open" should animate to.
                    rows.forEach(row => {
                        row._openHeight = row.querySelector('.expertise-row-inner').offsetHeight;
                    });

                    gsap.set(heroes, { autoAlpha: 0 });
                    gsap.set(heroes[0], { autoAlpha: 1 });
                    gsap.set(panels, { autoAlpha: 0, scale: 0.97 });
                    gsap.set(rows, { height: 0, opacity: 0 });

                    // "top 75px" (not "top top") so the pinned stage settles just below
                    // the fixed header's scrolled height instead of directly underneath
                    // it -- by the time a visitor has scrolled this far the header is
                    // already in its .scrolled (75px) state for effectively the entire
                    // pinned duration.
                    const tl = gsap.timeline({
                        scrollTrigger: {
                            trigger: wrap,
                            start: 'top 75px',
                            end: () => '+=' + Math.round(n * window.innerHeight * 0.85),
                            scrub: 0.6,
                            pin: pinEl,
                            anticipatePin: 1,
                            invalidateOnRefresh: true,
                        }
                    });

                    rows.forEach((row, i) => {
                        if (i === 0) {
                            tl.to({}, { duration: 0.4 }); // reading pause on the first hero, already visible at rest
                        } else {
                            tl.to(heroes[i], { autoAlpha: 1, duration: 0.35 });
                            tl.to({}, { duration: 0.25 });
                        }
                        // Hero fades/slides toward the list while its row grows open --
                        // two separate elements, timed to overlap, rather than a literal
                        // shared-element morph (far more fragile to keep smooth/bug-free
                        // across every viewport), but reads as one continuous movement.
                        tl.to(heroes[i], { autoAlpha: 0, x: -50, scale: 0.4, duration: 0.35, ease: 'power2.in' });
                        tl.to(row, { height: row._openHeight, opacity: 1, duration: 0.35, ease: 'power2.out' }, '<');
                        tl.to(panels[i], { autoAlpha: 1, scale: 1, duration: 0.4, ease: 'power2.out' }, '<0.1');
                        tl.to({}, { duration: 0.6 }); // reading pause on the expanded panel
                        // The last item's panel stays expanded at rest instead of
                        // collapsing away to an empty stage right as the pin releases.
                        if (i < n - 1) {
                            tl.to(panels[i], { autoAlpha: 0, scale: 0.97, duration: 0.3, ease: 'power2.in' });
                        }
                    });

                    return () => section.classList.remove('expertise-js-ready');
                });

                // Below 900px the pin never engages (see the CSS) -- panels are
                // always statically expanded, just fading up into view individually
                // as the user reaches them, same as every other grid on the site.
                mm.add('(max-width: 899px)', () => {
                    panels.forEach(panel => {
                        gsap.fromTo(panel, { opacity: 0, y: 34 }, {
                            opacity: 1, y: 0, duration: 0.55, ease: 'power2.out', clearProps: 'transform',
                            scrollTrigger: { trigger: panel, start: 'top 90%', toggleActions: 'play none none none' }
                        });
                    });
                    return () => {};
                });
            });

            // Two-column image/text (or info/form) sections slide in from opposite sides --
            // only at the desktop width where these grids are actually two columns (matches
            // the CSS breakpoint that collapses .agency-grid/.contact-grid to a single
            // column at 1024px and below). Without this guard, the initial x:50/x:-50
            // offset below was being applied at every viewport width, pushing content 50px
            // past the single-column mobile layout's edge and causing horizontal page
            // overflow on every page that uses these grids. (.methodology-grid used to be
            // a third member of this pattern -- the "Our Process" image+steps sections --
            // but the image column is gone now, so .methodology-steps is just a full-width
            // vertical list on its own and gets the standard per-item stagger reveal
            // instead, same as every other entry in the gridSelectors list above.)
            mm.add("(min-width: 1025px)", () => {
                gsap.utils.toArray('.agency-grid, .contact-grid').forEach(grid => {
                    const media = grid.querySelector('.agency-image-wrapper, .contact-info-card');
                    const copy = grid.querySelector('.agency-text, .contact-form-card');
                    const trigger = { trigger: grid, start: 'top 82%', toggleActions: 'play none none none' };
                    if (media) gsap.fromTo(media, { opacity: 0, x: -50 }, { opacity: 1, x: 0, duration: 0.8, ease: 'power2.out', scrollTrigger: trigger });
                    if (copy) gsap.fromTo(copy, { opacity: 0, x: 50 }, { opacity: 1, x: 0, duration: 0.8, ease: 'power2.out', scrollTrigger: trigger });
                });
                return () => {};
            });

            // Stat numbers count up from zero once they scroll into view.
            gsap.utils.toArray('.stat-box h3').forEach(el => {
                const text = el.textContent.trim();
                const match = text.match(/^([^0-9\-]*)(-?[0-9.,]+)(.*)$/);
                if (!match) return;
                const [, prefix, rawNum, suffix] = match;
                const cleanNum = rawNum.replace(/,/g, '');
                const target = parseFloat(cleanNum);
                if (isNaN(target)) return;
                const decimals = (cleanNum.split('.')[1] || '').length;
                const counter = { val: 0 };
                ScrollTrigger.create({
                    trigger: el, start: 'top 88%', once: true,
                    onEnter: () => {
                        gsap.to(counter, {
                            val: target, duration: 1.5, ease: 'power2.out',
                            onUpdate: () => { el.textContent = prefix + counter.val.toFixed(decimals) + suffix; }
                        });
                    }
                });
            });
        });


// Homepage Google reviews slider: prev/next buttons scroll the snap track one
// card at a time, "Read more" appears only on cards whose text is clamped, and
// the track advances itself every few seconds until the visitor hovers, focuses
// or touches it (never when reduced motion is requested).
(function () {
    const track = document.querySelector('.rv-track');
    if (!track) return;
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const prev = document.querySelector('[data-rv="prev"]');
    const next = document.querySelector('[data-rv="next"]');
    const step = () => {
        const card = track.querySelector('.rv-card');
        return card ? card.getBoundingClientRect().width + 22 : 300;
    };
    const atEnd = () => track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
    const sync = () => {
        if (prev) prev.disabled = track.scrollLeft <= 4;
        if (next) next.disabled = atEnd();
    };
    const go = dir => track.scrollBy({ left: dir * step(), behavior: reduce ? 'auto' : 'smooth' });
    if (prev) prev.addEventListener('click', () => go(-1));
    if (next) next.addEventListener('click', () => go(1));
    track.addEventListener('scroll', sync, { passive: true });
    track.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight') { e.preventDefault(); go(1); }
        if (e.key === 'ArrowLeft') { e.preventDefault(); go(-1); }
    });

    const markClamped = () => {
        track.querySelectorAll('.rv-card').forEach(card => {
            const text = card.querySelector('.rv-text');
            const more = card.querySelector('.rv-more');
            if (!text || !more || card.classList.contains('is-open')) return;
            more.hidden = text.scrollHeight <= text.clientHeight + 1;
        });
    };
    track.addEventListener('click', e => {
        const more = e.target.closest('.rv-more');
        if (!more) return;
        const card = more.closest('.rv-card');
        const open = card.classList.toggle('is-open');
        more.textContent = open ? 'Read less' : 'Read more';
    });
    markClamped();
    window.addEventListener('load', () => { markClamped(); sync(); });
    window.addEventListener('resize', () => { markClamped(); sync(); });
    sync();

    if (reduce) return;
    let paused = false, visible = false;
    ['mouseenter', 'focusin', 'touchstart'].forEach(ev => track.addEventListener(ev, () => { paused = true; }, { passive: true }));
    ['mouseleave', 'focusout'].forEach(ev => track.addEventListener(ev, () => { paused = false; }));
    if ('IntersectionObserver' in window) {
        new IntersectionObserver(entries => { visible = entries[0].isIntersecting; }).observe(track);
    }
    setInterval(() => {
        if (paused || !visible || track.querySelector('.rv-card.is-open')) return;
        if (atEnd()) track.scrollTo({ left: 0, behavior: 'smooth' });
        else go(1);
    }, 6000);
})();

// FAQ accordions (smooth open/close). Two flavours share one animation:
//  1. Homepage: <button aria-expanded> triggers. The answers are all in the HTML
//     on load (so crawlers and no-JS visitors see everything); this collapses
//     all but the first into an accordion.
//  2. /faq, services, industries, content-writing: native <details class="faq-item">.
//     A native <details> snaps open/shut, so the click is intercepted and the
//     element's height is animated instead. Keyboard use (Enter/Space on the
//     summary) fires the same click, and without JS it still works natively.
// Anything is skipped for visitors who ask for reduced motion.
(function () {
    const reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const DURATION = 150;
    const EASE = 'ease-out';
    const px = v => (parseFloat(v) || 0);

    // Homepage accordion
    const list = document.querySelector('[data-faq]');
    if (list) {
        const items = Array.from(list.querySelectorAll('.hfaq-item'));
        const setOpen = (item, open, animate) => {
            const btn = item.querySelector('.hfaq-q');
            const panel = item.querySelector('.hfaq-answer');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            item.classList.toggle('is-open', open);
            if (panel._anim) {
                // Interrupted mid-animation: start from what is on screen right now.
                var cs = getComputedStyle(panel);
                var from = { height: panel.offsetHeight + 'px', paddingBottom: cs.paddingBottom, opacity: cs.opacity };
                panel._anim.cancel();
                panel._anim = null;
            }
            if (!animate || reduce || !panel.animate) {
                panel.hidden = !open;
                return;
            }
            const wasHidden = panel.hidden;
            panel.hidden = false;
            const full = panel.offsetHeight;
            const padBottom = getComputedStyle(panel).paddingBottom;
            const shut = { height: '0px', paddingBottom: '0px', opacity: 0 };
            const shown = { height: full + 'px', paddingBottom: padBottom, opacity: 1 };
            const start = from || (wasHidden ? shut : shown);
            panel.style.overflow = 'hidden';
            const anim = panel._anim = panel.animate(
                open ? [start, shown] : [start, shut],
                { duration: DURATION, easing: EASE }
            );
            anim.onfinish = () => {
                panel._anim = null;
                panel.style.overflow = '';
                panel.hidden = !open;
            };
        };
        items.forEach((item, i) => setOpen(item, i === 0, false));
        list.addEventListener('click', e => {
            const btn = e.target.closest('.hfaq-q');
            if (!btn) return;
            const item = btn.closest('.hfaq-item');
            setOpen(item, btn.getAttribute('aria-expanded') !== 'true', true);
        });
    }

    // Native <details> accordions
    if (reduce) return;
    document.querySelectorAll('details.faq-item').forEach(d => {
        const summary = d.querySelector('summary');
        if (!summary || !d.animate) return;
        summary.addEventListener('click', e => {
            e.preventDefault();
            const cs = getComputedStyle(d);
            const chrome = px(cs.paddingTop) + px(cs.paddingBottom) + px(cs.borderTopWidth) + px(cs.borderBottomWidth);
            const start = d.offsetHeight;                       // what is on screen right now
            const opening = d._closing || !d.open;
            if (d._anim) { d._anim.cancel(); d._anim = null; }
            d._closing = !opening;
            d.classList.toggle('is-closing', !opening);
            if (opening) d.open = true;
            const end = opening ? d.offsetHeight : summary.offsetHeight + chrome;
            d.style.overflow = 'hidden';
            const anim = d._anim = d.animate(
                { height: [start + 'px', end + 'px'] },
                { duration: DURATION, easing: EASE }
            );
            anim.onfinish = () => {
                if (!opening) d.open = false;
                d._anim = null;
                d._closing = false;
                d.classList.remove('is-closing');
                d.style.overflow = '';
            };
        });
    });
})();
