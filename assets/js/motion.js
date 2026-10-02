/* CSS Cable — premium motion (Lenis smooth scroll + reveal + parallax). Без jQuery. */
(function () {
	'use strict';
	var cfg = window.CSSC_MOTION || {};
	var root = document.documentElement;
	var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
	var editor = document.body.classList.contains('elementor-editor-active') || /elementor-preview/.test(location.search);
	if (!root.classList.contains('cssc-motion') || reduce) { root.classList.remove('cssc-motion'); document.body.classList.add('is-loaded'); return; }

	var CSSC = window.CSSC = window.CSSC || {};
	var lenis = null;

	/* ---------- Lenis ---------- */
	if (cfg.smooth !== false && window.Lenis && !editor) {
		lenis = new window.Lenis({ lerp: 0.085, wheelMultiplier: 0.95, smoothWheel: true, syncTouch: false, anchors: false });
		CSSC.lenis = lenis;
		// якорные ссылки (кроме #zayavka — это форма)
		document.addEventListener('click', function (e) {
			var a = e.target.closest('a[href*="#"]');
			if (!a || /#zayavka$/.test(a.getAttribute('href'))) return;
			var url = new URL(a.href, location.href);
			if (url.pathname !== location.pathname || !url.hash || url.hash.length < 2) return;
			var t = document.querySelector(url.hash); if (!t) return;
			e.preventDefault(); lenis.scrollTo(t, { offset: -90, duration: 1.6, easing: function (x) { return 1 - Math.pow(1 - x, 4); } });
		});
		// останавливаем скролл при открытых модалках
		var watch = function () {
			var open = document.querySelector('.modal.is-open, .drawer.is-open, .video-modal.is-open');
			open ? lenis.stop() : lenis.start();
		};
		new MutationObserver(watch).observe(document.body, { subtree: true, attributes: true, attributeFilter: ['class'] });
	}

	/* ---------- разбивка заголовков на слова ---------- */
	function splitWords(el) {
		if (el.__split) return; el.__split = true;
		var i = 0;
		(function walk(node) {
			Array.prototype.slice.call(node.childNodes).forEach(function (n) {
				if (n.nodeType === 3) {
					var parts = n.textContent.split(/(\s+)/), frag = document.createDocumentFragment();
					parts.forEach(function (p) {
						if (!p) return;
						if (/^\s+$/.test(p)) { frag.appendChild(document.createTextNode(' ')); return; }
						var w = document.createElement('span'); w.className = 'w';
						var inner = document.createElement('i'); inner.textContent = p; inner.style.setProperty('--wi', i++);
						w.appendChild(inner); frag.appendChild(w);
					});
					n.parentNode.replaceChild(frag, n);
				} else if (n.nodeType === 1 && n.tagName !== 'BR') { walk(n); }
			});
		})(el);
		el.setAttribute('aria-label', el.textContent.replace(/\s+/g, ' ').trim());
	}

	function tag(sel, type, stagger, base, ctx) {
		(ctx || document).querySelectorAll(sel).forEach(function (el, k) {
			if (el.hasAttribute('data-a') || el.__done) return;
			el.setAttribute('data-a', type);
			el.style.setProperty('--dl', ((base || 0) + k * (stagger || 0)).toFixed(2) + 's');
			if (type === 'words') splitWords(el);
		});
	}

	function autotag() {
		tag('.cssc-hero__title', 'words', 0, 0.35);
		tag('.cssc-hero__sub', 'up', 0, 0.7);
		tag('.cssc-hero__btns .btn', 'up', 0.07, 0.8);
		tag('.cssc-hero__badge', 'scale', 0, 0.6);
		tag('.cssc-hero__aside p', 'up', 0, 0.8);
		document.querySelectorAll('.cssc-hero').forEach(function (h) { if (!h.hasAttribute('data-a')) { h.setAttribute('data-a', 'none'); } });

		tag('.cssc-products__card', 'card', 0, 0);
		tag('.cssc-products__card .pill', 'up', 0, 0.35);
		tag('.cssc-product', 'up', 0.12, 0.45);

		tag('.cssc-deliveries .h-display', 'words', 0, 0);
		tag('.cssc-deliveries__text', 'up', 0, 0.2);
		tag('.cssc-counter', 'up', 0, 0.3);
		tag('.cssc-slider__nav', 'left', 0, 0.3);
		tag('.cssc-slider__viewport', 'right', 0, 0.2);

		tag('.cssc-news .h-display', 'words', 0, 0);
		tag('.cssc-news .section-head p', 'up', 0, 0.2);
		tag('.news-card', 'news', 0.15, 0.1);

		tag('.contact-bar__logo, .contact-bar__item', 'up', 0.08, 0);
		tag('.footer-col, .footer-main .addr', 'up', 0.07, 0.1);

		/* О компании */
		tag('.ab-hero .ab-eyebrow', 'up', 0, 0.3);
		tag('.ab-hero .ab-h', 'words', 0, 0.4);
		tag('.ab-hero .ab-hero__desc', 'up', 0, 0.75);
		tag('.ab-hero .ab-fact', 'up', 0.1, 0.9);
		document.querySelectorAll('.ab:not(.ab-hero)').forEach(function (sec) {
			tag('.ab-eyebrow', 'up', 0, 0, sec);
			tag('.ab-h', 'words', 0, 0.08, sec);
			tag('.ab-label, .ab-lead, .ab-quality__text, .ab-projects__before, .ab-projects__after', 'up', 0, 0.2, sec);
			tag('.ab-text', 'up', 0, 0.25, sec);
			tag('.ab-media', 'reveal', 0.15, 0.1, sec);
			tag('.ab-tl-item', 'up', 0.15, 0.2, sec);
			tag('.ab-timeline', 'fade', 0, 0, sec);
			tag('.ab-plist li', 'up', 0.06, 0.1, sec);
			tag('.ab-num', 'up', 0.1, 0, sec);
			tag('.ab-pcard', 'scale', 0.12, 0.1, sec);
			tag('.ab-scard, .ab-cert', 'up', 0.1, 0.15, sec);
			tag('.ab-check', 'left', 0.08, 0.2, sec);
			tag('.ab-plist2 li', 'up', 0.04, 0.05, sec);
			tag('.ab-team__count', 'up', 0, 0.2, sec);
			tag('.ab-final__btn', 'up', 0, 0.35, sec);
		});

		tag('.shop-wrap .woocommerce-products-header, .shop-wrap .woocommerce-breadcrumb', 'up', 0.06, 0);
		tag('.woocommerce ul.products li.product', 'up', 0.06, 0);
		tag('.page-wrap .h-display, .entry__title', 'words', 0, 0.1);
		tag('.page-wrap .entry__content > *', 'up', 0.05, 0.2);
	}

	/* ---------- reveal ---------- */
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (en) {
			if (!en.isIntersecting) return;
			var el = en.target; el.classList.add('in'); io.unobserve(el);
			setTimeout(function () { el.__done = true; el.classList.add('done'); if (!el.classList.contains('cssc-hero')) el.removeAttribute('data-a'); }, 3400);
		});
	}, { rootMargin: '0px 0px -6% 0px', threshold: 0 });

	function observe() {
		document.querySelectorAll('[data-a]:not(.in)').forEach(function (el) {
			if (el.__io) return; el.__io = true;
			if ((el.closest('.cssc-hero') || el.closest('.ab-hero')) && el.getAttribute('data-a') !== 'none') { return; } // первый экран — после загрузки
			io.observe(el);
		});
	}

	/* ---------- надёжная проверка видимости (страховка к IO) ---------- */
	var lastCheck = 0;
	function reveal(el) {
		if (el.classList.contains('in')) return;
		el.classList.add('in'); io.unobserve(el);
		setTimeout(function () { el.__done = true; el.classList.add('done'); if (!el.classList.contains('cssc-hero')) el.removeAttribute('data-a'); }, 3400);
	}
	function manualCheck(t) {
		if (t - lastCheck < 120) return; lastCheck = t;
		var vh = window.innerHeight, atBottom = window.scrollY + vh >= document.documentElement.scrollHeight - 4;
		document.querySelectorAll('[data-a]:not(.in)').forEach(function (el) {
			if ((el.closest('.cssc-hero') || el.closest('.ab-hero')) && !document.body.classList.contains('is-loaded')) return;
			var r = el.getBoundingClientRect();
			if (r.width === 0 && r.height === 0) { var p = el.parentElement; r = p ? p.getBoundingClientRect() : r; }
			if (r.top < vh * 0.94 || atBottom) reveal(el);
		});
	}

	/* ---------- видео ---------- */
	function video() {
		var v = document.querySelector('.site-header__video'); if (!v) return;
		var ready = function () { v.classList.add('is-ready'); };
		if (v.readyState >= 3) ready(); else { v.addEventListener('playing', ready, { once: true }); v.addEventListener('canplay', ready, { once: true }); }
		setTimeout(ready, 4000);
		var p = v.play && v.play(); if (p && p.catch) p.catch(function () {});
	}

	/* ---------- липкое меню ---------- */
	
	/* ---------- параллакс ---------- */
	var hv = document.querySelector('.site-header__video');
	var header = document.querySelector('.site-header--video');
	function parallax(y) {
		if (hv && header && y < header.offsetHeight + 200) hv.style.setProperty('--py', (y * 0.32).toFixed(1) + 'px');
		var vw = window.innerWidth;
		document.querySelectorAll('.cssc-slide').forEach(function (s) {
			var r = s.getBoundingClientRect(); if (r.right < 0 || r.left > vw) return;
			var c = (r.left + r.width / 2 - vw / 2) / vw; // -0.5..0.5
			var img = s.querySelector('.cssc-slide__img'); if (img) img.style.setProperty('--px', (-c * 60).toFixed(1) + 'px');
		});
	}

	/* ---------- слайдер: drag + прогресс ---------- */
	function sliderPlus(root) {
		if (root.__plus) return; root.__plus = true;
		var track = root.querySelector('.cssc-slider__track'); if (!track) return;
		var prog = document.createElement('div'); prog.className = 'cssc-slider__progress'; prog.innerHTML = '<i></i>';
		root.appendChild(prog);
		var upd = function () {
			var m = new DOMMatrix(getComputedStyle(track).transform).m41 || 0;
			var max = track.scrollWidth - root.querySelector('.cssc-slider__viewport').clientWidth;
			var target = parseFloat((track.style.transform.match(/-?[\d.]+/) || [0])[0]);
			prog.style.setProperty('--p', max > 0 ? Math.min(1, Math.max(0.12, -target / max)) : 1);
			return m;
		};
		new MutationObserver(upd).observe(track, { attributes: true, attributeFilter: ['style'] }); upd();
		// перетаскивание мышью
		var x0 = null, base = 0, moved = false;
		track.addEventListener('pointerdown', function (e) {
			if (e.pointerType !== 'mouse') return;
			x0 = e.clientX; moved = false; base = new DOMMatrix(getComputedStyle(track).transform).m41 || 0;
			track.classList.add('is-drag'); track.style.transform = 'translateX(' + base + 'px)';
		});
		window.addEventListener('pointermove', function (e) { if (x0 === null) return; var dx = e.clientX - x0; if (Math.abs(dx) > 4) moved = true; track.style.transform = 'translateX(' + (base + dx * 0.9) + 'px)'; });
		window.addEventListener('pointerup', function (e) {
			if (x0 === null) return; var dx = e.clientX - x0; x0 = null; track.classList.remove('is-drag');
			var btn = root.querySelector(dx < 0 ? '[data-next]' : '[data-prev]');
			if (Math.abs(dx) > 60 && btn) { btn.click(); } else { track.style.transform = 'translateX(' + base + 'px)'; }
		});
		track.addEventListener('click', function (e) { if (moved) { e.preventDefault(); e.stopPropagation(); moved = false; } }, true);
		track.addEventListener('dragstart', function (e) { e.preventDefault(); });
	}

	/* ---------- spotlight в блоке продукции ---------- */
	function spotlight() {
		document.querySelectorAll('.cssc-products__card').forEach(function (c) {
			if (c.__sp) return; c.__sp = true;
			c.addEventListener('pointermove', function (e) { var r = c.getBoundingClientRect(); c.style.setProperty('--mx', (e.clientX - r.left) + 'px'); c.style.setProperty('--my', (e.clientY - r.top) + 'px'); });
		});
	}

	/* ---------- магнитные кнопки + курсор ---------- */
	var cursor = null, cx = -100, cy = -100, tx = -100, ty = -100;
	function magnets() {
		if (!fine) return;
		document.querySelectorAll('.sq-btn, .video-play, .contact-bar__wa, .cssc-slide__link').forEach(function (b) {
			if (b.__mg) return; b.__mg = true;
			b.style.transition = (b.style.transition ? b.style.transition + ',' : '') + 'translate .6s cubic-bezier(.16,1,.3,1)';
			b.addEventListener('pointermove', function (e) { var r = b.getBoundingClientRect(); var k = b.classList.contains('video-play') ? 0.18 : 0.3; b.style.translate = ((e.clientX - r.left - r.width / 2) * k).toFixed(1) + 'px ' + ((e.clientY - r.top - r.height / 2) * k).toFixed(1) + 'px'; });
			b.addEventListener('pointerleave', function () { b.style.translate = '0 0'; });
		});
		if (cfg.cursor === false || cursor) return;
		cursor = document.createElement('div'); cursor.className = 'cssc-cursor'; document.body.appendChild(cursor);
		window.addEventListener('pointermove', function (e) {
			if (e.pointerType !== 'mouse') return; tx = e.clientX; ty = e.clientY; cursor.classList.add('on');
			var t = e.target;
			cursor.classList.toggle('drag', !!t.closest('.cssc-slider__viewport'));
			cursor.classList.toggle('hover', !t.closest('.cssc-slider__viewport') && !!t.closest('a, button, .btn, input, select, textarea, label'));
		});
		document.addEventListener('pointerleave', function () { cursor.classList.remove('on'); });
	}

	/* ---------- счётчик (плавнее, expo) ---------- */
	CSSC.count = function (el, target) {
		var sup = el.querySelector('sup'), supHtml = sup ? sup.outerHTML : '', t0 = performance.now(), d = 2400;
		(function tick(t) { var k = Math.min(1, (t - t0) / d), e = k === 1 ? 1 : 1 - Math.pow(2, -10 * k); el.innerHTML = Math.round(target * e) + supHtml; if (k < 1) requestAnimationFrame(tick); })(t0);
	};

	/* ---------- счёт чисел «О компании» (1368, 17–20, 9–15, 15) ---------- */
	var ioNum = new IntersectionObserver(function (en) {
		en.forEach(function (e) {
			if (!e.isIntersecting) return; ioNum.unobserve(e.target);
			var el = e.target, src = el.textContent, nums = src.match(/\d+/g); if (!nums) return;
			var t0 = performance.now(), d = 2200;
			(function tick(t) {
				var k = Math.min(1, (t - t0) / d), ez = k === 1 ? 1 : 1 - Math.pow(2, -10 * k), i = 0;
				el.textContent = src.replace(/\d+/g, function (m) { var v = parseInt(nums[i++], 10); return String(Math.round(v * ez)); });
				if (k < 1) requestAnimationFrame(tick); else el.textContent = src;
			})(t0);
		});
	}, { threshold: 0.3 });
	function counters() { document.querySelectorAll('[data-ab-count]').forEach(function (el) { if (el.__cnt) return; el.__cnt = true; if (/\d/.test(el.textContent)) ioNum.observe(el); }); }

	/* ---------- главный цикл ---------- */
	var ticking = true;
	function raf(time) {
		if (lenis) lenis.raf(time);
		var y = window.scrollY;
		parallax(y);
manualCheck(time || performance.now());

		if (cursor) { cx += (tx - cx) * 0.18; cy += (ty - cy) * 0.18; cursor.style.transform = 'translate3d(' + cx.toFixed(1) + 'px,' + cy.toFixed(1) + 'px,0)'; }
		if (ticking) requestAnimationFrame(raf);
	}

	function initAll() {
		autotag(); observe(); spotlight(); magnets(); counters();
		document.querySelectorAll('[data-cssc-slider]').forEach(sliderPlus);
	}
	CSSC.motionInit = initAll;

	initAll();
