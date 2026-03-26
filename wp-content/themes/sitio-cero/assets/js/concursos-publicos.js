document.addEventListener('DOMContentLoaded', function () {
    var accordions = document.querySelectorAll('.concurso-accordion details');
    if (!accordions.length) {
        return;
    }

    var DURATION = 1500;

    function openItem(detail, body) {
        detail.setAttribute('open', '');
        detail.classList.add('is-active');
        body.style.height = '0px';
        body.style.opacity = '0';
        body.style.transform = 'translateY(-4px)';

        var targetHeight = body.scrollHeight;
        requestAnimationFrame(function () {
            body.style.height = targetHeight + 'px';
            body.style.opacity = '1';
            body.style.transform = 'translateY(0)';
        });

        var onEnd = function (event) {
            if (event.propertyName !== 'height') {
                return;
            }
            body.style.height = 'auto';
            body.removeEventListener('transitionend', onEnd);
            detail.dataset.animating = '0';
        };
        body.addEventListener('transitionend', onEnd);
    }

    function closeItem(detail, body) {
        var startHeight = body.scrollHeight;
        body.style.height = startHeight + 'px';
        body.offsetHeight;

        detail.classList.remove('is-active');
        requestAnimationFrame(function () {
            body.style.height = '0px';
            body.style.opacity = '0';
            body.style.transform = 'translateY(-4px)';
        });

        window.setTimeout(function () {
            detail.removeAttribute('open');
            detail.dataset.animating = '0';
        }, DURATION);
    }

    var accordionRoot = document.querySelectorAll('.concurso-accordion');
    accordionRoot.forEach(function (root) {
        root.classList.add('is-js');
    });

    accordions.forEach(function (detail) {
        var body = detail.querySelector('.pc-accordion__body');
        var summary = detail.querySelector('summary');
        if (!body || !summary) {
            return;
        }

        detail.removeAttribute('open');
        detail.classList.remove('is-active');
        detail.dataset.animating = '0';
        body.style.height = '0px';
        body.style.opacity = '0';
        body.style.transform = 'translateY(-4px)';

        summary.addEventListener('click', function (event) {
            event.preventDefault();
            if (detail.dataset.animating === '1') {
                return;
            }
            detail.dataset.animating = '1';

            if (detail.hasAttribute('open')) {
                closeItem(detail, body);
            } else {
                openItem(detail, body);
            }
        });
    });
});
