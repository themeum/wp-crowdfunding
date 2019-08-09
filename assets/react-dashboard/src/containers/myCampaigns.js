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
        const { loading, data } = this.props.campaign;
        if( loading ) { 
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
                    { data.map( (campaign, index) => 
                        <ItemCampaign key={index} data={campaign} />
                    )}
                    <Pagination items={ data } onChangePage={this.onChangePage} />
                </div>
            </div>
        )
	}
}


const mapStateToProps = state => ({
    campaign: state.campaign
})

export default connect( mapStateToProps, { fetchCampaigns } )(MyCampaigns);