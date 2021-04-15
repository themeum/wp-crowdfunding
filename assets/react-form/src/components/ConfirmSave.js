import React from 'react';
import { render, unmountComponentAtNode } from 'react-dom';

const ConfirmBox = (props) => {
	const { redirect, message } = props;

	const handleClickButton = () => {
		location.href = redirect;
		close();
	};

	const close = () => {
		removeBodyClass();
		removeElement();
	};

	return (
		<div className='wpcf-confirm-alert-overlay'>
			<div className='wpcf-confirm-alert'>
				<div className='wpcf-confirm-alert-body'>
					<h2>{message}</h2>
					<div className='wpcf-confirm-alert-button-group'>
						<button onClick={() => handleClickButton()}>
							Back To My Campaigns
						</button>
					</div>
				</div>
			</div>
		</div>
	);
};

const createElement = (props) => {
	let divTarget = document.getElementById('wpcf-confirm-alert');
	if (divTarget) {
		render(<ConfirmBox {...props} />, divTarget);
	} else {
		divTarget = document.createElement('div');
		divTarget.id = 'wpcf-confirm-alert';
		document.body.appendChild(divTarget);
		render(<ConfirmBox {...props} />, divTarget);
	}
};

const removeElement = () => {
	const target = document.getElementById('wpcf-confirm-alert');
	unmountComponentAtNode(target);
	target.parentNode.removeChild(target);
};

const addBodyClass = () => {
	document.body.classList.add('wpcf-confirm-alert-body-element');
};

const removeBodyClass = () => {
	document.body.classList.remove('wpcf-confirm-alert-body-element');
};

export default (props) => {
	addBodyClass();
	createElement(props);
};
