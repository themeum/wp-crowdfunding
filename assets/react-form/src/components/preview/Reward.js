import React from 'react';

const PreviewReward = (props) => {
	const { rewards, selectedItem } = props;
	const getValue = (key) => {
		return rewards.length > 0 && rewards[selectedItem]
			? rewards[selectedItem][key]
			: '';
	};

	return (
		<div className='wpcf-preview-reward'>
			<div className='wpcf-reward-box'>
				{getValue('image') && rewards[selectedItem].image.length > 0 ? (
					<div className='wpcf-reward-img'>
						<img src={rewards[selectedItem].image[0].src} />
					</div>
				) : (
					<div className='wpcf-reward-img wpcf-placeholder'>
						<span>No Image</span>
					</div>
				)}
				<div className='wpcf-reward-content'>
					<h3>
						{getValue('title')
							? getValue('title')
							: 'No title added'}{' '}
					</h3>
					<p>{getValue('description')}</p>
					<div className='wpcf-reward-meta'>
						<h6>Estimate Delivery Date</h6>
						{/* <span>{getMonthName(getValue('end_month'))} {getValue('end_year')}</span> */}
						<span>{getValue('estimate_delivery')}</span>
					</div>
				</div>
			</div>
		</div>
	);
};

export default PreviewReward;
