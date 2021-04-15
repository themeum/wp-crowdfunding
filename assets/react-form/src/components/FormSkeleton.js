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
					<button class='wpcf-tab-title active skeleton-bg'>
						<span>1</span> Campaign Basics
					</button>
					<button class='wpcf-tab-title skeleton-bg'>
						<span>2</span> Story
					</button>
					<button class='wpcf-tab-title skeleton-bg'>
						<span>3</span> Rewards
					</button>
					<button class='wpcf-tab-title skeleton-bg'>
						<span>4</span> Team
					</button>
				</div>
				<div class='row'>
					<div class='col-md-7'>
						<div class='wpcf-accordion-wrapper'>
							<div class='wpcf-accordion'>
								<div class='wpcf-accordion-title active skeleton-bg'>
									{' '}
									campaign info
								</div>
							</div>
						</div>
					</div>
					<div class='col-md-5'>
						<div class='wpcf-form-sidebar'>
							<div class='preview-title skeleton-bg'>
								{' '}
								Preview
							</div>
							<div class='wpcf-preview-empty'></div>
						</div>
					</div>
				</div>
			</div>
		</Fragment>
	);
};

export default FormSkeleton;
