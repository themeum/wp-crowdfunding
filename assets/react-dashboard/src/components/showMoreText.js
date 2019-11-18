import React, { useState } from 'react';

const ShowMoreText = (props) => {
    const { length, content } = props;
    const [ expand, setExpand ] = useState(false);
    if( content.length > length ) {
        const className = expand ? 'wpcf-expand' : 'wpcf-collapse';
        const buttonText = expand ? 'See Less' : 'See More';
        return (
            <div className={className}>
                <p dangerouslySetInnerHTML={{__html: content}}/>
                <span onClick={() => setExpand( !expand )}>{ buttonText }</span>
            </div>
        );
    } else {
        return (
            <p dangerouslySetInnerHTML={{__html: content}}/>
        );
    }
}

export default ShowMoreText;