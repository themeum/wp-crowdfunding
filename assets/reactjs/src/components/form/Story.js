import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { FieldArray, reduxForm, getFormValues, change as changeFieldValue } from 'redux-form';
import PreviewStory from './preview/Story';

class Story extends Component {
	constructor(props) {
		super(props);
	}


	render() {
		const { tools } = this.props;
		return (
			<div className="row">
                <div className='col-md-7'>
					<div className="wpcf-accordion-wrapper">
						<div className="wpcf-accordion-title active">
							Add Brief Story
						</div>
						<div className='wpcf-accordion-details'>
							<p>Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.</p>
							<div className="wpcf-story-tools">
								{ Object.keys(tools).map( key => 
									<div key={key} className="story-tool-item">
										<img src={tools[key].name}/>
										<p>{tools[key].name}</p>
									</div>
								)}
							</div>
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
    tools: state.data.storyTools,
	formValues: getFormValues('campaignForm')(state)
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({ 
        getFormValues, 
        changeFieldValue
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
	form: 'campaignForm',
	destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Story));
