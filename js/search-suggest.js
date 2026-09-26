document.addEventListener('DOMContentLoaded', function () {

    var inputs = document.querySelectorAll('.search-box input[type="text"]');
    var inPagesFolder = window.location.pathname.indexOf('/pages/') !== -1;
    var suggestUrl = inPagesFolder ? 'search-suggest.php' : 'pages/search-suggest.php';
    var productLinkPrefix = inPagesFolder ? 'product.php?id=' : 'pages/product.php?id=';
    var imagePrefix = inPagesFolder ? '../' : '';

    inputs.forEach(function (input) {

        var wrapper = input.closest('.search-wrapper');
        if (!wrapper) return;

        var box = wrapper.querySelector('.search-suggestions');
        if (!box) return;

        var debounceTimer = null;

        input.addEventListener('input', function () {

            var term = input.value.trim();

            clearTimeout(debounceTimer);

            if (term.length < 2) {
                box.innerHTML = '';
                box.classList.remove('search-suggestions-show');
                return;
            }

            debounceTimer = setTimeout(function () {

                fetch(suggestUrl + '?q=' + encodeURIComponent(term))
                    .then(function (res) { return res.json(); })
                    .then(function (data) { renderSuggestions(data); })
                    .catch(function () {
                        box.innerHTML = '';
                        box.classList.remove('search-suggestions-show');
                    });

            }, 250);

        });

        document.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target)) {
                box.innerHTML = '';
                box.classList.remove('search-suggestions-show');
            }
        });

        function renderSuggestions(items) {

            if (!items || items.length == 0) {
                box.innerHTML = '';
                box.classList.remove('search-suggestions-show');
                return;
            }

            var html = '';

            items.forEach(function (item) {
                html += '<a href="' + productLinkPrefix + item.id + '" class="suggestion-item">' +
                        '<img src="' + imagePrefix + item.image + '" alt="">' +
                        '<span><strong>' + item.name + '</strong><br><small>' + item.category + '</small></span>' +
                        '</a>';
            });

            box.innerHTML = html;
            box.classList.add('search-suggestions-show');

        }

    });

});