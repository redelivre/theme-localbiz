jQuery(document)
        .ready(
                function () {
                    jQuery("#captcha_code").removeAttr("style");
                    jQuery('input[name=senha], input[name=senha2]').on(
                        'change',
                        function () {
                            var password = jQuery('input[name=senha]'), repassword = jQuery('input[name=senha2]'), both = password
                                    .add(repassword)
                                    .removeClass(
                                            'has-success has-error');

                            if (password.val().length > 5) {
                                password.addClass('has-success');
                                password[0].setCustomValidity('');
                            } else {
                                password.addClass('has-error');
                                password[0].setCustomValidity('As senhas precisam ter no mínimo 6 dígitos');
                            }
                            
                            if (repassword.val().length > 5) {
                                repassword.addClass('has-success');
                                repassword[0].setCustomValidity('');
                            } else {
                                repassword.addClass('has-error');
                                repassword[0].setCustomValidity('As senhas precisam ter no mínimo 6 dígitos');
                            }
                            
                            if (password.val() != repassword.val()) {
                                both.addClass('has-error');
                                both.each(function() {
                                    this.setCustomValidity('As senhas precisam coincidir');
                                });
                            }
                    });
                    if (jQuery("#cep").length) {
                        jQuery('#cep').autocompleteAddress();
                    }
                    if (jQuery("#cnpj").length) {
                        jQuery("#cnpj").mask('00.000.000/0000-00');
                    }

                    if (jQuery("#cat").length) {
                        jQuery('#cat').change(function () {
                            catchangeaction();
                        });
                        if (jQuery('#cat').value != -1) {
                            catchangeaction();
                        }
                    }
                    if (jQuery("#tem_cnpj").length) {
                        jQuery('#tem_cnpj').click(function () {
                            jQuery("#cnpj").prop('disabled', this.checked);
                        });
                    }
                    if (jQuery("#produtos").length) {
                        jQuery('#produtos').tagit({
                            allowSpaces : true
                        });
                        jQuery("input#tel").mask("(00) 00000-00009");
                    }
                    if (jQuery("#produtos").length) {
                        jQuery("input#site").change(function() {
                            if (!/^https*:\/\//.test(this.value)) {
                                this.value = "http://" + this.value;
                            }
                        });
                    }

                    function ct_media_upload(button_class) {
                        var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
                        jQuery('body')
                                .on(
                                        'click',
                                        button_class,
                                        function (e) {
                                            var button_id = '#'
                                                    + jQuery(this).attr('id');
                                            var send_attachment_bkp = wp.media.editor.send.attachment;
                                            var button = jQuery(button_id);
                                            _custom_media = true;
                                            wp.media.editor.send.attachment = function (
                                                    props, attachment) {
                                                if (_custom_media) {
                                                    jQuery(
                                                            '#localbiz-perfil-image-id')
                                                            .val(attachment.id);
                                                    jQuery(
                                                            '#localbiz-perfil-image-wrapper')
                                                            .html(
                                                                    '<div class="img-Oval" ></div>');
                                                    jQuery(
                                                            '#localbiz-perfil-image-wrapper .img-Oval')
                                                            .css(
                                                                    'background-image',
                                                                    'url('
                                                                            + attachment.url
                                                                            + ')');
                                                } else {
                                                    return _orig_send_attachment
                                                            .apply(
                                                                    button_id,
                                                                    [ props,
                                                                            attachment ]);
                                                }
                                            }
                                            wp.media.editor.open(button);
                                            return false;
                                        });
                    }
                    if (jQuery("#localbiz-perfil-image-id").length) {
                        ct_media_upload('.ct_tax_media_button.button');
                        jQuery('body')
                                .on(
                                        'click',
                                        '.ct_tax_media_remove',
                                        function () {
                                            jQuery('#localbiz-perfil-image-id')
                                                    .val('');
                                            jQuery(
                                                    '#localbiz-perfil-image-wrapper')
                                                    .html(
                                                            '<div class="img-Oval" style="background-image: url(\''
                                                                    + localbiz.stylesheet_directory
                                                                    + '/img/invalid-name.svg\');background-size: auto;"></div>');
                                        });
                    }
                });
function catchangeaction() {
    var parentCat = jQuery('#cat').val();
    // call admin ajax
    jQuery.ajax({
        url : "/wp-admin/admin-ajax.php",
        type : 'POST',
        data : 'action=locabiz_category_selected_action&parent_cat_ID='
                + parentCat,
        success : function (results) {
            jQuery("#subcat").html(results);
            jQuery("#subcat").removeAttr("disabled");
        }
    });
}
function submitpasso2() {
    if (jQuery("#cnpj").length) {
        jQuery('#cnpj').val(jQuery('#cnpj').cleanVal());
    }
    // jQuery('#localbiz_registerform2').submit();
    jQuery('#submit').trigger('click');
}