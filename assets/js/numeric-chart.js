/**
 * Frontend handler for Numeric Chart.
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

	function getScopeElement(scope) {
		if (!scope) {
			return null;
		}

		return scope.jquery && scope.length ? scope[0] : scope;
	}

	function initOnScope(scope) {
		var scopeElement = getScopeElement(scope);
		if (!scopeElement) {
			return;
		}

		var widget = scopeElement.querySelector('.numeric-chart');
		var canvas = widget ? widget.querySelector('.numeric-chart__canvas') : null;
		if (!widget || !canvas || typeof window.Chart !== 'function') {
			return;
		}

		// Elementor keeps the widget wrapper across editor rerenders and replaces
		// its content, so a chart bound to a previous canvas is destroyed first.
		var previousChart = scopeElement.numericChart;
		if (previousChart) {
			if (previousChart.canvas === canvas) {
				return;
			}

			previousChart.destroy();
			scopeElement.numericChart = null;
		}

		var settings = parseSettings(widget);
		var type = ['bar', 'line', 'doughnut'].indexOf(settings.type) !== -1 ? settings.type : 'bar';
		var colors = Array.isArray(settings.colors) ? settings.colors : [];
		var labels = Array.isArray(settings.labels) ? settings.labels : [];
		var values = Array.isArray(settings.values) ? settings.values : [];

		scopeElement.numericChart = new window.Chart(canvas.getContext('2d'), {
			type: type,
			data: {
				labels: labels,
				datasets: [
					{
						label: settings.title || '',
						data: values,
						backgroundColor: colors,
						borderColor: colors,
						borderWidth: 2,
						borderRadius: type === 'bar' ? 6 : 0,
						fill: type === 'line' && settings.fillLine === true,
						tension: type === 'line' ? 0.3 : 0,
					},
				],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: settings.showLegend === true,
					},
					title: {
						display: Boolean(settings.title),
						text: settings.title || '',
					},
				},
				scales: type === 'doughnut' ? {} : {
					x: {
						grid: {
							display: settings.showGrid === true,
						},
					},
					y: {
						beginAtZero: true,
						grid: {
							display: settings.showGrid === true,
						},
					},
				},
			},
		});
	}

	function initStandalone() {
		var widgets = document.querySelectorAll('.elementor-widget-numeric-chart');
		for (var index = 0; index < widgets.length; index++) {
			initOnScope(widgets[index]);
		}
	}

	if (window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', function () {
			if (window.elementorFrontend && window.elementorFrontend.hooks) {
				window.elementorFrontend.hooks.addAction(
					'frontend/element_ready/numeric-chart.default',
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
