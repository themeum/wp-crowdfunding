const { __ } = wp.i18n;
const { withState } = wp.compose;
const { withSelect } = wp.data
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody, ColorPalette, Spinner } = wp.components;

  
class Edit extends Component {
    constructor(props) { 
        super(props)
        this.state = { openPanelSetting: '' };
    }
    
    render() {
        const {
            setAttributes,
            attributes: {
                majorColorpalette,
                titlecolor
            },
        } = this.props

        const { products } = this.props

        // Major Color 
        const MajorColorPalette = withState( {
            bgcolor: majorColorpalette,
        } )( ( { bgcolor, setState } ) => { 
            const bgcolors = [ 
                { name: 'Color Code: #1adc68', color: '#1adc68' }, 
                { name: 'Color Code: #8224e3', color: '#8224e3' }, 
                { name: 'Dark Black', color: '#111111' }, 
                { name: 'Light Gray', color: '#767676' }, 
                { name: 'White', color: '#ffffff' }, 
            ];
            
            return ( 
                <ColorPalette 
                    label="Background Color"
                    colors={ bgcolors } 
                    value={ bgcolor }
                    onChange={(value) => { setAttributes({ majorColorpalette: value }) }}
                />
            ) 
        } );

        // Title Color Color 
        const TextColorPalette = withState( {
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

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title={__('')} initialOpen={true}>
                        <label className="components-base-control__label">{ __( 'Major Color', 'wp-crowdfunding' ) }</label>
                        <MajorColorPalette />
                        <label className="components-base-control__label">{ __( 'Text Color', 'wp-crowdfunding' ) }</label>
                        <TextColorPalette />
                    </PanelBody>
                </InspectorControls>

                <div className={`wpcf-dashboard`}>
                    { (products && products.length) ?
                        <Fragment>
                        { products &&
                            <div className="wpneo-wrapper-inner">
                                { products.map(product => {
                                    return <div dangerouslySetInnerHTML={{__html: product.wpcf_dashboard}} />
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
                        .wpcf-dashboard .wp-crowd-btn-primary, .wpcf-dashboard .wpneo-dashboard-summary ul li.active,
                        .wpcf-dashboard .wpneo-edit-btn, .wpcf-dashboard .wpneo-pagination ul li span.current, .wpneo-pagination ul li a:hover, .wpneo-pagination ul li span.current {
                            background-color: ${majorColorpalette}
                        }
                        .wpneo-links div.active a, .wpneo-links div a:hover, 
                        .wpcf-dashboard .wpneo-name > p, .wpcf-dashboard .wpcrowd-listing-content .wpcrowd-admin-title h3 a{
                            color: ${majorColorpalette}
                        }

                        .wpneo-links div a.wp-crowd-btn.wp-crowd-btn-primary, .wpneo-links div a.wp-crowd-btn.wp-crowd-btn-primary:hover, .wpcf-dashboard .wp-crowd-btn-primary, .wpcf-dashboard .wpneo-pagination ul li span.current, .wpneo-pagination ul li a:hover, .wpneo-pagination ul li span.current {
                            color: ${titlecolor}
                        }
                    `}
                </style>

            </Fragment>
        )
    }
}

export default withSelect((select, props) => {
    return {
        products: select( 'core' ).getEntityRecords( 'postType', 'product', { per_page: 1, status: 'publish',
        _embed: true, } ),
    };
})

(Edit)

