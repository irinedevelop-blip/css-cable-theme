(function () {
	'use strict';

	var config = window.CSSC_PRODUCT_SEARCH || {};
	var search = document.querySelector('[data-product-search]');

	if (!search || !config.ajaxUrl) {
		return;
	}

	var openButtons = document.querySelectorAll('[data-product-search-open]');
	var closeButtons = search.querySelectorAll('[data-product-search-close]');
	var form = search.querySelector('[data-product-search-form]');
	var input = search.querySelector('[data-product-search-input]');
	var status = search.querySelector('[data-product-search-status]');
	var results = search.querySelector('[data-product-search-results]');
	var viewAll = search.querySelector('[data-product-search-all]');

	var labels = config.labels || {};
	var minChars = parseInt(config.minChars, 10) || 2;
	var timer = null;
	var controller = null;
	var lastFocused = null;

	function setStatus(message, state) {
		status.textContent = message || '';
		status.dataset.state = state || '';
		status.hidden = !message;
	}

	function clearResults() {
		results.replaceChildren();
		viewAll.hidden = true;
		viewAll.removeAttribute('href');
	}

	function openSearch() {
		lastFocused = document.activeElement;

		search.classList.add('is-open');
		search.setAttribute('aria-hidden', 'false');
		document.body.classList.add('product-search-open');

		openButtons.forEach(function (button) {
			button.setAttribute('aria-expanded', 'true');
		});

		window.setTimeout(function () {
			input.focus();
		}, 80);
	}

	function closeSearch() {
		if (controller) {
			controller.abort();
			controller = null;
		}

		window.clearTimeout(timer);

		search.classList.remove('is-open');
		search.setAttribute('aria-hidden', 'true');
		document.body.classList.remove('product-search-open');

		openButtons.forEach(function (button) {
			button.setAttribute('aria-expanded', 'false');
		});

		if (lastFocused && typeof lastFocused.focus === 'function') {
			lastFocused.focus();
		}
	}

	function createText(tag, className, text) {
		var element = document.createElement(tag);

		if (className) {
			element.className = className;
		}

		element.textContent = text || '';

		return element;
	}

	function createProductCard(product) {
		var link = document.createElement('a');
		link.className = 'product-search-card';
		link.href = product.url;

		var imageBox = document.createElement('span');
		imageBox.className = 'product-search-card__image';

		var image = document.createElement('img');
		image.src = product.image;
		image.alt = product.name;
		image.loading = 'lazy';
		image.width = 92;
		image.height = 92;

		imageBox.appendChild(image);

		var content = document.createElement('span');
		content.className = 'product-search-card__content';

		if (product.category) {
			content.appendChild(
				createText(
					'span',
					'product-search-card__category',
					product.category
				)
			);
		}

		content.appendChild(
			createText(
				'strong',
				'product-search-card__name',
				product.name
			)
		);

		if (product.sku) {
			content.appendChild(
				createText(
					'span',
					'product-search-card__sku',
					(labels.sku || 'Артикул:') + ' ' + product.sku
				)
			);
		}

		var price = document.createElement('span');
		price.className = 'product-search-card__price';

		/*
		 * price_html формируется и очищается WordPress на сервере.
		 */
		price.innerHTML = product.price_html || '';

		content.appendChild(price);

		var arrow = document.createElement('span');
		arrow.className = 'product-search-card__arrow';
		arrow.setAttribute('aria-hidden', 'true');
		arrow.innerHTML =
			'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" ' +
			'stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
			'<path d="M5 12h14M13 6l6 6-6 6"></path></svg>';

		link.appendChild(imageBox);
		link.appendChild(content);
		link.appendChild(arrow);

		return link;
	}

	function renderProducts(items, allUrl) {
		results.replaceChildren();

		if (!items.length) {
			setStatus(
				labels.empty || 'По вашему запросу товары не найдены',
				'empty'
			);

			viewAll.hidden = true;
			return;
		}

		setStatus('', 'ready');

		var fragment = document.createDocumentFragment();

		items.forEach(function (product) {
			fragment.appendChild(createProductCard(product));
		});

		results.appendChild(fragment);

		if (allUrl) {
			viewAll.href = allUrl;
			viewAll.hidden = false;
		}
	}

	function performSearch(query) {
		if (controller) {
			controller.abort();
		}

		controller = new AbortController();

		setStatus(labels.loading || 'Ищем товары…', 'loading');
		clearResults();

		var body = new URLSearchParams();
		body.set('action', 'cssc_product_search');
		body.set('nonce', config.nonce);
		body.set('query', query);

		fetch(config.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			body: body.toString(),
			signal: controller.signal
		})
			.then(function (response) {
				if (!response.ok) {
					throw new Error('HTTP ' + response.status);
				}

				return response.json();
			})
			.then(function (response) {
				if (!response.success || !response.data) {
					throw new Error('Invalid response');
				}

				renderProducts(
					response.data.items || [],
					response.data.view_all_url || ''
				);
			})
			.catch(function (error) {
				if (error.name === 'AbortError') {
					return;
				}

				clearResults();

				setStatus(
					labels.error ||
						'Не удалось выполнить поиск. Попробуйте ещё раз.',
					'error'
				);
			});
	}

	function handleInput() {
		var query = input.value.trim();

		window.clearTimeout(timer);

		if (query.length < minChars) {
			if (controller) {
				controller.abort();
			}

			clearResults();

			setStatus(
				labels.start ||
					'Начните вводить название или артикул товара',
				'start'
			);

			return;
		}

		timer = window.setTimeout(function () {
			performSearch(query);
		}, 300);
	}

	openButtons.forEach(function (button) {
		button.addEventListener('click', openSearch);
	});

	closeButtons.forEach(function (button) {
		button.addEventListener('click', closeSearch);
	});

	input.addEventListener('input', handleInput);

	form.addEventListener('submit', function (event) {
		if (input.value.trim().length < minChars) {
			event.preventDefault();
			input.focus();
		}
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape' && search.classList.contains('is-open')) {
			closeSearch();
		}
	});
})();
