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
                fontSize,
                columns,
                categories,
				order,
				order_by,
                numbers,   
            },
            setAttributes,
        } = this.props

        const { products } = this.props

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

        // Number of column
        const ProductColumControl = withState({
            column: columns,
        })(({ column, setState }) => (
            <SelectControl
                label="Select Column"
                value={column}
                options={[
                    { label: 'One Column', value: '1' },
                    { label: 'Two Column', value: '2' },
                    { label: 'Three Column', value: '3' },
                    { label: 'Four Column', value: '4' },
                ]}
                onChange={(value) => { setAttributes({ columns: value }) }}
            />
        ));

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
        let count = 0;

        return (
            <Fragment>
                <InspectorControls key="inspector">

                    {blockSettings}

                    <PanelBody title={__('Product Style')} initialOpen={false}>
                        <ProductColumControl />
                        <BtnFontSizePicker />
                    </PanelBody>
                </InspectorControls>


                <div className="wpneo-wrapper">
                    <div className="wpneo-container">
                        { (products && products.length) ?
                        <Fragment>
                        { products &&
                            <div className="wpneo-wrapper-inner">
                                { products.map(product => {

                                    output = <div className={`wpneo-listings two col-${columns}`}>
                                        <div className="wpneo-listing-img">
                                            <a href="#" title="">
                                                <img width="300" height="300" src="http://localhost/plugins/cf/wp-content/uploads/woocommerce-placeholder-300x300.png" className="woocommerce-placeholder wp-post-image" alt="Placeholder" />
                                            </a>
                                            <div className="overlay">
                                                <div>
                                                    <div>
                                                        <a href="">View Campaign</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="wpneo-listing-content">
                                            <div className="woocommerce"></div>
                                            <h4><a href="">{product.title.rendered}</a></h4>
                                            <p className="wpneo-author">by
                                                <a href="">Rejuan Ahamed</a>
                                            </p>
                                            <div className="wpneo-location">
                                                <i className="wpneo-icon wpneo-icon-location"></i>
                                                <div className="wpneo-meta-desc">Ment Road 23, Australia</div>
                                            </div>
                                            <p className="wpneo-short-description">Lorem ipsum dolor si...</p>
                                            <div className="wpneo-raised-percent">
                                                <div className="wpneo-meta-name">Raised Percent :</div>
                                                <div className="wpneo-meta-desc">3.33%</div>
                                            </div>
                                            <div className="wpneo-raised-bar">
                                                <div className="neo-progressbar">
                                                    <div>33.33%</div>
                                                </div>
                                            </div>
                                            <div className="wpneo-funding-data">
                                                <div className="wpneo-funding-goal">
                                                    <div className="wpneo-meta-desc"><span className="woocommerce-Price-amount amount"><span className="woocommerce-Price-currencySymbol">$</span>6,000</span>
                                                    </div>
                                                    <div className="wpneo-meta-name">Funding Goal</div>
                                                </div>
                                                <div className="wpneo-time-remaining">
                                                    <div className="wpneo-meta-desc">0</div>
                                                    <div className="wpneo-meta-name float-left">Days to go</div>
                                                </div>
                                                <div className="wpneo-fund-raised">
                                                    <div className="wpneo-meta-desc">
                                                        <span className="woocommerce-Price-amount amount">
                                                        <span className="woocommerce-Price-currencySymbol">$</span>200</span>
                                                    </div>
                                                    <div className="wpneo-meta-name">Fund Raised</div>
                                                </div>
                                            </div>

                                            <a href="" className="btn-details">Campaign Details</a> 
                                            
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
    const { attributes: { numbers, order, order_by, categories } } = props
    
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

