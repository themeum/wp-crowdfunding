import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { Field, FieldArray, reduxForm, change as changeFieldValue, formValueSelector } from 'redux-form';

class Reward extends Component {
	render() {
		return (
			<div className='wpcf-accordion-wrapper'>
				Reward Content
      		</div>
		)
	}
}

const mapStateToProps = state => ({
    fields: state.data.formFields
});

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ 
        changeFieldValue, 
        formValueSelector
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
	form: 'campaignForm',
	destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Reward));
