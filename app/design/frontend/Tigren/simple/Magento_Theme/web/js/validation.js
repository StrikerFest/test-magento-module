define([
    'jquery'
], function ($) {
    $.widget('mage.validation', {
        options: {
            maxlen: 100 // Override option defaults
        },
        _create: function () {

            this._case = $(this.element);
            let maxlen = this.options.maxlen;
            let messagebox = this.options.messagebox;

            $(this._case).keyup(function () {

                let len = this.value.length;

                if (len >= maxlen) {
                    let char = len - maxlen;
                    $('#' + messagebox).text('Limit reached! ' + char + ' characters too much');
                } else {
                    let char = maxlen - len;
                    $('#' + messagebox).text(char + ' characters left');
                }
            });
        }
    });

    return $.mage.validation;
});

