AmAjax = Class.create();
AmAjax.prototype =
{
    options : null,
    nimiCartClass : 'a.top-link-cart',
    url : null,
    updateUrl : null,
    srcImageProgress : null,
    isProductView : 0,
    typeLoading : 0,
    enMinicart : 0,
    productId : 0,
    top_cart_selector: '.header-minicart',

    initialize : function(options) {
        this.url = options['send_url'];
        this.updateUrl = options['update_url'];
        this.enMinicart = options['enable_minicart'];
        this.typeLoading = options['type_loading'];
        this.options = options;
        this.srcImageProgress = options['src_image_progress'];
        this.isProductView = options['is_product_view'];
        this.currentCategory = options['current_category'];
        this.productId = options['product_id'];
        this.top_cart_selector = options['top_cart_selector'];
        this.nimiCartClass = this.top_cart_selector;
    },
    
    updateCart : function() {
        if (jQuery('.main-container .block-cart', window.parent.document).length) {
            var url = this.url.replace(this.url.substring(this.url.length-6, this.url.length), 'cart');//    replace ajax to cart
            new Ajax.Updater(jQuery('.main-container .block-cart', window.parent.document)[0], url, {
                method: 'post'
            });
        }
        if (jQuery(this.top_cart_selector, window.parent.document).length) {
            var url = this.url.replace(this.url.substring(this.url.length-6, this.url.length), 'mcart');//    replace ajax to mincart
            new Ajax.Request(url, {
                method: 'post',
                onSuccess: function(transport) {
                    if (transport.responseText) {
                        var response = transport.responseText;
                        var wrapperMinicart = jQuery(this.top_cart_selector, window.parent.document);
                        if (wrapperMinicart.hasClass('header-minicart')) {
                            wrapperMinicart.html(response);
                        } else {
                            wrapperMinicart.html(jQuery(response).html());
                        }

                        if (AmAjaxObj.enMinicart === "1") {
                            AmAjaxObj.createMinicart()
                        }
                        
                        AmAjaxObj.external();
                    }
                }.bind(this)
            });
        }
    },


    updateLinc : function(count) {
        var element = jQuery('a.top-link-cart', window.parent.document)[0];
        if (element) {
            var pos = element.innerHTML.indexOf("(");
            if (pos >= 0 && count) {
                element.innerHTML =  element.innerHTML.substring(0, pos) + count;
            } else {
                if (count)
                    element.innerHTML =  element.innerHTML + count;
            }
            new Effect.Morph(element, {
                style: 'color: #ffff00;font-weight:bold;',
                duration: 0.8,
                afterFinish: function() {
                    new Effect.Morph(element, {
                        style: 'color: #EBBC58;font-weight:normal;',
                        duration: 0.4
                    });
                }
            });
        };
    },

    updateShoppingCart : function() {
        if ($$('body.checkout-cart-index div.cart')[0]) {
            var url = this.url.replace(this.url.substring(this.url.length-6, this.url.length), 'checkout');//
            new Ajax.Request(url, {
                method: 'post',
                onSuccess: function(transport) {
                    if (transport.responseText) {
                        var response = transport.responseText;
                        var holderDiv = document.createElement('div');
                        holderDiv = $(holderDiv);
                        holderDiv.innerHTML = response;
                        $$('body.checkout-cart-index div.cart')[0].innerHTML = holderDiv.childElements()[0].innerHTML;
                    }
                }.bind(this)
            });
        }
    },

    showAnimation: function(loading, element) {
        var foundImage = 0;
        if (loading != 0 && element && element.parentNode && element.parentNode.parentNode && $$(this.nimiCartClass)[0]) {
            var i = 0;
            var el = $(element.parentNode);
            while(!child && i < 6) {
                if (el) {
                    if (el.className.indexOf('product-shop') >= 0) {
                        el = $(el.parentNode);
                        i++;
                        continue;
                    }
                    var massClass = el.getElementsByClassName('product-image');
                    if (massClass[0]) {
                        var child =$(massClass[0]);
                    } else {
                        el = $(el.parentNode);
                        i++;
                    }
                }
            }
            if (child) {
                var massClass = child.getElementsByClassName('wrap');
                if (massClass[0]) {
                    var child =$(massClass[0]);
                }
                var container = document.createElement('div');
                container = $(container);
                container.id = 'am_loading_container';
                container.style.position = 'absolute';
                container.style.zIndex = '99919';
                var contImage = child.getElementsByTagName('img');
                if (contImage[0]) {
                    container.appendChild($(contImage[0]).cloneNode(true));
                    foundImage = 1;
                }
                child.appendChild(container);
                var img = container.childElements()[0],
                    maxWidth = img.getWidth();

                img.setStyle({
                    maxWidth: maxWidth + 'px'
                });

                var posContainer = jQuery(child).offset();
                if (jQuery(this.nimiCartClass).children().length) {
                    var posLink = jQuery(this.nimiCartClass).children().first().offset();
                } else {
                    var posLink = jQuery(this.nimiCartClass).offset();
                }

                $$('body')[0].appendChild(container);
                container.style.position = 'absolute';
                if (img)
                    container.style.top = posContainer.top + 'px';
                container.style.left = posContainer.left + 'px';
                container = $(container);
                new Effect.Squish(img, {duration: 1.5});
                new Effect.Fade(container, {duration: 1.5 });
                new Effect.Move(container, {
                    x: posLink.left,
                    y: posLink.top,
                    duration: 0.9,
                    mode: 'absolute',
                    afterFinish: function() { $('am_loading_container').remove(); }
                });
            }
        }

        if (loading == 0 || !foundImage) {
            jQuery(function($) {
                var progress = document.createElement('div');
                progress = $(progress); // fix for IE
                progress.attr('id','amprogress');

                var container = document.createElement('div');
                container = $(container); // fix for IE
                container.attr('id','amimg_container');
                container.appendTo(progress);

                var img = document.createElement('img');
                img = $(img); // fix for IE
                img.attr('src', this.srcImageProgress);
                img.appendTo(container);

                container.width('126px');
                var width = container.width();
                width = "-" + width/2 + "px" ;
                container.css("margin-left", width);
                progress.hide().appendTo(jQuery('body', window.parent.document)).fadeIn();
            }.bind(this));
        }
    },

    hideAnimation: function() {
        if (jQuery('#amprogress', window.parent.document).length) {
            jQuery(function($) {
                jQuery('#amprogress', window.parent.document).fadeOut(function() {
                    $(this).remove();
                });
            });
        }
    },

    //run every second while time !=0
    oneSec: function() {
        var elem= jQuery('#confirmButtons .timer', window.parent.document),
            value = elem.text(),
            sec = parseInt(value.replace(/\D+/g,""));
        if (sec) {
            value =  value.replace(sec, sec-1);
            elem.text(value);
            if (sec <= 1) {
                clearInterval(document.timer);
                elem.click();
            }
        } else {
            clearInterval(document.timer);
        }
    },

    //add parametr from form on product view page
    addProductParam: function(postData, element) {
        var form = null;
        if (element) {
           var form = $(element).up('form', 0);
        }
        if ($$('#messageBox #product_addtocart_form')[0]) {
            form = $$('#messageBox #product_addtocart_form')[0];
        }
        if (form) {
            var len=form.elements.length-1,
                tmpPostData = postData,
                validator = new Validation(form);
            if (validator.validate()) {
                var serialize = jQuery(form).serialize();
                if (serialize == '' && len > 0) {
                    jQuery(form).find('input').each(function(item) {
                        postData += "&" + item.name + "=";
                    });
                } else {
                    postData += "&" + serialize;
                }
            } else {
                return '';
            }
       } else {
            form = $('product_addtocart_form-' + postData.replace(/[^\d]/gi, ''));
            if (form && $('amconf-amcart-' + postData.replace(/[^\d]/gi, ''))) {
                if (form.hasClassName('isValid')) {
                    postData += "&" + jQuery(form).serialize()
                    form.remove();
                } else {
                    form.remove();
                }
            }
        }
        postData += '&IsProductView=' + this.isProductView + '&current_category=' + this.currentCategory;
        return postData;
    },

    sendAjax : function(idProduct, param, oldEvent, element) {
        var form = jQuery(element).closest('form');
	    if (form.has('input[type="file"]').length && form.find('input[type="file"]').val() !== '') {
		    eval(oldEvent);
		    return false;
	    }
        if (idProduct) {
            var postData = 'product=' + idProduct;
            if (jQuery('#amconf-block-' + idProduct).length) {
                var validator = new Validation($('amconf-block-' + idProduct));
                if (validator.validate()) {
                    postData += "&" + jQuery('#amconf-block-' + idProduct + ' :input').serialize();
                } else {
                    return '';
                }

            }
            postData = this.addProductParam( postData , element);
            if ('' == postData)
                return true;
            if (param) {
                jQuery.confirm.hide();
            }
            if (typeof AEC == 'object') {
                AEC.ajax(element, dataLayer);
            }
            new Ajax.Request(this.url, {
                method: 'post',
                postBody : postData,
                onCreate: function()
                {
                    this.showAnimation(this.typeLoading, element);
                }.bind(this),
                onComplete: function()
                {
                    this.hideAnimation();
                }.bind(this),
                onSuccess: function(transport) {
                    if (transport.responseText.isJSON()) {
                        var response = transport.responseText.evalJSON();
                        if (response.error) {
                            this.hideAnimation();
                            alert(response.error);
                        }
                        else{
                            if (response.redirect) {
                                //if IE7
                                if (document.all && !document.querySelector) {
                                    oldEvent = oldEvent.substring(21, oldEvent.length-2)
                                    eval(oldEvent);
                                }
                                else{
                                    eval(oldEvent);
                                }
                                return true;
                            }
                            this.hideAnimation();
                            try {
                                jQuery.confirm({
                                    'title'      : response.title,
                                    'message'    : response.message,
                                    'related'    : response.related,
                                    'checkout_button' : response.checkout_button,
                                    'buttons'    : {
                                        '1'    : {
                                            'name'  :  response.b1_name,
                                            'class' : 'am-btn-left',
                                            'timer' : response.timer,
                                            'action': function() {
                                                if (response.b1_action.indexOf('document.location') > -1 && window.parent.location != window.location) {
                                                    response.b1_action = response.b1_action.replace('document.location', 'window.parent.location');
                                                }
                                                eval(response.b1_action);
                                            }
                                        },
                                        '2'    : {
                                            'name'  :  response.b2_name,
                                            'class' : 'am-btn-right',
                                            'action': function() {
                                                if (response.b2_action.indexOf('document.location') > -1 && window.parent.location != window.location) {
                                                    response.b2_action = response.b2_action.replace('document.location', 'window.parent.location');
                                                }
                                                eval(response.b2_action);
                                            }
                                        }
                                    }
                                });

                                AmAjaxShoppCartLoad('.amcart-related-block button.add-tocart');

                                var maxHeight = 0.7 * jQuery(window).height();
                                var height = jQuery('#messageBox').height();
                                if (height > maxHeight) {
                                    $('messageBox').addClassName('scroll');
                                }
                                if ('undefined' != typeof(optionsPrice)) {
                                    window.currentOptionsPrice = optionsPrice;
                                }
                                if ('undefined' != typeof(opConfig)) {
                                    window.currentopConfig = opConfig;
                                }
                                if ('undefined' != typeof(spConfig)) {
                                    window.currentspConfig = spConfig;
                                }

                                window.eval(response.script);
                            } catch(e) {
                                console.warn(e);
                            }
                            if (response.is_add_to_cart === '1') {
                                this.updateCart();
                                this.updateShoppingCart();
                                this.updateMinicart();
                                this.updateLinc(response.count);
                            }
                        }
                    }
                }.bind(this),
                onFailure: function()
                {
                    this.hideAnimation();
                    eval(oldEvent);
                }.bind(this)
            });
        }
    },

    sendLinkCompareAjax: function(idProduct) {
        var postData = 'product_id=' + idProduct;
        var url = this.url.replace(this.url.substring(this.url.length-6, this.url.length), 'linkcompare');

        new Ajax.Request(url, {
            method: 'post',
            postBody : postData,
            onCreate: function()
            {
                this.showAnimation();
            }.bind(this),
            onComplete: function()
            {
                this.hideAnimation();
            }.bind(this),
            onSuccess: function(transport) {
                if (transport.responseText.isJSON()) {
                    var response = transport.responseText.evalJSON();
                    if (response.error) {
                        this.hideAnimation();
                        alert(response.error);
                    }
                    else{
                        if (response.redirect) {
                            eval(response.redirect);
                        }
                        this.hideAnimation();
                        try {
                            jQuery.confirm({
                                'title'      : response.title,
                                'message'    : response.message,
                                'buttons'    : {
                                    '1'    : {
                                        'name'  :  response.b1_name,
                                        'class' : 'am-btn-left',
                                        'timer' : response.timer,
                                        'action': function() {
                                            eval(response.b1_action);
                                        }
                                    },
                                    '2'    : {
                                        'name'  :  response.b2_name,
                                        'class'    : 'am-btn-right',
                                        'action': function() {
                                            if (response.b2_action.indexOf('document.location') > -1 && window.parent.location != window.location) {
                                                response.b2_action = response.b2_action.replace('document.location', 'window.parent.location');
                                            }
                                            eval(response.b2_action);
                                        }
                                    }
                                }
                            });
                            this.updateCompare();
                        } catch(e) {
                            console.warn(e);
                        }
                    }
                }
            }.bind(this),
            onFailure: function()
            {
                this.hideAnimation();
            }.bind(this)
        });
    },

    updateCompare : function() {
        if (jQuery('.main-container .block-compare', window.parent.document).length) {
            var url = this.url.replace(this.url.substring(this.url.length - 6, this.url.length), 'compare');
            new Ajax.Request(url, {
                method: 'post',
                onSuccess: function(transport) {
                    if (transport.responseText) {
                        var response = transport.responseText;
                        jQuery('.main-container .block-compare', window.parent.document)[0].outerHTML = response;

                    }
                }.bind(this)
            });
        }
    },

    updateWishlist : function() {
        if (jQuery('.main-container .block-wishlist', window.parent.document).length) {
            var url = this.url.replace(this.url.substring(this.url.length - 6, this.url.length), 'wishlist');
            new Ajax.Request(url, {
                method: 'post',
                onSuccess: function(transport) {
                    if (transport.responseText) {
                        var response = transport.responseText;
                        jQuery('.main-container .block-wishlist', window.parent.document)[0].outerHTML = response;

                    }
                }.bind(this)
            });
        }
    },


    sendWishlistAjax: function(idProduct) {
        var postData = 'product_id=' + idProduct;
        var url = this.url.replace(this.url.substring(this.url.length-11, this.url.length), 'wishlist/add');

        new Ajax.Request(url, {
            method: 'post',
            postBody : postData,
            onCreate: function()
            {
                this.showAnimation();
            }.bind(this),
            onComplete: function()
            {
                this.hideAnimation();
            }.bind(this),
            onSuccess: function(transport) {
                if (transport.responseText.isJSON()) {
                    var response = transport.responseText.evalJSON();
                    if (response.error) {
                        this.hideAnimation();
                        alert(response.error);
                    }
                    else{
                        if (response.redirect) {
                            eval(response.redirect);
                        }
                        this.hideAnimation();
                        try {
                            jQuery.confirm({
                                'title'      : response.title,
                                'message'    : response.message,
                                'buttons'    : {
                                    '1'    : {
                                        'name'  :  response.b1_name,
                                        'class' : 'am-btn-left',
                                        'timer' : response.timer,
                                        'action': function() {
                                            eval(response.b1_action);
                                        }
                                    },
                                    '2'    : {
                                        'name'  :  response.b2_name,
                                        'class'    : 'am-btn-right',
                                        'action': function() {
                                            if (response.b2_action.indexOf('document.location') > -1 && window.parent.location != window.location) {
                                                response.b2_action = response.b2_action.replace('document.location', 'window.parent.location');
                                            }
                                            eval(response.b2_action);
                                        }
                                    }
                                }
                            });
                            this.updateWishlist();
                        } catch(e) {
                            console.warn(e);
                        }
                    }
                }
            }.bind(this),
            onFailure: function()
            {
                this.hideAnimation();
            }.bind(this)
        });
    },

    //minicart
    createMinicart: function() {
        var nmCart = $$(this.nimiCartClass)[0];
        if ($$('.header-minicart').length) {
            return false;//disable for rwd
        }

        if (nmCart) {
            var container = document.createElement('div');
            container = $(container);
            container.id = 'am_minicart_container';
            container.style.display = 'none';
            if (nmCart.parentNode) {
                nmCart.parentNode.appendChild(container);
                this.updateMinicart();

                Event.observe(container, 'mouseover',function() {AmAjaxObj.showMinicart()} );
                Event.observe(container, 'mouseout',function() {AmAjaxObj.hideMinicart()} );
                Event.observe(nmCart,   'mouseover',function() {AmAjaxObj.showMinicart()} );
                Event.observe(nmCart,   'mouseout',function() {AmAjaxObj.hideMinicart()} );
            }
        }
    },

    updateMinicart: function() {
        var url = AmAjaxObj.url.replace(AmAjaxObj.url.substring(AmAjaxObj.url.length-6, AmAjaxObj.url.length), 'minicart');
        var element = $('am_minicart_container');
        if (!element) return;
        new Ajax.Updater(element, url, {
            method: 'post'
        });
    },

    showMinicart: function() {
        jQuery("#am_minicart_container").stop(true, true).delay(300).slideDown(500, "easeOutBounce");
    },



    hideMinicart: function() {
        jQuery("#am_minicart_container").stop(true, true).delay(300).fadeOut(800, "easeInCubic");
    },


    getProductId: function(parent) {
        var selector =  'div.price-box [id^=amcart-], ' +
            'div.price-box [id^=product-price-], ' +
            'div.special-price [id^=product-price-], ' +
            'div.price-box [id^=price-excluding-tax-], ' +
            'div.price-box [id^=price-including-tax-], ' +
            'div.price-box [id^=product-minimal-price-], ' +
            'div.price-box [id^=amasty-product-id-], ' +
            'div.price-box [id^=old-price-]';
        var element = parent.select(selector);
        if (!element[0]) return false;

        var productId = element[0].id.match(/\d+/).first();
        if (parseInt(productId) > 0 && productId < 10000000) {
            return parseInt(productId);
        }

        return false;
    },

    searchInPriceBox: function(parent, oldEvent, element) {
        var productId = this.getProductId(parent);
        if (productId) {
            this.sendAjax(productId, '', oldEvent, element);
        }

        return productId;
    },

    external: function() {
        /*
         * default rwd theme
         * */
        if (jQuery('html').width() < 800 && 'undefined' != typeof(SmartHeader)) {
            SmartHeader.init();
        }
         if (jQuery(".skip-cart").length) {
            var skipContents = $j('.skip-content.block-cart');
            var skipLinks = $j('.skip-link.skip-cart');

            skipLinks.on('click', function (e) {
                e.preventDefault();

                var self = $j(this);
                var target = self.attr('data-target-element') ? self.attr('data-target-element') : self.attr('href');

                // Get target element
                var elem = $j(target);

                // Check if stub is open
                var isSkipContentOpen = elem.hasClass('skip-active') ? 1 : 0;

                // Hide all stubs
                skipLinks.removeClass('skip-active');
                skipContents.removeClass('skip-active');

                // Toggle stubs
                if (isSkipContentOpen) {
                    self.removeClass('skip-active');
                } else {
                    self.addClass('skip-active');
                    elem.addClass('skip-active');
                }
            });

            $j('#header-cart').on('click', '.skip-link-close', function(e) {
                var parent = $j(this).parents('.skip-content');
                var link = parent.siblings('.skip-link');

                parent.removeClass('skip-active');
                link.removeClass('skip-active');

                e.preventDefault();
            });
            
            if (typeof Minicart != 'undefined') {
                var minicartOptions = {
                    formKey: AmAjaxObj.options.form_key
                }
                var Mini = new Minicart(minicartOptions);
                Mini.init();
            }
        }
        /*
         * fortis theme
         * */
        if (jQuery('#mini-cart').length && jQuery('.dropdown-toggle').length) {
            var ddOpenTimeout;
            var dMenuPosTimeout;
            var DD_DELAY_IN = 200;
            var DD_DELAY_OUT = 0;
            var DD_ANIMATION_IN = 0;
            var DD_ANIMATION_OUT = 0;
            jQuery(function($) {

                $(".clickable-dropdown > .dropdown-toggle").click(function() {
                    $(this).parent().addClass('open');
                    $(this).parent().trigger('mouseenter');
                });
                $(".dropdown").hover(function() {


                    var ddToggle = $(this).children('.dropdown-toggle');
                    var ddMenu = $(this).children('.dropdown-menu');
                    var ddWrapper = ddMenu.parent();
                    ddMenu.css("left", "");
                    ddMenu.css("right", "");

                    if ($(this).hasClass('clickable-dropdown'))
                    {
                        if ($(this).hasClass('open'))
                        {
                            $(this).children('.dropdown-menu').stop(true, true).delay(DD_DELAY_IN).fadeIn(DD_ANIMATION_IN, "easeOutCubic");
                        }
                    }
                    else
                    {
                        clearTimeout(ddOpenTimeout);
                        ddOpenTimeout = setTimeout(function() {

                            ddWrapper.addClass('open');

                        }, DD_DELAY_IN);

                        //$(this).addClass('open');
                        $(this).children('.dropdown-menu').stop(true, true).delay(DD_DELAY_IN).fadeIn(DD_ANIMATION_IN, "easeOutCubic");
                    }

                    clearTimeout(dMenuPosTimeout);
                    dMenuPosTimeout = setTimeout(function() {

                        if (ddMenu.offset().left < 0)
                        {
                            var space = ddWrapper.offset().left; 					ddMenu.css("left", (-1)*space);
                            ddMenu.css("right", "auto");
                        }

                    }, DD_DELAY_IN);

                }, function() {
                    var ddMenu = $(this).children('.dropdown-menu');
                    clearTimeout(ddOpenTimeout); 			ddMenu.stop(true, true).delay(DD_DELAY_OUT).fadeOut(DD_ANIMATION_OUT, "easeInCubic");
                    if (ddMenu.is(":hidden"))
                    {
                        ddMenu.hide();
                    }
                    $(this).removeClass('open');
                });
            })
        }
        
        if (typeof truncateOptions != 'undefined') {
            truncateOptions();
        }

        if (jQuery('.cdz-dropdown').length > 0 && typeof jQuery('.cdz-dropdown').dropdownMenu == 'function') {
            jQuery('.cdz-dropdown').dropdownMenu({trigger:'.cdz-trigger',dropdown:'.cdz-dropdown-content'});
        }

    }
}



