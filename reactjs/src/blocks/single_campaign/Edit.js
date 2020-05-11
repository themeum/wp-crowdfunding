const { __ } = wp.i18n;
const { withState } = wp.compose;
const { withSelect } = wp.data
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody, PanelColorSettings, ColorPalette, TextControl, Spinner } = wp.components;

// import { CirclePicker } from 'react-color';
  
class Edit extends Component {
    constructor(props) { 
        super(props)
        this.state = { openPanelSetting: '' };
    }
    
    render() {
        const { 
            setAttributes,
            attributes: { 
                campaignID,
                textColor,
                bgColor
            },  
        } = this.props;

        const { products } = this.props
        
        const textColors = [ 
            { name: 'Green', color: '#94c94a' }, 
            { name: 'White', color: '#fff' }, 
            { name: 'Black', color: '#000' }, 
        ];

        const bgcolors = [
            { name: 'Green', color: '#94c94a' }, 
            { name: 'White', color: '#fff' }, 
            { name: 'Black', color: '#000' }, 
        ];

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

                    <PanelBody title={ __( 'Style' ) } initialOpen={ true }>
                        <label className="components-base-control__label">{ __( 'Text Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label={ __( 'Title Color') }
                            colors={ textColors } 
                            value={ textColor }
                            onChange={ ( value ) => setAttributes( { textColor: value } ) }
                            withTransparentOption
                        />
                        <label className="components-base-control__label">{ __( 'Background Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label={ __( 'Title Color') }
                            colors={ bgcolors } 
                            value={ bgColor }
                            onChange={ ( value ) => setAttributes( { bgColor: value } ) }
                            withTransparentOption
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

                <style>
                    {`
                        .wpneo-list-details .wpneo_donate_button{
                            background-color: ${bgColor}
                        }
                        .tab-rewards-wrapper .overlay {
                            background: ${bgColor}
                        }
                        a.wpneo-fund-modal-btn.wpneo-link-style1, 
                        .wpneo-tabs-menu li.wpneo-current a {
                            color: ${textColor}
                        }
                        .wpneo-tabs-menu li.wpneo-current {
                            border-bottom: 3px solid ${textColor};
                        }
                    `}
                </style>

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

