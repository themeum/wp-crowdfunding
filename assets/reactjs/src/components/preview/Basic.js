import React, { useState } from 'react';
import { getYotubeVideoID } from '../../Helper';

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

export default (props) => {
    const { data:{ media } } = props;
    const [ index, setIndex] = useState(0);
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
        </div>
    )
}
