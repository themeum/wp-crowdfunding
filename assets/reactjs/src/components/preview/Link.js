import React from 'react';

const PreviewLink = (props) => {
    return (
        <div className="wpcf-preview-link">
            {props.postId ? (
                <a href={`${WPCF.site_url}?post_type=product&p=${props.postId}&preview=true`} target="_blank">
                    Full Preview <span className="fas fa-desktop"></span>
                </a>
            ) : (
                <button disabled>
                    Full Preview <span className="fas fa-desktop"></span>
                </button>
            )}
        </div>
    );
}

export default PreviewLink;