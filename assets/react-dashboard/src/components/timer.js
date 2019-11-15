
import { __ } from '@wordpress/i18n';
import React, { Component } from 'react';

class Timer extends Component {
    state = { seconds: this.props.seconds}

    componentDidMount() {
        const { seconds } = this.state;
        this.myInterval = setInterval(() => {
            if (seconds > 0) {
                this.setState(({ seconds }) => ({
                    seconds: seconds - 1
                }))
            } else {
                clearInterval(this.myInterval);
            }
        }, 1000)
    }

    componentWillUnmount() {
        clearInterval(this.myInterval)
    }
    
    getDetails = () => {
        const { seconds } = this.state;
        const days        = Math.floor(seconds/24/60/60);
        const hoursLeft   = Math.floor((seconds) - (days*86400));
        const hours       = Math.floor(hoursLeft/3600);
        const minutesLeft = Math.floor((hoursLeft) - (hours*3600));
        const minutes     = Math.floor(minutesLeft/60);
        return {days, hours, minutes, seconds: seconds % 60};
    }

    pad = (n) => {
        return (n < 10 ? "0" + n : n);
    }

    render() {
        const { seconds } = this.state
        const details = this.getDetails(seconds);
        return (
            <div className="wpcf-reward-perks-wrap">
                <h5>{ __('Perks about to end', 'wp-crowdfunding') }</h5>
                <div className="wpcf-reward-perks">
                    <div className="wpcf-reward-perk-item">
                        <h6>{ this.pad(details.days) }</h6>
                        <span>{ __('Days', 'wp-crowdfunding') }</span>
                    </div>
                    <div className="wpcf-reward-perk-item">
                        <h6>{ this.pad(details.hours) }</h6>
                        <span>{ __('Hrs', 'wp-crowdfunding') }</span>
                    </div>
                    <div className="wpcf-reward-perk-item">
                        <h6>{ this.pad(details.minutes) }</h6>
                        <span>{ __('Min', 'wp-crowdfunding') }</span>
                    </div>
                    <div className="wpcf-reward-perk-item">
                        <h6>{ this.pad(details.seconds) }</h6>
                        <span>{ __('Sec', 'wp-crowdfunding') }</span>
                    </div>
                </div>
            </div>
        )
    }
}

export default Timer;