//Class for increasing product count

AmQty = Class.create();
AmQty.prototype =
{
    initialize : function(min) {
        this.min = min;
        if (!this.min) this.min = 1;
        this.input = $('am-input');
        this.formKey = $('am-form-key');
    },

    increment: function() {
        this.input.value++;
        this.paint();
    },

    decrement: function() {
        if (this.input.value > this.min) {
            this.input.value--;
            this.paint();
        }

    },

    update: function(quoteItem) {
        if (this.input.value >= this.min) {
            postData = "id=" + quoteItem + "&qty=" + this.input.value + "&form_key=" + this.formKey.value;
            new Ajax.Request(AmAjaxObj.updateUrl, {
                method: 'post',
                postBody : postData,
                onCreate: function()
                {
                    AmAjaxObj.showAnimation();
                }.bind(this),

                onComplete: function()
                {
                    AmAjaxObj.hideAnimation();
                }.bind(this),

                onSuccess: function(transport) {
                    var url = AmAjaxObj.url.replace(AmAjaxObj.url.substring(AmAjaxObj.url.length - 6, AmAjaxObj.url.length), 'data'), //replace ajax to count,
                        errorBlock = jQuery(this.input).closest('.amcart-dialog-container').find('.amcart-dialog-error');
                    if (transport.responseJSON) {
                        if (transport.responseJSON.notice) {
                            errorBlock.html(transport.responseJSON.notice);
                        } else if (transport.responseJSON.error) {
                            errorBlock.html(transport.responseJSON.error);
                        }
                    }
                    new Ajax.Request(url, {
                        method: 'post',
                        onSuccess: function(transport) {
                            if (transport.responseText.isJSON()) {
                                var response = transport.responseText.evalJSON();
                                if ($('amcart-count') && response.count) $('amcart-count').innerHTML = response.count;
                                var price = $$('#messageBox span.am_price')[0];
                                if (price && response.price) price.innerHTML = response.price;
                            }
                        }.bind(this),
                        onComplete: function() {
                            AmAjaxObj.updateLinc(" (" + $$('#amcart-count a')[0].text + ")");
                        }
                    });
                    AmAjaxObj.updateCart();
                    AmAjaxObj.hideAnimation();
                    new Effect.Highlight(this.input, { startcolor: '#ffff99', endcolor: '#a4e9ac', restorecolor : '#a4e9ac'});
                    $('am-qty-button-update').setStyle({'visibility': 'hidden'});
                    this.input.removeClassName('focus');

                }.bind(this),

                onFailure: function()
                {
                    AmAjaxObj.hideAnimation();
                }.bind(this)
            });
        }
        else {
            this.input.value = this.min;
            new Effect.Highlight('am-input', { endcolor: '#E44545', restorecolor : '#E44545'});
        }

    },

    paint: function() {
        new Effect.Highlight('am-input', { endcolor: '#ffff99', restorecolor : '#ffff99'});
        $('am-input').addClassName('focus');
        $('am-qty-button-update').setStyle({'visibility' : 'visible'});
        this.clearTimer();
    },

    clearTimer: function() {
        jQuery(function($) {
            var elem= $('#confirmButtons .timer'),
                value = elem.text(),
                sec = parseInt(value.replace(/\D+/g,""));
            if (sec) {
                value =  value.replace('(' + sec + ')', '');
                elem.text(value);
                clearInterval(document.timer);
            }
        });
    }
}


