import React, {Fragment} from 'react';

export default (props) => {
    const { data } = props;

    return (
        <div className="wpcf-campaign-item">
            <a
                className="wpcf-campaign-thumbnail"
                title={ data.title }
                href={ data.permalink }
                dangerouslySetInnerHTML={{__html: data.thumbnail}}
            />

            <div className="wpcf-campaign-content">

                <div className="wpcf-campaign-content-links">
                    <a href="javascript:void(0)" onClick={ () => props.onClickReport( {id: data.id, name: data.title} ) }>Report</a>
                    <a href="javascript:void(0)" onClick={ () => props.onClickUpdates( data.id, data.updates ) }>Update</a>
                    <a href="#" className="wp-crowd-btn wp-crowd-btn-primary">Edit</a>
                </div>

                <h3><a href={data.permalink}>{ data.title }</a></h3>
                <h4 className="wpcf-campaign-author">by <a href="javascript:void(0)">{ data.author_name }</a> </h4>
                <div className="wpneo-location">
                    <i className="wpneo-icon wpneo-icon-location"></i>
                    <div className="wpneo-meta-desc">{ data.location }</div>
                </div>

                <div className="wpneo-percent-rund-wrap">
                    <div data-percent={ data.raised_percent }>
                        <div><span>{ data.raised_percent }</span></div>
                    </div>
                    <div>
                        <div className="wpneo-meta-desc" dangerouslySetInnerHTML={{__html: data.total_raised}}/>
                        <div className="wpneo-meta-name">Fund Raised</div>
                    </div>
                    <div>
                        <div className="wpneo-meta-desc" dangerouslySetInnerHTML={{__html: data.funding_goal}}/>
                        <div className="wpneo-meta-name">Funding Goal</div>
                    </div>

                    { ( data.end_method !== 'never_end' ) &&
                        <div>
                            <div className="wpneo-meta-desc">{ ( data.is_started ) ? data.days_remaining :  data.days_until_launch }</div>
                            <div className="wpneo-meta-name">Days { ( data.is_started ) ? "to go" :  "Until Launch" }</div>
                        </div>
                    }

                    { props.pledge &&
                        <div className="">
                            <span><a href="#">Pledge More</a></span>
                        </div>
                    }
                </div>
            </div>
        </div>
    )
};
