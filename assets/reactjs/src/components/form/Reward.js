import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { required, notRequred, uploadFiles, removeArrValue  } from '../../Helper';
import { FormSection, Field, FieldArray, reduxForm, getFormValues, change as changeFieldValue, formValueSelector } from 'redux-form';
import { RenderField, renderRepeatableFields } from './RenderField';

class Reward extends Component {
	constructor(props) {
		super(props);
		this.state = {
			selectedType: 0,
			selectedItem: 0,
			openForm: false,
		}
        this._uploadFile = this._uploadFile.bind(this);
        this._removeArrValue = this._removeArrValue.bind(this);
        this._addReward = this._addReward.bind(this);
	}

	_uploadFile(type, field, sFiles, multiple) {
        uploadFiles(type, sFiles, multiple).then( (files) => {
            this.props.changeFieldValue('campaignForm', field, files);
        });
    }

    _removeArrValue(index, field, values) {
        values = removeArrValue(values, index);
        this.props.changeFieldValue('campaignForm', field, values);
	}
	
	_addReward() {
		const { formValues: {rewards} } = this.props;
		this.props.changeFieldValue('campaignForm', 'rewards', [...rewards, {}]);
		this.setState({ openForm: true });
	}

	render() {
		const { openForm, selectedType, selectedItem } = this.state;
		const { rewardTypes, rewardFields, formValues: {rewards} } = this.props;
		console.log(rewards);
		return (
			<div>
				<div className="wpcf-accordion-wrapper">
					<div className="wpcf-accordion-title active">
						Create Rewards
					</div>
					<div className='wpcf-accordion-details'>
						{ !openForm &&
							<div>
								<p>Tell potential contributors more about your campaign. Provide details that will motivate people to contribute. A good pitch is compelling, informative, and easy to digest.</p>
								{rewardTypes.map((item, index) =>
									<div
										key={index}
										className={`wpcf-reward-type ${(selectedType == index) ? 'active':''}`}
										onClick={() => this.setState({selectedType: index})}>
										<img src={item.icon} alt={item.title}/>
										<p>{item.title}</p>
									</div>
								)}
							</div>
						}
						{ openForm &&
							<form>
								<FieldArray
									name="rewards"
									component={ ({ fields }) => (
										<div>
											{fields.map( (field, index) => (
                								<div key={index}>
													{Object.keys(rewardFields).map( key =>
														<div key={key} className='wpcf-form-field'>
															<div className='wpcf-field-title'>{rewardFields[key].title}</div>
															<div className='wpcf-field-desc'>{rewardFields[key].desc}</div>
															{ rewardFields[key].type == 'repeatable' ?
																<FieldArray
																	name={`${field}.${key}`}
																	item={rewardFields[key]}
																	uploadFile={this._uploadFile}
																	removeArrValue={this._removeArrValue}
																	component={renderRepeatableFields}/>
																:
																<Field
																	name={`${field}.${key}`}
																	item={rewardFields[key]}
																	uploadFile={this._uploadFile}
																	removeArrValue={this._removeArrValue}
																	component={RenderField}
																	validate={[rewardFields[key].required ? required : notRequred]}/>
															}
														</div>
													)}
												</div>
											))}
										</div>
									)
								}/>
							</form>
						}
					</div>
				</div>

				<div className="">
					<h3>Rewards</h3>
					{rewards && rewards.map((item, index) =>
						<div
							key={index}
							className={`wpcf-reward-item ${(selectedItem == index) ? 'active':''}`}
							onClick={() => this.setState({openForm: true, selectedItem: index})}>
							<p>Reward {index+1}</p>
						</div>
					)}
					<div className="wpcf-reward-item" onClick={this._addReward}>
						<span className="fa fa-plus"/>
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
	formValues: getFormValues('campaignForm')(state),
    rewardTypes: state.data.rewardTypes,
	rewardFields: state.data.rewardFields
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
