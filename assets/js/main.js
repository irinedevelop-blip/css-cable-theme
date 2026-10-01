/* CSS Cable — main.js (без зависимостей) */
(function () {
	'use strict';
	var CSSC = window.CSSC = window.CSSC || {};

	function initSlider(root) {
		if (root.__cssc) return; root.__cssc = true;
		var track = root.querySelector('.cssc-slider__track');
		var slides = track ? track.children : [];
		if (!slides.length) return;
		var idx = 0;
		function step() { var s = slides[0]; var gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0; return s.getBoundingClientRect().width + gap; }
		function max() { var vp = root.querySelector('.cssc-slider__viewport'); var total = step() * slides.length; return Math.max(0, Math.ceil((total - vp.clientWidth) / step())); }
		function go(i) { var m = max(); idx = i > m ? 0 : (i < 0 ? m : i); track.style.transform = 'translateX(' + (-idx * step()) + 'px)'; }
		var n = root.querySelector('[data-next]'), p = root.querySelector('[data-prev]');
		n && n.addEventListener('click', function () { go(idx + 1); });
		p && p.addEventListener('click', function () { go(idx - 1); });
		window.addEventListener('resize', function () { go(idx); });
		// свайп
		var x0 = null;
		track.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
		track.addEventListener('touchend', function (e) { if (x0 === null) return; var dx = e.changedTouches[0].clientX - x0; if (Math.abs(dx) > 40) go(idx + (dx < 0 ? 1 : -1)); x0 = null; });
		var ap = root.closest('[data-autoplay]');
		if (ap) { var t = parseInt(ap.getAttribute('data-autoplay'), 10) * 1000; if (t > 0) setInterval(function () { go(idx + 1); }, t); }
	}

	function initCounter(el) {
		if (el.__cssc) return; el.__cssc = true;
		var target = parseInt(el.getAttribute('data-count'), 10); if (!target || !('IntersectionObserver' in window)) return;
		var sup = el.querySelector('sup'); var supHtml = sup ? sup.outerHTML : '';
		var io = new IntersectionObserver(function (en) {
			if (!en[0].isIntersecting) return; io.disconnect();
			if (window.CSSC.count) { window.CSSC.count(el, target); return; }
			var t0 = performance.now(), d = 1400;
			(function tick(t) { var k = Math.min(1, (t - t0) / d); el.innerHTML = Math.round(target * (1 - Math.pow(1 - k, 3))) + supHtml; if (k < 1) requestAnimationFrame(tick); })(t0);
		}, { threshold: .4 });
		io.observe(el);
	}

	/* Продукция: раскрытие карточки поверх блока + видео */
	function cpClose(card, focusBack) {
		var d = card.querySelector('.cp-detail:not([hidden])'); if (!d) return;
		d.classList.remove('is-in'); d.classList.add('is-out');
		d.querySelectorAll('video').forEach(function (v) { v.pause(); });
		setTimeout(function () {
			d.hidden = true; d.classList.remove('is-out'); card.classList.remove('is-open');
			var b = card.querySelector('[data-cp-open="' + d.id + '"]');
			if (b) { b.setAttribute('aria-expanded', 'false'); if (focusBack) b.focus({ preventScroll: true }); }
			card.style.minHeight = '';
		}, 380);
	}
	function cpOpen(btn) {
		var card = btn.closest('[data-cp]'), d = document.getElementById(btn.getAttribute('data-cp-open')); if (!card || !d) return;
		var cur = card.querySelector('.cp-detail:not([hidden])'); if (cur && cur !== d) { cur.hidden = true; cur.classList.remove('is-in'); }
		card.classList.add('is-open'); d.hidden = false; btn.setAttribute('aria-expanded', 'true');
		void d.offsetWidth; d.classList.add('is-in');
		if (window.innerWidth > 900) { var need = d.querySelector('.cp-detail__body').offsetHeight + 90; if (need > card.offsetHeight) card.style.minHeight = need + 'px'; }
		d.querySelectorAll('video').forEach(function (v) { try { v.currentTime = 0; } catch (e) {} var p = v.play(); if (p && p.catch) p.catch(function () {}); });
		var c = d.querySelector('[data-cp-close]'); if (c) c.focus({ preventScroll: true });
		if (window.innerWidth <= 900) { var r = card.getBoundingClientRect(); if (r.top < 0) window.scrollBy({ top: r.top - 90, behavior: 'smooth' }); }
	}
	document.addEventListener('click', function (e) {
		var o = e.target.closest('[data-cp-open]'); if (o) { cpOpen(o); return; }
		var c = e.target.closest('[data-cp-close]'); if (c) { cpClose(c.closest('[data-cp]'), true); return; }
		var open = document.querySelector('[data-cp].is-open');
		if (open && !e.target.closest('.cp-detail')) cpClose(open, false);
	});
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { var o = document.querySelector('[data-cp].is-open'); if (o) cpClose(o, true); } });

	/* Видео кабеля в сетке: играет при наведении / при попадании в экран на тач-устройствах */
	/* Safari не показывает прозрачность WebM — подменяем видео постером */
	var isSafari = /^((?!chrome|android|crios|fxios).)*safari/i.test(navigator.userAgent);
	function safariFallback() {
		if (!isSafari) return;
		document.querySelectorAll('video[data-cable-video]').forEach(function (v) {
			var src = v.querySelector('source'); if (!src || !/\.webm/i.test(src.src) || !v.poster) return;
			var img = document.createElement('img'); img.src = v.poster; img.className = v.className; img.alt = '';
			v.parentNode.replaceChild(img, v);
		});
	}
	function cableVideos() {
		safariFallback();
		var hover = window.matchMedia('(hover: hover)').matches;
		document.querySelectorAll('.cssc-product [data-cable-video]').forEach(function (v) {
			if (v.__cv) return; v.__cv = true;
			var btn = v.closest('.cssc-product');
			var play = function () { var p = v.play(); if (p && p.catch) p.catch(function () {}); };
			if (hover) {
				btn.addEventListener('mouseenter', play);
				btn.addEventListener('mouseleave', function () { v.pause(); });
			}
			if ('IntersectionObserver' in window) {
				new IntersectionObserver(function (en) { en.forEach(function (x) { if (x.isIntersecting) { play(); if (hover) setTimeout(function () { v.pause(); }, 5200); } else v.pause(); }); }, { threshold: .5 }).observe(v);
			}
		});
	}

	CSSC.init = function () {
		cableVideos();
		document.querySelectorAll('[data-cssc-slider]').forEach(initSlider);
		document.querySelectorAll('.cssc-counter__num[data-count]').forEach(initCounter);
	};

	document.addEventListener('click', function (e) {
		var a = e.target.closest('a[href$="#zayavka"], [data-req]');
		var modal = document.getElementById('cssc-modal');
		if (a && modal) {
			e.preventDefault();
			var f = modal.querySelector('[data-req-product]'); if (f) f.value = a.getAttribute('data-req') || '';
			modal.classList.add('is-open'); modal.setAttribute('aria-hidden', 'false');
			return;
		}
		if (modal && (e.target.closest('[data-modal-close]') || e.target.matches('[data-modal]'))) {
			modal.classList.remove('is-open'); modal.setAttribute('aria-hidden', 'true');
			if (location.search.indexOf('cssc_sent') > -1) history.replaceState(null, '', location.pathname);
		}
		var st = e.target.closest('[data-search-toggle]');
		if (st) { var s = document.querySelector('[data-search]'); s.classList.toggle('is-open'); if (s.classList.contains('is-open')) s.querySelector('input').focus(); }
		var dt = e.target.closest('[data-drawer-toggle]') || (e.target.matches('[data-drawer]') ? e.target : null);
		if (dt) { var d = document.querySelector('[data-drawer]'); d.classList.toggle('is-open'); d.setAttribute('aria-hidden', d.classList.contains('is-open') ? 'false' : 'true'); }
		if (e.target.closest('.drawer a[href$="#zayavka"]')) { document.querySelector('[data-drawer]').classList.remove('is-open'); }
		var vo = e.target.closest('[data-video-open]');
		if (vo) {
			var vm = document.querySelector('[data-video-modal]'), fr = vm.querySelector('[data-video-frame]'), src = vo.getAttribute('data-src');
			fr.innerHTML = vo.getAttribute('data-type') === 'iframe'
				? '<iframe src="' + src + '" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>'
				: '<video src="' + src + '" controls autoplay playsinline></video>';
			vm.classList.add('is-open');
		}
		if (e.target.closest('[data-video-close]') || e.target.matches('[data-video-modal]')) {
			var vm2 = document.querySelector('[data-video-modal]'); vm2.classList.remove('is-open'); vm2.querySelector('[data-video-frame]').innerHTML = '';
		}
	});
	document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { var m = document.getElementById('cssc-modal'); m && m.classList.remove('is-open'); document.querySelectorAll('[data-drawer].is-open').forEach(function (x) { x.classList.remove('is-open'); }); var v = document.querySelector('[data-video-modal].is-open'); if (v) { v.classList.remove('is-open'); v.querySelector('[data-video-frame]').innerHTML = ''; } } });

	// Калькулятор веса
	document.addEventListener('input', function (e) {
		var c = e.target.closest('[data-weight-calc]'); if (!c) return;
		var sel = c.querySelector('[data-wc-product]'), kg = c.querySelector('[data-wc-kg]'), len = c.querySelector('[data-wc-len]');
		if (e.target === sel && sel.value) kg.value = sel.value;
		var r = (parseFloat(kg.value) || 0) * (parseFloat(len.value) || 0) / 1000;
		c.querySelector('[data-wc-res]').textContent = r.toLocaleString('ru-RU', { maximumFractionDigits: 2 });
	});
	document.addEventListener('change', function (e) { if (e.target.matches('[data-wc-product]')) e.target.dispatchEvent(new Event('input', { bubbles: true })); });

	if (document.readyState !== 'loading') CSSC.init(); else document.addEventListener('DOMContentLoaded', CSSC.init);
})();
