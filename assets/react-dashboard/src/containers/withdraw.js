import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchWithdraws } from '../actions/orderAction';
import Pagination from '../components/pagination';
import ItemWithdraw from '../components/itemWithdraw';
import WithdrawModal from '../components/withdrawModal';

class Withdraw extends Component {
	constructor (props) {
        super(props);
        this.state = {
            pageOfItems: [],
            openModal: false,
            modalData: ''
        };
        this.onChangePage = this.onChangePage.bind(this);
        this.onClickDetails = this.onClickDetails.bind(this);
        this.onClickModalClose = this.onClickModalClose.bind(this);
    }

    componentDidMount() {
        const { loaded } = this.props.withdraw;
        if( !loaded ) {
            this.props.fetchWithdraws();
        }
    }

    onChangePage(pageOfItems) {
        this.setState({ pageOfItems });
    }

    onClickDetails( data ) {
        this.setState({ openModal: true, modalData: data });
    }

    onClickModalClose() {
        this.setState({ openModal: false });
    }

	render() {
        const { withdraw } = this.props;
        if( withdraw.loading ) { 
            return (
                <div>
                    Loading...
                </div>
            )
        };

        const { pageOfItems, openModal, modalData } = this.state;
        
        return (
            <div className="wpcf-dashboard-content">
                <h3>withdraw Recieved</h3>
                <div className="wpcf-dashboard-content-inner">
                    { withdraw.data.length ?
                        <div className="wpcf-dashboard-info-table-wrap">
                            <table className="wpcf-dashboard-info-table">
                                <thead>
                                    <tr>
                                        <td>Name</td>
                                        <td>Raised</td>
                                        <td>Receivable { receiver_percent && `(${receiver_percent}%)` }</td>
                                        <td>Marketplace { receiver_percent && `(${100-receiver_percent}%)` }</td>
                                        <td>Status</td>
                                        <td>Email</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    { pageOfItems.map( (item, index) =>
                                        <ItemWithdraw
                                            key={index}
                                            data={ item }
                                            onClickDetails={ this.onClickDetails } />
                                    ) }
                                </tbody>
                            </table>

                            <Pagination
                                items={ withdraw.data }
                                pageSize={ 5 }
                                onChangePage={ this.onChangePage } />

                            { openModal && 
                                <WithdrawModal 
                                    data={ modalData }
                                    onClickModalClose={ this.onClickModalClose }/>
                            }
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
    withdraw: state.withdraw
})

export default connect( mapStateToProps, { fetchWithdraws } )(Withdraw);