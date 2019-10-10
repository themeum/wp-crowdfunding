import React, { useState } from 'react';

export default (props) => {
    const { index, data, edit, deleteItem, move, upload } = props;
    switch (data.type) {
        case 'image':
            let imageVal = [];
            let imageSrc = `${WPCF.assets}images/wpcf-default-image.jpg`
            if(data.value && data.value.length > 0) {
                imageSrc = data.value[0].src;
                imageVal = data.value
            }
            return (
                <div className="wpcf-story-edit-image">
                    <img src={imageSrc} onClick={() => upload(index, 'image', imageVal)}/>
                </div>
            );
        case 'video':
            return (
                <div className="wpcf-story-edit-video">
                </div>
            );
        case 'embeded_file':
            return (
                <div className="wpcf-story-edit-embeded_file">
                </div>
            );
        case 'text':
            return (
                <div className="wpcf-story-edit-text">
                </div>
            );
        case 'text_image':
            return (
                <div className="wpcf-story-edit-text_image">
                </div>
            );
        case 'image_image':
            return (
                <div className="wpcf-story-edit-image_image">
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'text_text':
            return (
                <div className="wpcf-story-edit-text_text">
                </div>
            );
        case 'text_video':
            return (
                <div className="wpcf-story-edit-text_video">
                </div>
            );
        
        default:
            return '';
    }
}