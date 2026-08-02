(() => {
	'use strict';

	const ENABLED_SELECTOR = '.elementor-element.avh-border-beam--yes';
	const TYPE_PREFIX = 'avh-border-beam-type-';
	const PULSE_TYPES = new Set(['pulse-inner', 'pulse-outside']);
	const instances = new Map();
	const targetOwners = new WeakMap();
	const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
	const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
	const frameInterval = 1000 / 30 - 2;
	const twoPi = Math.PI * 2;
	let elementorHookBound = false;
	let mutationObserver = null;
	let intersectionObserver = null;
	let resizeObserver = null;
	let animationFrame = null;
	let lastFrame = 0;

	const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
	const pingPong = (phase) => (1 - Math.cos(twoPi * phase)) / 2;

	function getType(root) {
		for (const className of root.classList) {
			if (className.startsWith(TYPE_PREFIX)) {
				return className.slice(TYPE_PREFIX.length);
			}
		}

		return 'md';
	}

	function getTarget(root) {
		if (!root.classList.contains('elementor-widget')) {
			return root;
		}

		for (const child of root.children) {
			if (child.classList.contains('elementor-widget-container')) {
				return child;
			}
		}

		return root;
	}

	function createLayer(className) {
		const layer = document.createElement('span');
		layer.className = className;
		return layer;
	}

	function createEffect() {
		const effect = document.createElement('span');
		effect.className = 'avh-border-beam__effect';
		effect.setAttribute('aria-hidden', 'true');
		effect.append(
			createLayer('avh-border-beam__stroke'),
			createLayer('avh-border-beam__inner'),
			createLayer('avh-border-beam__bloom')
		);
		return effect;
	}

	function resolveTheme(root) {
		const isAuto = root.classList.contains('avh-border-beam-theme-auto');
		root.classList.toggle(
			'avh-border-beam-theme-resolved-dark',
			isAuto && darkModeQuery.matches
		);
		root.classList.toggle(
			'avh-border-beam-theme-resolved-light',
			isAuto && !darkModeQuery.matches
		);
	}

	function updateGeometry(instance) {
		if (instance.type !== 'pulse-outside') {
			instance.effect.style.removeProperty('--avh-beam-glow-sx');
			instance.effect.style.removeProperty('--avh-beam-glow-sy');
			instance.effect.style.removeProperty('--avh-beam-glow-scale');
			return;
		}

		const rect = instance.target.getBoundingClientRect();
		if (!rect.width || !rect.height) {
			return;
		}

		const scaleX = Number(clamp(rect.width / 350, 0.35, 4).toFixed(3));
		const scaleY = Number(clamp(rect.height / 140, 0.35, 4).toFixed(3));
		const averageScale = Number(Math.sqrt(scaleX * scaleY).toFixed(3));

		instance.effect.style.setProperty('--avh-beam-glow-sx', String(scaleX));
		instance.effect.style.setProperty('--avh-beam-glow-sy', String(scaleY));
		instance.effect.style.setProperty('--avh-beam-glow-scale', String(averageScale));
	}

	function resetPulse(instance) {
		instance.effect.style.setProperty('--avh-beam-pulse-scale', '1');
		instance.effect.style.setProperty('--avh-beam-pulse-opacity', reducedMotionQuery.matches ? '0.82' : '1');
		instance.effect.style.setProperty('--avh-beam-pulse-x', '0px');
		instance.effect.style.setProperty('--avh-beam-pulse-y', '0px');
		instance.effect.style.setProperty('--avh-beam-hue', '0deg');
	}

	function cleanup(root) {
		const instance = instances.get(root);
		if (!instance) {
			return;
		}

		intersectionObserver?.unobserve(instance.target);
		resizeObserver?.unobserve(instance.target);
		targetOwners.delete(instance.target);
		instance.effect.remove();

		if (instance.addedPositionClass) {
			instance.target.classList.remove('avh-border-beam__target--positioned');
		}
		instance.target.classList.remove('avh-border-beam__target');
		root.classList.remove(
			'avh-border-beam-theme-resolved-dark',
			'avh-border-beam-theme-resolved-light'
		);
		instances.delete(root);
		updateAnimationLoop();
	}

	function syncRoot(root) {
		if (!(root instanceof Element) || !root.classList.contains('elementor-element')) {
			return;
		}

		if (!root.classList.contains('avh-border-beam--yes')) {
			cleanup(root);
			return;
		}

		resolveTheme(root);

		const target = getTarget(root);
		let instance = instances.get(root);
		if (instance && (instance.target !== target || !instance.effect.isConnected)) {
			cleanup(root);
			instance = null;
		}

		if (!instance) {
			const effect = createEffect();
			const computedPosition = window.getComputedStyle(target).position;
			const addedPositionClass = computedPosition === 'static';

			target.classList.add('avh-border-beam__target');
			if (addedPositionClass) {
				target.classList.add('avh-border-beam__target--positioned');
			}

			target.append(effect);
			instance = {
				root,
				target,
				effect,
				type: getType(root),
				visible: true,
				addedPositionClass,
			};
			instances.set(root, instance);
			targetOwners.set(target, root);
			intersectionObserver?.observe(target);
			resizeObserver?.observe(target);
		} else {
			instance.type = getType(root);
		}

		updateGeometry(instance);
		resetPulse(instance);
		updateAnimationLoop();
	}

	function syncTree(node = document) {
		if (!(node instanceof Document || node instanceof DocumentFragment || node instanceof Element)) {
			return;
		}

		if (node instanceof Element && node.matches(ENABLED_SELECTOR)) {
			syncRoot(node);
		}

		node.querySelectorAll?.(ENABLED_SELECTOR).forEach(syncRoot);
	}

	function cleanupDetachedInstances() {
		for (const root of instances.keys()) {
			if (!root.isConnected) {
				cleanup(root);
			}
		}
	}

	function getNumericProperty(root, propertyName, fallback) {
		const rawValue = window.getComputedStyle(root).getPropertyValue(propertyName).trim();
		const parsedValue = Number.parseFloat(rawValue);
		return Number.isFinite(parsedValue) ? parsedValue : fallback;
	}

	function isStatic(instance) {
		return (
			instance.root.classList.contains('avh-border-beam-static-yes') ||
			instance.root.classList.contains('avh-border-beam-color-mono')
		);
	}

	function hasActivePulse() {
		if (reducedMotionQuery.matches) {
			return false;
		}

		for (const instance of instances.values()) {
			if (PULSE_TYPES.has(instance.type) && instance.visible && instance.root.isConnected) {
				return true;
			}
		}

		return false;
	}

	function renderPulseFrame(timestamp) {
		animationFrame = window.requestAnimationFrame(renderPulseFrame);
		if (timestamp - lastFrame < frameInterval) {
			return;
		}
		lastFrame = timestamp;

		for (const instance of instances.values()) {
			if (!PULSE_TYPES.has(instance.type) || !instance.visible || !instance.root.isConnected) {
				continue;
			}

			const duration = Math.max(
				0.2,
				getNumericProperty(instance.root, '--avh-beam-duration', 2.3)
			);
			const hueRange = clamp(
				getNumericProperty(instance.root, '--avh-beam-hue-range', 30),
				0,
				180
			);
			const seconds = timestamp / 1000;
			const phase = (seconds / duration) % 1;
			const slowPhase = (seconds / Math.max(duration * 2.6, 1)) % 1;
			const pulse = pingPong(phase);
			const drift = Math.sin(twoPi * slowPhase);
			const scaleAmount = instance.type === 'pulse-outside' ? 0.07 : 0.035;
			const opacityFloor = instance.type === 'pulse-outside' ? 0.55 : 0.68;

			instance.effect.style.setProperty(
				'--avh-beam-pulse-scale',
				(1 + scaleAmount * pulse).toFixed(4)
			);
			instance.effect.style.setProperty(
				'--avh-beam-pulse-opacity',
				(opacityFloor + (1 - opacityFloor) * pulse).toFixed(4)
			);
			instance.effect.style.setProperty('--avh-beam-pulse-x', `${(drift * 1.5).toFixed(2)}px`);
			instance.effect.style.setProperty(
				'--avh-beam-pulse-y',
				`${(Math.cos(twoPi * slowPhase) * 1.2).toFixed(2)}px`
			);

			const hue = isStatic(instance)
				? 0
				: -hueRange + 2 * hueRange * pingPong((seconds / 12) % 1);
			instance.effect.style.setProperty('--avh-beam-hue', `${hue.toFixed(2)}deg`);
		}

		if (!hasActivePulse()) {
			window.cancelAnimationFrame(animationFrame);
			animationFrame = null;
		}
	}

	function updateAnimationLoop() {
		if (hasActivePulse()) {
			if (animationFrame === null) {
				lastFrame = 0;
				animationFrame = window.requestAnimationFrame(renderPulseFrame);
			}
			return;
		}

		if (animationFrame !== null) {
			window.cancelAnimationFrame(animationFrame);
			animationFrame = null;
		}
	}

	function handleThemeChange() {
		for (const instance of instances.values()) {
			resolveTheme(instance.root);
		}
	}

	function handleMotionChange() {
		for (const instance of instances.values()) {
			resetPulse(instance);
		}
		updateAnimationLoop();
	}

	function bindElementorHook() {
		if (elementorHookBound || !window.elementorFrontend?.hooks) {
			return;
		}

		window.elementorFrontend.hooks.addAction('frontend/element_ready/global', ($scope) => {
			const scope = $scope?.[0] || $scope;
			syncTree(scope);
		});
		elementorHookBound = true;
	}

	function initializeObservers() {
		if ('IntersectionObserver' in window) {
			intersectionObserver = new IntersectionObserver(
				(entries) => {
					for (const entry of entries) {
						const root = targetOwners.get(entry.target);
						const instance = root ? instances.get(root) : null;
						if (!instance) {
							continue;
						}

						instance.visible = entry.isIntersecting;
						instance.effect.classList.toggle(
							'avh-border-beam__effect--paused',
							!entry.isIntersecting
						);
					}
					updateAnimationLoop();
				},
				{ rootMargin: '256px' }
			);
		}

		if ('ResizeObserver' in window) {
			resizeObserver = new ResizeObserver((entries) => {
				for (const entry of entries) {
					const root = targetOwners.get(entry.target);
					const instance = root ? instances.get(root) : null;
					if (instance) {
						updateGeometry(instance);
					}
				}
			});
		}

		mutationObserver = new MutationObserver((mutations) => {
			for (const mutation of mutations) {
				if (mutation.type === 'attributes') {
					const changedElement = mutation.target;
					if (changedElement.classList.contains('elementor-element')) {
						syncRoot(changedElement);
					}
					continue;
				}

				for (const addedNode of mutation.addedNodes) {
					syncTree(addedNode);
				}
			}
			cleanupDetachedInstances();
		});
		mutationObserver.observe(document.documentElement, {
			attributes: true,
			attributeFilter: ['class'],
			childList: true,
			subtree: true,
		});
	}

	function initialize() {
		initializeObservers();
		syncTree(document);
		bindElementorHook();

		window.jQuery?.(window).on('elementor/frontend/init', bindElementorHook);
		window.addEventListener('elementor/frontend/init', bindElementorHook);
		darkModeQuery.addEventListener('change', handleThemeChange);
		reducedMotionQuery.addEventListener('change', handleMotionChange);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initialize, { once: true });
	} else {
		initialize();
	}
})();
