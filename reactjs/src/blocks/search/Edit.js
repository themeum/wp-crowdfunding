const { __ } = wp.i18n;
const { InspectorControls, RichText } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody,SelectControl } = wp.components;
  
class Edit extends Component {
    constructor(props) {
        super(props)
        this.state = { openPanelSetting: '' };
    }

    render() {
        const {
            setAttributes,
            attributes: {
                layout,
            },
        } = this.props
        
        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title='' initialOpen={true}>
                        <SelectControl
							label={__("Select Layout")}
							value={layout}
							options={[
								{ label: __('Layout 1'), value: 1 },
								{ label: __('Layout 2'), value: 2 },
							]}
							onChange={value => setAttributes({ layout: value })}
						/>
					</PanelBody>
                </InspectorControls>

                <div className={`wpcf-title-inner`}>
                    AAA={layout}
                </div> 
            </Fragment>
        )
    }
}
export default Edit
