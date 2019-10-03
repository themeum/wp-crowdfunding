
import React from 'react'

export const required = value => value ? undefined : 'Required';
export const notRequred = value => '';

export const uploadFiles = (type, sFiles, multiple) => {
    return new Promise((resolve, reject) => {
        const prevFiles = sFiles ? [...sFiles] : [];
        const mediaLibrary = wp.media({
            multiple,
            library: { type }
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
                    thumb: (type == 'image') ? files[i].changed.sizes.thumbnail.url : '',
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