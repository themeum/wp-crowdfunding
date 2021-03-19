import { __ } from '@wordpress/i18n';
import React, {Fragment, useState} from 'react';
import { getYotubeVideoID, getRaisedPercent, wcPrice } from '../../Helper';

const RenderPreview = (props) => {
    const { items, index } = props;
    let viewItem = (typeof items[index] !== 'undefined') ? items[index] : items[0];
    switch (viewItem.type) {
        case 'video_link':
            return (
            <div className='embed-container' >
                <iframe
                    src={`//www.youtube.com/embed/${getYotubeVideoID(viewItem.src)}`}
                    frameBorder='0'
                    allowFullScreen
                />
            </div>
            );
        case 'video':
            return (
                <video controls><source src={viewItem.src} type={viewItem.mime}/>Your browser does not support the video tag</video>
            );
        case 'image':
            return (
                <img src={viewItem.src}/>
            );
    }
}

const PreviewBasic = (props) => {
    const { data, data:{ media }, raised, backers } = props;
    const [ index, setIndex] = useState(0);
    const goal = data['funding_goal'] || 0;
    const goalType = data['goal_type'] || false;
    const fundType = data['fund_type'] || '';
    let diffDays = 0;
    if(goalType == 'target_date' && data['end_date']) {
        const dArray = data['end_date'].split('-');
        const endDate = new Date(dArray[2], dArray[1]-1, dArray[0]);
        const currentDate = new Date();
        const timeDiff = endDate.getTime() - currentDate.getTime();
        diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        diffDays = (diffDays > 0) ? diffDays : 0;
    }
    return (
        <div className="wpcf-preview-media">
            <div className="wpcf-preview-media-view">
                { media && media.length > 0 &&
                    <RenderPreview items={media} index={index}/>
                }
            </div>
            <div className="wpcf-thumbnail-view">
                {media && media.map( (item, index) =>
                    <img key={index} src={item.thumb} onClick={() => setIndex(index)} alt="Thumbnail"/>
                )}
            </div>
            <div className="wpcf-preview-raised-area">
                <h3><span dangerouslySetInnerHTML={{ __html: wcPrice(raised) }}/> {__('raised by', 'wp-crowdfunding')} <span>{backers}</span> {__('backers', 'wp-crowdfunding')}</h3>
                <div className="wpcf-raised-progress">
                    <span className="wpcf-raised-percent" style={{width: getRaisedPercent(goal, raised) + '%'}}></span>
                </div>
                <div className="wpcf-preview-raised-footer">
                    <div className="wpcf-preview-raised-left">
                        <span>{getRaisedPercent(goal, raised)}%</span> of <span dangerouslySetInnerHTML={{ __html: wcPrice(goal) }}/> {fundType && fundType.replace('_', ' ')}
                    </div>
                    <div className="wpcf-preview-raised-right">
                        {goalType == 'target_date' && <Fragment>{diffDays} {__('days Left', 'wp-crowdfunding')}</Fragment>}
                    </div>
                </div>
            </div>
        </div>
    )
}

export default PreviewBasic;
