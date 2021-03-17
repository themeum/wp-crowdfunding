import { __ } from '@wordpress/i18n';
import React, { Component } from 'react';
import { HashRouter, Route, NavLink } from "react-router-dom";
import { connect } from 'react-redux';
import { fetchUser } from '../actions/userAction';
import Profile from './profile';
import MyCampaigns from './myCampaigns';
import CampaignReport from './campaignReport';
import InvestedCampaigns from './investedCampaigns';
import PledgeReceived from './pledgeReceived';
import BookmarkCampaigns from './bookmarkCampaigns';
import Order from './order';
import Withdraw from './withdraw';
import ProfileSettings from './profileSettings';
import WithdrawMethodSettings from './withdrawMethodSettings';
import Rewards from './rewards';
import '../styles/style.scss';
import Icon from '../components/Icon'

class App extends Component {
    state = {
        myCampainsCollapse: false,
        userSettingsCollapse: false
    }

    componentDidMount() {
        const { loaded } = this.props.user;
        if (!loaded) {
            this.props.fetchUser();
        }
    }

    logout = (e) => {
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
        const { loading, data } = this.props.user;
        if (loading) {
            return (
                <div className="wpcf-dashboard-container is-skeleton">
                    <div className="wpcf-dashboard-sidebar">
                        <div className="wpcf-dashboard-profile skeleton-parent">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <ul className="wpcf-dashboard-permalinks skeleton-parent">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                    <div className="wpcf-dashboard-content"></div>
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
                            <li><NavLink exact activeClassName="is-active" to="/"><Icon name="home" className="wpcf-icon"/>{ __('Dashboard', 'wp-crowdfunding') }</NavLink></li>
                            <li><NavLink activeClassName="is-active" to="/profile"><Icon name="user" className="wpcf-icon"/>{ __('My Profile', 'wp-crowdfunding') }</NavLink></li>
                            <li className={(this.state.myCampainsCollapse ? 'wpcf-collapse' : 'wpcf-collapsed')}>
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
                                    <Icon name="plane" className="wpcf-icon"/>
                                    { __('Campaigns', 'wp-crowdfunding') }

                                    {
                                        this.state.myCampainsCollapse ? <Icon className="wpcf-float-icon" name="angleUp"/> : <Icon className="wpcf-float-icon" name="angleDown"/>
                                    }

                                </a>
                                <ul className=" wpcf-dashboard-sub-permalinks">
                                    <li><NavLink activeClassName="is-active" to="/my-campaigns">{ __('My Campaigns', 'wp-crowdfunding') }</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/invested-campaigns">{ __('Invested Campaigns', 'wp-crowdfunding') }</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/pledge-received">{ __('Pledge Received', 'wp-crowdfunding') }</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/bookmark-campaigns">{ __('Bookmarks', 'wp-crowdfunding') }</NavLink></li>
                                    <li><NavLink activeClassName="is-active" to="/order">{ __('Order', 'wp-crowdfunding') }</NavLink></li>
                                    {WPCF.active_pro && <li><NavLink activeClassName="is-active" to="/withdraw">{ __('Withdraw', 'wp-crowdfunding') }</NavLink></li> }
                                </ul>
                            </li>
                            <li><NavLink activeClassName="is-active" to="/rewards"><Icon name="gift" className="wpcf-icon"/>{ __('Rewards', 'wp-crowdfunding') }</NavLink></li>
                            <li className={(this.state.userSettingsCollapse ? 'wpcf-collapse' : 'wpcf-collapsed')}>
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
                                    <Icon name="cog" className="wpcf-icon" />
                                    { __('User Settings', 'wp-crowdfunding') }

                                    {
                                        this.state.userSettingsCollapse ? <Icon className="wpcf-float-icon" name="angleUp"/> : <Icon className="wpcf-float-icon" name="angleDown"/>
                                    }
                                </a>
                                <ul className=" wpcf-dashboard-sub-permalinks">
                                    <li><NavLink activeClassName="is-active" to="/settings/profile">{ __('Profile Settings', 'wp-crowdfunding') }</NavLink></li>
                                    {WPCF.active_pro && <li><NavLink activeClassName="is-active" to="/settings/withdraw">{ __('Withdraw Method', 'wp-crowdfunding') }</NavLink></li>}
                                </ul>
                            </li>
                            <li><a href="javascript:void(0)" onClick={this.logout}><Icon name="logout" className="wpcf-icon" />{ __('Logout', 'wp-crowdfunding') }</a></li>
                        </ul>
                    </div>
                    <div className="wpcf-dashboard-content">
                        <Route path="/" exact component={CampaignReport}/>
                        <Route path="/profile" component={Profile}/>
                        <Route path="/my-campaigns" component={MyCampaigns}/>
                        <Route path="/invested-campaigns" component={InvestedCampaigns}/>
                        <Route path="/pledge-received" component={PledgeReceived}/>
                        <Route path="/bookmark-campaigns" component={BookmarkCampaigns}/>
                        <Route path="/order" component={Order}/>
                        {WPCF.active_pro &&
                            <Route path="/withdraw" component={Withdraw}/>
                        }
                        <Route path="/rewards" component={Rewards}/>
                        <Route path="/settings/profile" component={ProfileSettings}/>
                        {WPCF.active_pro &&
                            <Route path="/settings/withdraw" component={WithdrawMethodSettings}/>
                        }
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
