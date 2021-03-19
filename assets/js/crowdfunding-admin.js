/*========================================================================
 * WP Crowdfunding
 *======================================================================== */
jQuery(function($){
	
	function countRemovesBtn(btn) {
		var rewards_count = $(btn).length;
		if (rewards_count > 1){
			$(btn).show();
		}else {
			$(btn).hide();
			if (btn == '.removeCampaignRewards') {
				$('.wpcf-reward-group').show();
			}
			if (btn == '[wpcf-action-remove-update]') {
				$('#wpcf-campaign-update-field').show();
			}
		}
		$(btn).first().hide();
	}

	//Add More Campaign Update Field
	$('#addreward').on('click', function (e) {
		e.preventDefault();
		$('#rewards_addon_fields').append( $('.wpcf-reward-group').html() );
		countRemovesBtn('.removeCampaignRewards');
	});

	$('body').on('click', '.removeCampaignRewards', function (e) {
		e.preventDefault();
		$(this).closest('.wpcf-reward-group').remove();
		countRemovesBtn('.removeCampaignRewards');
	});
	countRemovesBtn('.removeCampaignRewards');

	//Add More Campaign Update Field
	$('[wpcf-action-add-update]').on('click', function (e) {
		e.preventDefault();
		var update = $('#wpcf-campaign-update-field').html();
		$('#wpcf-campaign-updates').append(update);
		countRemovesBtn('[wpcf-action-remove-update]');
	});

	$('body').on('click', '[wpcf-action-remove-update]', function (event) {
		event.preventDefault();
		$(this).closest('.wpcf-campaign-update').remove();
		countRemovesBtn('[wpcf-action-remove-update]');
	});
	countRemovesBtn('[wpcf-action-remove-update]');


	/**
	 * Show necessary Meta field and hide base on product type select
	 * WooCommerce compatibility
	 */
	$('.show_if_neo_crowdfunding_options').hide();
	$('#campaign-update-status-meta').hide();
	$('body').on('change','select#product-type',function() {
		if (this.value == "crowdfunding"){
			$('ul.product_data_tabs li').removeClass('active');
			$('.panel').hide();
			$('.show_if_neo_crowdfunding_options').show();
			$('.general_tab').addClass('active').show();
			$('#general_product_data').show();
			$('#campaign-update-status-meta').show();
		} else {
			$('.show_if_neo_crowdfunding_options').hide();
			$('#campaign-update-status-meta').hide();
		}
	});

	if ($('select#product-type').val() == "crowdfunding"){
		$('.show_if_neo_crowdfunding_options').show();
		$('#campaign-update-status-meta').show();
	}

    //Select2
    if(typeof $.fn.select2 !== 'undefined' ){
    	$('select.select2').select2();
    }

	//Date picker for input
	if(typeof $.fn.datepicker !== 'undefined' ){
		$('#_nf_duration_start, #_nf_duration_end').datepicker({
			dateFormat : 'dd-mm-yy'
		});
    }

	$('body').on('click','.wpneo-image-upload-btn',function(e) {
        e.preventDefault();
        var that = $(this);
        var image = wp.media({ 
            title: 'Upload Image',
            multiple: false
        }).open().on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            var uploaded_url = uploaded_image.toJSON().url;
            uploaded_image = uploaded_image.toJSON().id;
            $(that).parent().find( '.wpneo_rewards_image_field' ).val( uploaded_image );
            $(that).parent().find( '.wpneo-image-container' ).html( '<img width="100" src="'+uploaded_url+'" ><span class="wpneo-image-remove">x</span>' );
        });
    });

	$('body').on('click','.wpneo-image-remove',function(e) {
		var that = $(this);
	    $(that).parent().parent().find( '.wpneo_rewards_image_field' ).val( '' );
        $(that).parent().parent().find( '.wpneo-image-container' ).html( '' );
	});


	$('.wpneo-color-field').wpColorPicker();

	$(document).on('click', '[action-wpcf-reset-settings]', function(){
		if ( ! confirm('[WARNING!] This will be reset your full settings, Are you sure?')){
			return false;
		}
		$.ajax({
			type : 'POST',
			url : ajaxurl,
			data : { action : 'wpcf_settings_reset'},
			success : function(data){
				window.location.reload(true);
			}
		});
	});


	$(document).on('change', '.wpcf_addons_list_item', function(e) {
        var $that = $(this);
        var isEnable = $that.prop('checked') ? 1 : 0;
        var addonFieldName = $that.attr('name');
        $.ajax({
            url : ajaxurl,
            type : 'POST',
            data : {isEnable:isEnable, addonFieldName:addonFieldName, action : 'wpcf_addon_enable_disable'},
            success: function (data) {
                if (data.success){
                    //Success
                }
            }
        });
    });

});