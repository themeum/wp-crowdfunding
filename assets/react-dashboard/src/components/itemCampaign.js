import { __ } from '@wordpress/i18n';
import React, { Component } from 'react';
import { decodeEntities, wcPice, secondsToDetails, pad } from "../helper";
import CircleProgress from "./circleProgress";

class ItemCampaign extends Component {
    state = { seconds: this.props.data.seconds || 0}

    static defaultProps = {
        invested: false
    }

    componentDidMount() {
        const { seconds } = this.state;
        this.campaignInterval = setInterval(() => {
            if (seconds > 0) {
                this.setState(({ seconds }) => ({
                    seconds: seconds - 1
                }));
            } else {
                clearInterval(this.campaignInterval);
            }
        }, 1000);
    }

    componentWillUnmount() {
        clearInterval(this.campaignInterval)
    }

    renderInterval() {
        const { seconds } = this.state;
        const details = secondsToDetails(seconds);
        return (
            <div className="wpcf-reward-perks-wrap">
                <h5>{ __('Perks about to end', 'wp-crowdfunding') }</h5>
                <div className="wpcf-reward-perks">
                    <div className="wpcf-reward-perk-item">
                        <h6>{ pad(details.hours) }</h6>
                        <span>{ __('Hrs', 'wp-crowdfunding') }</span>
                    </div>
                    <div className="wpcf-reward-perk-item">
                        <h6>{ pad(details.minutes) }</h6>
                        <span>{ __('Min', 'wp-crowdfunding') }</span>
                    </div>
                    <div className="wpcf-reward-perk-item">
                        <h6>{ pad(details.seconds) }</h6>
                        <span>{ __('Sec', 'wp-crowdfunding') }</span>
                    </div>
                </div>
            </div>
        );
    }



    render() {
        const { data, invested, pledge } = this.props;
        return (
            <div className="wpcf-campaign-item">
                {   data.status == 'running' &&
                    data.end_method=='target_date' &&
                    data.seconds <= 43200 && //If 24hour left

                    this.renderInterval()
                }
                <a
                    className="wpcf-campaign-thumbnail"
                    title={ decodeEntities(data.title) }
                    href={ data.permalink }
                    dangerouslySetInnerHTML={{__html: data.thumbnail}}
                />
    
                <div className="wpcf-campaign-content">
                    <div className={"wpcf-campaign-heading" + (invested !== true ? "" : "wpcf-has-not-campaign-link")}>
                        <h3 className="wpcf-campaign-title">
                            <a href={data.permalink}>{decodeEntities(data.title)}</a>
                        </h3>
                        {this.props.children}
                    </div>
                    <h4 className="wpcf-campaign-author">by <a href="javascript:void(0)">{ data.author_name }</a> </h4>
    
                    <div className="wpcf-campaign-infos">
                        <div className="wpcf-campaign-info">
                            <div className="wpcf-campaign-raised">
                                <CircleProgress size={50} thickness={3} percent={Math.round(data.raised_percent)}/>
                                <span className="wpcf-raised-percent">{ Math.round(data.raised_percent) }%</span>
                            </div>
                        </div>
                        <div className="wpcf-campaign-info">
                            <h5>
                                <span dangerouslySetInnerHTML={{__html: wcPice(data.total_raised)}}/>
                                <span>Fund Raised</span>
                            </h5>
                        </div>
                        <div className="wpcf-campaign-info">
                            <h5>
                                <span dangerouslySetInnerHTML={{__html: wcPice(data.funding_goal)}}/>
                                <span>Funding Goal</span>
                            </h5>
                        </div>
    
                        {( data.end_method == 'target_date' ) && (
                            <div className="wpcf-campaign-info">
                                <h5>
                                    <span>{ ( data.is_started ) ? data.days_remaining :  data.days_until_launch }</span>
                                    <span>Days { ( data.is_started ) ? "to go" :  "Until Launch" }</span>
                                </h5>
                            </div>
                        )}
    
                        { pledge &&
                        <div  className="wpcf-campaign-info">
                            <a className="wpcf-btn wpcf-btn-round wpcf-btn-outline wpcf-btn-sm" href={data.permalink}>Pledge More</a>
                        </div>
                        }
                    </div>
                </div>
            </div>
        )
    }
}

export default ItemCampaign
