import React from 'react';
import Icon from './Icon'

const Control = (props) => {
    const { current, prevStep, lastStep } = props;
    return (
        <div id="wpcf-page-control" className="wpcf-form-next-prev">
            <button type="button" onClick={prevStep} disabled={current==0}><Icon name="arrowLeft" className="wpcf-icon"/> <span>Previous</span></button>
            <button type="submit"><span>{lastStep ? 'Submit' : 'Next'}</span> <Icon name="arrowRight" className="wpcf-icon"/></button>
        </div>
    )
}

export default Control;
