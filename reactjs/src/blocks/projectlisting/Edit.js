const { __ } = wp.i18n;
const { withState } = wp.compose;
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody,SelectControl, ColorPalette, RangeControl, TextControl, TextareaControl } = wp.components;

  
class Edit extends Component {
    constructor(props) { 
        super(props)
        this.state = { openPanelSetting: '' };
    }
    
    render() {
        const {
            setAttributes,
            attributes: {
                fontSize,
            },
        } = this.props

        

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

        

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title={__('Button Style')} initialOpen={false}>
                        <BtnFontSizePicker />
                    </PanelBody>
                </InspectorControls>

                <div className={`wpcf-form-field`}>
                    <div className="wpcf-donate-form-wrap">
                        AAA
                    </div>
                </div>

            </Fragment>
        )
    }
}
export default Edit
