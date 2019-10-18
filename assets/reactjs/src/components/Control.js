import React from 'react';

export default (props) => {
    const { current, prevStep } = props;
    return (
        <div>
            <button type="button" onClick={prevStep} disabled={current==0}><i className="fa fa-long-arrow-left"/> Previous</button>
            <button type="submit"><i className="fa fa-long-arrow-right"/> {(current == 3) ? 'Submit' : 'Next'}</button>
        </div>
    )
}