import { isUndefined, pickBy } from 'lodash';

const { __ } = wp.i18n;
const { apiFetch } = wp;
const { withSelect } = wp.data
const { addQueryArgs } = wp.url;
const { withState } = wp.compose;
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody,SelectControl, RangeControl, Spinner, QueryControls } = wp.components;
 
class Edit extends Component {

    constructor() {
		super( ...arguments );
		this.state = {
			categoriesList: [],
		}
    }
    
    componentWillMount() {
		this.isStillMounted = true;
		this.fetchRequest = apiFetch( {
			path: addQueryArgs( `/wc/v2/products/categories`, {
				tax: 'product_cat'
			}),
		} ).then(
			categoriesList => {
				if ( this.isStillMounted ) {
					this.setState( { categoriesList } );
				}
			}
		).catch(
			() => {
				if ( this.isStillMounted ) {
					this.setState( { categoriesList: [] } );
				}
			}
		)
    }
    
    componentWillUnmount() {
		this.isStillMounted = false;
	}

    render() {
        const {
            attributes: {
                categories,
				order,
				order_by,
                numbers,   
            },
            setAttributes,
        } = this.props

        const { products } = this.props

        const { categoriesList } = this.state;
		const blockSettings = (
            <PanelBody title={ __( 'Query Product', 'wp-crowdfunding' ) } initialOpen={ true }>
                <QueryControls
                    { ...{ order, order_by } }
                    numberOfItems={ numbers }
                    categoriesList={ categoriesList }
                    selectedCategoryId={ categories }
                    onOrderChange={ order => setAttributes( { order } ) }
                    onOrderByChange={ order_by => setAttributes( { order_by } ) }
                    onCategoryChange={ value => setAttributes( { categories: '' !== value ? value : undefined } ) }
                    onNumberOfItemsChange={ numbers => setAttributes( { numbers } ) }
                />
            </PanelBody>
		);

        let output = '';

        return (
            <Fragment>
                <InspectorControls key="inspector">
                    {blockSettings}
                </InspectorControls>

                <div className="wpneo-wrapper">
                    <div className="wpneo-container">
                        { (products && products.length) ?
                        <Fragment>
                        { products &&
                            <div className="wpneo-wrapper-inner">
                                { products.map(product => {

                                    console.log('A', product)

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
                                            <h4><a href="">{product.title.rendered}</a></h4>
                                            <p className="wpneo-author">by
                                                <a href=""> {product.wpcf_product.display_name}</a>
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

            </Fragment>
        )
    }
}


export default withSelect((select, props) => {
    const { attributes: { numbers, order, categories } } = props
    
    const { getEntityRecords } = select( 'core' );

    const ProductsQuery = pickBy({
        categories,
        order,
        per_page: numbers,
        status: 'publish',
        _embed: true,
    }, value => ! isUndefined( value ) );

    return {
        products: getEntityRecords( 'postType', 'product', ProductsQuery ),
    }
})

(Edit)

