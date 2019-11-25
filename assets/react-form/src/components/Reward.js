import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { uploadFiles, removeArrValue  } from '../Helper';
import { FieldArray, reduxForm, getFormValues, change as changeFieldValue } from 'redux-form';
import RenderRewardFields from './fields/Reward';
import PreviewReward from './preview/Reward';
import PageControl from './Control';
import Preview from "./preview/Preview";
import Icon from './Icon'

const formName = "campaignForm";
const sectionName = "rewards";
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
            this.props.changeFieldValue(formName, field, files);
        });
	}

	_changeType(e) {
		const { value } = e.target;
		const { selectedItem } = this.state;
		let { formValues: {rewards} } = this.props;
		rewards = [...rewards];
		rewards[selectedItem].type = value;
		this.props.changeFieldValue(formName, sectionName, rewards);
	}

    _removeArrValue(type, index, field, values) {
        values = removeArrValue(values, index);
        this.props.changeFieldValue(formName, field, values);
	}

	_addReward() {
		const { selectedType } = this.state;
		let { formValues: {rewards} } = this.props;
		rewards = [ ...rewards ]; rewards.push({type:selectedType});
		this.props.changeFieldValue(formName, sectionName, rewards);
		const selectedItem = rewards.length-1;
		this.setState({ openForm: true, selectedItem});
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
		this.props.changeFieldValue(formName, sectionName, values);
	}

	render() {
		const { openForm, selectedType, selectedItem } = this.state;
		const { rewardTypes, rewardFields, formValues: {rewards}, handleSubmit, current, prevStep, lastStep } = this.props;
		const { options: months } = rewardFields.estimate_delivery.fields.end_month;
		return (
			<div className="row">
                <div className='col-md-7'>
					<form onSubmit={handleSubmit}>
						<div className="wpcf-accordion-wrapper">
							<div className="wpcf-accordion">
								<div className="wpcf-accordion-title active">
									Create Rewards
								</div>
								<div className='wpcf-accordion-details'>
									{ !openForm &&
										<div className="wpcf-rewards-content">
											<p>Tell potential contributors more about your campaign. Provide details that will motivate people to contribute. A good pitch is compelling, informative, and easy to digest.</p>
											<div className="wpcf-rewards-options">
												{rewardTypes.map((item, index) =>
													<div
														key={index}
														className={`wpcf-rewards-option ${(selectedType == index) ? 'active':''}`}
														onClick={() => this.setState({selectedType: index})}>
														<Icon name={item.icon}/>
														{/*<span className="fas fa-shopping-cart"></span>*/}
														<p>{item.title}</p>
													</div>
												)}
											</div>
										</div>
									}
									{ openForm &&
										<FieldArray
											name={sectionName}
											rewards={rewards}
											rewardTypes={rewardTypes}
											selectedItem={selectedItem}
											rewardFields={rewardFields}
											onChangeType={this._changeType}
											uploadFile={this._uploadFile}
											removeArrValue={this._removeArrValue}
											component={RenderRewardFields} />
									}
								</div>
							</div>
						</div>
						<div className="wpcf-rewards">
							<h3>Rewards</h3>
							<div className="wpcf-add-reward-lists">
								{
									rewards && rewards.map((item, index) => {
										return (
											<div key={index} className={`wpcf-reward-item ${(selectedItem == index) ? 'active' : ''}`}>
												{
													rewards[index].image !== undefined && rewards[index].image.length !== 0 ? (
														<img className="wpcf-reward-item-image" src={rewards[index].image[0].src} alt=""/>
													) : (
														<span className="wpcf-reward-item-placeholder">No image</span>
													)
												}
												{item.title && <h6>{item.title}</h6>}
												{item.description && <p>{item.description}</p>}
												<div className="wpcf-reward-item-overlay">
													<Icon tabIndex="0" name="delete" onClick={() => this._deleteReward(index)}/>
													<Icon tabIndex="0" name="pen" onClick={() => this._editReward(index)}/>
												</div>
											</div>
										)
									})
								}
								<div className="wpcf-reward-item wpcf-reward-item-empty" onClick={() => this._addReward()}>
									<span className="fa fa-plus"/>
								</div>
							</div>
						</div>
						<PageControl current={current} prevStep={prevStep} lastStep={lastStep} />
					</form>
				</div>
				<div className='col-md-5'>
					<Preview title="Preview">
						<PreviewReward
							months={months}
							rewards={rewards}
							selectedItem={selectedItem}/>
					</Preview>
                </div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    rewardTypes: state.data.reward_types,
	rewardFields: state.data.reward_fields,
	formValues: getFormValues(formName)(state)
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({
        getFormValues,
        changeFieldValue
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
	form: formName,
	destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Reward));
