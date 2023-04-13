var config = {

    map: {
        "*": {
            "theme/hello": 'Magento_Theme/js/hello',
            validation: "js/validation"

        },
    },
    deps: ["jquery","js/main"],

    shim: {
        hello_world: {
            deps: ["jquery"],
            exports: "hi_world",
            init: function () {
                return {
                    hi_world: hi_world,
                    sup_world: sup_world
                };
            }
        }
    },
    config: {
        mixins: {
//        'mage/accordion': {
//         'Vendor_Module/js/accordion-mixin': true
//         }
        },
    },
};
