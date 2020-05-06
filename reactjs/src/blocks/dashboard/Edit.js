const { __ } = wp.i18n;
const { withSelect } = wp.data
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { SelectControl, RangeControl, Spinner } = wp.components;

  
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
            },
        } = this.props

        const { products } = this.props

        return (
            <Fragment>
                <div className={`wpcf-form-field ${formSize}`}>
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

            </Fragment>
        )
    }
}

export default withSelect((select, props) => {
    return {
        products: select( 'core' ).getEntityRecords( 'postType', 'product' ),
    };
})

(Edit)

