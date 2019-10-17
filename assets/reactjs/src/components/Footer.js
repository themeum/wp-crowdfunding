import React from "react";

export default (props) => {
	const { steps, current, prevStep, nextStep } = props;
	return (
		<div className="row">
			<div className="col-md-12">
				{ (current > 0) &&
					<button onClick={() => prevStep()}>Previous</button>
				}
				<button onClick={() => nextStep()}>{(current+1 == steps.length) ? 'Submit' : 'Next'}</button>
			</div>
		</div>
	);
}
