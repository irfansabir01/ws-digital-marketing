<?php if (function_exists('seo_render_extra_content')) seo_render_extra_content(); ?>
    </main>

    <!-- PREMIUM FOOTER -->

    <footer>
        <div class="container">
            <div class="footer-grid fade-up">
                <!-- Brand Col -->
                <div class="footer-brand">
                    <a href="/" class="logo footer-logo">
                        <img src="/images/logo-ws.webp" alt="W&S Digital Marketing Logo" class="logo-img">
                    </a>
                    <p>
                        Helping ambitious Australian businesses confidently grow online with data-driven strategies that consistently deliver real results.
                    </p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/w.and.s.digital.marketing" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/wsdigitalmarketing" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://www.linkedin.com/company/w-s-digital-marketing/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="footer-heading">Company</h3>
                    <ul class="footer-links">
                        <li><a href="/">Home</a></li>
                        <li><a href="/about">About Us</a></li>
                        <li><a href="/our-team">Our Team</a></li>
                        <li><a href="/services">Services</a></li>
                        <li><a href="/industries">Industries</a></li>
                        <li><a href="/case-studies">Case Studies</a></li>
                        <li><a href="/pricing">Pricing</a></li>
                        <li><a href="/blog">Blog</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h3 class="footer-heading">Services</h3>
                    <ul class="footer-links">
                        <li><a href="/seo">Search Engine Optimisation</a></li>
                        <li><a href="/ecommerce">Ecommerce</a></li>
                        <li><a href="/website-development">Website Design &amp; Development</a></li>
                        <li><a href="/social-media">Social Media Marketing</a></li>
                        <li><a href="/ppc-advertising">Paid Advertising</a></li>
                        <li><a href="/graphic-design">Graphic Design</a></li>
                        <li><a href="/content-writing">Content Writing</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="footer-heading">Get In Touch</h3>
                    <ul class="footer-contact">
                        <li><i class="fa-solid fa-phone"></i> <a href="tel:+61403889630"><span>+61 403 889 630</span></a></li>
                        <li><i class="fa-solid fa-envelope"></i> <a href="mailto:info@wsdigitalmarketing.com.au"><span>info@wsdigitalmarketing.com.au</span></a></li>
                        <li><i class="fa-solid fa-location-dot"></i> <span>3 Weebill St, Melonba, NSW 2765, Australia</span></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>© 2026 W&S Digital Marketing. All Rights Reserved.</p>
                <div style="display: flex; gap: 20px;">
                    <a href="/privacy-policy" style="color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.3s;">Privacy Policy</a>
                    <a href="/terms-of-service" style="color: rgba(255,255,255,0.5); text-decoration: none; transition: color 0.3s;">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <div id="scroll-progress"></div>
    <a href="https://wa.me/61403889630" target="_blank" rel="noopener noreferrer" id="whatsapp-chat" aria-label="Chat with us on WhatsApp" title="Chat with us on WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
    <button id="back-to-top" aria-label="Back to top" title="Back to top"><i class="fa-solid fa-arrow-up"></i></button>

    <!-- Site JS: nav, scroll effects, GSAP animations, and stat counters -- loads and
         runs as soon as gsap/ScrollTrigger are ready, independent of Three.js below.
         .htaccess caches this file for a year as "immutable", so the ?v= query string
         (the file's own last-modified time) is what actually busts that cache on every
         real edit -- without it, visitors who already loaded the site once would keep
         running the old JS for a full year no matter how many fixes get shipped. -->
    <script src="/js/main.js?v=<?php echo @filemtime(__DIR__ . '/js/main.js') ?: time(); ?>" defer></script>

    <!-- Three.js + the background wallpaper animation are the heaviest, least
         time-critical piece of the page (~600KB, purely decorative), so their
         <script> tags aren't injected during the initial load at all. They start
         the moment the visitor first interacts (moves the mouse, scrolls, touches,
         presses a key) -- which on a real visit is almost immediately -- or, if no
         interaction happens, a few seconds after the window "load" event in an idle
         callback. Nothing about the visible effect changes (the canvas already fades
         in via its own opacity transition once ready); it just keeps ~600KB of
         download plus the WebGL start-up work from competing with the page's first
         paint and from blocking the main thread while the page is becoming
         interactive. Three.js itself is self-hosted (same r128 build as before). -->
    <script>
        (function () {
            var started = false;
            var events = ['pointermove', 'pointerdown', 'scroll', 'wheel', 'touchstart', 'keydown'];
            function loadBackground() {
                if (started) return;
                started = true;
                events.forEach(function (ev) { window.removeEventListener(ev, loadBackground); });
                var three = document.createElement('script');
                three.src = '/vendor/three-r128/three.min.js';
                three.onload = function () {
                    var bg = document.createElement('script');
                    bg.src = '/js/bg-animation.js?v=<?php echo @filemtime(__DIR__ . '/js/bg-animation.js') ?: time(); ?>';
                    document.body.appendChild(bg);
                };
                document.body.appendChild(three);
            }
            events.forEach(function (ev) { window.addEventListener(ev, loadBackground, { passive: true }); });
            window.addEventListener('load', function () {
                setTimeout(function () {
                    if ('requestIdleCallback' in window) {
                        requestIdleCallback(loadBackground, { timeout: 2000 });
                    } else {
                        loadBackground();
                    }
                }, 3500);
            });
        })();
    </script>

<?php if (function_exists('seo_setting') && (string) seo_setting('footer_code', '') !== '') echo seo_setting('footer_code') . "\n"; ?>
    <!-- Developed and designed by Usman Zahid -->
</body>
</html>