/**
 * Frontend handler for Toggle Content.
 */
(function () {
	'use strict';

	function setButtonState(button, isOpen) {
		var openText = button.getAttribute('data-open-text') || '';
		var closeText = button.getAttribute('data-close-text') || '';
		var textNode = button.querySelector('.toggle-content__button-text');

		button.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		if (textNode) {
			textNode.textContent = isOpen ? closeText || openText : openText;
		}
	}

	function getScopeElement(scope) {
		if (!scope) {
			return null;
		}

		if (scope.jquery && scope.length) {
			return scope[0];
		}

		return scope;
	}

	function toggleContent(scope) {
		var scopeEl = getScopeElement(scope);

		if (!scopeEl || scopeEl.dataset.toggleContentBound === 'yes') {
			return;
		}

		var button = scopeEl.querySelector('.toggle-content__button');
		var body = scopeEl.querySelector('.toggle-content__body');

		if (!button || !body) {
			return;
		}

		scopeEl.dataset.toggleContentBound = 'yes';
		setButtonState(button, body.hidden === false);

		button.addEventListener('click', function () {
			var isOpen = body.hidden === false;

			body.hidden = isOpen;
			scopeEl.classList.toggle('is-open', !isOpen);
			scopeEl.classList.toggle('is-closed', isOpen);
			setButtonState(button, !isOpen);
		});
	}

	function initStandalone() {
		var nodes = document.querySelectorAll('.elementor-widget-toggle-content');
		for (var i = 0; i < nodes.length; i++) {
			toggleContent(nodes[i]);
		}
	}

	if (window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', function () {
			if (window.elementorFrontend && window.elementorFrontend.hooks) {
				window.elementorFrontend.hooks.addAction(
					'frontend/element_ready/toggle-content.default',
					toggleContent
				);
			}
		});
	}

	if (document.readyState === 'complete') {
		initStandalone();
	} else {
		window.addEventListener('load', initStandalone);
	}
})();
