import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchRewards } from '../actions/campaignAction';
import ItemReward from '../components/itemReward';
import Pagination from '../components/pagination';

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
            <div className="wpcf-dashboard-content">
                <h3>Rewards</h3>
                <div>
                    <span className={ (filterValue==''? 'active' : '') } onClick={ e => this.onClickFilter( '' ) }>All</span>
                    <span className={ (filterValue=='completed'? 'active' : '') } onClick={ e => this.onClickFilter( 'completed' ) }>Completed</span>
                    <span className={ (filterValue=='remain'? 'active' : '') } onClick={ e => this.onClickFilter( 'remain' ) }>Remain</span>
                    <span className={ (filterValue=='about_to_end'? 'active' : '') } onClick={ e => this.onClickFilter( 'about_to_end' ) }>About To End</span>
                </div>
                <div className="wpcf-dashboard-content-inner">
                    { rewardsData.length ?
                        <div>
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