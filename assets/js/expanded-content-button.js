function initExpandedContentButton(scope) {
    var root = scope || document;

    root.querySelectorAll('.expanded-content-widget-container').forEach(function (expandedContentButton) {
        var switchContentButton = expandedContentButton.querySelector('.switch-content-button');
        var expandedContentContainer = expandedContentButton.querySelector('.expanded-content-main');

        if (!switchContentButton || !expandedContentContainer || switchContentButton.dataset.expandedContentBound === '1') {
            return;
        }

        switchContentButton.dataset.expandedContentBound = '1';
        switchContentButton.addEventListener('click', function () {
            expandedContentContainer.classList.toggle('expanded-content-main--open');
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initExpandedContentButton(document);
});

window.addEventListener('elementor/frontend/init', function () {
    if (!window.elementorFrontend || !window.elementorFrontend.hooks) {
        return;
    }

    window.elementorFrontend.hooks.addAction('frontend/element_ready/expanded-content-button.default', function ($scope) {
        initExpandedContentButton($scope[0]);
    });
});
