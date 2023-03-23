jQuery($ => {
    $(document).on("click", ".delete-product-button", function() {
        const product_id = $(this).attr("data-id");

        bootbox.confirm({
            message: "<h4>Вы уверены?</h4>",
            buttons: {
                confirm: {
                    label: "<span class='glyphicon glyphicon-ok'></span> Да",
                    className: "btn-danger"
                },
                cancel: {
                    label: "<span class='glyphicon glyphicon-remove'></span> Нет",
                    className: "btn-primary"
                }
            },
            callback: result => {
        
                if(result === true) {
                    $.ajax({
                        url: '/php-course/rest-jquery-shop/api/product/delete.php',
                        type: 'POST',
                        dataType: 'json',
                        data: JSON.stringify({id: product_id}),
                        success: result => {
                            location.reload();
                            showProduct()
                        },
                        error:(xhr, resp, text) => {
                            console.log(xhr, resp, text)
                        }
                    })
                }
            }
        });
    })
})