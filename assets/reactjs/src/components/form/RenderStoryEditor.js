import React, { useState } from 'react';


export const RenderStoryEditor = (props) => {
    const { input, meta: { touched, error }, item, uploadFile, removeArrValue, className, fieldValue} = props;
    switch (item.type) {
        case 'image':
            return (
                <div className={className}>
                    <input {...input} type={item.type} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'video':
            return (
                <div className={className}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'embeded_file':
            return (
                <div className={className}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'text':
            return (
                <div className={className}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'text_image':
            return (
                <div className={className}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'image_image':
            return (
                <div className={className}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'text_text':
            return (
                <div className={className}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'text_video':
            return (
                <div className={className}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        
        default:
            return '';
    }
}