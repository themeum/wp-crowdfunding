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
                formSize,
                bgColorpalette,
                titlecolor,
                fontSize,
                fontWeight,
                SearchfontSize
            },
        } = this.props

        // Search Font size.
        const InputFontSizePicker = withState({
            fontSize: SearchfontSize,
        })(({ fontSize, setState }) => (
            <RangeControl
                label="Font Size"
                value={fontSize}
                onChange={(value) => { setAttributes({ SearchfontSize: value }) }}
                min={5}
                max={20}
            />
        ));

        // Background Color 
        const BgColorPalette = withState( {
            bgcolor: bgColorpalette,
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
                    onChange={(value) => { setAttributes({ bgColorpalette: value }) }}
                />
            ) 
        } );

        // Title Color Color 
        const TitleColorPalette = withState( {
            color: titlecolor,
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
                    onChange={(value) => { setAttributes({ titlecolor: value }) }}
                />
            ) 
        } );

        // Font size.
        const BtnFontSizePicker = withState({
            fontSize: fontSize,
        })(({ fontSize, setState }) => (
            <RangeControl
                label="Font Size"
                value={fontSize}
                onChange={(value) => { setAttributes({ fontSize: value }) }}
                min={5}
                max={30}
            />
        ));

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
            color: titlecolor,
            fontSize: fontSize,
            fontWeight: fontWeight,
        }
        const searchStyle = {
            fontSize: SearchfontSize,
        }

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title='' initialOpen={true}>
                        <SelectControl
							label={__("Form Size")}
							value={formSize}
							options={[
								{ label: __('Small'), value: 'small' },
								{ label: __('Medium'), value: 'medium' },
                                { label: __('Full'), value: 'full' },
							]}
							onChange={value => setAttributes({ formSize: value })}
						/>
                        <InputFontSizePicker />
					</PanelBody>

                    <PanelBody title={__('Button Style')} initialOpen={false}>
                        <BtnFontSizePicker />
                        <SelectFontWidthControl />
                        <label className="components-base-control__label">{ __( 'Text Color', 'wp-crowdfunding' ) }</label>
                        <TitleColorPalette />
                        <label className="components-base-control__label">{ __( 'Background Color', 'wp-crowdfunding' ) }</label>
                        <BgColorPalette />
                    </PanelBody>
                </InspectorControls>

                <div className={`wpcf-form-field ${formSize}`}>
                    <form role="search" method="get" action="">
                        <input type="search" className="search-field" placeholder="Search" style={searchStyle}/>
                        <input type="hidden" name="post_type" value="product" />
                        <input type="hidden" name="product_type" value="croudfunding" />
                        <button type="button" style={btnStyle}>Search</button>
                    </form>
                </div>

            </Fragment>
        )
    }
}
export default Edit
