import React, {Fragment} from 'react';
import CircleProgress from "./circleProgress";
import decodeEntities from "../helpers/decodeEntities"

export default (props) => {
    const { data } = props;

    return (
        <div className="wpcf-campaign-item">
            <a
                className="wpcf-campaign-thumbnail"
                title={ decodeEntities(data.title) }
                href={ data.permalink }
                dangerouslySetInnerHTML={{__html: data.thumbnail}}
            />

            <div className="wpcf-campaign-content">
                <div className="wpcf-campaign-heading">
                    <h3 className="wpcf-campaign-title">
                        <a href={data.permalink}>{decodeEntities(data.title)}</a>
                    </h3>
                    <div className="wpcf-campaign-links">
                        <button aria-label="Report" title="Report"  onClick={ () => props.onClickReport( {id: data.id, name: data.title} ) }>
                            <span className="fas fa-chart-bar"></span>
                        </button>
                        <button aria-label="Edit" title="Edit" onClick={ () => props.onClickUpdates( data.id, data.updates ) }>
                            <i className="far fa-edit"></i>
                        </button>
                        <button aria-label="Delete" title="Delete">
                            <span className="fas fa-trash-alt"></span>
                        </button>
                    </div>
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
                        <span><a href="#">Pledge More</a></span>
                    </div>
                    }

                </div>
            </div>
        </div>
    )
};
