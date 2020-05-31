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
                titleColor,
                fontSize,
                fontWeight,
                SearchfontSize,
                formAlign
            },
        } = this.props

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
        const searchStyle = {
            fontSize: SearchfontSize,
        }

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title='' initialOpen={true}>
                        <SelectControl
							label={__("Alignment")}
							value={formAlign}
							options={[
								{ label: __('Left'), value: 'left' },
								{ label: __('Right'), value: 'right' },
                                { label: __('Center'), value: 'center' },
							]}
							onChange={value => setAttributes({ formAlign: value })}
						/>
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
                        <RangeControl
                            label="Font Size"
                            value={SearchfontSize}
                            onChange={(value) => { setAttributes({ SearchfontSize: value }) }}
                            min={5}
                            max={20}
                        />
					</PanelBody>

                    <PanelBody title={__('Button Style')} initialOpen={false}>
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
                </InspectorControls>

                <div className={`wpcf-form-field ${formSize} ${formAlign}`}>
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