function searchIdAndSendAjax(event) {
    var element = Event.element(event);
    event.preventDefault();
    var addToLinc = 'add-to-links';

    if ($('confirmBox')) {
        jQuery(function($) {
            $.confirm.hide();
        })
    }
    //in Chrome element = span
    if ("SPAN" == element.tagName) {
        if (element.up('button')) {
            element = element.up('button');
        } else if(element.up('.addtocart')) {
            element = element.up('.addtocart');
        }
    }

    //if colors swatches pro
    if (amconf = element.getAttribute('amconf')) {
        eval(amconf);
    }

    var oldEvent = element.getAttribute('oldEvent');
    var idProduct = false;

    var i = 0;	el = element;
    while(!idProduct && i < 5) {
        el = $(el.parentNode);
        i++;
        if (el) {
            var idProduct = AmAjaxObj.searchInPriceBox(el, oldEvent, element, idProduct);
            if (idProduct) {
                return false;
            }
        }
    }

    //for bundle

    var el = $(element.parentNode);
    var child  = el.getElementsByClassName(addToLinc)[0];
    if (child) {
        var childNext = child.childElements()[0];
        if (childNext) {
            var childNext = childNext.childElements()[0];
        }
        if (childNext) {
            var idProduct = childNext.href.match(/product(.*?)form_key/)[0].replace(/[^\d]/gi, '');
        }
        if (parseInt(idProduct) > 0) {
            var tmp = parseInt(idProduct);
            AmAjaxObj.sendAjax(tmp, '', oldEvent, element);
            return true;
       } else {
            idProduct = false;
        }
    }
    if (idProduct) return false;

    //other
    if ($$("input[name='product']")[0] && $$("input[name='product']")[0].value) {
        idProduct = $$("input[name='product']")[0].value;
        if (parseInt(idProduct) > 0) {
            var tmp = parseInt(idProduct);
            AmAjaxObj.sendAjax(tmp, '', oldEvent, element);
            return true;
        }
    }
    if (idProduct) return false;

    if (idProduct == '' && oldEvent) {
        var productString = '/product/';
        var posStart = oldEvent.indexOf(productString);
        if (posStart) {
            var posFinish = oldEvent.indexOf('/', posStart + productString.length);
            if (posFinish) {
                var idProduct = oldEvent.substring(posStart + productString.length, posFinish);
                if (parseInt(idProduct) > 0) {
                    var tmp = parseInt(idProduct);
                    AmAjaxObj.sendAjax(tmp, '', oldEvent, element);
                    return false;
                }
                else {
                    idProduct = false;
                }
            }
        }
    }
    //default acrion
    if (idProduct) return false;
    //if IE7
    if (document.all && !document.querySelector) {
        oldEvent = oldEvent.substring(21, oldEvent.length-2)
    }
    eval(oldEvent);

}

