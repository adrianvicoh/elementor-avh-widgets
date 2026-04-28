/**
 * Frontend handler for AVH Coverflow Slider.
 *
 * Reads the JSON payload serialized by the PHP widget into `data-settings`,
 * builds a Swiper config that mirrors the developer reference snippet, and
 * scopes selectors per widget instance so multiple sliders can coexist.
 */
(function () {
	'use strict';

	var INSTANCE_KEY = 'avhCoverflowSwiper';

	function parseSettings(el) {
		try {
			return JSON.parse(el.getAttribute('data-settings') || '{}');
		} catch (err) {
			return {};
		}
	}

	function buildConfig(scopeEl, payload) {
		var widgetId = scopeEl.getAttribute('data-id') || '';
		var scopeSel = widgetId ? '.elementor-element-' + widgetId : '';

		var config = {
			effect: 'coverflow',
			direction: payload.direction === 'vertical' ? 'vertical' : 'horizontal',
			loop: !!payload.loop,
			grabCursor: !!payload.grabCursor,
			centeredSlides: payload.centeredSlides !== false,
			speed: typeof payload.speed === 'number' ? payload.speed : 500,
			coverflowEffect: {
				rotate: typeof payload.rotate === 'number' ? payload.rotate : 50,
				stretch: typeof payload.stretch === 'number' ? payload.stretch : 0,
				depth: typeof payload.depth === 'number' ? payload.depth : 100,
				modifier: typeof payload.modifier === 'number' ? payload.modifier : 1,
				slideShadows: !!payload.slideShadows,
			},
			wrapperClass: 'avh-coverflow-swiper-wrapper',
			slideClass: 'avh-coverflow-swiper-slide',
			watchSlidesProgress: true,
			observer: true,
			observeParents: true,
		};

		if (payload.arrows) {
			config.navigation = {
				nextEl: scopeSel + ' .avh-coverflow-swiper-next',
				prevEl: scopeSel + ' .avh-coverflow-swiper-prev',
			};
		}

		if (payload.pagination) {
			config.pagination = {
				el: scopeSel + ' .avh-coverflow-swiper-pagination',
				type: payload.pagination,
				clickable: payload.pagination === 'bullets',
			};
		}

		if (payload.breakpoints && typeof payload.breakpoints === 'object') {
			config.breakpoints = {};
			Object.keys(payload.breakpoints).forEach(function (key) {
				var bp = payload.breakpoints[key] || {};
				config.breakpoints[key] = {
					slidesPerView: bp.slidesPerView || 1,
					spaceBetween: typeof bp.spaceBetween === 'number' ? bp.spaceBetween : 0,
				};
			});
		}

		return config;
	}

	function destroyExisting(scopeEl) {
		var existing = scopeEl[INSTANCE_KEY];
		if (existing && typeof existing.destroy === 'function') {
			try {
				existing.destroy(true, true);
			} catch (err) {
				/* ignore */
			}
			scopeEl[INSTANCE_KEY] = null;
		}
	}

	function initOnScope(scopeEl) {
		if (!scopeEl) {
			return;
		}

		var sliderEl = scopeEl.querySelector('.avh-coverflow-swiper');
		if (!sliderEl) {
			return;
		}

		if (typeof window.Swiper !== 'function') {
			return;
		}

		destroyExisting(sliderEl);

		var payload = parseSettings(sliderEl);
		var config = buildConfig(scopeEl, payload);

		sliderEl[INSTANCE_KEY] = new window.Swiper(sliderEl, config);
	}

	function initFromJQueryScope($scope) {
		if (!$scope || !$scope.length) {
			return;
		}
		initOnScope($scope[0]);
	}

	function initStandalone() {
		var nodes = document.querySelectorAll('.elementor-widget-avh-coverflow-slider');
		for (var i = 0; i < nodes.length; i++) {
			initOnScope(nodes[i]);
		}
	}

	if (window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', function () {
			if (window.elementorFrontend && window.elementorFrontend.hooks) {
				window.elementorFrontend.hooks.addAction(
					'frontend/element_ready/avh-coverflow-slider.default',
					initFromJQueryScope
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
