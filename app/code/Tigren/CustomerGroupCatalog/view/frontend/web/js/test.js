/*
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright  Copyright (c)  2023.  Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 */

define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/confirm',
    'jquery/ui'
], function ($, alert, confirmation) {
    'use strict';
    $.widget('mage.test', {
        _create: function () {
            $('#' + this.options.id).click(() => {
                confirmation({
                    title: $.mage.__('Are you sure about that?'),
                    actions: {
                        confirm: function () {
                            alert({
                                content: $.mage.__('Success')
                            });
                        },
                        cancel: function () {

                        }
                    },
                    buttons: [
                        {
                            text: $.mage.__('Cancel'),
                            click: function (event) {
                                this.closeModal(event);
                            }
                        }, {
                            text: $.mage.__('OK'),
                            click: function (event) {
                                this.closeModal(event, true);
                            }
                        }
                    ]
                });
            });
        }

    });
    return $.mage.test;
});


