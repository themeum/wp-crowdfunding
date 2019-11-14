import React from 'react';

const RenderPreview = (props) => {
    const { data } = props;
    switch (data.type) {
        case 'image':
            if(data.value && data.value.length > 0){
                return <div className="story-item-image"><img src={data.value[0].src}/></div>
            }
            return (
                <div className="wpcf-story-item-image wpcf-empty-image">
                    <span className="fas fa-image"></span>
                </div>
            );
        case 'video':
            return (
                <div className="story-item-image">
                    { data.value && data.value.length > 0 ?
                        <video controls><source src={data.value[0].src} type={data.value[0].mime}/>Your browser does not support the video tag</video>
                    :   <img src={`${WPCF.assets}images/story-default-image.png`}/> }
                </div>
            );
        case 'text':
        case 'embeded':
            return (
                <div dangerouslySetInnerHTML={{__html: data.value}} />
            );
        default:
            return null;
    }
}

const PreviewStory = (props) => {
    const { data } =  props;
    return (
        <div className="preview-story wpcf-preview-content">
            { data.map( (item, index) =>
                <div key={index} className="preview-story-item">
                    <div className="story-item-value">
                        { item && item.map((itm, i) =>
                            <div key={index+i} className={"wpcf-story-column wpcf-story-column-" + item.length}>
                                <RenderPreview data={itm}/>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </div>
    )
}

export default PreviewStory;
