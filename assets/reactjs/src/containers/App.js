import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { reduxForm, getFormValues, submit } from 'redux-form';
import { fetchFormFields, fetchFormStoryTools, fetchRewardTypes, fetchRewardFields, fetchTeamFields } from '../actions';
import TabBar from '../components/TabBar';
import Footer from '../components/Footer';
import Basic from '../components/form/Basic';
import Story from '../components/form/Story';
import Reward from '../components/form/Reward';
import Team from '../components/form/Team';

const formName = "campaignForm";
const steps = ["Campaign Basics", "Story", "Rewards", "Team"];
class App extends Component {
	constructor (props) {
		super(props);
		this.state = { current: 0, percent: 0 };
		this._prevStep = this._prevStep.bind(this);
		this._nextStep = this._nextStep.bind(this);
		this._onSave = this._onSave.bind(this);
		this._onSubmit = this._onSubmit.bind(this);
    }
    
    componentDidMount() {
        this.props.fetchFormFields();
        this.props.fetchFormStoryTools();
        this.props.fetchRewardTypes();
        this.props.fetchRewardFields();
        this.props.fetchTeamFields();
    }

	_prevStep() {
		this.setState({ current: this.state.current-1 })
	}

	_nextStep() {
		this.props.submit(formName);
	}

	_onSave() {
		const { formValues } = this.props;
		console.log( formValues );
	}

	_onSubmit(values) {
		const { current } = this.state;
		if(current+1 < steps.length) {
			this.setState({ current: current+1 })
		} else {
			console.log(values);
		}
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
							<button onClick={this._onSave}>Save</button>
							<button onClick={this._onSubmit}>Submit</button>
						</div>
					</div>
				</div>
				<div>
					<div className='wpcf-form-wrapper'>
						<TabBar 
							steps={steps}
							current={current}/>

						{ current == 0 && <Basic /> }
						{ current == 1 && <Story /> }
						{ current == 2 && <Reward /> }
						{ current == 3 && <Team /> }

						<Footer
							steps={steps}
							current={current}
							prevStep={this._prevStep}
							nextStep={this._nextStep}/>
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    loading: state.data.loading,
	formValues: getFormValues(formName)(state),
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({ 
        fetchFormFields, 
        fetchFormStoryTools,
        fetchRewardTypes, 
        fetchRewardFields,
		fetchTeamFields,
		getFormValues,
		submit,
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: formName,
    onSubmit: App.prototype._onSubmit, //submit function must be passed to onSubmit
})(App));