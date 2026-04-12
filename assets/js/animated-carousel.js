/**
 * Frontend handler for AVH Animated Carousel (extends Elementor CarouselBase / Nested Carousel behaviour).
 */
(function ($) {
	'use strict';

	if (typeof elementorModules === 'undefined' || !elementorModules.frontend || !elementorModules.frontend.handlers || !elementorModules.frontend.handlers.CarouselBase) {
		return;
	}

	const CarouselBase = elementorModules.frontend.handlers.CarouselBase;

	class AVHAnimatedNestedCarousel extends CarouselBase {
		getDefaultSettings() {
			const settings = super.getDefaultSettings();
			settings.selectors.carousel = '.e-n-carousel';
			settings.selectors.slidesWrapper = '.e-n-carousel > .swiper-wrapper';
			return settings;
		}

		getSwiperSettings() {
			const settings = super.getSwiperSettings();
			const elementSettings = this.getElementSettings();
			const isRtl = elementorFrontend.config.is_rtl;
			const widgetSelector = `.elementor-element-${this.getID()}`;

			if (elementorFrontend.isEditMode()) {
				delete settings.autoplay;
				settings.loop = false;
				settings.noSwipingSelector = '.swiper-slide > .e-con .elementor-element';
				settings.allowTouchMove = false;
			}

			if ('yes' === elementSettings.arrows) {
				settings.navigation = {
					prevEl: isRtl
						? `${widgetSelector} .elementor-swiper-button-next`
						: `${widgetSelector} .elementor-swiper-button-prev`,
					nextEl: isRtl
						? `${widgetSelector} .elementor-swiper-button-prev`
						: `${widgetSelector} .elementor-swiper-button-next`,
				};
			}

			this.applySwipeOptions(settings);

			settings.slidesPerView = 'auto';
			settings.centeredSlides = true;
			settings.slidesPerGroup = 1;

			if (settings.breakpoints) {
				Object.keys(settings.breakpoints).forEach(function (bp) {
					settings.breakpoints[bp].slidesPerView = 'auto';
					settings.breakpoints[bp].slidesPerGroup = 1;
				});
			}

			if (settings.loop && !elementorFrontend.isEditMode()) {
				settings.loopAdditionalSlides = Math.max(
					settings.loopAdditionalSlides || 0,
					2
				);
			}

			return settings;
		}

		applyOffsetSettings(elementSettings, swiperSettings, slidesToShow) {
			if (elementorFrontend.isEditMode()) {
				return;
			}
			super.applyOffsetSettings(elementSettings, swiperSettings, slidesToShow);
		}

		async onInit(...args) {
			this.wrapSlideContent();
			super.onInit(...args);
			this.ranElementHandlers = false;
		}

		async initSwiper() {
			const SwiperCtor = elementorFrontend.utils.swiper;
			this.swiper = await new SwiperCtor(
				this.elements.$swiperContainer,
				this.getSwiperSettings()
			);
			this.elements.$swiperContainer.data('swiper', this.swiper);
		}

		handleElementHandlers() {
			if (this.ranElementHandlers || !this.swiper) {
				return;
			}
			const slideDuplicateClass = this.swiper.params.slideDuplicateClass;
			const duplicates = Array.from(this.swiper.slides).filter(function (slide) {
				return slide.classList.contains(slideDuplicateClass);
			});
			duplicates.forEach(function (slide) {
				$(slide)
					.find('.elementor-element')
					.each(function () {
						elementorFrontend.elementsHandler.runReadyTrigger($(this));
					});
			});
			this.ranElementHandlers = true;
		}

		wrapSlideContent() {
			if (!elementorFrontend.isEditMode()) {
				return;
			}
			const settings = this.getSettings();
			const slideClass = settings.selectors.slideContent.replace('.', '');
			const $widget = this.$element;
			let index = 1;
			this.findElement(`${settings.selectors.slidesWrapper} > .e-con`).each(function () {
				const $con = $(this);
				const alreadyWrapped = $con.closest('div').hasClass(slideClass);
				const $target = $widget.find(
					`${settings.selectors.slidesWrapper} > .${slideClass}:nth-child(${index})`
				);
				if (!alreadyWrapped) {
					$target.append($con);
				}
				index++;
			});
		}

		togglePauseOnHover(state) {
			if (elementorFrontend.isEditMode()) {
				return;
			}
			super.togglePauseOnHover(state);
		}

		getChangeableProperties() {
			return {
				arrows_position: 'arrows_position',
			};
		}

		applySwipeOptions(settings) {
			if (this.isTouchDevice()) {
				settings.touchRatio = 1;
				settings.longSwipesRatio = 0.3;
				settings.followFinger = true;
				settings.threshold = 10;
			} else {
				settings.shortSwipes = false;
			}
		}

		isTouchDevice() {
			return elementorFrontend.utils.environment.isTouchDevice;
		}

		async linkContainer(e) {
			const detail = e.detail;
			const container = detail.container;
			const index = detail.index;
			const targetContainer = detail.targetContainer;
			const actionType = detail.action.type;
			const $containerView = container.view.$el;
			if (container.model.get('id') !== this.$element.data('id')) {
				return;
			}
			const slides = this.getDefaultElements().$slides;
			let parentNode;
			let node;
			switch (actionType) {
				case 'move':
					[parentNode, node] = this.move($containerView, index, targetContainer, slides);
					break;
				case 'duplicate':
					[parentNode, node] = this.duplicate($containerView, index, targetContainer, slides);
					break;
				default:
					return;
			}
			if (undefined !== parentNode) {
				parentNode.appendChild(node);
			}
			this.shouldHideNavButtons($containerView, slides);
			this.updateIndexValues(slides);
			const swiperOk = this.swiper && !this.swiper.destroyed;
			const hasMultiple = slides.length > 1;
			if (!swiperOk && hasMultiple) {
				await this.initSwiper();
			} else if (swiperOk && !hasMultiple) {
				this.swiper.destroy(true);
			}
			this.updateListeners();
		}

		updateListeners() {
			if (!this.swiper) {
				return;
			}
			this.swiper.initialized = false;
			this.swiper.init();
		}

		move($view, index, targetContainer, slides) {
			return [slides[index], targetContainer.view.$el[0]];
		}

		duplicate($view, index, targetContainer, slides) {
			return [slides[index + 1], targetContainer.view.$el[0]];
		}

		updateIndexValues(slides) {
			slides.each(function (i, slide) {
				slide.setAttribute('data-slide', i + 1);
			});
		}

		bindEvents() {
			super.bindEvents();
			elementorFrontend.elements.$window.on(
				'elementor/nested-container/atomic-repeater',
				this.linkContainer.bind(this)
			);
		}

		shouldHideNavButtons($element, slides) {
			const buttons = $element[0].querySelectorAll('.elementor-swiper-button');
			const single = slides.length === 1;
			const hasHide = buttons[0] && buttons[0].classList.contains('hide');
			if (single !== hasHide) {
				buttons.forEach(function (btn) {
					btn.classList.toggle('hide', single);
				});
			}
		}
	}

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/avh-animated-carousel.default',
			function ($scope) {
				if (!$scope.find('.e-n-carousel').length) {
					return;
				}
				elementorFrontend.elementsHandler.addHandler(AVHAnimatedNestedCarousel, {
					$element: $scope,
				});
			}
		);
	});
})(jQuery);
