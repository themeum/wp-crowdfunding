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
        case 'embeded_file':
        case 'text':
            return (
                <div dangerouslySetInnerHTML={{__html: data.value}} />
            );
        default:
            return '';
    }
}

export default (props) => {
    const { data } =  props;
    return (
        <div className="preview-story">
            { data.map( (item, index) =>
                <div key={index} className="preview-story-item">
                    { item && item.length > 0 ?
                        <div className="story-item-multiple">
                            { item.map((itm, i) =>
                                <RenderPreview 
                                    key={index+i} 
                                    data={itm}
                                />
                            )}
                        </div>
                    :   <RenderPreview 
                            key={index} 
                            data={item}
                        />
                    }
                </div>
            )}
        </div>
    )
}