document.addEventListener('DOMContentLoaded', function () {

    var forms = document.querySelectorAll('.add-to-cart-form');

    forms.forEach(function (form) {

        form.addEventListener('submit', function (e) {

            e.preventDefault();

            var formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    var cartLink = form.action.replace('cart-add.php', 'cart.php');
                    showCartToast(data.product_name, cartLink);
                }
            })
            .catch(function () {
                // If anything goes wrong (e.g. very old browser), fall back to a normal submit
                form.submit();
            });

        });

    });

    function showCartToast(productName, cartLink) {

        var existing = document.getElementById('cart-toast');
        if (existing) {
            existing.remove();
        }

        var toast = document.createElement('div');
        toast.id = 'cart-toast';
        toast.className = 'cart-toast';
        toast.innerHTML =
            '<p><strong>' + productName + '</strong> added to cart!</p>' +
            '<a href="' + cartLink + '" class="cart-toast-link">View Cart</a>';

        document.body.appendChild(toast);

        setTimeout(function () {
            toast.classList.add('cart-toast-show');
        }, 10);

        setTimeout(function () {
            toast.classList.remove('cart-toast-show');
            setTimeout(function () {
                toast.remove();
            }, 300);
        }, 3000);

    }

});