import React, { Fragment } from 'react';

const FormSkeleton = () => {
	return (
		<Fragment>
			<div className='is-skeleton'>
				<div className='wpcf-campaign-header'>
					<h3>Setup New Campaign</h3>
					<div className='wpcf-campaign-header-right'>
						<button className='wpcf-btn wpcf-btn-round wpcf-btn-outline skeleton-bg'>
							Save
						</button>
						<button className='wpcf-btn wpcf-btn-round skeleton-bg'>
							Submit
						</button>
					</div>
				</div>
			</div>

			<div className='wpcf-campaign-body is-skeleton'>
				<div className='wpcf-form-tabs-menu'>
					<button className='wpcf-tab-title active skeleton-bg'>
						<span>1</span> Campaign Basics
					</button>
					<button className='wpcf-tab-title skeleton-bg'>
						<span>2</span> Story
					</button>
					<button className='wpcf-tab-title skeleton-bg'>
						<span>3</span> Rewards
					</button>
					<button className='wpcf-tab-title skeleton-bg'>
						<span>4</span> Team
					</button>
				</div>
				<div className='row'>
					<div className='col-md-7'>
						<div className='wpcf-accordion-wrapper'>
							<div className='wpcf-accordion'>
								<div className='wpcf-accordion-title active skeleton-bg'>
									{' '}
									campaign info
								</div>
							</div>
						</div>
					</div>
					<div className='col-md-5'>
						<div className='wpcf-form-sidebar'>
							<div className='preview-title skeleton-bg'>
								{' '}
								Preview
							</div>
							<div className='wpcf-preview-empty'></div>
						</div>
					</div>
				</div>
			</div>
		</Fragment>
	);
};

export default FormSkeleton;
