import React, {Fragment} from 'react';

export default (props) => {
    const { data } = props;
    return (
        <div className="wpcf-reward-item">

            <div className="wpcf-reward-thumbnail">
                <img src={data.image} alt={data.title} />
            </div>
            <div className="wpcf-reward-content">
                <h5>{ data.title }</h5>
                <div>{ data.endmonth+' '+data.endyear }</div>
                <p>Estimate Delivery Date</p>
            </div>

            <div className="wpcf-reward-perks-wrap">
                <h6>Perks about to end</h6>
                <div className="wpcf-reward-perks">
                    <div className="wpcf-reward-perk-item">
                        <h6>{data.interval.d}</h6>
                        <span>Days</span>
                    </div>
                    <div className="wpcf-reward-perk-item">
                        <h6>{data.interval.h}</h6>
                        <span>Hrs</span>
                    </div>
                    <div className="wpcf-reward-perk-item">
                        <h6>{data.interval.i}</h6>
                        <span>Min</span>
                    </div>
                    <div className="wpcf-reward-perk-item">
                        <h6>{data.interval.s}</h6>
                        <span>Sec</span>
                    </div>
                </div>
            </div>
        </div>
    )
};
