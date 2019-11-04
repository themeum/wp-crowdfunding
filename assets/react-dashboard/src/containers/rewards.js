import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchRewards } from '../actions/campaignAction';
import ItemReward from '../components/itemReward';
import Pagination from '../components/pagination';
import Header from "../components/header";

class Rewards extends Component {
	constructor (props) {
        super(props);
        this.state = {
            pageOfItems: [],
            filterValue: ''
        };
        this.onChangePage = this.onChangePage.bind(this);
    }

    componentDidMount() {
        const { loaded } = this.props.reward;
        if( !loaded ) {
            this.props.fetchRewards();
        }
    }

    onChangePage(pageOfItems) {
        this.setState({ pageOfItems });
    }

    onClickFilter(filterValue) {
        this.setState({ filterValue });
    }

    getRewardsData() {
        const { filterValue } = this.state;
        const { reward } = this.props;
        let filterData = reward.data;
        if( filterValue ) {
            filterData = reward.data.filter( item => item.status == filterValue );
        }
        return filterData;
    }

	render() {
        const { reward } = this.props;
        if( reward.loading ) {
            return (
                <div>
                    Loading...
                </div>
            )
        };

        const { pageOfItems, filterValue } = this.state;
        const rewardsData = this.getRewardsData();

        return (
            <div>
                <Header title={"Rewards"}></Header>
                <div className='wpcf-mycampaign-filter-group wpcf-btn-group'>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue==''? 'active' : '') } onClick={ e => this.onClickFilter('') }>All</button>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='completed'? 'active' : '') } onClick={ e => this.onClickFilter('completed') }>Completed</button>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='remain'? 'active' : '') } onClick={ e => this.onClickFilter('remain') }>Remain</button>
                    <button className={ "wpcf-btn wpcf-btn-outline wpcf-btn-round wpcf-btn-secondary " + (filterValue=='about_to_end'? 'active' : '') } onClick={ e => this.onClickFilter('about_to_end') }>About To End</button>
                </div>

                <div className="wpcf-dashboard-content-inner">
                    { rewardsData.length ?
                        <div className="wpcf-rewards">
                            { pageOfItems.map( (item, index) =>
                                <ItemReward
                                    key={index}
                                    data={ item } />
                            ) }
                            <Pagination
                                items={ rewardsData }
                                pageSize={ 5 }
                                filterValue={ filterValue }
                                onChangePage={ this.onChangePage } />
                        </div>
                    :   <div>
                            Reward not found
                        </div>
                    }

                </div>
            </div>
        )
	}
}

const mapStateToProps = state => ({
    reward: state.reward
})

export default connect( mapStateToProps, { fetchRewards } )(Rewards);
