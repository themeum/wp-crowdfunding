const { __ } = wp.i18n;
const { withState } = wp.compose;
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody, ColorPalette } = wp.components;

  
class Edit extends Component {
    constructor(props) { 
        super(props)
        this.state = { openPanelSetting: '' };
    }
    
    render() {
        const {
            setAttributes,
            attributes: {
                textColor,
                bgColor,
                cancelBtnColor
            },
        } = this.props

        const bgcolors = [
            { name: 'Color Code: #1adc68', color: '#1adc68' }, 
            { name: 'Color Code: #8224e3', color: '#8224e3' }, 
            { name: 'Dark Black', color: '#111111' }, 
            { name: 'Light Gray', color: '#767676' }, 
            { name: 'White', color: '#ffffff' }, 
        ];

        const textColors = [ 
            { name: 'Green', color: '#94c94a' }, 
            { name: 'White', color: '#fff' }, 
            { name: 'Black', color: '#000' }, 
        ];

        const cancelBtnColors = [
            { name: 'Red', color: 'red' }, 
            { name: 'Dark', color: '#cf0000' }, 
        ]

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title='Form Style' initialOpen={true}>
                        <label className="components-base-control__label">{ __( 'Background Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label={ __( 'Bg Color') }
                            colors={ bgcolors } 
                            value={ bgColor }
                            onChange={ ( value ) => setAttributes( { bgColor: value } ) }
                            withTransparentOption
                        />

                        <label className="components-base-control__label">{ __( 'Text Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label={ __( 'Title Color') }
                            colors={ textColors } 
                            value={ textColor }
                            onChange={ ( value ) => setAttributes( { textColor: value } ) }
                            withTransparentOption
                        />

                        <label className="components-base-control__label">{ __( 'Cancel Button Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label={ __( 'Title Color') }
                            colors={ cancelBtnColors } 
                            value={ cancelBtnColor }
                            onChange={ ( value ) => setAttributes( { cancelBtnColor: value } ) }
                            withTransparentOption
                        />

                    </PanelBody>
                </InspectorControls>

                <div className="wpcf-form-field">
                    <form type="post" action="" id="wpneofrontenddata">
                        <div className="wpneo-single">
                            <div className="wpneo-name">Title</div>
                            <div className="wpneo-fields">
                                <input type="text" name="wpneo-form-title" value="" /><small>Put the campaign title here</small>
                            </div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Description</div>
                            <div className="wpneo-fields">
                                <div id="wp-wpneo-form-description-wrap" className="wp-core-ui wp-editor-wrap tmce-active">
                                    <link rel="stylesheet" id="editor-buttons-css" href="" media="all" />
                                    <div id="wp-wpneo-form-description-editor-tools" className="wp-editor-tools hide-if-no-js">
                                        <div id="wp-wpneo-form-description-media-buttons" className="wp-media-buttons">
                                            <button type="button" id="insert-media-button" className="button insert-media add_media" data-editor="wpneo-form-description"><span className="wp-media-buttons-icon"></span> Add Media</button>
                                        </div>
                                        <div className="wp-editor-tabs">
                                            <button type="button" id="wpneo-form-description-tmce" className="wp-switch-editor switch-tmce" data-wp-editor-id="wpneo-form-description">Visual</button>
                                            <button type="button" id="wpneo-form-description-html" className="wp-switch-editor switch-html" data-wp-editor-id="wpneo-form-description">Text</button>
                                        </div>
                                    </div>

                                    <div id="wp-wpneo-form-description-editor-container" className="wp-editor-container">
                                        <div id="qt_wpneo-form-description_toolbar" className="quicktags-toolbar">
                                            <input type="button" id="qt_wpneo-form-description_strong" className="ed_button button button-small" aria-label="Bold" value="b" />
                                            <input type="button" id="qt_wpneo-form-description_em" className="ed_button button button-small" aria-label="Italic" value="i" />
                                            <input type="button" id="qt_wpneo-form-description_link" className="ed_button button button-small" aria-label="Insert link" value="link" />
                                            <input type="button" id="qt_wpneo-form-description_block" className="ed_button button button-small" aria-label="Blockquote" value="b-quote" />
                                            <input type="button" id="qt_wpneo-form-description_del" className="ed_button button button-small" aria-label="Deleted text (strikethrough)" value="del" />
                                            <input type="button" id="qt_wpneo-form-description_ins" className="ed_button button button-small" aria-label="Inserted text" value="ins" />
                                            <input type="button" id="qt_wpneo-form-description_img" className="ed_button button button-small" aria-label="Insert image" value="img" />
                                            <input type="button" id="qt_wpneo-form-description_ul" className="ed_button button button-small" aria-label="Bulleted list" value="ul" />
                                            <input type="button" id="qt_wpneo-form-description_ol" className="ed_button button button-small" aria-label="Numbered list" value="ol" />
                                            <input type="button" id="qt_wpneo-form-description_li" className="ed_button button button-small" aria-label="List item" value="li" />
                                            <input type="button" id="qt_wpneo-form-description_code" className="ed_button button button-small" aria-label="Code" value="code" />
                                            <input type="button" id="qt_wpneo-form-description_more" className="ed_button button button-small" aria-label="Insert Read More tag" value="more" />
                                            <input type="button" id="qt_wpneo-form-description_close" className="ed_button button button-small" title="Close all open tags" value="close tags" />
                                        </div>
                                        
                                        <textarea className="wp-editor-area" rows="15" autocomplete="off" cols="40" name="wpneo-form-description" id="wpneo-form-description" aria-hidden="true"></textarea>
                                    </div>
                                </div>
                                <small>Put the campaign description here</small>
                            </div>
                        </div> 
                        <div className="wpneo-single">
                            <div className="wpneo-name">Short Description</div>
                            <div className="wpneo-fields">
                                <div id="wp-wpneo-form-short-description-wrap" className="wp-core-ui wp-editor-wrap tmce-active">
                                    <div id="wp-wpneo-form-short-description-editor-tools" className="wp-editor-tools hide-if-no-js">
                                        <div id="wp-wpneo-form-short-description-media-buttons" className="wp-media-buttons">
                                            <button type="button" className="button insert-media add_media" data-editor="wpneo-form-short-description"><span className="wp-media-buttons-icon"></span> Add Media</button>
                                        </div>
                                        <div className="wp-editor-tabs">
                                            <button type="button" id="wpneo-form-short-description-tmce" className="wp-switch-editor switch-tmce" data-wp-editor-id="wpneo-form-short-description">Visual</button>
                                            <button type="button" id="wpneo-form-short-description-html" className="wp-switch-editor switch-html" data-wp-editor-id="wpneo-form-short-description">Text</button>
                                        </div>
                                    </div>
                                    <div id="wp-wpneo-form-short-description-editor-container" className="wp-editor-container">
                                        <div id="qt_wpneo-form-short-description_toolbar" className="quicktags-toolbar">
                                            <input type="button" id="qt_wpneo-form-short-description_strong" className="ed_button button button-small" aria-label="Bold" value="b" />
                                            <input type="button" id="qt_wpneo-form-short-description_em" className="ed_button button button-small" aria-label="Italic" value="i" />
                                            <input type="button" id="qt_wpneo-form-short-description_link" className="ed_button button button-small" aria-label="Insert link" value="link" />
                                            <input type="button" id="qt_wpneo-form-short-description_block" className="ed_button button button-small" aria-label="Blockquote" value="b-quote" />
                                            <input type="button" id="qt_wpneo-form-short-description_del" className="ed_button button button-small" aria-label="Deleted text (strikethrough)" value="del" />
                                            <input type="button" id="qt_wpneo-form-short-description_ins" className="ed_button button button-small" aria-label="Inserted text" value="ins" />
                                            <input type="button" id="qt_wpneo-form-short-description_img" className="ed_button button button-small" aria-label="Insert image" value="img" />
                                            <input type="button" id="qt_wpneo-form-short-description_ul" className="ed_button button button-small" aria-label="Bulleted list" value="ul" />
                                            <input type="button" id="qt_wpneo-form-short-description_ol" className="ed_button button button-small" aria-label="Numbered list" value="ol" />
                                            <input type="button" id="qt_wpneo-form-short-description_li" className="ed_button button button-small" aria-label="List item" value="li" />
                                            <input type="button" id="qt_wpneo-form-short-description_code" className="ed_button button button-small" aria-label="Code" value="code" />
                                            <input type="button" id="qt_wpneo-form-short-description_more" className="ed_button button button-small" aria-label="Insert Read More tag" value="more" />
                                            <input type="button" id="qt_wpneo-form-short-description_close" className="ed_button button button-small" title="Close all open tags" value="close tags" />
                                        </div>
                                        <textarea className="wp-editor-area" rows="10" autocomplete="off" cols="40" name="wpneo-form-short-description" id="wpneo-form-short-description" aria-hidden="true"></textarea>
                                    </div>
                                </div>
                                <small>Put Here Product Short Description</small>
                            </div>
                        </div>
                    
                        <div className="wpneo-single">
                            <div className="wpneo-name">Category</div>
                            <div className="wpneo-fields">
                                <select name="wpneo-form-category">
                                    <option value="clothing">Clothing</option>
                                    <option value="crafts">Crafts</option>
                                </select><small>Select your campaign category</small>
                            </div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Tag</div>
                            <div className="wpneo-fields">
                                <input type="text" name="wpneo-form-tag" placeholder="Tag" value="" /><small>Separate tags with commas eg: tag1,tag2</small>
                            </div>
                        </div>

                        <div className="wpneo-single">
                            <div className="wpneo-name">Feature Image</div>
                            <div className="wpneo-fields">
                                <input type="text" name="wpneo-form-image-url" className="wpneo-upload wpneo-form-image-url" value="" />
                                <input type="hidden" name="wpneo-form-image-id" className="wpneo-form-image-id" value="" />
                                <input type="button" id="cc-image-upload-file-button" className="wpneo-image-upload float-right" value="Upload Image" data-url="" /><small>Upload a campaign feature image</small>
                            </div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Video</div>
                            <div className="wpneo-fields">
                                <input type="text" name="wpneo-form-video" value="" placeholder="" /><small>Put the campaign video URL here</small>
                            </div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Campaign End Method</div>
                            <div className="wpneo-fields">
                                <select name="wpneo-form-type" className="wpneo-form-type">
                                    <option value="target_goal">Target Goal</option>
                                    <option value="target_date">Target Date</option>
                                    <option value="target_goal_and_date">Target Goal &amp; Date</option>
                                    <option value="never_end">Campaign Never Ends</option>
                                </select><small>Choose the stage when campaign will end</small>
                            </div>
                        </div>
                        <div className="wpneo-single wpneo-first-half">
                            <div className="wpneo-name">Start Date</div>
                            <div className="wpneo-fields">
                                <input type="text" name="wpneo-form-start-date" value="" id="wpneo_form_start_date" className="hasDatepicker" /><small>Campaign start date (dd-mm-yy)</small>
                            </div>
                        </div>
                        <div className="wpneo-single wpneo-second-half">
                            <div className="wpneo-name">End Date</div>
                            <div className="wpneo-fields">
                                <input type="text" name="wpneo-form-end-date" value="" id="wpneo_form_end_date" className="hasDatepicker" /><small>Campaign end date (dd-mm-yy)</small>
                            </div>
                        </div>
                        <div className="wpneo-single wpneo-first-half">
                            <div className="wpneo-name">Minimum Amount</div>
                            <div className="wpneo-fields">
                                <input type="number" name="wpneo-form-min-price" value="" /><small>Minimum campaign funding amount</small>
                            </div>
                        </div>
                        <div className="wpneo-single wpneo-second-half">
                            <div className="wpneo-name">Maximum Amount</div>
                            <div className="wpneo-fields">
                                <input type="number" name="wpneo-form-max-price" value="" /><small>Maximum campaign funding amount</small>
                            </div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Funding Goal</div>
                            <div className="wpneo-fields">
                                <input type="number" name="wpneo-form-funding-goal" value="" /><small>Campaign funding goal</small>
                            </div>
                        </div>
                        <div className="wpneo-single wpneo-first-half">
                            <div className="wpneo-name">Recommended Amount</div>
                            <div className="wpneo-fields">
                                <input type="number" name="wpneo-form-recommended-price" value="" /><small>Recommended campaign funding amount</small>
                            </div>
                        </div>
                        <div className="wpneo-single wpneo-second-half">
                            <div className="wpneo-name">Predefined Pledge Amount</div>
                            <div className="wpneo-fields">
                                <input type="text" name="wpcf_predefined_pledge_amount" value="" /><small>Predefined amount allow you to place the amount in donate box by click, price should separated by comma (,), example: <code>10,20,30,40</code></small>
                            </div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Contributor Table</div>
                            <div className="wpneo-fields">
                                <input type="checkbox" name="wpneo-form-contributor-table" value="1" />Show contributor table on campaign single page</div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Contributor Anonymity</div>
                            <div className="wpneo-fields">
                                <input type="checkbox" name="wpneo-form-contributor-show" value="1" />Make contributors anonymous on the contributor table</div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Country</div>
                            <div className="wpneo-fields">
                                <select name="wpneo-form-country" className="wpneo-form-country">
                                    <option selected="selected" value="0">Select a country</option>
                                    <option value="BD">Bangladesh</option>
                                </select>
                                <small>Select your country</small>
                            </div>
                        </div>
                        <div className="wpneo-single">
                            <div className="wpneo-name">Location</div>
                            <div className="wpneo-fields">
                                <input type="text" name="wpneo-form-location" value="" /><small>Put the campaign location here</small>
                            </div>
                        </div>
                        <div className="wpneo-reward-option">Reward Option</div>
                        <div className="panel" id="reward_options">
                            <div className="reward_group">
                                <div className="campaign_rewards_field_copy">
                                    <div className="wpneo-single">
                                        <div className="wpneo-name">Pledge Amount</div>
                                        <div className="wpneo-fields">
                                            <input type="text" value="" id="wpneo_rewards_pladge_amount[]" name="wpneo_rewards_pladge_amount[]" className="wc_input_price" /><small>Pledge Amount</small>
                                        </div>
                                    </div>
                                    <div className="wpneo-single">
                                        <div className="wpneo-name">Reward Image</div>
                                        <div className="wpneo-fields">
                                            <input type="text" name="wpneo_rewards_image_fields" className="wpneo-upload wpneo_rewards_image_field_url" value="" />
                                            <input type="hidden" name="wpneo_rewards_image_field[]" className="wpneo_rewards_image_field" value="" />
                                            <input type="button" id="cc-image-upload-file-button" className="wpneo-image-upload-btn float-right" value="Upload Image" /><small>Upload a reward image</small>
                                        </div>
                                    </div>
                                    <div className="wpneo-single form-field">
                                        <div className="wpneo-name">Reward</div>
                                        <div className="wpneo-fields float-right">
                                            <textarea cols="20" rows="2" id="wpneo_rewards_description[]" name="wpneo_rewards_description[]" className="short"></textarea><small>Reward Description</small>
                                        </div>
                                    </div>
                                    <div className="wpneo-single wpneo-first-half">
                                        <div className="wpneo-name">Estimated Delivery Month</div>
                                        <div className="wpneo-fields">
                                            <select className="select short" name="wpneo_rewards_endmonth[]" id="wpneo_rewards_endmonth[]">
                                                <option selected="selected" value="">- Select -</option>
                                                <option value="jan">January</option>
                                                <option value="feb">February</option>
                                                <option value="mar">March</option>
                                                <option value="apr">April</option>
                                                <option value="may">May</option>
                                                <option value="jun">June</option>
                                                <option value="jul">July</option>
                                                <option value="aug">August</option>
                                                <option value="sep">September</option>
                                                <option value="oct">October</option>
                                                <option value="nov">November</option>
                                                <option value="dec">December</option>
                                            </select><small>Estimated Delivery Month of the Reward</small>
                                        </div>
                                    </div>
                                    <div className="wpneo-single wpneo-second-half">
                                        <div className="wpneo-name">Estimated Delivery Year</div>
                                        <div className="wpneo-fields">
                                            <select className="select short" name="wpneo_rewards_endyear[]" id="wpneo_rewards_endyear[]">
                                                <option selected="selected" value="">- Select -</option>
                                                <option value="2019">2019</option>
                                                <option value="2020">2020</option>
                                                <option value="2021">2021</option>
                                                <option value="2022">2022</option>
                                                <option value="2023">2023</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                            </select><small>Estimated Delivery Year of the Reward</small>
                                        </div>
                                    </div>
                                    <div className="wpneo-single">
                                        <div className="wpneo-name">Quantity</div>
                                        <div className="wpneo-fields">
                                            <input type="text" value="" id="wpneo_rewards_item_limit[]" name="wpneo_rewards_item_limit[]" className="wc_input_price" /><small>Quantity of physical products</small>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            {/* <div id="rewards_addon_fields"></div> */}
                            <div className="text-right">
                                <input type="button" value="+ Add" id="addreward" className="button tagadd" name="save" />
                            </div>
                        </div>
                        <div className="wpneo-title"></div>
                        <div className="wpneo-text"></div>
                        <div className="wpneo-requirement-title">
                            <input id="wpcf-term-agree" type="checkbox" value="agree" name="wpneo_terms_agree" />
                            <label for="wpcf-term-agree">I agree with the terms and conditions.</label>
                        </div>
                        <div className="wpneo-form-action">
                            <input type="hidden" name="action" value="addfrontenddata" />
                            <input type="submit" className="wpneo-submit-campaign" value="Submit campaign" />
                            <a href="#" className="wpneo-cancel-campaign">Cancel</a>
                        </div>
                        <input type="hidden" id="wpcf_form_action_field" name="wpcf_form_action_field" value="" />
                        <input type="hidden" name="_wp_http_referer" value="" />
                    </form>
                </div>

                <style>
                    {`
                        input[type="button"].wpneo-image-upload, .wpneo-image-upload.float-right, .wpneo-image-upload-btn, #addreward, #wpneofrontenddata .wpneo-form-action input[type="submit"].wpneo-submit-campaign{
                            background-color: ${bgColor}
                        }
                        
                        input[type="button"].wpneo-image-upload, .wpneo-image-upload.float-right, .wpneo-image-upload-btn, #addreward, #wpneofrontenddata .wpneo-form-action input[type="submit"].wpneo-submit-campaign, a.wpneo-cancel-campaign, .editor-styles-wrapper a.wpneo-cancel-campaign {
                            color: ${textColor}
                        }
                        a.wpneo-cancel-campaign {
                            background-color: ${cancelBtnColor}
                        }
                    `}
                </style>
            </Fragment>
        )
    }
}
export default Edit
