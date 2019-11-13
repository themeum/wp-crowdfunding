import React from 'react';

const PreviewEmpty = () => {
    return (
        <div className="wpcf-preview-empty">
            <div className="wpcf-preview-empty-image">
                <img src={`${WPCF.assets}images/no-preview.svg`} alt=""/>
            </div>
            <span>Nothing to See here</span>
        </div>
    );
}

export default PreviewEmpty;