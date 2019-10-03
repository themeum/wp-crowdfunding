import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { reduxForm, change as changeFieldValue, formValueSelector } from 'redux-form';
import PreviewStory from './preview/Story'

class Story extends Component {
	render() {
		return (
			<div className="row">
                <div className='col-md-7'>
					<div className="wpcf-accordion-wrapper">
						<div className="wpcf-accordion-title active">
							Add Brief Story
						</div>
						<div className='wpcf-accordion-details'>
							<p>Story Content</p>
						</div>
					</div>
				</div>
				<div className='col-md-5'>
                    <div className='wpcf-form-sidebar'>
                        <div className="preview-title">Preview</div>
                        <PreviewStory />
                    </div>
                </div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    fields: state.data.formFields
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({ 
        changeFieldValue, 
        formValueSelector
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
	form: 'campaignForm',
	destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Story));
