import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { reduxForm, getFormValues } from 'redux-form';
import { fetchFormFields, fetchFormStoryTools, fetchRewardFields, fetchTeamFields, saveCampaign } from './actions';
import TabBar from './components/TabBar';
import Basic from './components/Basic';
import Story from './components/Story';
import Reward from './components/Reward';
import Team from './components/Team';

const formName = "campaignForm";
const steps = ["Campaign Basics", "Story", "Rewards", "Team"];
class App extends Component {
	constructor (props) {
		super(props);
		this.state = { current: 0, percent: 0 };
		this._prevStep = this._prevStep.bind(this);
		this._nextStep = this._nextStep.bind(this);
		this._onSave = this._onSave.bind(this);
    }
    
    componentDidMount() {
        this.props.fetchFormFields();
        this.props.fetchFormStoryTools();
        this.props.fetchRewardFields();
		this.props.fetchTeamFields();
		
		if(this.props.postId) {
			this.props.fetchFormValues();
		}
    }

	_prevStep() {
		this.setState({ current: this.state.current-1 })
	}

	_nextStep() {
		this.setState({ current: this.state.current+1 })
	}

	_onSave(submit) {
		let { formValues, postId } = this.props;
		formValues.postId = postId; //inject submit value with form values
		formValues.submit = submit; //inject submit value with form values
		this.props.saveCampaign(formValues);
	}

	render() {
		const { loading } = this.props;
        const { current } = this.state;
		
        if(loading) {
            return (
                <div>Loading...</div>
            )
		}
		
		return (
			<div>
				<div style={ {borderBottom: '1px solid #dcdce4'} }>
					<div className='wpcf-form-wrapper'>
						<div className="wpcf-form-edit-panel">
							<span>Setup New Campaign</span>
							<span>Last Edit was on 01 july</span>
							<button onClick={() => this._onSave(false)}>Save</button>
							<button onClick={() => this._onSave(true)} disabled={current<3}>Submit</button>
						</div>
					</div>
				</div>
				<div>
					<div className='wpcf-form-wrapper'>
						<TabBar 
							steps={steps}
							current={current}/>

						{ current == 0 && 
							<Basic
								current={current}
								prevStep={this._prevStep}
								onSubmit={this._nextStep}/> 
						}
						{ current == 1 && 
							<Story
								current={current}
								prevStep={this._prevStep}
								onSubmit={this._nextStep}/> 
						}
						{ current == 2 && 
							<Reward
								current={current}
								prevStep={this._prevStep}
								onSubmit={this._nextStep}/> 
						}
						{ current == 3 && 
							<Team
								current={current}
								prevStep={this._prevStep}
								onSubmit={this._onSubmit}/> 
						}
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    postId: state.data.postId,
    loading: state.data.loading,
	formValues: getFormValues(formName)(state),
	initialValues: state.data.initialValues,
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({ 
        fetchFormFields,
        fetchFormStoryTools,
        fetchRewardFields,
		fetchTeamFields,
		getFormValues,
		saveCampaign,
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: formName,
})(App));