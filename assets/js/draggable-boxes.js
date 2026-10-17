/**
 * Frontend handler for Draggable Boxes.
 */
(function () {
	'use strict';

	function parseSettings(element) {
		try {
			return JSON.parse(element.getAttribute('data-settings') || '{}');
		} catch (error) {
			return {};
		}
	}

	function getPosition(item, axis) {
		var value = parseFloat(item.getAttribute('data-' + axis) || '0');
		return Number.isFinite(value) ? value : 0;
	}

	function setPosition(item, x, y) {
		item.setAttribute('data-x', String(x));
		item.setAttribute('data-y', String(y));
		item.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
	}

	function getScopeElement(scope) {
		if (!scope) {
			return null;
		}

		return scope.jquery && scope.length ? scope[0] : scope;
	}

	function clampPosition(item, canvas, x, y) {
		var maxX = Math.max(0, canvas.clientWidth - item.offsetWidth);
		var maxY = Math.max(0, canvas.clientHeight - item.offsetHeight);
		var left = item.offsetLeft;
		var top = item.offsetTop;

		return {
			x: Math.min(Math.max(x, -left), maxX - left),
			y: Math.min(Math.max(y, -top), maxY - top),
		};
	}

	function clampItem(item, canvas) {
		// A hidden canvas (e.g. inside a closed tab) reports no size.
		if (!canvas.clientWidth || !canvas.clientHeight) {
			return;
		}

		var position = clampPosition(item, canvas, getPosition(item, 'x'), getPosition(item, 'y'));
		setPosition(item, position.x, position.y);
	}

	function announce(status, message) {
		status.textContent = '';
		window.setTimeout(function () {
			status.textContent = message;
		}, 0);
	}

	function bindKeyboard(item, canvas, keyboardStep, status) {
		item.addEventListener('keydown', function (event) {
			var x = getPosition(item, 'x');
			var y = getPosition(item, 'y');
			var handled = true;

			switch (event.key) {
				case 'ArrowLeft':
					x -= keyboardStep;
					break;
				case 'ArrowRight':
					x += keyboardStep;
					break;
				case 'ArrowUp':
					y -= keyboardStep;
					break;
				case 'ArrowDown':
					y += keyboardStep;
					break;
				default:
					handled = false;
			}

			if (!handled) {
				return;
			}

			event.preventDefault();
			var position = clampPosition(item, canvas, x, y);
			setPosition(item, position.x, position.y);
			announce(status, (item.getAttribute('aria-label') || '') + ' moved.');
		});
	}

	// Elementor keeps the widget wrapper across editor rerenders and replaces
	// its content, so the previous instance is torn down before binding again.
	function destroyInstance(scopeElement) {
		var instance = scopeElement.draggableBoxesInstance;
		if (!instance) {
			return;
		}

		instance.interactables.forEach(function (interactable) {
			interactable.unset();
		});

		if (instance.resizeObserver) {
			instance.resizeObserver.disconnect();
		}

		scopeElement.draggableBoxesInstance = null;
	}

	function initOnScope(scope) {
		var scopeElement = getScopeElement(scope);
		if (!scopeElement) {
			return;
		}

		var widget = scopeElement.querySelector('.draggable-boxes');
		if (!widget || typeof window.interact !== 'function') {
			return;
		}

		if (scopeElement.draggableBoxesInstance && scopeElement.draggableBoxesInstance.widget === widget) {
			return;
		}

		destroyInstance(scopeElement);

		var canvas = widget.querySelector('.draggable-boxes__canvas');
		var items = widget.querySelectorAll('.draggable-boxes__item');
		var status = widget.querySelector('.draggable-boxes__status');
		if (!canvas || !status) {
			return;
		}

		var settings = parseSettings(widget);
		var keyboardStep = Number.isFinite(settings.keyboardStep) ? settings.keyboardStep : 10;
		var instance = {
			widget: widget,
			interactables: [],
			resizeObserver: null,
		};

		for (var index = 0; index < items.length; index++) {
			(function (item) {
				instance.interactables.push(window.interact(item).draggable({
					inertia: settings.inertia === true,
					modifiers: [
						window.interact.modifiers.restrictRect({
							restriction: canvas,
							endOnly: false,
						}),
					],
					listeners: {
						start: function (event) {
							event.target.classList.add('is-dragging');
							event.target.setAttribute('aria-grabbed', 'true');
						},
						move: function (event) {
							setPosition(
								event.target,
								getPosition(event.target, 'x') + event.dx,
								getPosition(event.target, 'y') + event.dy
							);
						},
						end: function (event) {
							event.target.classList.remove('is-dragging');
							event.target.removeAttribute('aria-grabbed');
							announce(status, (event.target.getAttribute('aria-label') || '') + ' placed.');
						},
					},
				}));

				bindKeyboard(item, canvas, keyboardStep, status);
				clampItem(item, canvas);
			})(items[index]);
		}

		// Keep boxes inside the canvas when its size changes (viewport resize,
		// responsive controls in the editor).
		if (typeof window.ResizeObserver === 'function') {
			instance.resizeObserver = new window.ResizeObserver(function () {
				for (var itemIndex = 0; itemIndex < items.length; itemIndex++) {
					clampItem(items[itemIndex], canvas);
				}
			});
			instance.resizeObserver.observe(canvas);
		}

		scopeElement.draggableBoxesInstance = instance;
	}

	function initStandalone() {
		var widgets = document.querySelectorAll('.elementor-widget-draggable-boxes');
		for (var index = 0; index < widgets.length; index++) {
			initOnScope(widgets[index]);
		}
	}

	if (window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', function () {
			if (window.elementorFrontend && window.elementorFrontend.hooks) {
				window.elementorFrontend.hooks.addAction(
					'frontend/element_ready/draggable-boxes.default',
					initOnScope
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