video();
	requestAnimationFrame(raf);
	window.addEventListener('resize', function () {
	if (lenis) {
		lenis.resize();
	}
});


	// интро: всё, что в первом экране, запускаем после загрузки
	function loaded() {
		if (document.body.classList.contains('is-loaded')) return;
		document.body.classList.add('is-loaded');
		setTimeout(function () {
			document.querySelectorAll('.cssc-hero, .cssc-hero [data-a], .ab-hero [data-a]').forEach(function (el) { el.classList.add('in'); setTimeout(function () { el.__done = true; if (!el.classList.contains('cssc-hero')) el.removeAttribute('data-a'); }, 3600); });
		}, 60);
	}
	if (document.readyState === 'complete') setTimeout(loaded, 80);
	else { window.addEventListener('load', function () { setTimeout(loaded, 80); }); setTimeout(loaded, 2500); }

	// страховка: через 8 с всё, что уже выше низа экрана, гарантированно видно
	setTimeout(function () { document.querySelectorAll('[data-a]:not(.in)').forEach(function (el) { if (el.getBoundingClientRect().top < window.innerHeight) reveal(el); }); }, 8000);

	// Elementor: переинициализация виджетов
	window.addEventListener('elementor/frontend/init', function () {
		if (!window.elementorFrontend) return;
		elementorFrontend.hooks.addAction('frontend/element_ready/global', function () { initAll(); });
	});
})();
