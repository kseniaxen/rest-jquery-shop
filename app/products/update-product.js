jQuery($ => {
    $(document).on("click", ".update-product-button", function () {
        const id = $(this).attr("data-id")
        $.getJSON("http://localhost:8080/php-course/rest-jquery-shop/api/product/read_one.php?id=" + id, data => {
            const name = data.name;
            const price = data.price;
            const description = data.description;
            const category_id = data.category_id;
            const category_name = data.category_name;
            $.getJSON("http://localhost:8080/php-course/rest-jquery-shop/api/category/read.php", data => {
                let categories_options_html = `<select name="category_id" class="form-control">`

                $.each(data.records, (key, val) => {
                    if (val.id == category_id) {
                        categories_options_html += `<option value="` + val.id + `" selected>` + val.name + `</option>`
                    } else {
                        categories_options_html += `<option value="` + val.id + `">` + val.name + `</option>`
                    }
                });

                categories_options_html += `</select>`

                let update_product_html = `
                    <div id="read-products" class="btn btn-primary pull-right m-b-15px read-products-button">
                        <span class="glyphicon glyphicon-list"></span> Все товары
                    </div>

                    <form id="update-product-form" action="#" method="post" border="0">
                        <table class="table table-hover table-responsive table-bordered">

                            <tr>
                                <td>Название</td>
                                <td><input value=\"` + name + `\" type="text" name="name" class="form-control" required /></td>
                            </tr>

                            <tr>
                                <td>Цена</td>
                                <td><input value=\"` + price + `\" type="number" min="1" name="price" class="form-control" required /></td>
                            </tr>

                            <tr>
                                <td>Описание</td>
                                <td><textarea name="description" class="form-control" required>` + description + `</textarea></td>
                            </tr>

                            <tr>
                                <td>Категория</td>
                                <td>` + categories_options_html + `</td>
                            </tr>

                            <tr>
                                <td><input value=\"` + id + `\" name="id" type="hidden" /></td>
                                <td>
                                    <button type="submit" class="btn btn-info">
                                        <span class="glyphicon glyphicon-edit"></span> Обновить товар
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </form>`;

                $("#page-content").html(update_product_html);
                changePageTitle("Обновление товара");
            })
        })
        $(document).on('submit', '#update-product-form', function() {
            const form_data = JSON.stringify($(this).serializeObject());
            $.ajax({
                url: "/php-course/rest-jquery-shop/api/product/update.php",
                type : "POST",
                contentType : "application/json",
                data : form_data,
                success : result => {
                    showProducts();
                },
                error: (xhr, resp, text) => {
                    console.log(xhr, resp, text);
                }
            });
        
            return false;
        })
    })
})