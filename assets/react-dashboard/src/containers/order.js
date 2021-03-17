import React, { Component, Fragment } from 'react';
import { connect } from 'react-redux';
import { fetchOrders } from '../actions/orderAction';
import Pagination from '../components/pagination';
import ItemOrder from '../components/itemOrder';
import OrderDetails from '../components/orderDetails';
import Header from "../components/header";
import Skeleton from "../components/skeleton";
import ExportCSV from '../components/exportCSV';

class Order extends Component {
	state = {
        pageOfItems: [],
        filterValue: '',
        searchText: '',
        orderDetails: ''
    }

    componentDidMount() {
        const { loaded } = this.props.order;
        if( !loaded ) {
            this.props.fetchOrders();
        }
    }

    onChangePage = (pageOfItems) => {
        this.setState({ pageOfItems });
    }

    onClickDetails = (orderDetails) => {
        this.setState({ orderDetails });
    }

    onClickFilter = (e) => {
        e.preventDefault();
        const filterValue = e.target.innerText.toLowerCase();
        this.setState({ filterValue });
    }

    onChangeInput = (key, value) => {
        this.setState({ [key]: value });
    }

    geOrderLength = (key) => {
        const { data } = this.props.order;
        let orderLength = data.length;
        if( key ) {
            orderLength = data.filter( item => item.details.status == key ).length;
        }
        return orderLength;
    }

    getOrderData = () => {
        const { filterValue, searchText } = this.state;
        const { order } = this.props;
        let filterData = order.data;
        if( filterValue ) {
            filterData = order.data.filter( item => item.details.status == filterValue );
        }
        if( searchText ) {
            filterData = order.data.filter( item =>
                ( item.details.id.toString().search( searchText ) !== -1 ) ||
                ( item.details.status.toLowerCase().search( searchText.toLowerCase()) !== -1 ) ||
                ( item.details.billing.first_name.toLowerCase().search( searchText.toLowerCase()) !== -1 ) ||
                ( item.details.billing.last_name.toLowerCase().search( searchText.toLowerCase()) !== -1 )
            );
        }
        return filterData;
    }

    getCsv() {
        const { data } = this.props.order;
        let csv = [];
        csv.push(['Order', 'Pledge', 'Date', 'Payment', 'Fulfillment']);
        data.map( item => {
            const { details } = item;
            const date = details.formatted_c_date.replace(',', '');
            csv.push([details.id, details.total, date, details.status, details.fulfillment]);
        });
        return csv;
    }

	render() {
        const { order } = this.props;
        if( order.loading ) {
            return (
                <Skeleton />
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
                <Header title={"Order"}></Header>
                <div className="wpcf-dashboard-content-inner">

                    <div className="wpcf-pledge-received-cards">
                        <div className="wpcf-pledge-received-card">
                            <h4 className="wpcf-dashboard-info-val">{this.geOrderLength()}</h4>
                            <span>Total Order</span>
                        </div>
                        <div className="wpcf-pledge-received-card">
                            <h4 className="wpcf-dashboard-info-val">{ this.geOrderLength( 'pending' ) }</h4>
                            <span>Pending Order</span>
                        </div>
                        <div className="wpcf-pledge-received-card">
                            <h4 className="wpcf-dashboard-info-val">{ this.geOrderLength( 'completed' ) }</h4>
                            <span>Completed Order</span>
                        </div>
                    </div>
                    <div className="wpcf-dashboard-search">
                        <div>
                            <input name="searchText" placeholder="Search" onChange={ (e) => this.onChangeInput( 'searchText', e.target.value ) } value={ searchText } />
                        </div>
                        <div>
                            <ExportCSV data={this.getCsv()} file_name="order-list"/>
                        </div>

                    </div>


                    <div className='wpcf-mycampaign-filter-group wpcf-btn-group'>
                        <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='pending' ? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Pending</button>
                        <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='processing'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Processing</button>
                        <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='cancelled'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Cancelled</button>
                        <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='completed'? 'active' : '') } onClick={ e => this.onClickFilter(e) }>Completed</button>
                    </div>


                    { orderData.length ?
                        <Fragment>
                            <table className="wpcf-report-table">
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
                        </Fragment>

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
