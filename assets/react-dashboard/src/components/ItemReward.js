import React from 'react';

export default (props) => {
    const { data } = props;
    return (
        <div className="">
            <div className="thumbnail">
                <img src={data.image} alt={data.title} />
            </div>
            <div className="content">
                <div>{ data.title }</div>
                <p>{ data.description }</p>
            </div>
            <div className="content">
                <div>{ data.endmonth+' '+data.endyear }</div>
                <p>Estimate Delivery Date</p>
            </div>
            <div className="perks">
                <p>Perks about to end</p>
                <div>
                    <p>{data.interval.d}</p>
                    <p>Days</p>
                </div>
                <div>
                    <p>{data.interval.h}</p>
                    <p>Hrs</p>
                </div>
                <div>
                    <p>{data.interval.i}</p>
                    <p>Min</p>
                </div>
                <div>
                    <p>{data.interval.s}</p>
                    <p>Sec</p>
                </div>
            </div>
        </div>
    )
};