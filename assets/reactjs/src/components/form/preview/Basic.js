import React, { useState } from 'react';
import { getYotubeVideoID } from '../../../Helper';

const RenderPreview = (props) => {
    const { items, index } = props;
    let viewItem = (typeof items[index] !== 'undefined') ? items[index] : items[0];
    switch (viewItem.type) {
        case 'youtube':
            return (
                <iframe width="100%" height="300" src={`//www.youtube.com/embed/${getYotubeVideoID(viewItem.src)}`}/>
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
    const { data } = props;
    const [ index, setIndex] = useState(0);
    const getAllItems = () => {
        let items = [];
        const video_link = data.video_link || [];
        const video = data.video || [];
        const image = data.image || [];
        if(video_link.length > 0) {
            video_link.map( item => {
                if(item.src) {
                    const videoId = getYotubeVideoID(item.src);
                    items.push({
                        id: videoId,
                        type: 'youtube',
                        src: item.src,
                        thumb: `https://img.youtube.com/vi/${videoId}/default.jpg`,
                    });
                }
            });
        }
        return items.concat(video).concat(image);
    }
    const items = getAllItems();
    return (
        <div className="preview-media">
            <div className="main-view">
                { items && items.length > 0 &&
                    <RenderPreview items={items} index={index}/>
                }
            </div>
            <div className="thumbnails-view">
                {items && items.map( (item, index) =>
                    <img key={index} src={item.thumb} onClick={() => setIndex(index)} alt="Thumbnail"/>
                )}
            </div>
        </div>
    )
}