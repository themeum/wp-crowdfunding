/*========================================================================
 * Neo Crowdfunding Wallet
 *======================================================================== */
jQuery(document).ready(function($){
    //Add More Campaign Update Field
    $(document).on('click', 'button.wpneo-message', function(){
        $(this).next('.wpneo-message-content').show();
        $('#wpneo-fade').show();

        document.addEventListener('keydown', function(event) {
            if (event.keyCode == 27){
                $('.wpneo-message-content').hide();
                $('#wpneo-fade').hide();
            }
        });

    });

    $(document).on('click', '.wpneo-message-close', function(){
        $(this).parents('.wpneo-message-content').hide();
        $('#wpneo-fade').hide();
    });

    //wpneo-request-paid
    $(document).on('click', '.wpneo-request-paid', function(){
        var that = $(this);
        var post_id = that.data('post-id');
        $.ajax({
            type : 'POST',
            url : ajaxurl,
            data : { action : 'wpneo_crowdfunding_request_paid', 'postid' : post_id },
            success : function(data){
                console.log('Data Send!');
                that.addClass('label-success wpneo-request-pending').removeClass('label-warning wpneo-request-paid').text('Paid');;
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Data Not Send!');
            }
        });
    });

    //wpneo-request-pending
    $(document).on('click', '.wpneo-request-pending', function(){
        var that = $(this);
        var post_id = that.data('post-id');
        $.ajax({
            type : 'POST',
            url : ajaxurl,
            data : { action : 'wpneo_crowdfunding_request_pending' , 'postid' : post_id },
            success : function(data){
                console.log('Data Send2!');
                that.addClass('label-warning wpneo-request-paid').removeClass('label-success wpneo-request-pending').text('Pending');
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Data Not Send!');
            }
        });
    });

    $(document).on('click', '[name="wpneo_wallet_withdraw_type"]', function (e) {
        var radio_value = $(this).val();
        if (radio_value === 'after_certain_period'){
            $('#wpneo_wallet_withdraw_period_wrap').slideDown();
        }else {
            $('#wpneo_wallet_withdraw_period_wrap').slideUp();
        }
    });

    $(document).on('click', '.wpneo_withdraw_button', function(e){
        e.preventDefault();
        $that = $(this);
        var campaign_id = $('input[name="wpneo_wallet_withdraw_amount"]').data('campaign-id');
        var withdraw_amount = $('input[name="wpneo_wallet_withdraw_amount"]').val();
        var withdraw_message = $('[name="wpneo_wallet_withdraw_message"]').val();


        $.ajax({
            type : 'POST',
            url : wpcf_ajax_object.ajax_url,
            data : { action : 'wpneo_crowdfunding_wallet_withdraw' , 'campaign_id' : campaign_id, withdraw_amount : withdraw_amount, withdraw_message : withdraw_message },
            success : function(data){
                var response = JSON.parse(data);
                if (response.success == 1){
                    $('.wpneo-message-content').hide();
                    $('#wpneo-fade').hide();
                    window.location.reload(true);
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Data Not Send!');
            }
        });

    });





});
