document.querySelectorAll('.expanded-content-button').forEach(function (expandedContentButton) {
    var switchContentButton = expandedContentButton.querySelectorAll('.switch-content-button')[0];
    var expandedContentText = expandedContentButton.querySelectorAll('.expanded-content-text')[0];
    var isOpen = false;
    switchContentButton.addEventListener('click', function () {
        if (!isOpen) {
            expandedContentText.classList.add('expanded-content-text--open');
            isOpen = true;
        } else {
            expandedContentText.classList.remove('expanded-content-text--open');
            isOpen = false;
        }
    });
});