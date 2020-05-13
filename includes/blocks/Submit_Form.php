<?php
namespace WPCF\blocks;

defined( 'ABSPATH' ) || exit;

class Submit_Form{
    
    public function __construct(){
        $this->register_submit_form();
    }

    public function register_submit_form(){
        register_block_type(
            'wp-crowdfunding/submitform',
            array(
                'attributes' => array(
                    'formSize'   => array(
                        'type'      => 'string',
                        'default'   => 'small'
                    ),
                ),
                'render_callback' => array( $this, 'submit_form_block_callback' ),
            )
        );
    }

    public function submit_form_block_callback( $att ){
        // $formSize           = isset($att['formSize']) ? $att['formSize'] : '';

        $html = '';
        $html .= 'Submit From';
        // $html .= '<div class="wpcf-form-field">
        //         <form type="post" action="" id="wpneofrontenddata">
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Title</div>
        //                 <div class="wpneo-fields">
        //                     <input type="text" name="wpneo-form-title" value="" /><small>Put the campaign title here</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Description</div>
        //                 <div class="wpneo-fields">
        //                     <div id="wp-wpneo-form-description-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
        //                         <link rel="stylesheet" id="editor-buttons-css" href="" media="all" />
        //                         <div id="wp-wpneo-form-description-editor-tools" class="wp-editor-tools hide-if-no-js">
        //                             <div id="wp-wpneo-form-description-media-buttons" class="wp-media-buttons">
        //                                 <button type="button" id="insert-media-button" class="button insert-media add_media" data-editor="wpneo-form-description"><span class="wp-media-buttons-icon"></span> Add Media</button>
        //                             </div>
        //                             <div class="wp-editor-tabs">
        //                                 <button type="button" id="wpneo-form-description-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="wpneo-form-description">Visual</button>
        //                                 <button type="button" id="wpneo-form-description-html" class="wp-switch-editor switch-html" data-wp-editor-id="wpneo-form-description">Text</button>
        //                             </div>
        //                         </div>

        //                         <div id="wp-wpneo-form-description-editor-container" class="wp-editor-container">
        //                             <div id="qt_wpneo-form-description_toolbar" class="quicktags-toolbar">
        //                                 <input type="button" id="qt_wpneo-form-description_strong" class="ed_button button button-small" aria-label="Bold" value="b" />
        //                                 <input type="button" id="qt_wpneo-form-description_em" class="ed_button button button-small" aria-label="Italic" value="i" />
        //                                 <input type="button" id="qt_wpneo-form-description_link" class="ed_button button button-small" aria-label="Insert link" value="link" />
        //                                 <input type="button" id="qt_wpneo-form-description_block" class="ed_button button button-small" aria-label="Blockquote" value="b-quote" />
        //                                 <input type="button" id="qt_wpneo-form-description_del" class="ed_button button button-small" aria-label="Deleted text (strikethrough)" value="del" />
        //                                 <input type="button" id="qt_wpneo-form-description_ins" class="ed_button button button-small" aria-label="Inserted text" value="ins" />
        //                                 <input type="button" id="qt_wpneo-form-description_img" class="ed_button button button-small" aria-label="Insert image" value="img" />
        //                                 <input type="button" id="qt_wpneo-form-description_ul" class="ed_button button button-small" aria-label="Bulleted list" value="ul" />
        //                                 <input type="button" id="qt_wpneo-form-description_ol" class="ed_button button button-small" aria-label="Numbered list" value="ol" />
        //                                 <input type="button" id="qt_wpneo-form-description_li" class="ed_button button button-small" aria-label="List item" value="li" />
        //                                 <input type="button" id="qt_wpneo-form-description_code" class="ed_button button button-small" aria-label="Code" value="code" />
        //                                 <input type="button" id="qt_wpneo-form-description_more" class="ed_button button button-small" aria-label="Insert Read More tag" value="more" />
        //                                 <input type="button" id="qt_wpneo-form-description_close" class="ed_button button button-small" title="Close all open tags" value="close tags" />
        //                             </div>
                                    
        //                             <textarea class="wp-editor-area" rows="15" autocomplete="off" cols="40" name="wpneo-form-description" id="wpneo-form-description" aria-hidden="true"></textarea>
        //                         </div>
        //                     </div>
        //                     <small>Put the campaign description here</small>
        //                 </div>
        //             </div>
                    
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Short Description</div>
        //                 <div class="wpneo-fields">
        //                     <div id="wp-wpneo-form-short-description-wrap" class="wp-core-ui wp-editor-wrap tmce-active">
        //                         <div id="wp-wpneo-form-short-description-editor-tools" class="wp-editor-tools hide-if-no-js">
        //                             <div id="wp-wpneo-form-short-description-media-buttons" class="wp-media-buttons">
        //                                 <button type="button" class="button insert-media add_media" data-editor="wpneo-form-short-description"><span class="wp-media-buttons-icon"></span> Add Media</button>
        //                             </div>
        //                             <div class="wp-editor-tabs">
        //                                 <button type="button" id="wpneo-form-short-description-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="wpneo-form-short-description">Visual</button>
        //                                 <button type="button" id="wpneo-form-short-description-html" class="wp-switch-editor switch-html" data-wp-editor-id="wpneo-form-short-description">Text</button>
        //                             </div>
        //                         </div>
        //                         <div id="wp-wpneo-form-short-description-editor-container" class="wp-editor-container">
        //                             <div id="qt_wpneo-form-short-description_toolbar" class="quicktags-toolbar">
        //                                 <input type="button" id="qt_wpneo-form-short-description_strong" class="ed_button button button-small" aria-label="Bold" value="b" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_em" class="ed_button button button-small" aria-label="Italic" value="i" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_link" class="ed_button button button-small" aria-label="Insert link" value="link" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_block" class="ed_button button button-small" aria-label="Blockquote" value="b-quote" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_del" class="ed_button button button-small" aria-label="Deleted text (strikethrough)" value="del" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_ins" class="ed_button button button-small" aria-label="Inserted text" value="ins" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_img" class="ed_button button button-small" aria-label="Insert image" value="img" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_ul" class="ed_button button button-small" aria-label="Bulleted list" value="ul" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_ol" class="ed_button button button-small" aria-label="Numbered list" value="ol" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_li" class="ed_button button button-small" aria-label="List item" value="li" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_code" class="ed_button button button-small" aria-label="Code" value="code" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_more" class="ed_button button button-small" aria-label="Insert Read More tag" value="more" />
        //                                 <input type="button" id="qt_wpneo-form-short-description_close" class="ed_button button button-small" title="Close all open tags" value="close tags" />
        //                             </div>
        //                             <textarea class="wp-editor-area" rows="10" autocomplete="off" cols="40" name="wpneo-form-short-description" id="wpneo-form-short-description" aria-hidden="true"></textarea>
        //                         </div>
        //                     </div>
        //                     <small>Put Here Product Short Description</small>
        //                 </div>
        //             </div>
                
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Category</div>
        //                 <div class="wpneo-fields">
        //                     <select name="wpneo-form-category">
        //                         <option value="clothing">Clothing</option>
        //                         <option value="crafts">Crafts</option>
        //                     </select><small>Select your campaign category</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Tag</div>
        //                 <div class="wpneo-fields">
        //                     <input type="text" name="wpneo-form-tag" placeholder="Tag" value="" /><small>Separate tags with commas eg: tag1,tag2</small>
        //                 </div>
        //             </div>

        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Feature Image</div>
        //                 <div class="wpneo-fields">
        //                     <input type="text" name="wpneo-form-image-url" class="wpneo-upload wpneo-form-image-url" value="" />
        //                     <input type="hidden" name="wpneo-form-image-id" class="wpneo-form-image-id" value="" />
        //                     <input type="button" id="cc-image-upload-file-button" class="wpneo-image-upload float-right" value="Upload Image" data-url="" /><small>Upload a campaign feature image</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Video</div>
        //                 <div class="wpneo-fields">
        //                     <input type="text" name="wpneo-form-video" value="" placeholder="" /><small>Put the campaign video URL here</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Campaign End Method</div>
        //                 <div class="wpneo-fields">
        //                     <select name="wpneo-form-type" class="wpneo-form-type">
        //                         <option value="target_goal">Target Goal</option>
        //                         <option value="target_date">Target Date</option>
        //                         <option value="target_goal_and_date">Target Goal &amp; Date</option>
        //                         <option value="never_end">Campaign Never Ends</option>
        //                     </select><small>Choose the stage when campaign will end</small>
        //                 </div>
        //             </div>

        //             <div class="wpneo-single wpneo-first-half">
        //                 <div class="wpneo-name">Start Date</div>
        //                 <div class="wpneo-fields">
        //                     <input type="text" name="wpneo-form-start-date" value="" id="wpneo_form_start_date" class="hasDatepicker" /><small>Campaign start date (dd-mm-yy)</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single wpneo-second-half">
        //                 <div class="wpneo-name">End Date</div>
        //                 <div class="wpneo-fields">
        //                     <input type="text" name="wpneo-form-end-date" value="" id="wpneo_form_end_date" class="hasDatepicker" /><small>Campaign end date (dd-mm-yy)</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single wpneo-first-half">
        //                 <div class="wpneo-name">Minimum Amount</div>
        //                 <div class="wpneo-fields">
        //                     <input type="number" name="wpneo-form-min-price" value="" /><small>Minimum campaign funding amount</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single wpneo-second-half">
        //                 <div class="wpneo-name">Maximum Amount</div>
        //                 <div class="wpneo-fields">
        //                     <input type="number" name="wpneo-form-max-price" value="" /><small>Maximum campaign funding amount</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Funding Goal</div>
        //                 <div class="wpneo-fields">
        //                     <input type="number" name="wpneo-form-funding-goal" value="" /><small>Campaign funding goal</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single wpneo-first-half">
        //                 <div class="wpneo-name">Recommended Amount</div>
        //                 <div class="wpneo-fields">
        //                     <input type="number" name="wpneo-form-recommended-price" value="" /><small>Recommended campaign funding amount</small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single wpneo-second-half">
        //                 <div class="wpneo-name">Predefined Pledge Amount</div>
        //                 <div class="wpneo-fields">
        //                     <input type="text" name="wpcf_predefined_pledge_amount" value="" /><small>Predefined amount allow you to place the amount in donate box by click, price should separated by comma (,), example: <code>10,20,30,40</code></small>
        //                 </div>
        //             </div>
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Contributor Table</div>
        //                 <div class="wpneo-fields">
        //                     <input type="checkbox" name="wpneo-form-contributor-table" value="1" />Show contributor table on campaign single page</div>
        //             </div>
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Contributor Anonymity</div>
        //                 <div class="wpneo-fields">
        //                     <input type="checkbox" name="wpneo-form-contributor-show" value="1" />Make contributors anonymous on the contributor table</div>
        //             </div>
        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Country</div>
        //                 <div class="wpneo-fields">
        //                     <select name="wpneo-form-country" class="wpneo-form-country">
        //                         <option selected="selected" value="0">Select a country</option>
        //                         <option value="BD">Bangladesh</option>
        //                     </select>
        //                     <small>Select your country</small>
        //                 </div>
        //             </div>

        //             <div class="wpneo-single">
        //                 <div class="wpneo-name">Location</div>
        //                 <div class="wpneo-fields">
        //                     <input type="text" name="wpneo-form-location" value="" /><small>Put the campaign location here</small>
        //                 </div>
        //             </div>

        //             <div class="wpneo-reward-option">Reward Option</div>
        //             <div class="panel" id="reward_options">
        //                 <div class="reward_group">
        //                     <div class="campaign_rewards_field_copy">
        //                         <div class="wpneo-single">
        //                             <div class="wpneo-name">Pledge Amount</div>
        //                             <div class="wpneo-fields">
        //                                 <input type="text" value="" id="wpneo_rewards_pladge_amount[]" name="wpneo_rewards_pladge_amount[]" class="wc_input_price" /><small>Pledge Amount</small>
        //                             </div>
        //                         </div>
        //                         <div class="wpneo-single">
        //                             <div class="wpneo-name">Reward Image</div>
        //                             <div class="wpneo-fields">
        //                                 <input type="text" name="wpneo_rewards_image_fields" class="wpneo-upload wpneo_rewards_image_field_url" value="" />
        //                                 <input type="hidden" name="wpneo_rewards_image_field[]" class="wpneo_rewards_image_field" value="" />
        //                                 <input type="button" id="cc-image-upload-file-button" class="wpneo-image-upload-btn float-right" value="Upload Image" /><small>Upload a reward image</small>
        //                             </div>
        //                         </div>
        //                         <div class="wpneo-single form-field">
        //                             <div class="wpneo-name">Reward</div>
        //                             <div class="wpneo-fields float-right">
        //                                 <textarea cols="20" rows="2" id="wpneo_rewards_description[]" name="wpneo_rewards_description[]" class="short"></textarea><small>Reward Description</small>
        //                             </div>
        //                         </div>
        //                         <div class="wpneo-single wpneo-first-half">
        //                             <div class="wpneo-name">Estimated Delivery Month</div>
        //                             <div class="wpneo-fields">
        //                                 <select class="select short" name="wpneo_rewards_endmonth[]" id="wpneo_rewards_endmonth[]">
        //                                     <option selected="selected" value="">- Select -</option>
        //                                     <option value="jan">January</option>
        //                                     <option value="feb">February</option>
        //                                     <option value="mar">March</option>
        //                                     <option value="apr">April</option>
        //                                     <option value="may">May</option>
        //                                     <option value="jun">June</option>
        //                                     <option value="jul">July</option>
        //                                     <option value="aug">August</option>
        //                                     <option value="sep">September</option>
        //                                     <option value="oct">October</option>
        //                                     <option value="nov">November</option>
        //                                     <option value="dec">December</option>
        //                                 </select><small>Estimated Delivery Month of the Reward</small>
        //                             </div>
        //                         </div>
        //                         <div class="wpneo-single wpneo-second-half">
        //                             <div class="wpneo-name">Estimated Delivery Year</div>
        //                             <div class="wpneo-fields">
        //                                 <select class="select short" name="wpneo_rewards_endyear[]" id="wpneo_rewards_endyear[]">
        //                                     <option selected="selected" value="">- Select -</option>
        //                                     <option value="2019">2019</option>
        //                                     <option value="2020">2020</option>
        //                                     <option value="2021">2021</option>
        //                                     <option value="2022">2022</option>
        //                                     <option value="2023">2023</option>
        //                                     <option value="2024">2024</option>
        //                                     <option value="2025">2025</option>
        //                                 </select><small>Estimated Delivery Year of the Reward</small>
        //                             </div>
        //                         </div>
        //                         <div class="wpneo-single">
        //                             <div class="wpneo-name">Quantity</div>
        //                             <div class="wpneo-fields">
        //                                 <input type="text" value="" id="wpneo_rewards_item_limit[]" name="wpneo_rewards_item_limit[]" class="wc_input_price" /><small>Quantity of physical products</small>
        //                             </div>
        //                         </div>
        //                         <div class="wpneo-remove-button">
        //                             <input type="button" value="- Remove" class="button tagadd removeCampaignRewards" name="remove_rewards" />
        //                         </div>
        //                     </div>
        //                 </div>
        //                 <div id="rewards_addon_fields"></div>
        //                 <div class="text-right">
        //                     <input type="button" value="+ Add" id="addreward" class="button tagadd" name="save" />
        //                 </div>
        //             </div>

        //             <div class="wpneo-title"></div>
        //             <div class="wpneo-text"></div>
        //             <div class="wpneo-requirement-title">
        //                 <input id="wpcf-term-agree" type="checkbox" value="agree" name="wpneo_terms_agree" />
        //                 <label for="wpcf-term-agree">I agree with the terms and conditions.</label>
        //             </div>
        //             <div class="wpneo-form-action">
        //                 <input type="hidden" name="action" value="addfrontenddata" />
        //                 <input type="submit" class="wpneo-submit-campaign" value="Submit campaign" />
        //                 <a href="#" class="wpneo-cancel-campaign">Cancel</a>
        //             </div>
        //             <input type="hidden" id="wpcf_form_action_field" name="wpcf_form_action_field" value="" />
        //             <input type="hidden" name="_wp_http_referer" value="" />
        //         </form>
        //     </div>';

        
        return $html;
    }
}
