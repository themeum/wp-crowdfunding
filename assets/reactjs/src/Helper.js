
import React from 'react'

export const required = value => value ? undefined : 'Required';
export const notRequred = value => '';

export const uploadFiles = (field, sFiles, multiple) => {
    return new Promise((resolve, reject) => {
        const prevFiles = sFiles ? [...sFiles] : [];
        const mediaLibrary = wp.media({
            multiple,
            library: {
                type: field
            }
        });
        mediaLibrary.on('open', () => {
            const selectionAPI = mediaLibrary.state().get('selection');
            prevFiles.forEach( item => {
                const attachment = wp.media.attachment( item.id );
                selectionAPI.add( attachment ? [ attachment ] : []);
            });
        });
        mediaLibrary.on('select', () => {
            const length = mediaLibrary.state().get('selection').length;
            const files = mediaLibrary.state().get('selection').models;
            let selectedFiles = [];
            for(let i = 0; i < length; i++) {
                selectedFiles.push({
                    id: files[i].id,
                    name: files[i].changed.filename,
                    type: files[i].changed.type,
                    src: files[i].changed.url,
                    mime: files[i].changed.mime,
                    thumb: (field == 'image') ? files[i].changed.sizes.thumbnail.url : '',
                });
            }
            resolve(selectedFiles);
        });
        mediaLibrary.open();
    });
};

export const removeArrValue = (values, index) => {
    values = [...values];
    values.splice(index, 1);
    return values;
}

export const getYotubeVideoID = src => {
    var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    var match = src.match(regExp);
    if (match && match[2].length == 11) {
        return match[2];
    }
    return false;
}

export const generateBasicPreview = (items, index) => {
    let mainViewItem;
    if( (items && items.length > 0) ) {
        mainViewItem = (typeof items[index] !== 'undefined') ? items[index] : items[0];
    } else {
        return '';
    }
    if(mainViewItem.type == 'youtube') {
        return (
            <iframe width="100%" height="300" src={`//www.youtube.com/embed/${getYotubeVideoID(mainViewItem.src)}`}/>
        );
    } else if(mainViewItem.type == 'video') {
        return (
            <video controls><source src={mainViewItem.src} type={mainViewItem.mime}/>Your browser does not support the video tag</video>
        );
    } else if(mainViewItem.type == 'image') {
        return (
            <img src={mainViewItem.src}/>
        );
    }
}