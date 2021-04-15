import React, { Component, Fragment } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { reduxForm, getFormValues } from 'redux-form';
import { fetchFormFields, fetchFormValues, saveCampaign } from './actions';
import TabBar from './components/TabBar';
import Basic from './components/Basic';
import Story from './components/Story';
import Reward from './components/Reward';
import Team from './components/Team';
import ConfirmSave from './components/ConfirmSave';

const formName = 'campaignForm';
const components = { basic: Basic, story: Story, reward: Reward, team: Team };
class App extends Component {
	constructor(props) {
		super(props);
		this.state = { current: 0, percent: 0 };
		this._prevStep = this._prevStep.bind(this);
		this._nextStep = this._nextStep.bind(this);
		this._onSave = this._onSave.bind(this);
	}

	componentDidMount() {
		const { editPostId } = this.props;
		this.props.fetchFormFields(editPostId);
		if (editPostId) {
			this.props.fetchFormValues(editPostId);
		}
	}

	componentDidUpdate() {
		const { submit, submitData } = this.props.data;
		if (submit) {
			ConfirmSave(submitData);
			//alert(submitData.message);
			//location.href = submitData.redirect;
		}
	}

	_prevStep() {
		this.setState({ current: this.state.current - 1 });
	}

	_nextStep() {
		this.setState({ current: this.state.current + 1 });
	}

	_onSave(submit) {
		// console.log('save');
		let { formValues, data } = this.props;
		formValues.submit = submit; //inject submit value with form values
		formValues.postId = data.postId; //inject postid value with form values
		this.props.saveCampaign(formValues);
	}

	render() {
		const {
			loading,
			steps,
			saveDate,
			recaptcha,
			validateRecaptcha,
		} = this.props.data;
		const { current } = this.state;

		if (loading) {
			return <div>Loading...</div>;
		}

		const formSteps = Object.keys(steps);
		const lastStep = current + 1 == formSteps.length; //is last step
		return (
			<Fragment>
				<div className='wpcf-campaign-header'>
					<h3>Setup New Campaign</h3>
					<div className='wpcf-campaign-header-right'>
						{saveDate && <span>Last edit was on {saveDate}</span>}
						<button
							className='wpcf-btn wpcf-btn-round wpcf-btn-outline'
							onClick={() => this._onSave(false)}
						>
							<i className='far fa-save wpcf-icon'></i> Save
						</button>
						<button
							className='wpcf-btn wpcf-btn-round'
							onClick={() => this._onSave(true)}
							disabled={
								(recaptcha && !validateRecaptcha) || !lastStep
							}
						>
							Submit
						</button>
					</div>
				</div>
				<div className='wpcf-campaign-body'>
					<TabBar steps={steps} current={current} />

					{formSteps.map((key, index) => {
						const FormComponent = components[key];
						if (index == current) {
							return (
								<FormComponent
									key={index}
									current={current}
									lastStep={lastStep}
									prevStep={() => this._prevStep()}
									onSubmit={
										lastStep
											? () => this._onSave(true)
											: this._nextStep
									}
								/>
							);
						}
					})}
				</div>
			</Fragment>
		);
	}
}

const mapStateToProps = (state) => ({
	data: state.data,
	formValues: getFormValues(formName)(state),
	initialValues: state.data.initialValues,
	recaptcha: state.data.recaptcha,
	validateRecaptcha: state.data.validateRecaptcha,
});

const mapDispatchToProps = (dispatch) => {
	return bindActionCreators(
		{
			fetchFormFields,
			fetchFormValues,
			getFormValues,
			saveCampaign,
		},
		dispatch
	);
};

export default connect(
	mapStateToProps,
	mapDispatchToProps
)(
	reduxForm({
		form: formName,
		enableReinitialize: true,
	})(App)
);
