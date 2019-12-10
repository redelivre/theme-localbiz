jQuery(document).ready(function(){
    jQuery("#captcha_code").removeAttr("style");
    jQuery('input[name=senha], input[name=senha2]').on('change', function () {
        var password   = jQuery('input[name=senha]'),
            repassword = jQuery('input[name=senha2]'),
            both       = password.add(repassword).removeClass('has-success has-error');

        password.addClass(
            password.val().length > 0 ? 'has-success' : 'has-error' 
        );
        repassword.addClass(
            password.val().length > 0 ? 'has-success' : 'has-error'
        );

        if (password.val() != repassword.val()) {
            both.addClass('has-error');
        }
    });
    if ( jQuery( "#cep" ).length ) {
        jQuery('#cep').autocompleteAddress();
    }
    if ( jQuery( "#cnpj" ).length ) {
        jQuery("#cnpj").mask('00.000.000/0000-00');
    }
    
    if ( jQuery( "#cat" ).length ) {
        jQuery('#cat').change(function(){
            catchangeaction();
        });
        if( jQuery('#cat').value != -1 ) {
            catchangeaction();
        }
    }
    jQuery('#tem_cnpj').click(function() {
        jQuery("#cnpj").prop('disabled',  this.checked);
    });
});
function catchangeaction() {
    var parentCat=jQuery( '#cat').val();
    // call admin ajax
    jQuery.ajax({
        url:"/wp-admin/admin-ajax.php",
        type:'POST',
        data:'action=locabiz_category_selected_action&parent_cat_ID=' + parentCat,
        success:function(results) {
            jQuery("#subcat").html(results);
            jQuery("#subcat").removeAttr("disabled");
        }
    });
}
function submitpasso2() {
    if ( jQuery( "#cnpj" ).length ) {
        jQuery('#cnpj').val(jQuery('#cnpj').cleanVal());
    }
    //jQuery('#localbiz_registerform2').submit();
    jQuery('#submit').trigger('click');
}