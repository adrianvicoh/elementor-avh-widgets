document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.expanded-content-widget-container').forEach(function (expandedContentButton) {
        var switchContentButton = expandedContentButton.querySelectorAll('.switch-content-button')[0];
        var expandedContentContainer = expandedContentButton.querySelectorAll('.expanded-content-main')[0];
        var isOpen = false;
        switchContentButton.addEventListener('click', function () {
            if (!isOpen) {
                expandedContentContainer.classList.add('expanded-content-main--open');
                isOpen = true;
            } else {
                expandedContentContainer.classList.remove('expanded-content-main--open');
                isOpen = false;
            }
        });
    });
});