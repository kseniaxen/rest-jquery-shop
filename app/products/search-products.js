jQuery($ => {
    $(document).on("submit", "#search-product-form", function () {

        const keywords = $(this).find("input[name='keywords']").val();
        $.getJSON("http://localhost:8080/php-course/rest-jquery-shop/api/product/search.php?s=" + keywords, data => {

            readProductsTemplate(data, keywords);
            changePageTitle("Поиск товаров: " + keywords);

        });
        return false;
    });

});