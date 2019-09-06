import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchRewards } from '../actions/campaignAction';
import ItemReward from '../components/itemReward';
import Pagination from '../components/pagination';

class Reward extends Component {
	constructor (props) {
        super(props);
        this.state = {
            pageOfItems: [],
            filterValue: 'running'
        };
        this.onChangePage = this.onChangePage.bind(this);
    }

    componentDidMount() {
        const { loaded } = this.props.campaign;
        if( !loaded ) {
            this.props.fetchMyCampaigns();
        }
    }

    onChangePage(pageOfItems) {
        this.setState({ pageOfItems });
    }

    onClickFilter(e) {
        e.preventDefault();
        const filterValue = e.target.innerText.toLowerCase();
        this.setState({ filterValue });
    }

    getCampaignData() {
        const { filterValue } = this.state;
        const { campaign } = this.props;
        const filterData = campaign.data.filter( item => item.status == filterValue );
        return filterData;
    }

	render() {
        const { campaign } = this.props;
        if( campaign.loading ) { 
            return (
                <div>
                    Loading...
                </div>
            )
        };

        const { pageOfItems, filterValue } = this.state;
        const campaignData = this.getCampaignData();
        
        return (
            <div className="wpcf-dashboard-content">
                <h3>Rewards</h3>
                <div className="wpcf-dashboard-content-inner">
                    { campaignData.length ?
                        <div>
                            { pageOfItems.map( (item, index) =>
                                <ItemReward 
                                    key={index} 
                                    data={ item } />
                            ) }
                            <Pagination
                                items={ campaignData }
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
    campaign: state.myCampaign
})

export default connect( mapStateToProps, { fetchRewards } )(Reward);