import React, { Component, Fragment } from 'react';
import { connect } from 'react-redux';
import { wcPice } from "../helper";
import { fetchPledgeReceived } from '../actions/campaignAction';
import Pagination from '../components/pagination';
import ItemPledgeReceived from '../components/itemPledgeReceived';
import PledgeDetails from '../components/pledgeDetails';
import Header from '../components/header';
import Skeleton from "../components/skeleton";

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

                <Skeleton />
            )
        };

        const { pageOfItems, openModal, modalData } = this.state;
        const { total_goal, total_raised, total_available, receiver_percent, orders } = pledge.data;

        return (
            <div>
                <Header title="Pledge Recieved" />
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-pledge-received-cards">
                        <div className="wpcf-pledge-received-card">
                            <h4 className="wpcf-dashboard-info-val" dangerouslySetInnerHTML={{__html: wcPice(total_raised)}}/>
                            <span>Fund Raised</span>
                        </div>
                        <div className="wpcf-pledge-received-card">
                            <h4 className="wpcf-dashboard-info-val" dangerouslySetInnerHTML={{__html: wcPice(total_goal)}}/>
                            <span>Goal</span>
                        </div>
                        <div className="wpcf-pledge-received-card">
                            <h4 className="wpcf-dashboard-info-val" dangerouslySetInnerHTML={{__html: wcPice(total_available)}}/>
                            <span>Available</span>
                        </div>
                    </div>

                    { orders.length ?
                        <Fragment>
                            <table className="wpcf-report-table">
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
    pledge: state.pledgeReceived
})

export default connect( mapStateToProps, { fetchPledgeReceived } )(PledgeReceived);
