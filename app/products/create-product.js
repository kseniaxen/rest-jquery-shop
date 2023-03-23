jQuery(($) => {
    $(document).on('click', '.create-product-button', function () {
        $.getJSON("http://localhost:8080/php-course/rest-jquery-shop/api/category/read.php", (data) => {
            let categories_options_html = `<select name="category_id" class="form-control">`;

            $.each(data.records, (key, val) => {
                categories_options_html += `<option value="` + val.id + `">` + val.name + `</option>`;
            });

            categories_options_html += `</select>`;
            let create_product_html = `
            <div id="read-products" class="btn btn-primary pull-right m-b-15px read-products-button">
                <span class="glyphicon glyphicon-list"></span> Все товары
            </div>
            <form id="create-product-form" action="#" method="post" border="0">
                <table class="table table-hover table-responsive table-bordered">

                    <tr>
                        <td>Название</td>
                        <td><input type="text" name="name" class="form-control" required /></td>
                    </tr>

                    <tr>
                        <td>Цена</td>
                        <td><input type="number" min="1" name="price" class="form-control" required /></td>
                    </tr>

                    <tr>
                        <td>Описание</td>
                        <td><textarea name="description" class="form-control" required></textarea></td>
                    </tr>

                    <tr>
                        <td>Категория</td>
                        <td>` + categories_options_html + `</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" class="btn btn-primary">
                                <span class="glyphicon glyphicon-plus"></span> Создать товар
                            </button>
                        </td>
                    </tr>
                </table>
            </form>`;
            $("#page-content").html(create_product_html);
            changePageTitle("Создание товара");
        })
    })

    $(document).on("submit", "#create-product-form", function () {
        let form_data = JSON.stringify($(this).serializeObject());
        $.ajax({
            url: "/php-course/rest-jquery-shop/api/product/create.php",
            type: "POST",
            contentType: "application/json",
            data: form_data,
            success: result => {
                showProducts();
            },
            error: (xhr, resp, text) => {
                console.log(xhr, resp, text);
            }
        });

        return false;
    });
})