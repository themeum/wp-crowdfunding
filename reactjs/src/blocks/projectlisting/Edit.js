const { __ } = wp.i18n;
const { apiFetch } = wp;
const { withSelect } = wp.data
const { withState } = wp.compose;
const { InspectorControls } = wp.editor;
const { Component, Fragment } = wp.element;
const { PanelBody,SelectControl, ColorPalette, RangeControl, TextControl, TextareaControl, Spinner } = wp.components;
 
class Edit extends Component {
    constructor(props) {
        super(props)
        this.state = {
            categories: []
        }
    }

    componentDidMount() {
        const { posts } = this.props
        // this.getFeaturedImages(posts)
        let postSelections = [];

        

        wp.apiFetch({ path: "/wc/v2/products/categories" }).then(posts => {
            postSelections.push({ label: "Select All Category", value: 'all' });
            $.each(posts, function (key, val) {
                postSelections.push({ label: val.name, value: val.id });
            });
            return postSelections;
        });
        this.setState({ categories: postSelections })

       
    }

    
    // componentDidUpdate(prevProps, prevState) {
    //     const { posts } = this.props
    //     if (posts != prevProps.posts) {
    //         this.getFeaturedImages(posts)
    //     }
    // }
    // getFeaturedImages = (posts) => {
    //     if (posts) {
    //         posts.forEach(post => {
    //             post._links["wp:featuredmedia"] && apiFetch({
    //                 method: 'POST',
    //                 url: post._links["wp:featuredmedia"][0].href,
    //                 headers: { 'Content-Type': 'application/json' }
    //             }).then(response => {
    //                 this.setState({
    //                     ...this.state,
    //                     [post.id]: response.source_url
    //                 })
    //             }).catch(error => {
    //                 console.log('error : ', error)
    //             })
    //         })
    //     }

    //     console.log('DD ', this.state.categories)
    // }

    render() {
        const {
            setAttributes,
            attributes: {
                fontSize,
                columns,
                numbers,
                orderby,
                selectedCategory
            },
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

        // Number of post.
        const NumberProductControl = withState({
            numbers: numbers,
        })(({ numbers, setState }) => (
            <RangeControl
                label="Number Of Product"
                value={numbers}
                onChange={(value) => { setAttributes({ numbers: value }) }}
                min={1}
                max={40}
            />
        ));
        const ProductPostOrder = withState({
            postorder: orderby,
        })(({ postorder, setState }) => (
            <SelectControl
                label="Post Order"
                value={postorder}
                options={[
                    { label: 'ASC', value: 'asc' },
                    { label: 'DESC', value: 'desc' },
                ]}
                onChange={(value) => { setAttributes({ orderby: value }) }}
            />
        ));

        let output = '';
        let count = 0;

        return (
            <Fragment>
                <InspectorControls key="inspector">

                    <PanelBody initialOpen={true}>
                        <SelectControl
                            value={selectedCategory}
                            options={this.state.categories}
                            onChange={(value) => setAttributes({ selectedCategory: value })}
                        />
                        <ProductPostOrder /> 
                        <NumberProductControl />
                        
                    </PanelBody>

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
                        <div className="qubely-postgrid-is-loading">
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
    const { attributes: { numbers, orderby, selectedCategory } } = props
    const { getEntityRecords } = select('core')
    const output = (selectedCategory == 'all') ? 
    ({products: getEntityRecords('postType', 'product', { per_page: numbers, order: orderby, status: 'publish', } )}) : 
    ({products: getEntityRecords('postType', 'product', { per_page: numbers, order: orderby, status: 'publish', } )})

    return output; 
})

(Edit)

