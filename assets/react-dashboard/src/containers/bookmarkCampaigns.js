import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchBookmarkCampaigns } from '../actions/campaignAction';
import ItemCampaign from '../components/itemCampaign';
import Pagination from '../components/pagination';

class BookmarkCampaigns extends Component {
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
            this.props.fetchBookmarkCampaigns();
        }
    }

    onChangePage(pageOfItems) {
        this.setState({ pageOfItems });
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

        const { pageOfItems } = this.state;

        return (
            <div>
                <h3>Bookmarks</h3>
                <div className="wpcf-dashboard-content-inner">
                    { campaign.data.length ?
                        <div>
                            { pageOfItems.map( (item, index) =>
                                <ItemCampaign
                                    key={index}
                                    data={ item } >
                                    <div className="wpcf-campaign-links">
                                        {/*TODO: Need Button Working*/}
                                        <button aria-label="Remove Bookmark" title="Remove Bookmark">
                                            <span className="fas fa-trash-alt"></span>
                                        </button>
                                    </div>
                                </ItemCampaign>
                            ) }
                            <Pagination
                                items={ campaign.data }
                                pageSize={ 5 }
                                onChangePage={ this.onChangePage } />
                        </div>
                    :   <div>
                            Campaign not found
                        </div>
                    }

                </div>
            </div>
        )
	}
}

const mapStateToProps = state => ({
    campaign: state.bookmarkCampaign
})

export default connect( mapStateToProps, { fetchBookmarkCampaigns } )(BookmarkCampaigns);
