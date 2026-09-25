(() => {
	'use strict';

	const ENABLED_SELECTOR = '.avh-animated-menu--yes';
	const instances = new Map();
	const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

	const clamp = (value, min, max) => Math.max(min, Math.min(max, value));

	function getNumber(style, property, fallback) {
		const value = Number.parseFloat(style.getPropertyValue(property));
		return Number.isFinite(value) ? value : fallback;
	}

	function getColor(value, fallback) {
		const parts = value.trim().match(/[\d.]+/g);

		if (value.trim().startsWith('#')) {
			const hex = value.trim().slice(1);
			const expanded = hex.length === 3
				? hex.split('').map((character) => character + character).join('')
				: hex;

			if (/^[\da-f]{6}$/i.test(expanded)) {
				return [
					Number.parseInt(expanded.slice(0, 2), 16),
					Number.parseInt(expanded.slice(2, 4), 16),
					Number.parseInt(expanded.slice(4, 6), 16),
				];
			}
		}

		if (parts && parts.length >= 3) {
			return parts.slice(0, 3).map((part) => clamp(Number.parseFloat(part), 0, 255));
		}

		return fallback;
	}

	function getSettings(root) {
		const style = window.getComputedStyle(root);
		const extension = getNumber(style, '--avh-animated-menu-extension', 100) / 100;
		const blur = getNumber(style, '--avh-animated-menu-blur', 22);

		return {
			blur,
			endColor: getColor(
				style.getPropertyValue('--avh-animated-menu-end-color'),
				[90, 180, 100]
			),
			extension,
			motion: root.classList.contains('avh-animated-menu-motion-static')
				|| reducedMotionQuery.matches
				? 'static'
				: 'cursor',
			pad: Math.ceil(Math.max(90, blur * 3, 45 * extension)),
			speed: clamp(getNumber(style, '--avh-animated-menu-speed', 0.18), 0.01, 0.5),
			spread: 26 * extension,
			startColor: getColor(
				style.getPropertyValue('--avh-animated-menu-start-color'),
				[150, 220, 150]
			),
		};
	}

	function createParticle(settings, anchor) {
		return {
			alpha: 0.28,
			colorShift: (Math.random() - 0.5) * 0.16,
			offsetX: (Math.random() - 0.5) * settings.spread,
			offsetY: (Math.random() - 0.5) * settings.spread,
			size: (40 + Math.random() * 50) * settings.extension,
			x: anchor.x,
			y: anchor.y,
		};
	}

	class AnimatedMenu {
		constructor(root) {
			this.root = root;
			this.canvas = document.createElement('canvas');
			this.canvas.className = 'avh-animated-menu__canvas';
			this.canvas.setAttribute('aria-hidden', 'true');
			this.root.insertBefore(this.canvas, this.root.firstChild);
			this.context = this.canvas.getContext('2d');
			this.anchor = { x: 0, y: 0, targetX: 0, targetY: 0 };
			this.particles = [];
			this.frame = null;
			this.settings = getSettings(root);
			this.resizeObserver = typeof ResizeObserver === 'function'
				? new ResizeObserver(() => this.refresh())
				: null;

			this.onPointerMove = this.onPointerMove.bind(this);
			this.onVisibilityChange = this.onVisibilityChange.bind(this);
			this.onResize = this.refresh.bind(this);
			this.render = this.render.bind(this);

			this.root.addEventListener('pointermove', this.onPointerMove, { passive: true });
			document.addEventListener('visibilitychange', this.onVisibilityChange);
			window.addEventListener('resize', this.onResize);
			this.resizeObserver?.observe(this.root);
			this.refresh();
		}

		getInitialAnchor() {
			const item = this.root.querySelector(
				'.elementor-nav-menu--main .menu-item > a, .elementor-nav-menu--main a.elementor-item'
			);

			if (!item) {
				return {
					x: this.settings.pad + this.root.clientWidth * 0.2,
					y: this.settings.pad + this.root.clientHeight / 2,
				};
			}

			const itemRect = item.getBoundingClientRect();
			const canvasRect = this.canvas.getBoundingClientRect();

			return {
				x: itemRect.left - canvasRect.left - 14 * this.settings.extension,
				y: itemRect.top - canvasRect.top + itemRect.height / 2,
			};
		}

		refresh() {
			this.settings = getSettings(this.root);
			const padValue = `${this.settings.pad}px`;
			if (this.root.style.getPropertyValue('--avh-animated-menu-pad').trim() !== padValue) {
				this.root.style.setProperty('--avh-animated-menu-pad', padValue);
			}

			const width = this.root.clientWidth + this.settings.pad * 2;
			const height = this.root.clientHeight + this.settings.pad * 2;
			const pixelRatio = Math.min(window.devicePixelRatio || 1, 2);

			if (!width || !height) {
				return;
			}

			this.canvas.width = Math.round(width * pixelRatio);
			this.canvas.height = Math.round(height * pixelRatio);
			this.context.setTransform(pixelRatio, 0, 0, pixelRatio, 0, 0);

			const shouldReset = !this.particles.length || this.settings.motion === 'static';
			if (shouldReset) {
				const initialAnchor = this.getInitialAnchor();
				this.anchor.x = this.anchor.targetX = initialAnchor.x;
				this.anchor.y = this.anchor.targetY = initialAnchor.y;
				this.particles = Array.from(
					{ length: 4 },
					() => createParticle(this.settings, this.anchor)
				);
			}

			this.draw();
		}

		onPointerMove(event) {
			if (this.settings.motion !== 'cursor') {
				return;
			}

			const rect = this.canvas.getBoundingClientRect();
			this.anchor.targetX = clamp(event.clientX - rect.left, 0, rect.width);
			this.anchor.targetY = clamp(event.clientY - rect.top, 0, rect.height);
			this.requestRender();
		}

		onVisibilityChange() {
			if (document.hidden && this.frame) {
				window.cancelAnimationFrame(this.frame);
				this.frame = null;
			} else if (!document.hidden) {
				this.draw();
			}
		}

		requestRender() {
			if (!this.frame && !document.hidden) {
				this.frame = window.requestAnimationFrame(this.render);
			}
		}

		render() {
			this.frame = null;

			const speed = this.settings.speed;
			const particleSpeed = speed * 0.67;
			this.anchor.x += (this.anchor.targetX - this.anchor.x) * speed;
			this.anchor.y += (this.anchor.targetY - this.anchor.y) * speed;

			let isMoving = Math.abs(this.anchor.targetX - this.anchor.x) > 0.25
				|| Math.abs(this.anchor.targetY - this.anchor.y) > 0.25;

			for (const particle of this.particles) {
				const targetX = this.anchor.x + particle.offsetX;
				const targetY = this.anchor.y + particle.offsetY;
				particle.x += (targetX - particle.x) * particleSpeed;
				particle.y += (targetY - particle.y) * particleSpeed;
				isMoving = isMoving
					|| Math.abs(targetX - particle.x) > 0.25
					|| Math.abs(targetY - particle.y) > 0.25;
			}

			this.draw();

			if (isMoving) {
				this.requestRender();
			}
		}

		draw() {
			const pixelRatio = Math.min(window.devicePixelRatio || 1, 2);
			const width = this.canvas.width / pixelRatio;
			const height = this.canvas.height / pixelRatio;

			if (!width || !height) {
				return;
			}

			this.context.clearRect(0, 0, width, height);
			this.context.globalCompositeOperation = 'multiply';
			this.context.filter = `blur(${this.settings.blur}px)`;

			for (const particle of this.particles) {
				const progress = clamp(
					this.anchor.x / width + particle.colorShift,
					0,
					1
				);
				const color = this.settings.startColor.map(
					(channel, index) => Math.round(
						channel + (this.settings.endColor[index] - channel) * progress
					)
				);
				const gradient = this.context.createRadialGradient(
					particle.x,
					particle.y,
					0,
					particle.x,
					particle.y,
					particle.size
				);

				gradient.addColorStop(0, `rgba(${color.join(', ')}, ${particle.alpha})`);
				gradient.addColorStop(0.5, `rgba(${color.join(', ')}, ${particle.alpha * 0.5})`);
				gradient.addColorStop(1, `rgba(${color.join(', ')}, 0)`);
				this.context.fillStyle = gradient;
				this.context.beginPath();
				this.context.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
				this.context.fill();
			}

			this.context.filter = 'none';
			this.context.globalCompositeOperation = 'source-over';
		}

		destroy() {
			window.cancelAnimationFrame(this.frame);
			this.resizeObserver?.disconnect();
			this.root.removeEventListener('pointermove', this.onPointerMove);
			document.removeEventListener('visibilitychange', this.onVisibilityChange);
			window.removeEventListener('resize', this.onResize);
			this.root.style.removeProperty('--avh-animated-menu-pad');
			this.canvas.remove();
		}
	}

	let scanQueued = false;

	function scan() {
		scanQueued = false;

		for (const [root, instance] of instances) {
			if (!root.isConnected || !root.matches(ENABLED_SELECTOR)) {
				instance.destroy();
				instances.delete(root);
			} else {
				instance.refresh();
			}
		}

		document.querySelectorAll(ENABLED_SELECTOR).forEach((root) => {
			if (!instances.has(root)) {
				instances.set(root, new AnimatedMenu(root));
			}
		});
	}

	function queueScan() {
		if (!scanQueued) {
			scanQueued = true;
			window.requestAnimationFrame(scan);
		}
	}

	function boot() {
		scan();

		const observer = new MutationObserver(queueScan);
		observer.observe(document.body, {
			attributes: true,
			attributeFilter: ['class', 'style'],
			childList: true,
			subtree: true,
		});

		reducedMotionQuery.addEventListener?.('change', queueScan);

		if (window.elementorFrontend?.hooks) {
			window.elementorFrontend.hooks.addAction('frontend/element_ready/nav-menu.default', queueScan);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot, { once: true });
	} else {
		boot();
	}
})();
