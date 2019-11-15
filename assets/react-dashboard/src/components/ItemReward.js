import { __ } from '@wordpress/i18n';
import React from 'react';
import { months } from '../helper';
import Timer from './Timer';

export default (props) => {
    const { data } = props;
    return (
        <div className="wpcf-reward-item">

            <div className="wpcf-reward-thumbnail">
                <img src={data.image} alt={data.title} />
            </div>
            <div className="wpcf-reward-content">
                <h5>{ data.title || __( 'No title', 'wp-crowdfunding' ) }</h5>
                <p>{ data.description || __( 'No description', 'wp-crowdfunding' ) }</p>
                <div>{ months[data.endmonth]+' '+data.endyear }</div>
                <p>{ __('Estimate Delivery Date', 'wp-crowdfunding') }</p>
            </div>

            { data.status == 'about_to_end' && data.seconds > 0 &&
                <Timer seconds={data.seconds}/>
            }
        </div>
    )
};