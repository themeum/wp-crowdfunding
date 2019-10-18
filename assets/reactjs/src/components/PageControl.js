import React from 'react';

export default (props) => {
    const { current, prevStep } = props;
    return (
        <div>
            <button type="button" onClick={prevStep} disabled={current==0}>Previous</button>
            <button type="submit">{(current == 3) ? 'Submit' : 'Next'}</button>
        </div>
    )
}