/**
 * Registers avh-animated-carousel as a Nested Element type in the Elementor editor.
 * Without this, slides cannot host editable container children.
 *
 * The core event "elementor/nested-element-type-loaded" is dispatched as a
 * native CustomEvent on window (not jQuery), so we must use addEventListener.
 */
(function () {
	'use strict';

	var registered = false;
	var retryTimer = null;
	var maxRetries = 40;
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

		var AVHAnimatedCarousel = class extends NestedElementBase {
			getType() {
				return 'avh-animated-carousel';
			}
		};

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

	window.addEventListener('elementor/nested-element-type-loaded', function () {
		tryRegister();
	});

	retryTimer = setInterval(function () {
		retryCount++;
		if (registered || retryCount > maxRetries) {
			clearInterval(retryTimer);
			retryTimer = null;
			return;
		}
		tryRegister();
	}, 250);
})();
