/**
 * Registers avh-animated-carousel as a Nested Element type in the Elementor editor (same idea as Elementor Pro Nested Carousel).
 * Without this, slides cannot host editable container children.
 *
 * Load order is enforced in PHP: this script depends on elementor-pro + nested-elements so NestedElementBase exists.
 */
(function () {
	'use strict';

	var registered = false;
	var retryTimer = null;
	var maxRetries = 120;
	var retryCount = 0;

	function registerType(NestedElementBase) {
		if (registered) {
			return;
		}
		if (
			typeof elementor === 'undefined' ||
			!elementor.elementsManager ||
			typeof NestedElementBase !== 'function'
		) {
			return;
		}

		var AVHAnimatedCarousel = (function (Base) {
			return class extends Base {
				getType() {
					return 'avh-animated-carousel';
				}
			};
		})(NestedElementBase);

		elementor.elementsManager.registerElementType(new AVHAnimatedCarousel());
		registered = true;

		if (retryTimer) {
			clearInterval(retryTimer);
			retryTimer = null;
		}
	}

	function tryRegister() {
		if (registered) {
			return true;
		}
		if (typeof elementor === 'undefined' || !elementor.modules) {
			return false;
		}
		var types = elementor.modules.elements && elementor.modules.elements.types;
		if (!types || !types.NestedElementBase) {
			return false;
		}

		var Base = types.NestedElementBase;

		if (typeof Base.then === 'function') {
			Base.then(function (mod) {
				registerType(mod.default || mod);
			});
			return true;
		}

		if (typeof Base === 'function') {
			registerType(Base);
			return true;
		}

		return false;
	}

	function startRetries() {
		if (retryTimer) {
			return;
		}
		retryTimer = setInterval(function () {
			retryCount++;
			if (registered || retryCount > maxRetries) {
				clearInterval(retryTimer);
				retryTimer = null;
				return;
			}
			tryRegister();
		}, 250);
	}

	window.addEventListener('elementor/nested-element-type-loaded', function () {
		tryRegister();
	});

	if (typeof elementor !== 'undefined' && elementor.on) {
		elementor.on('init', function () {
			tryRegister();
		});
	}

	tryRegister();
	startRetries();
})();
