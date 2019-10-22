import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchPledgeReceived } from '../actions/campaignAction';
import Pagination from '../components/pagination';
import ItemPledgeReceived from '../components/itemPledgeReceived';
import PledgeDetails from '../components/pledgeDetails';

class PledgeReceived extends Component {
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
        const { loaded } = this.props.pledge;
        if( !loaded ) {
            this.props.fetchPledgeReceived();
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
        const { pledge } = this.props;
        if( pledge.loading ) { 
            return (
                <div>
                    Loading...
                </div>
            )
        };

        const { pageOfItems, openModal, modalData } = this.state;
        const { total_goal, total_raised, total_available, receiver_percent, orders } = pledge.data;
        
        return (
            <div>
                <h3>Pledge Recieved</h3>
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-dashboard-info-cards">
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span className="wpcf-dashboard-info-val" dangerouslySetInnerHTML={{__html: total_raised}}></span>
                                <span>Fund Raised</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span className="wpcf-dashboard-info-val" dangerouslySetInnerHTML={{__html: total_goal}}></span>
                                <span>Goal</span>
                            </p>
                        </div>
                        <div className="wpcf-dashboard-info-card">
                            <p>
                                <span className="wpcf-dashboard-info-val" dangerouslySetInnerHTML={{__html: total_available}}></span>
                                <span>Available</span>
                            </p>
                        </div>
                    </div>

                    { orders.length ?
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
                                        <ItemPledgeReceived
                                            key={index}
                                            data={ item }
                                            onClickDetails={ this.onClickDetails } />
                                    ) }
                                </tbody>
                            </table>

                            <Pagination
                                items={ orders }
                                pageSize={ 5 }
                                onChangePage={ this.onChangePage } />

                            { openModal && 
                                <PledgeDetails 
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
    pledge: state.pledgeReceived
})

export default connect( mapStateToProps, { fetchPledgeReceived } )(PledgeReceived);