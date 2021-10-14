jQuery(document).ready(function ( $ ) {

    jQuery('.ip_releaser_it_action').on('click', function (e) {
        e.preventDefault();

        var obj = this;
        var ip_id = $(this).data('id');

        if ( jQuery.isNumeric(ip_id) ) {

            jQuery.ajax({
                url: ajax_var.url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'release_ip_action',
                    nonce: ajax_var.nonce,
                    ip_id: ip_id
                },
                success: function (data) {
                    jQuery(obj).parent().parent().hide();
                    jQuery(".notice-success").show(500);
                    setTimeout(function(){
                        jQuery(".notice-success").hide(500);
                    },2000);
                },
                error: function (xhr) {
                    alert('Request failed');
                }
            });
        }

        if( jQuery(".lockout_id:visible").length < 2 ) {
            location.reload();
        }

    });
});
