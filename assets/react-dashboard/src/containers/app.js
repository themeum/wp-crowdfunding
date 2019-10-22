import React, { Component } from 'react';
import {HashRouter, Route, NavLink } from "react-router-dom";
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
        this.state = { 
            basePath,
            myCampainsCollapse: false,
            userSettingsCollapse: false
        };
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
                            <li><NavLink exact activeClassName="is-active" to="/"><span className="fas fa-home wpcf-icon" />Dashboard</NavLink></li>
                            <li><NavLink activeClassName="is-active" to="/profile"><span className="far fa-user wpcf-icon" />My Profile</NavLink>
                            </li>
                            <li className={(this.state.myCampainsCollapse ? 'collapse' : 'collapsed')}>
                                <a 
                                    href="javascript:void(0)"
                                    onClick={
                                        () => {
                                            this.setState({
                                                myCampainsCollapse : !this.state.myCampainsCollapse
                                            })
                                        }
                                    } 
                                >
                                    <span className="far fa-paper-plane wpcf-icon" />
                                    Campaigns
                                    <span className={"wpcf-float-icon fas fa-angle-" + (this.state.myCampainsCollapse ? 'up' : 'down')} />
                                </a>
                                <ul className=" wpcf-dashboard-sub-permalinks">
                                    <li><NavLink activeClassName="is-active" to="/my-campaigns">My Campaigns</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/invested-campaigns">Invested Campaigns</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/pledge-received">Pledge Received</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/bookmark-campaigns">Bookmarks</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/order">Order</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/withdraw">Withdraw</NavLink></li>
                                </ul>
                            </li>
                            <li><NavLink activeClassName="is-active" to="/rewards"><span className="fas fa-gift wpcf-icon"></span>Rewards</NavLink></li>
                            <li className={(this.state.userSettingsCollapse ? 'collapse' : 'collapsed')}>
                                <a 
                                    href="javascript:void(0)"
                                    onClick={
                                        () => {
                                            this.setState({
                                                userSettingsCollapse : !this.state.userSettingsCollapse
                                            })
                                        }
                                    } 
                                >
                                    <span className="wpcf-icon fas fa-sliders-h"></span>
                                    User Settings
                                    <span className={"wpcf-float-icon fas fa-angle-" + (this.state.userSettingsCollapse ? 'up' : 'down')} />
                                </a>
                                <ul className=" wpcf-dashboard-sub-permalinks">
                                    <li><NavLink activeClassName="is-active" to="/settings/profile">Profile Settings</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/settings/withdraw">Withdraw Method</NavLink></li>
                                </ul>
                            </li>
                            <li><a href="javascript:void(0)" onClick={this.logout}><span className="wpcf-icon fas fa-sign-out-alt"></span>Logout</a></li>
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
                        <Route path="/rewards" component={Rewards} />
                        <Route path="/settings/profile" component={ProfileSettings} />
                        <Route path="/settings/withdraw" component={WithdrawMethodSettings} />
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