import React, {Fragment} from 'react';
import CircleProgress from "./circleProgress";
import decodeEntities from "../helpers/decodeEntities"


const ItemCampaign = (props) => {
    const { data } = props;

    console.log(props)

    return (
        <div className="wpcf-campaign-item">
            <a
                className="wpcf-campaign-thumbnail"
                title={ decodeEntities(data.title) }
                href={ data.permalink }
                dangerouslySetInnerHTML={{__html: data.thumbnail}}
            />

            <div className="wpcf-campaign-content">
                <div className={"wpcf-campaign-heading" + (props.invested !== true ? "" : "wpcf-has-not-campaign-link")}>
                    <h3 className="wpcf-campaign-title">
                        <a href={data.permalink}>{decodeEntities(data.title)}</a>
                    </h3>
                        {props.children }
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
                            <span>{WPCF.wc_currency_symbol + data.total_raised}</span>
                            <span>Fund Raised</span>
                        </h5>
                    </div>
                    <div className="wpcf-campaign-info">
                        <h5>
                            <span>{WPCF.wc_currency_symbol + data.funding_goal}</span>
                            <span>Funding Goal</span>
                        </h5>
                    </div>


                    {( data.end_method !== 'never_end' ) && (
                        <div className="wpcf-campaign-info">
                            <h5>
                                <span>{ ( data.is_started ) ? data.days_remaining :  data.days_until_launch }</span>
                                <span>Days { ( data.is_started ) ? "to go" :  "Until Launch" }</span>
                            </h5>
                        </div>
                    )}

                    { props.pledge &&
                    <div  className="wpcf-campaign-info">
                        <a className="wpcf-btn wpcf-btn-round wpcf-btn-outline wpcf-btn-sm" href={data.permalink}>Pledge More</a>
                    </div>
                    }

                </div>
            </div>
        </div>
    )
};

ItemCampaign.defaultProps = {
    invested: false
}

export default ItemCampaign
