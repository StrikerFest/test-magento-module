define(["jquery", "mage/translate", "jquery/ui"], function ($, $t) {
    "use strict";
    console.log("addToCartButtonTextWhileAdding");
    return function (target) {
        $.widget("mage.catalogAddToCart", target, {
            options: {
                // addToCartButtonTextAdded: $t("Product Added to Cart"),
                addToCartButtonTextWhileAdding: $t(
                    "Product Adding to Cart ..."
                ),
                // addToCartButtonTextDefault: $t("Product Add to Cart"),
            },
        });

        return $.mage.catalogAddToCart;
    };
});
