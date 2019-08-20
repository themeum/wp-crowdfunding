import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchCampaigns } from '../actions/campaignAction';
import ItemCampaign from '../components/itemCampaign';
import Pagination from '../components/pagination';

class MyCampaigns extends Component {
	constructor (props) {
        super(props);
        this.state = {
            pageOfItems: []
        };
        this.onChangePage = this.onChangePage.bind(this);
    }

    componentDidMount() {
        const { loaded } = this.props.campaign;
        if( !loaded ) {
            this.props.fetchCampaigns();

        }
    }

    onChangePage(pageOfItems) {
        // update state with new page of items
        this.setState({ pageOfItems: pageOfItems });
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
        
        return (
            <div className="wpcf-dashboard-content">
                <h3>My Campaigns</h3>
                <div className="wpcf-dashboard-content-inner">
                    {this.state.pageOfItems.map( (item, index) =>
                        <ItemCampaign key={index} data={ item } />
                    )}
                    <Pagination items={ campaign.data } pageSize={ 5 } onChangePage={this.onChangePage} />
                </div>
            </div>
        )
	}
}


const mapStateToProps = state => ({
    campaign: state.campaign
})

export default connect( mapStateToProps, { fetchCampaigns } )(MyCampaigns);