import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { Field, FieldArray, reduxForm, change as changeFieldValue, formValueSelector } from 'redux-form';
import { RenderField, renderRepeatableFields } from './RenderField';

class Reward extends Component {
	constructor(props) {
		super(props);
		this.state = {
			selectedType: 0,
			openForm: false
		}
	}

	render() {
		const { rewardTypes, rewardFields } = this.props;
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
										className={'wpcf-reward-type' (selectedType == index) ? 'active':''}
										onClick={() => this.setState({selectedType: index})}>
										<img src={item.icon} alt={item.title}/>
										<p>{item.title}</p>
									</div>
								)}
							</div>
						}
						{ openForm &&
							<form onSubmit={}>
								{Object.keys(rewardFields).map( field =>
									<div key={field} className='wpcf-form-field'>
										<div className='wpcf-field-title'>{rewardFields[field].title}</div>
										<div className='wpcf-field-desc'>{rewardFields[field].desc}</div>
										{ frewardFields[field].type == 'repeatable' ?
											<FieldArray
												name={field}
												item={rewardFields[field]}
												uploadFile={this._uploadFile}
												removeArrValue={this._removeArrValue}
												component={renderRepeatableFields}/>
											:
											<Field
												name={field}
												item={rewardFields[field]}
												addTag={this._addTag}
												onChangeSelect={this._onChangeSelect}
												uploadFile={this._uploadFile}
												removeArrValue={this._removeArrValue}
												component={RenderField}
												validate={[rewardFields[field].required ? required : notRequred]}/>
										}
									</div>
								)}
							</form>
						}
					</div>
				</div>

				<div className="">
					<h3>Rewards</h3>
					<div>
						<span className="fa fa-icon-plus"/>
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
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
