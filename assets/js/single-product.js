(function () {
	'use strict';

	var modal = document.querySelector('[data-certificate-modal]');
	var openButton = document.querySelector('[data-certificate-open]');

	if (!modal || !openButton) {
		return;
	}

	var closeButtons = modal.querySelectorAll('[data-certificate-close]');
	var closeButton = modal.querySelector('.certificate-modal__close');
	var lastFocused = null;

	function openModal() {
		lastFocused = document.activeElement;

		modal.hidden = false;
		modal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('certificate-modal-open');

		window.requestAnimationFrame(function () {
			modal.classList.add('is-open');

			if (closeButton) {
				closeButton.focus();
			}
		});
	}

	function closeModal() {
		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('certificate-modal-open');

		window.setTimeout(function () {
			modal.hidden = true;

			if (lastFocused && typeof lastFocused.focus === 'function') {
				lastFocused.focus();
			}
		}, 220);
	}

	openButton.addEventListener('click', openModal);

	closeButtons.forEach(function (button) {
		button.addEventListener('click', closeModal);
	});

	document.addEventListener('keydown', function (event) {
		if (
			event.key === 'Escape' &&
			modal.classList.contains('is-open')
		) {
			closeModal();
		}
	});
})();
