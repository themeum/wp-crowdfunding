const { __ } = wp.i18n;
const { withState } = wp.compose;
const { withSelect } = wp.data
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody, ColorPalette, TextControl, Spinner } = wp.components;

  
class Edit extends Component {
    constructor(props) { 
        super(props)
        this.state = { openPanelSetting: '' };
    }
    
    render() {
        const { 
            setAttributes,
            attributes: { 
                campaignID 
            },  
        } = this.props;

        const { products } = this.props

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title={ __( '' ) } initialOpen={ true }>
                        <TextControl
                            label={ __( 'Campaign ID' ) }
                            value={ campaignID }
                            onChange={ ( value ) => setAttributes( { campaignID: value } ) }
                        />
                    </PanelBody>
                </InspectorControls>

                <div className={`wpcf-dashboard`}>
                    { (products && products.length) ?
                        <Fragment>
                        { products &&
                            <div className="wpneo-wrapper-inner">
                                { products.map(product => {
                                    if( product.id == campaignID) {
                                        return <div className="woocommerce" dangerouslySetInnerHTML={{__html: product.wpcf_single_campaign}} />
                                    }
                                })}
                            </div>
                        }
                        </Fragment>
                    :
                    <div className="wpcf-products-is-loading">
                        <Spinner />
                    </div>
                    }
                </div>

            </Fragment>
        )
    }
}

export default withSelect((select, props) => {
    return {
        products: select( 'core' ).getEntityRecords( 'postType', 'product', { per_page: -1, status: 'publish',
        _embed: true, } ),
    };
})

(Edit)

