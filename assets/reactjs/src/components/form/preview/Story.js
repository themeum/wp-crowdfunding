import React from 'react'

export default (props) => {
    const { data } =  props;
    return (
        <div className="preview-story">
            { data.map( (item, index) =>
                <div key={index} className={item.class}>
                    <div dangerouslySetInnerHTML={{__html: item.value}} />
                </div>
            )}
        </div>
    )
}