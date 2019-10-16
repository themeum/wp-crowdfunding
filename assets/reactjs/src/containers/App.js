import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { getFormValues, submit } from 'redux-form';
import { fetchFormFields, fetchFormStoryTools, fetchRewardTypes, fetchRewardFields, fetchTeamFields } from '../actions';
import TabBar from '../components/TabBar';
import Content from '../components/Content';
import Footer from '../components/Footer';

class App extends Component {
	constructor (props) {
		super(props);
		this.state = { selectForm: 'basic', percent: 0 };
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

	_onSet(val) {
		this.setState({selectForm:val})
	}

	_onSave() {
		const { formValues } = this.props;
		console.log( formValues );
	}

	_onSubmit() {
		this.props.submit('campaignForm');
	}

	render() {
		const { loading } = this.props;
        const { selectForm } = this.state;
        
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
						<TabBar current={selectForm} />
						<Content current={selectForm} onSubmit={this._onSubmit}/>
						<Footer current={selectForm} onSet={val=>this._onSet(val)}/>
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    loading: state.data.loading,
	formValues: getFormValues('campaignForm')(state)
});

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ 
        fetchFormFields, 
        fetchFormStoryTools,
        fetchRewardTypes, 
        fetchRewardFields,
		fetchTeamFields,
		getFormValues,
		submit
    }, dispatch);
}

export default connect( mapStateToProps, mapDispatchToProps )(App);