import React from 'react';
import { Editor } from '@tinymce/tinymce-react';

//FIELD TYPES
const Image = (props) => {
    const { name, data, upload } = props;
    let imageVal = [];
    let imageSrc = `${WPCF.assets}images/story-default-image.png`;
    if(data.value && data.value.length > 0) {
        imageSrc = data.value[0].src;
        imageVal = data.value;
    }
    return (
        <div className="story-item-image" onClick={() => upload(name, 'image', imageVal)}><img src={imageSrc}/></div>
    );
}

const Video = (props) => {
    const { name, data, upload } = props;
    return (
        <div className="story-item-image" onClick={() => upload(name, 'video', [])}>
            { data.value && data.value.length > 0 ?
                <video controls><source src={data.value[0].src} type={data.value[0].mime}/>Your browser does not support the video tag</video>
            :   <img src={`${WPCF.assets}images/story-default-image.png`}/> }
        </div>
    );
}

const EmbededFile = (props) => {
    const { name, data, edit } = props;
    return (
        <div className="story-item-embeded_file">
            { data.value ?
                <div dangerouslySetInnerHTML={{__html: data.value}} />
            :   <textarea value={data.value} onChange={(e) => edit(name, e.target.value)}/> }
        </div>
    );
}

const TextEditor = (props) => {
    const { name, data, edit } = props;
    return (
        <Editor
            init={{
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
            onChange={(e) => edit(name, e.target.getContent())}
            className="wpcf-story-texteditor"
        />
    );
}

export default (props) => {
    switch (props.data.type) {
        case 'image':
            return <Image {...props}/>
        case 'video':
            return <Video {...props}/>
        case 'embeded':
            return <EmbededFile {...props}/>
        case 'text':
            return <TextEditor {...props}/>
        default:
            return null;
    }
}
