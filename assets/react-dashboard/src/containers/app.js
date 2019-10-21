import React, { Component } from 'react';
import { BrowserRouter as Router, HashRouter, Route, Link } from "react-router-dom";
import { connect } from 'react-redux';
import { fetchUser } from '../actions/userAction';
import CampaignReport from './campaignReport';
import Profile from './profile';
import MyCampaigns from './myCampaigns';
import InvestedCampaigns from './investedCampaigns';
import PledgeReceived from './pledgeReceived';
import BookmarkCampaigns from './bookmarkCampaigns';
import Order from './order';
import Withdraw from './withdraw';
import ProfileSettings from './profileSettings';
import WithdrawMethodSettings from './withdrawMethodSettings';
import Rewards from './rewards';
import '../styles/style.scss';

class App extends Component {
    constructor(props) {
        super(props);
        const basePath = WPCF.dashboard_url.replace(window.location.origin, '');
        this.state = { basePath };
        this.logout = this.logout.bind(this);
    }

    componentDidMount() {
        const { loaded } = this.props.user;
        if (!loaded) {
            this.props.fetchUser();
        }
    }

    logout(e) {
        e.preventDefault();
        fetch(`${WPCF.rest_url}/logout`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    location.href = res.redirect;
                }
            });
    }

    render() {
        const { basePath } = this.state;
        const { loading, data } = this.props.user;
        if (loading) {
            return (
                <div>
                    Loading...
                </div>
            )
        };

        return (
            <div className="wpcf-wrap wpcf-dashboard-container">
                <HashRouter>
                    <div className="wpcf-dashboard-sidebar">
                        <div className="wpcf-dashboard-profile">
                            <img className="profile-form-img" src={data.profile_image} alt="Profile Image" />
                            <h4>{data.display_name}</h4>
                            <span>{data.user_email}</span>
                        </div>
                        <ul className="wpcf-dashboard-permalinks">
                            <li><Link to="/"><span className="fas fa-home wpcf-icon" />Dashboard</Link></li>
                            <li><Link to="/profile"><span className="far fa-user wpcf-icon" />My Profile</Link>
                            </li>
                            <li><Link to="/my-campaigns"><span class="far fa-paper-plane wpcf-icon" />My Campaigns</Link>
                                <ul className="wpcf-dashboard-sub-permalinks">
                                    <li><Link to="/invested-campaigns">Invested Campaigns</Link></li>
                                    <li><Link to="/pledge-received">Pledge Received</Link></li>
                                    <li><Link to="/bookmark-campaigns">Bookmarks</Link></li>
                                    <li><Link to="/order">Order</Link></li>
                                    <li><Link to="/withdraw">Withdraw</Link></li>
                                </ul>
                            </li>
                            <li><Link to="/settings/profile"><span className="wpcf-icon fas fa-sliders-h"></span>User Settings</Link></li>
                            <li><Link to="/settings/withdraw">Withdraw Method</Link></li>
                            <li><Link to="/rewards">Rewards</Link></li>
                            <li><a href="javascript:void(0)" onClick={this.logout}>Logout</a></li>
                        </ul>
                    </div>
                    <div className="wpcf-dashboard-content">
                        <div className="wpcf-dashboard-header-button">
                            <a className="wpcf-btn bordered-btn" href="#">
                                <i className="wpcf-icon-checkbox-pen-outline"></i> &nbsp; Create Campaign
                            </a>
                        </div>
                        <Route path="/" exact component={CampaignReport} />
                        <Route path="/profile" component={Profile} />
                        <Route path="/my-campaigns" component={MyCampaigns} />
                        <Route path="/invested-campaigns" component={InvestedCampaigns} />
                        <Route path="/pledge-received" component={PledgeReceived} />
                        <Route path="/bookmark-campaigns" component={BookmarkCampaigns} />
                        <Route path="/order" component={Order} />
                        <Route path="/withdraw" component={Withdraw} />
                        <Route path="/settings/profile" component={ProfileSettings} />
                        <Route path="/settings/withdraw" component={WithdrawMethodSettings} />
                        <Route path="/rewards" component={Rewards} />
                    </div>
                </HashRouter>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    user: state.user
})

export default connect(mapStateToProps, { fetchUser })(App);