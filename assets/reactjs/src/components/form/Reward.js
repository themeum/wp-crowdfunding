import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { uploadFiles, removeArrValue  } from '../../Helper';
import { FieldArray, reduxForm, getFormValues, change as changeFieldValue } from 'redux-form';
import { renderRewardFields } from './RenderField';
import PreviewReward from './preview/Reward';

class Reward extends Component {
	constructor(props) {
		super(props);
		this.state = {
			selectedType: 0,
			selectedItem: 0,
			openForm: false,
		}
        this._changeType = this._changeType.bind(this);
        this._uploadFile = this._uploadFile.bind(this);
        this._removeArrValue = this._removeArrValue.bind(this);
        this._addReward = this._addReward.bind(this);
        this._editReward = this._editReward.bind(this);
        this._deleteReward = this._deleteReward.bind(this);
	}

	_uploadFile(type, field, sFiles, multiple) {
        uploadFiles(type, sFiles, multiple).then( (files) => {
            this.props.changeFieldValue('campaignForm', field, files);
        });
	}
	
	_changeType(e) {
		const { value } = e.target
		const { selectedItem } = this.state;
		let { formValues: {rewards} } = this.props;
		rewards = [...rewards];
		rewards[selectedItem].type = value;
		this.props.changeFieldValue('campaignForm', 'rewards', rewards);
	}

    _removeArrValue(index, field, values) {
        values = removeArrValue(values, index);
        this.props.changeFieldValue('campaignForm', field, values);
	}
	
	_addReward() {
		const { selectedType: type } = this.state;
		const { formValues: {rewards} } = this.props;
		this.props.changeFieldValue('campaignForm', 'rewards', [...rewards, {type}]);
		this.setState({ openForm: true });
	}

	_editReward(index) {
		this.setState({openForm: true, selectedItem: index})
	}
	
	_deleteReward(index) {
		const { formValues: {rewards} } = this.props;
		const openForm = (index==0 && rewards.length==1) ? false : true;
		const selectedItem = (index==0) ? 0 : index-1;
		const values = removeArrValue(rewards, index);
		this.setState({openForm, selectedItem});
		this.props.changeFieldValue('campaignForm', 'rewards', values);
	}

	render() {
		const { openForm, selectedType, selectedItem } = this.state;
		const { rewardTypes, rewardFields, formValues: {rewards} } = this.props;
		const { options: months } = rewardFields.estimate_delivery.fields.end_month;
		return (
			<div className="row">
                <div className='col-md-7'>
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
										rewards={rewards}
										rewardTypes={rewardTypes}
										selectedItem={selectedItem}
										rewardFields={rewardFields}
										onChangeType={this._changeType}
										uploadFile={this._uploadFile}
										removeArrValue={this._removeArrValue}
										component={renderRewardFields}/>
								</form>
							}
						</div>
					</div>
					<div className="">
						<h3>Rewards</h3>
						{rewards && rewards.map((item, index) =>
							<div key={index} className={`wpcf-reward-item ${(selectedItem == index) ? 'active':''}`}>
								<p>Reward {index+1}</p>
								<span className="fa fa-trash" onClick={ () => this._deleteReward(index)}/>
								<span className="fa fa-pencil" onClick={ () => this._editReward(index)}/>
							</div>
						)}
						<div className="wpcf-reward-item" onClick={() => this._addReward()}>
							<span className="fa fa-plus"/>
						</div>
					</div>
				</div>
				<div className='col-md-5'>
                    <div className='wpcf-form-sidebar'>
                        <div className="preview-title">Preview</div>
						<PreviewReward
							months={months}
							rewards={rewards}
							selectedItem={selectedItem}/>
                    </div>
                </div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    rewardTypes: state.data.rewardTypes,
	rewardFields: state.data.rewardFields,
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
})(Reward));
