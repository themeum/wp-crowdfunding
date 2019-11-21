import React from 'react';

const Control = (props) => {
    const { current, prevStep, lastStep } = props;
    return (
        <div className="wpcf-form-next-prev">
            <button type="button" onClick={prevStep} disabled={current==0}><span className="fa fa-long-arrow-left wpcf-icon"/> <span>Previous</span></button>
            <button type="submit"><span>{lastStep ? 'Submit' : 'Next'}</span> <span className="fa fa-long-arrow-right wpcf-icon"/></button>
        </div>
    )
}

export default Control;
