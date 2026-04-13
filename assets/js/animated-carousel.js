/**
 * Frontend handler for AVH Animated Carousel.
 * Self-contained Swiper initialization — no dependency on Elementor CarouselBase.
 */
(function ($) {
	'use strict';

	function initCarousel($scope) {
		var $carousel = $scope.find('.avh-ac-carousel');
		if (!$carousel.length) {
			return;
		}

		var widgetId = $scope.data('id');
		var widgetSelector = '.elementor-element-' + widgetId;
		var settings = getWidgetSettings($scope);
		var isRtl = elementorFrontend.config.is_rtl;
		var isEditor = elementorFrontend.isEditMode();

		var swiperConfig = {
			slidesPerView: 'auto',
			centeredSlides: true,
			slidesPerGroup: 1,
			speed: parseInt(settings.speed, 10) || 500,
			spaceBetween: parseInt(settings.avh_slide_gap ? settings.avh_slide_gap.size : 10, 10) || 10,
			loop: !isEditor && settings.infinite === 'yes',
			watchSlidesProgress: true,
		};

		if (!isEditor && settings.autoplay === 'yes') {
			swiperConfig.autoplay = {
				delay: parseInt(settings.autoplay_speed, 10) || 5000,
				disableOnInteraction: settings.pause_on_interaction === 'yes',
				pauseOnMouseEnter: settings.pause_on_hover === 'yes',
			};
		}

		if (settings.arrows === 'yes') {
			swiperConfig.navigation = {
				prevEl: isRtl
					? widgetSelector + ' .elementor-swiper-button-next'
					: widgetSelector + ' .elementor-swiper-button-prev',
				nextEl: isRtl
					? widgetSelector + ' .elementor-swiper-button-prev'
					: widgetSelector + ' .elementor-swiper-button-next',
			};
		}

		var pagination = settings.pagination;
		if (pagination) {
			swiperConfig.pagination = {
				el: widgetSelector + ' .swiper-pagination',
				type: pagination,
				clickable: true,
			};
		}

		if (swiperConfig.loop) {
			swiperConfig.loopAdditionalSlides = 2;
		}

		var swiperEl = $carousel[0];

		if (typeof elementorFrontend.utils !== 'undefined' && elementorFrontend.utils.swiper) {
			new elementorFrontend.utils.swiper(swiperEl, swiperConfig).then(function (swiperInstance) {
				storeAndBind($scope, swiperInstance, settings);
			});
		} else if (typeof Swiper !== 'undefined') {
			var swiperInstance = new Swiper(swiperEl, swiperConfig);
			storeAndBind($scope, swiperInstance, settings);
		}
	}

	function storeAndBind($scope, swiper, settings) {
		$scope.data('avhSwiper', swiper);

		$(window).on('resize.avhAnimatedCarousel' + $scope.data('id'), function () {
			if (swiper && !swiper.destroyed) {
				swiper.update();
			}
		});

		if (settings.pause_on_hover === 'yes' && settings.autoplay === 'yes') {
			$scope.find('.avh-ac-carousel').on('mouseenter', function () {
				if (swiper.autoplay && swiper.autoplay.running) {
					swiper.autoplay.stop();
				}
			}).on('mouseleave', function () {
				if (swiper.autoplay && !swiper.autoplay.running) {
					swiper.autoplay.start();
				}
			});
		}
	}

	function getWidgetSettings($scope) {
		var widgetModel = null;

		if (typeof elementorFrontend.config.elements !== 'undefined') {
			widgetModel = elementorFrontend.config.elements.data[$scope.data('model-cid')];
		}

		if (widgetModel) {
			return widgetModel.attributes || {};
		}

		var settingsAttr = $scope.data('settings') || {};
		return settingsAttr;
	}

	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction(
			'frontend/element_ready/avh-animated-carousel.default',
			function ($scope) {
				initCarousel($scope);
			}
		);
	});
})(jQuery);
