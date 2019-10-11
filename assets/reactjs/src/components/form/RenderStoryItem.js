import React, { useState } from 'react';
import { Editor } from '@tinymce/tinymce-react';

export default (props) => {
    const { index, data, edit, upload } = props;
    switch (data.type) {
        case 'image':
            let imageVal = [];
            let imageSrc = `${WPCF.assets}images/story-default-image.png`;
            if(data.value && data.value.length > 0) {
                imageSrc = data.value[0].src;
                imageVal = data.value
            }
            return (
                <div className="story-item-image" onClick={() => upload(index, 'image', imageVal)}><img src={imageSrc}/></div>
            );
        case 'video':
            return (
                <div className="story-item-image" onClick={() => upload(index, 'video', [])}>
                    { data.value && data.value.length > 0 ? 
                        <video controls><source src={data.value[0].src} type={data.value[0].mime}/>Your browser does not support the video tag</video>
                    :   <img src={`${WPCF.assets}images/story-default-image.png`}/> }
                </div>
            );
        case 'embeded_file':
            return (
                <div className="story-item-embeded_file">
                    { data.value ?
                        <div dangerouslySetInnerHTML={{__html: data.value}} />
                    :   <textarea value={data.value} onChange={(e) => edit(index, e.target.value)}/> }
                </div>
            )
        case 'text':
            return (
                <Editor
                    init={{
                        height: 500,
                        menubar: false,
                        inline: true,
                        plugins: [
                            'advlist autolink lists link image charmap print preview anchor',
                            'searchreplace visualblocks code fullscreen',
                            'insertdatetime media table paste code help wordcount'
                        ],
                        toolbar:
                            'formatselect | bold italic backcolor | \
                            alignleft aligncenter alignright alignjustify | \
                            bullist numlist outdent indent'
                    }}
                    initialValue={data.value} 
                    onChange={(e) => edit(index, e.target.getContent())}
                />
            );
        case 'text_image':
            return (
                <div className="story-item-text_image">
                </div>
            );
        case 'image_image':
            return (
                <div className="story-item-image_image">
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'text_text':
            return (
                <div className="story-item-text_text">
                </div>
            );
        case 'text_video':
            return (
                <div className="story-item-text_video">
                </div>
            );
        
        default:
            return '';
    }
}