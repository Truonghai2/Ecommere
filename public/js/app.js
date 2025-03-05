
// console.log(app_url);
function actionOnScrollBottom(element, callback) {
    $(element).on('scroll', () => {
        var scrollBottom = $(document).height() - ($(window).scrollTop() + $(window).height());
        if (scrollBottom < 14) {
            callback();
        }
    });
}


function actionOnScrollBottomHome(element, callback, footerSelector = 'footer') {
    $(element).on('scroll', () => {
        var footerHeight = $(footerSelector).outerHeight();
        var scrollBottom = $(document).height() - ($(window).scrollTop() + $(window).height() + footerHeight);
        if (scrollBottom < 10) {
            callback();
        }
    });
}


function actionOnScrollBottomElement(element, callback) {
    $(element).on('scroll', () => {
        var scrollTop = $(element).scrollTop();
        var elementHeight = $(element).height();
        var scrollHeight = $(element)[0].scrollHeight;

        var scrollBottom = scrollHeight - (scrollTop + elementHeight);

        if (scrollBottom < 35) {
            callback();
        }
    });
}

// 756 => 580

document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartIcon = document.querySelector('.cart-icon');
    const cartCount = document.querySelector('.cart-count');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const buttonRect = button.getBoundingClientRect();
            const cartRect = cartIcon.getBoundingClientRect();

            const animationElem = document.createElement('div');
            animationElem.className = 'add-to-cart-animation';
            document.body.appendChild(animationElem);

            const startX = buttonRect.left + buttonRect.width / 2;
            const startY = buttonRect.top + buttonRect.height / 2;
            const endX = cartRect.left + cartRect.width / 2;
            const endY = cartRect.top + cartRect.height / 2;

            animationElem.style.left = `${startX}px`;
            animationElem.style.top = `${startY}px`;

            setTimeout(() => {
                animationElem.style.left = `${endX}px`;
                animationElem.style.top = `${endY}px`;
                animationElem.style.width = '0px';
                animationElem.style.height = '0px';
            }, 10);

            // Update cart count
            let count = parseInt(cartCount.textContent, 10);
            cartCount.textContent = count + 1;

            // Remove animation element after animation
            animationElem.addEventListener('animationend', () => {
                animationElem.remove();
            });
        });
    });
});