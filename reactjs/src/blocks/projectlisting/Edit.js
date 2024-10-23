const { __ } = wp.i18n;
const { apiFetch } = wp;
const { withSelect } = wp.data
const { addQueryArgs } = wp.url;
const { withState } = wp.compose;
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody, Spinner, SelectControl, RangeControl, ColorPalette } = wp.components;
 
class Edit extends Component {

    constructor(props) { 
        super(props)
        this.state = { openPanelSetting: '' };
    }

    render() {
        const {
            attributes: {
				order,
				order_by,
                numbers, 
                mjColor,  
                progressbarColor,
                authorColor
            },
            setAttributes,
            products
        } = this.props

		// Major Color 
        const mjColors = [ 
            { name: 'Color Code: var(--wpcf-primary-color)', color: 'var(--wpcf-primary-color)' }, 
            { name: 'Color Code: #8224e3', color: '#8224e3' }, 
            { name: 'Dark Black', color: '#111111' }, 
            { name: 'Light Gray', color: '#767676' }, 
            { name: 'White', color: '#ffffff' }, 
        ];

        // Title Color Color 
        const progressbarColors = [ 
            { name: 'Color Code: var(--wpcf-primary-color)', color: 'var(--wpcf-primary-color)' }, 
            { name: 'Color Code: #8224e3', color: '#8224e3' }, 
            { name: 'Dark Black', color: '#111111' },
        ];
        const authorColors = [ 
            { name: 'Gray', color: '#ccc' }, 
            { name: 'White', color: '#737373' }, 
            { name: 'Black', color: '#000' }, 
        ];

        let output = '';

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    <PanelBody title={ __() } initialOpen={ true }>
                        <SelectControl
                            label={__('Post Order')}
                            value={order}
                            options={[
                                { label: 'ASC', value: 'asc' },
                                { label: 'DESC', value: 'desc' },
                            ]}
                            onChange={(value) => { setAttributes({ order: value }) }}
                        />
                        <SelectControl
                            label={__('Post Orderby')}
                            value={order_by}
                            options={[
                                { label: 'Date', value: 'date' },
                                { label: 'Title', value: 'title' },
                                { label: 'Modified', value: 'modified' },
                            ]}
                            onChange={(value) => { setAttributes({ order_by: value }) }}
                        />
                        <RangeControl
                            label={__('Number Of Post')}
                            value={numbers}
                            onChange={(value) => { setAttributes({ numbers: value }) }}
                            min={1}
                            max={20}
                        />
                    </PanelBody>
                
                    <PanelBody title={ __( 'Style', 'wp-crowdfunding' ) } initialOpen={ false }>
                        <label className="components-base-control__label">{ __( 'Major Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label={ __( 'Major Color') }
                            colors={ mjColors } 
                            value={ mjColor }
                            onChange={ ( value ) => setAttributes( { mjColor: value } ) }
                            withTransparentOption
                        />

                        <label className="components-base-control__label">{ __( 'Author Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label={ __( 'Author Color') }
                            colors={ authorColors } 
                            value={ authorColor }
                            onChange={ ( value ) => setAttributes( { authorColor: value } ) }
                            withTransparentOption
                        />

                        <label className="components-base-control__label">{ __( 'Progressbar Color', 'wp-crowdfunding' ) }</label>
                        <ColorPalette
                            label={ __( 'Progressbar Color') }
                            colors={ progressbarColors } 
                            value={ progressbarColor }
                            onChange={ ( value ) => setAttributes( { progressbarColor: value } ) }
                            withTransparentOption
                        />
                    </PanelBody>
                </InspectorControls>

                <div className="wpneo-wrapper">
                    <div className="wpneo-container">
                        { (products && products.length) ?
                        <Fragment>
                        { products &&
                            <div className="wpneo-wrapper-inner">
                                { products.map(product => {
                                    { product.wpcf_project_listing.map( campaign => {
                                        if(campaign.ID == product.id) {
                                            output = <div className={`wpneo-listings two col-${product.column}`}>
                                                <div className="wpneo-listing-img">
                                                    <div dangerouslySetInnerHTML={{__html: product.wpcf_product.product_thumb}} />
                                                    <div className="overlay">
                                                        <div>
                                                            <div>
                                                                <a href="#">View Campaign</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div className="wpneo-listing-content">
                                                    <div className="woocommerce"></div>
                                                    <h4><a href="#">{product.title.rendered}</a></h4>
                                                    <p className="wpneo-author">by
                                                        <a href="#"> {product.wpcf_product.display_name}</a>
                                                    </p>
                                                    <div className="wpneo-location">
                                                        <i className="wpneo-icon wpneo-icon-location"></i>
                                                        <div className="wpneo-meta-desc">{product.wpcf_product.location}</div>
                                                    </div>
                                                    <p className="wpneo-short-description">{product.wpcf_product.desc}</p>
                                                    <div className="wpneo-raised-percent">
                                                        <div className="wpneo-meta-name">Raised Percent :</div>
                                                        <div className="wpneo-meta-desc">{product.wpcf_product.raised_percent}</div>
                                                    </div>
                                                                                                
                                                    <div className="wpneo-raised-bar">
                                                        <div id="neo-progressbar">
                                                            <div style={{width: product.wpcf_product.raised_percent}}></div>
                                                        </div>
                                                    </div>

                                                    <div className="wpneo-funding-data">
                                                        <div className="wpneo-funding-goal">
                                                            <div className="wpneo-meta-desc"><span className="woocommerce-Price-amount amount"><span className="woocommerce-Price-currencySymbol">$</span>{product.wpcf_product.funding_goal}</span>
                                                            </div>
                                                            <div className="wpneo-meta-name">Funding Goal</div>
                                                        </div>
                                                        <div className="wpneo-time-remaining">
                                                            <div className="wpneo-meta-desc">{product.wpcf_product.days_remaining}</div>
                                                            <div className="wpneo-meta-name float-left">Days to go</div>
                                                        </div>
                                                        <div className="wpneo-fund-raised">
                                                            <div className="wpneo-meta-desc">
                                                                <span className="woocommerce-Price-amount amount">
                                                                <span className="woocommerce-Price-currencySymbol">$</span>{product.wpcf_product.total_raised}</span>
                                                            </div>
                                                            <div className="wpneo-meta-name">Fund Raised</div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        }
                                    })}
                                    return output
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
                </div>

                <style>
                    {`
                        #neo-progressbar > div, ul.wpneo-crowdfunding-update li:hover span.round-circle, .wpneo-links li a:hover, .wpneo-links li.active a, #neo-progressbar > div {
                            background-color: ${progressbarColor}
                        }
                        .wpneo-funding-data span, .wpneo-time-remaining .wpneo-meta-desc, .wpneo-funding-goal .wpneo-meta-name, .wpneo-raised-percent, .wpneo-listing-content p.wpneo-short-description, .wpneo-location .wpneo-meta-desc, .wpneo-listings .wpneo-listing-content h4 a, .wpneo-fund-raised, .wpneo-time-remaining{
                            color: ${mjColor}
                        }
                        .wpneo-listings .wpneo-listing-content .wpneo-author a, .wpneo-listings .wpneo-listing-content p.wpneo-author {
                            color: ${authorColor}
                        }
                    `}
                </style>

            </Fragment>
        )
    }
}


export default withSelect((select, props) => {
    const { attributes: { numbers, order, order_by } } = props
    const { getEntityRecords } = select( 'core' );

    return {
        products: getEntityRecords( 'postType', 'product', {per_page: numbers, order: order, orderby: order_by, status: 'publish'} ),
    }

})

(Edit)