function searchIdAndlinkCompare(event) {
    var element = Event.element(event);
    event.preventDefault();
    var productId = false;

    //in Chrome element = span
    if ( "SPAN" == element.tagName ) {
        element = element.up('a');
    }

    if (element.href && element.href != "") {
        var search = element.href.match(/product(.*?)uenc/);
        if (search && 'undefined' != typeof(search[0])) {
            productId = search[0].replace(/[^\d]/gi, '');
        }
    }

    if (!productId && element.getAttribute("onclick") && element.getAttribute("onclick") != "") {
        var search = element.getAttribute("onclick").match(/product(.*?)uenc/);
        if (search && 'undefined' != typeof(search[0])) {
            productId = search[0].replace(/[^\d]/gi, '');
        }

    }

    if (!productId) {
        productId = AmAjaxObj.getProductId(element.up('.item, #product_addtocart_form'));
    }
    if (productId > 0) {
        AmAjaxObj.sendLinkCompareAjax(productId);
    }
}

function searchIdWishlist(event) {
    var element = Event.element(event);
    event.preventDefault();
    var productId = false;

    //in Chrome element = span
    if ( "SPAN" == element.tagName ) {
        element = element.up('a');
    }

    if (element.href && element.href != "") {
        var search = element.href.match(/product\/(.*?)\//);
        if (search && 'undefined' != typeof(search[0])) {
            productId = search[0].replace(/[^\d]/gi, '');
        }
    }

    if (!productId && element.getAttribute("onclick") && element.getAttribute("onclick") != "") {
        var search = element.getAttribute("onclick").match(/product\/(.*?)\//);
        if (search && 'undefined' != typeof(search[0])) {
            productId = search[0].replace(/[^\d]/gi, '');
        }

    }

    if (!productId) {
        productId = AmAjaxObj.getProductId(element.up('.item, #product_addtocart_form'));
    }
    if (productId > 0) {
        AmAjaxObj.sendWishlistAjax(productId);
    }
}


function AmAjaxShoppCartLoad(buttonClass) {
    if ('undefined' != typeof(AmAjaxObj) && !$$('body').first().hasClassName('checkout-cart-configure')) {
        if (!buttonClass) buttonClass = AmAjaxObj.options['buttonClass'];//compatibility with old version
        jQuery(buttonClass, window.parent.document).each(function (index, element) {
            if (!element.hasClassName('amcart-ignore')) {
                if (element.getAttribute('onclick')) {
                    var attr = document.createAttribute('oldEvent');
                    attr.value = element.getAttribute('onclick').toString();
                    element.attributes.setNamedItem(attr);
                }
                element.onclick = '';
                element.stopObserving('click');
                element.removeAttribute('onclick');
                element.removeAttribute('disabled');
                Event.observe(element, 'click', searchIdAndSendAjax);
            }
        }.bind(this));

        if (AmAjaxObj.options['linkcompare']) {
            $$('a[href*="catalog/product_compare/add"]').each(function (link) {
                link.stopObserving('click');
                link.removeAttribute('onclick');
                Event.observe(link, 'click', searchIdAndlinkCompare);
            }.bind(this));
        }
        if (AmAjaxObj.options['wishlist']) {
            $$('a[href*="wishlist/index/add"]').each(function (link) {
                link.stopObserving('click');
                link.removeAttribute('onclick');
                Event.observe(link, 'click', searchIdWishlist);
            }.bind(this));
        }
    }
}

document.observe("dom:loaded", function() {
    if (AmAjaxObj.enMinicart === "1") {
        AmAjaxObj.createMinicart()
    }
});
