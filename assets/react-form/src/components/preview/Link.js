import React from 'react';
import Icon from "../Icon";

const PreviewLink = (props) => {
    return (
        <div className="wpcf-preview-link">
            {props.postId ? (
                <a href={`${WPCF.site_url}?post_type=product&p=${props.postId}&preview=true`} target="_blank">
                    Full Preview <Icon name="preview"/>
                </a>
            ) : (
                <button disabled>
                    Full Preview <Icon name="preview"/>
                </button>
            )}
        </div>
    );
}

export default PreviewLink;
