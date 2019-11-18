import { __ } from '@wordpress/i18n';
import React, { Component } from 'react';
import { months, secondsToDetails, pad } from '../helper';
import ShowMoreText from './showMoreText';

class ItemReward extends Component {
    state = { seconds: this.props.data.seconds}

    componentDidMount() {
        const { seconds } = this.state;
        this.rewardInterval = setInterval(() => {
            if (seconds > 0) {
                this.setState(({ seconds }) => ({
                    seconds: seconds - 1
                }));
            } else {
                clearInterval(this.rewardInterval);
            }
        }, 1000);
    }

    componentWillUnmount() {
        clearInterval(this.rewardInterval)
    }

    renderInterval() {
        const { seconds } = this.state;
        const details = secondsToDetails(seconds);
        return (
            <div className="wpcf-reward-perks-wrap">
                <h5>{ __('Perks about to end', 'wp-crowdfunding') }</h5>
                <div className="wpcf-reward-perks">
                    <div className="wpcf-reward-perk-item">
                        <h6>{ pad(details.days) }</h6>
                        <span>{ __('Days', 'wp-crowdfunding') }</span>
                    </div>
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
        const { seconds } = this.state;
        const { data } = this.props;
        return (
            <div className="wpcf-reward-item">
                <div className="wpcf-reward-thumbnail">
                    <img src={data.image} alt={data.title} />
                </div>
                <div className="wpcf-reward-content">
                    <h5>{ data.title || __( 'No title', 'wp-crowdfunding' ) }</h5>
                    <ShowMoreText 
                        length={100}
                        content={data.description || __( 'No description', 'wp-crowdfunding' )}/>
                    <div>{ months[data.endmonth]+' '+data.endyear }</div>
                    <p>{ __('Estimate Delivery Date', 'wp-crowdfunding') }</p>
                </div>

                { data.status == 'about_to_end' && data.seconds > 0 &&
                    this.renderInterval()
                }
            </div>
        )
    }
}

export default ItemReward;