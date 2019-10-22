import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchOrders } from '../actions/orderAction';
import Pagination from '../components/pagination';
import ItemOrder from '../components/itemOrder';
import OrderDetails from '../components/orderDetails';

class Order extends Component {
	constructor (props) {
        super(props);
        this.state = {
            pageOfItems: [],
            filterValue: '',
            searchText: '',
            orderDetails: ''
        };
        this.onChangePage = this.onChangePage.bind(this);
        this.onClickDetails = this.onClickDetails.bind(this);
    }

    componentDidMount() {
        const { loaded } = this.props.order;
        if( !loaded ) {
            this.props.fetchOrders();
        }
    }

    onChangePage(pageOfItems) {
        this.setState({ pageOfItems });
    }

    onClickDetails( orderDetails ) {
        this.setState({ orderDetails });
    }

    onClickFilter(e) {
        e.preventDefault();
        const filterValue = e.target.innerText.toLowerCase();
        this.setState({ filterValue });
    }

    onChangeInput( key, value ) {
        this.setState({ [key]: value });
    }

    geOrderLength( key ) {
        const { data } = this.props.order;
        let orderLength = data.length;
        if( key ) {
            orderLength = data.filter( item => item.details.status == key ).length;
        }
        return orderLength;
    }

    getOrderData() {
        const { filterValue, searchText } = this.state;
        const { order } = this.props;
        let filterData = order.data;
        if( filterValue ) {
            filterData = order.data.filter( item => item.details.status == filterValue );
        }
        if( searchText ) {
            filterData = order.data.filter( item =>
                ( item.details.billing.first_name.toLowerCase().search( searchText.toLowerCase()) !== -1 ) || 
                ( item.details.billing.last_name.toLowerCase().search( searchText.toLowerCase()) !== -1 )
            );
        }
        return filterData;
    }

	render() {
        const { order } = this.props;
        if( order.loading ) { 
            return (
                <div>
                    Loading...
                </div>
            )
        };

        const { pageOfItems, filterValue, searchText, orderDetails } = this.state;
        const orderData = this.getOrderData();
        
        if( orderDetails ) {
            return (
                <OrderDetails data={ orderDetails } onClickBack={ this.onClickDetails }/>
            )
        }
        
        return (
            <div>
                <h3>Order</h3>
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-dashboard-info-cards">
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span className="wpcf-dashboard-info-val">{this.geOrderLength()}</span>
                                <span>Total Order</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span className="wpcf-dashboard-info-val">{ this.geOrderLength( 'pending' ) }</span>
                                <span>Pending Order</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span className="wpcf-dashboard-info-val">{ this.geOrderLength( 'completed' ) }</span>
                                <span>Completed Order</span>
                            </p>
                        </div>
                    </div>
                    
                    <div className="wpcf-dashboard-search">
                        <div>
                            <input name="searchText" onChange={ (e) => this.onChangeInput( 'searchText', e.target.value ) } value={ searchText } />
                        </div>
                        <div>
                            <span className={ (filterValue=='pending' ? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Pending</span>
                            <span className={ (filterValue=='processing'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Processing</span>
                            <span className={ (filterValue=='cancelled'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Cancelled</span>
                            <span className={ (filterValue=='completed'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Completed</span>
                        </div>
                    </div>

                    { orderData.length ?
                        <div className="wpcf-dashboard-info-table-wrap">
                            <table className="wpcf-dashboard-info-table">
                                <thead>
                                    <tr>
                                        <td>Order</td>
                                        <td>Name</td>
                                        <td>Pledge</td>
                                        <td>Date</td>
                                        <td>Payment</td>
                                        <td>Fulfillment</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    { pageOfItems.map( (item, index) =>
                                        <ItemOrder
                                            key={index}
                                            data={ item }
                                            onClickDetails={ this.onClickDetails } />
                                    ) }
                                </tbody>
                            </table>

                            <Pagination
                                items={ orderData }
                                pageSize={ 5 }
                                filterValue={ filterValue }
                                onChangePage={ this.onChangePage } />
                        </div>

                    :   <div>
                            Data not found
                        </div>
                    }
                        
                </div>
            </div>
        )
	}
}

const mapStateToProps = state => ({
    order: state.order
})

export default connect( mapStateToProps, { fetchOrders } )(Order);