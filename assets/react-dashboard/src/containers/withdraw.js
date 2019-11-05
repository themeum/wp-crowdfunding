import React, { Component, Fragment } from 'react';
import { connect } from 'react-redux';
import { fetchWithdraws, fetchWithdrawMethods } from '../actions/withdrawAction';
import Pagination from '../components/pagination';
import ItemWithdraw from '../components/itemWithdraw';
import WithdrawDetails from '../components/withdrawDetails';
import Header from '../components/header';
import Skeleton from "../components/skeleton";

class Withdraw extends Component {
	constructor (props) {
        super(props);
        this.state = {
            pageOfItems: [],
            withdrawDetails: ''
        };
        this.onChangePage = this.onChangePage.bind(this);
        this.onClickWithdrawDetails = this.onClickWithdrawDetails.bind(this);
    }

    componentDidMount() {
        const { withdraw, methods } = this.props;
        if( !withdraw.loaded ) {
            this.props.fetchWithdraws();
        }
        if( !methods.loaded ) {
            this.props.fetchWithdrawMethods();
        }
    }

    onChangePage(pageOfItems) {
        this.setState({ pageOfItems });
    }

    onClickWithdrawDetails( data ) {
        this.setState({ withdrawDetails: data });
    }

	render() {
        const { withdraw, methods } = this.props;
        if( withdraw.loading || methods.loading ) {
            return (
                <Skeleton />
            )
        };

        const { pageOfItems, withdrawDetails } = this.state;

        if( withdrawDetails ) {
            return (
                <WithdrawDetails
                    data={ withdrawDetails }
                    methods={ methods.data.selected_method }
                    onClickBack={ this.onClickWithdrawDetails }/>
            );
        }

        return (
            <div>
                <Header title={"Withdraw"}/>
                <div className="wpcf-dashboard-content-inner">
                    { withdraw.data.length ?
                           <Fragment>
                               <table className="wpcf-report-table">
                                   <thead>
                                   <tr>
                                       <td>Project</td>
                                       <td>Goal Complete</td>
                                       <td>Available Currency</td>
                                       <td>Available to Withdraw</td>
                                       <td></td>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   { pageOfItems.map( (item, index) =>
                                       <ItemWithdraw
                                           key={index}
                                           data={ item }
                                           onClickWithdrawDetails={ this.onClickWithdrawDetails } />
                                   ) }
                                   </tbody>
                               </table>

                               <Pagination
                                   items={ withdraw.data }
                                   pageSize={ 5 }
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
    withdraw: state.withdraw,
    methods: state.withdrawMethod
})

export default connect( mapStateToProps, { fetchWithdraws, fetchWithdrawMethods } )(Withdraw);
