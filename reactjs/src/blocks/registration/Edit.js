const { __ } = wp.i18n;
const { withState } = wp.compose;
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody,SelectControl, ColorPalette, RangeControl } = wp.components;

  
class Edit extends Component {
    constructor(props) { 
        super(props)
        this.state = { openPanelSetting: '' };
    }
    
    render() {
        const {
            setAttributes,
            attributes: {
                labelfontSize,
                labelColor,

                inputfontSize,
                borderColor, 
                inputTextColor,

                bgColorpalette,
                titleColor,
                fontSize,
                fontWeight,
                
                cancelbtnbgColorpalette,
                cancelbtncolor,
                cancelfontWeight,
                cancelfontSize
            }, 
        } = this.props

        const inputtextcolors = [
            { name: 'Dark Black', color: '#000000' }, 
            { name: 'Light Gray', color: '#939393' }, 
            { name: 'White', color: '#ffffff' }, 
        ]
        const bordercolors = [
            { name: 'Dark Black', color: '#eeeeee' }, 
            { name: 'Gray', color: '#e2e4e7' },
            { name: 'Light Gray', color: '#fafafa' }, 
        ];

        const labelcolors = [
            { name: 'Dark Black', color: '#111111' }, 
            { name: 'Light Gray', color: '#767676' }, 
            { name: 'White', color: '#ffffff' }, 
        ];

        // Background Color 
        const bgcolors = [ 
            { name: 'Color Code: #0073a8', color: '#0073a8' }, 
            { name: 'Color Code: #005075', color: '#005075' }, 
            { name: 'Dark Black', color: '#111111' }, 
            { name: 'Light Gray', color: '#767676' }, 
            { name: 'White', color: '#ffffff' }, 
        ];

        // Title Color Color 
        const colors = [ 
            { name: 'Gray', color: '#ccc' }, 
            { name: 'White', color: '#fff' }, 
            { name: 'Black', color: '#000' }, 
        ];

        // Font Width.
        const SelectFontWidthControl = withState({
            fontWeight: fontWeight,
        })(({ fontWeight, setState }) => (
            <SelectControl
                label="Font Weight"
                value={fontWeight}
                options={[
                    { label: '100', value: '100' },
                    { label: '400', value: '400' },
                    { label: '500', value: '500' },
                    { label: '600', value: '600' },
                    { label: '700', value: '700' },
                    { label: '800', value: '800' },
                ]}
                onChange={(value) => { setAttributes({ fontWeight: value }) }}
            />
        ));

        const btnStyle = {
            background: bgColorpalette,
            color: titleColor,
            fontSize: fontSize,
            fontWeight: fontWeight,
        }

        // Font Width.
        const SelectCancelFontWidthControl = withState({
            cancelfontWeight: cancelfontWeight,
        })(({ cancelfontWeight, setState }) => (
            <SelectControl
                label="Font Weight"
                value={cancelfontWeight}
                options={[
                    { label: '100', value: '100' },
                    { label: '400', value: '400' },
                    { label: '500', value: '500' },
                    { label: '600', value: '600' },
                    { label: '700', value: '700' },
                    { label: '800', value: '800' },
                ]}
                onChange={(value) => { setAttributes({ cancelfontWeight: value }) }}
            />
        ));
        
        // Title Color Color 
        const CancelColorPalette = withState( {
            color: cancelbtncolor,
        } )( ( { color, setState } ) => { 
            const colors = [ 
                { name: 'Gray', color: '#ccc' }, 
                { name: 'White', color: '#fff' }, 
                { name: 'Black', color: '#000' }, 
            ];
            return ( 
                <ColorPalette
                    label="Title Color" 
                    colors={ colors } 
                    value={ color }
                    onChange={(value) => { setAttributes({ cancelbtncolor: value }) }}
                />
            ) 
        } );

        // Background Color 
        const CancelBgColorPalette = withState( {
            bgcolor: cancelbtnbgColorpalette,
        } )( ( { bgcolor, setState } ) => { 
            const bgcolors = [ 
                { name: 'Color Code: #0073a8', color: '#0073a8' }, 
                { name: 'Color Code: #005075', color: '#005075' }, 
                { name: 'Dark Black', color: '#111111' }, 
                { name: 'Light Gray', color: '#767676' }, 
                { name: 'White', color: '#ffffff' }, 
            ];
            
            return ( 
                <ColorPalette 
                    label="Background Color"
                    colors={ bgcolors } 
                    value={ bgcolor }
                    onChange={(value) => { setAttributes({ cancelbtnbgColorpalette: value }) }}
                />
            ) 
        } );

        const CancelBtnStyle = {
            background: cancelbtnbgColorpalette,
            color: cancelbtncolor,
            fontSize: cancelfontSize,
            fontWeight: cancelfontWeight,
        }

        return (
            <Fragment>
                <InspectorControls key="inspector">

                    <PanelBody title={__('')} initialOpen={true}>
                        <RangeControl
                            label="Input Font Size"
                            value={inputfontSize}
                            onChange={(value) => { setAttributes({ inputfontSize: value }) }}
                            min={5}
                            max={30}
                        />
                        <label className="components-base-control__label">{ __( 'Input Text Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label="Input Text Color" 
                            colors={ inputtextcolors } 
                            value={ inputTextColor }
                            onChange={(value) => { setAttributes({ inputTextColor: value }) }}
                        /> 
                        <label className="components-base-control__label">{ __( 'Border Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label="Border Color" 
                            colors={ bordercolors } 
                            value={ borderColor }
                            onChange={(value) => { setAttributes({ borderColor: value }) }}
                        />
                    </PanelBody>

                    <PanelBody title={__('Label Style')} initialOpen={false}>
                        <RangeControl
                            label="Label Font Size"
                            value={labelfontSize}
                            onChange={(value) => { setAttributes({ labelfontSize: value }) }}
                            min={5}
                            max={30}
                        />
                        <label className="components-base-control__label">{ __( 'Label Text Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label="Title Color" 
                            colors={ labelcolors } 
                            value={ labelColor }
                            onChange={(value) => { setAttributes({ labelColor: value }) }}
                        />
                    </PanelBody>

                    <PanelBody title={__('Signup Button Style')} initialOpen={false}>
                        <RangeControl
                            label="Font Size"
                            value={fontSize}
                            onChange={(value) => { setAttributes({ fontSize: value }) }}
                            min={5}
                            max={30}
                        />
                        <SelectFontWidthControl />
                        <label className="components-base-control__label">{ __( 'Text Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label="Title Color" 
                            colors={ colors } 
                            value={ titleColor }
                            onChange={(value) => { setAttributes({ titleColor: value }) }}
                        />
                        <label className="components-base-control__label">{ __( 'Background Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette 
                            label="Background Color"
                            colors={ bgcolors } 
                            value={ bgColorpalette }
                            onChange={(value) => { setAttributes({ bgColorpalette: value }) }}
                        />
                    </PanelBody>

                    <PanelBody title={__('Cancel Button Style')} initialOpen={false}>
                        <RangeControl
                            label="Font Size"
                            value={cancelfontSize}
                            onChange={(value) => { setAttributes({ cancelfontSize: value }) }}
                            min={5}
                            max={30}
                        />
                        <SelectCancelFontWidthControl />
                        <label className="components-base-control__label">{ __( 'Text Color', 'wp-crowdfunding' ) }</label>
                        <CancelColorPalette />
                        <label className="components-base-control__label">{ __( 'Background Color', 'wp-crowdfunding' ) }</label>
                        <CancelBgColorPalette />
                    </PanelBody>
                </InspectorControls>

                <div className={`wpcf-form-field`}>
                    <div className="wpneo-user-registration-wrap">
                        <form action="" id="wpneo-registration" method="post">
                            <input type="hidden" id="wpcf_form_action_field" name="wpcf_form_action_field" value="" />
                            <input type="hidden" name="_wp_http_referer" value="" />
                            <div className="wpneo-single wpneo-first-half">
                                <div className="wpneo-name">First Name</div>
                                <div className="wpneo-fields">
                                    <input type="text" id="fname" autocomplete="off" className="" name="fname" placeholder="Enter First Name" /> </div>
                            </div>
                            <div className="wpneo-single wpneo-second-half">
                                <div className="wpneo-name">Last Name</div>
                                <div className="wpneo-fields">
                                    <input type="text" id="lname" autocomplete="off" className="" name="lname" placeholder="Enter Last Name" /> </div>
                            </div>
                            <div className="wpneo-single ">
                                <div className="wpneo-name">Username *</div>
                                <div className="wpneo-fields">
                                    <input type="text" id="username" autocomplete="off" className="required" name="username" placeholder="Enter Username" /> </div>
                            </div>
                            <div className="wpneo-single ">
                                <div className="wpneo-name">Password *</div>
                                <div className="wpneo-fields">
                                    <input type="password" id="password" autocomplete="off" className="required" name="password" placeholder="Enter Password" /> </div>
                            </div>
                            <div className="wpneo-single wpneo-first-half">
                                <div className="wpneo-name">Email *</div>
                                <div className="wpneo-fields">
                                    <input type="text" id="email" autocomplete="off" className="required" name="email" placeholder="Enter Email" /> </div>
                            </div>
                            <div className="wpneo-single wpneo-second-half">
                                <div className="wpneo-name">Website</div>
                                <div className="wpneo-fields">
                                    <input type="text" id="website" autocomplete="off" className="" name="website" placeholder="Enter Website" /> </div>
                            </div>
                            <div className="wpneo-single ">
                                <div className="wpneo-name">Nickname</div>
                                <div className="wpneo-fields">
                                    <input type="text" id="nickname" autocomplete="off" className="" name="nickname" placeholder="Enter Nickname" /> </div>
                            </div>
                            <div className="wpneo-single ">
                                <div className="wpneo-name">About / Bio</div>
                                <div className="wpneo-fields">
                                    <textarea id="bio" autocomplete="off" className="" name="bio"></textarea>
                                </div>
                            </div>

                            <div className="wpneo-single wpneo-register">
                                <a href="#" className="wpneo-cancel-campaign" style={CancelBtnStyle}>Cancel</a>
                                <input type="hidden" name="action" value="wpcf_registration" />
                                <input type="hidden" name="current_page" value="" />
                                <input type="submit" className="wpneo-submit-campaign" id="user-registration-btn" value="Sign UP" name="submits" style={btnStyle} />
                            </div>

                        </form>
                    </div>

                </div>

                <style>
                    {`
                        .wpneo-user-registration-wrap .wpneo-name {
                            font-size: ${labelfontSize}px;
                            color: ${labelColor}
                        }
                        .wpneo-fields input[type="number"], .wpneo-fields input[type="text"], .wpneo-fields input[type="email"], .wpneo-fields input[type="password"] {
                            border: 1px solid ${borderColor};
                        }
                        .wpneo-fields input[type="number"], .wpneo-fields input[type="text"], .wpneo-fields input[type="email"], .wpneo-fields input[type="password"] {
                            font-size: ${inputfontSize}px;
                            color: ${inputTextColor}
                        }
                    `}
                </style>

            </Fragment>
        )
    }
}
export default Edit
