import React from 'react';

const RenderPreview = (props) => {
    const { data } = props;
    switch (data.type) {
        case 'image':
            const imageSrc = (data.value && data.value.length > 0) ? data.value[0].src  : `${WPCF.assets}images/story-default-image.png`;
            return (
                <div className="story-item-image"><img src={imageSrc}/></div>
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

export default (props) => {
    const { data } =  props;
    return (
        <div className="preview-story">
            { data.map( (item, index) =>
                <div key={index} className="preview-story-item">
                    <div className="story-item-value">
                        { item && item.map((itm, i) =>
                            <RenderPreview 
                                key={index+i} 
                                data={itm}
                            />
                        )}
                    </div>
                </div>
            )}
        </div>
    )
}