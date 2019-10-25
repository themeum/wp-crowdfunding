import React from 'react';

export default (props) => {
    const { data } = props;

    return (
        <div className="">
            <div className="">
                <a href="#" title={ data.title } dangerouslySetInnerHTML={{__html: data.thumbnail}} />
                <a href={ data.permalink }>View</a>
            </div>

            <div className="">
                <div className="">
                    <h4><a href={data.permalink}>{ data.title }</a></h4>
                    <p className="wpneo-author">by <a href="javascript:void(0)">{ data.author_name }</a> </p>
                    <div className="wpneo-location">
                        <i className="wpneo-icon wpneo-icon-location"></i>
                        <div className="wpneo-meta-desc">{ data.location }</div>
                    </div>
                </div>
                <div>
                    <a href="javascript:void(0)" onClick={ () => props.onClickReport( {id: data.id, name: data.title} ) }>Report</a>
                    <a href="javascript:void(0)" onClick={ () => props.onClickUpdates( data.id, data.updates ) }>Update</a>
                    <a href="#" className="wp-crowd-btn wp-crowd-btn-primary">Edit</a>
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
