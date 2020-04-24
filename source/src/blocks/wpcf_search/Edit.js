const { __ } = wp.i18n;
const { InspectorControls, RichText } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody,SelectControl } = wp.components;


  
class Edit extends Component {
    constructor(props) {
        super(props)
        this.state = { device: 'md', selector: true, spacer: true, openPanelSetting: '' };
    }
    componentDidMount() {
        const { setAttributes, clientId, attributes: { uniqueId } } = this.props
        const _client = clientId.substr(0, 6)
        if (!uniqueId) {
            setAttributes({ uniqueId: _client });
        } else if (uniqueId && uniqueId != _client) {
            setAttributes({ uniqueId: _client });
        }
    }

    handlePanelOpenings = (panelName) => {
        this.setState({ ...this.state, openPanelSetting: panelName })
    }

    render() {
        const {
            setAttributes,
            attributes: {
                layout,
            },
        } = this.props

        const { device } = this.state

        
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
                    <div>
                        AAA
                    </div>
                </div> 
            </Fragment>
        )
    }
}
export default Edit
