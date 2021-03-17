import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { fetchBookmarkCampaigns, deleteCampaign } from '../actions/campaignAction';
import ConfirmAlert from '../components/confirmAlert';
import ItemCampaign from '../components/itemCampaign';
import Pagination from '../components/pagination';
import Skeleton from "../components/skeleton";

class BookmarkCampaigns extends Component {
    state = {
        pageOfItems: []
    }

    componentDidMount() {
        const { loaded } = this.props.campaign;
        if( !loaded ) {
            this.props.fetchBookmarkCampaigns();
        }
    }

    onChangePage = (pageOfItems) => {
        this.setState({ pageOfItems });
    }

    onClickDelete = (campaignId) => {
        const data = {
            id: campaignId,
            bookmark: true
        }
        ConfirmAlert({
            title: 'Confirm to submit',
            message: 'Are you sure to do this.',
            buttons: [
                {
                    label: 'Yes',
                    onClick: () => this.props.deleteCampaign(data)
                },
                {
                    label: 'No'
                }
            ]
        });
    }

	render() {
        const { campaign } = this.props;
        if( campaign.loading ) {
            return (
                <Skeleton />
            )
        };

        const { pageOfItems } = this.state;

        return (
            <div>
                <h3>Bookmarks</h3>
                <div className="wpcf-dashboard-content-inner wpcf-mycampaign">
                    { campaign.data.length ?
                        <div>
                            { pageOfItems.map( (item, index) =>
                                <ItemCampaign
                                    key={index}
                                    data={ item } >
                                    <div className="wpcf-campaign-links">
                                        <button aria-label="Remove Bookmark" title="Remove Bookmark" onClick={ () => this.onClickDelete(item.id) }>
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
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({
        fetchBookmarkCampaigns,
        deleteCampaign
    }, dispatch);
};

export default connect( mapStateToProps, mapDispatchToProps )(BookmarkCampaigns);
