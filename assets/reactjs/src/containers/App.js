import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { fetchFormFields, fetchFormStoryTools, fetchRewardTypes, fetchRewardFields, fetchTeamFields } from '../actions';
import TabBar from '../components/TabBar';
import Content from '../components/Content';
import Footer from '../components/Footer';

class App extends Component {
	constructor (props) {
		super(props)
		this.state = { selectForm: 'basic', percent: 0 }
    }
    
    componentDidMount() {
        this.props.fetchFormFields();
        this.props.fetchFormStoryTools();
        this.props.fetchRewardTypes();
        this.props.fetchRewardFields();
        this.props.fetchTeamFields();
    }

	onSet(val) {
		this.setState({selectForm:val})
	}

	render() {
        const { selectForm } = this.state;
        
        if(this.props.loading) {
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
							<button>Save</button>
							<button>Submit</button>
						</div>
					</div>
				</div>
				<div>
					<div className='wpcf-form-wrapper'>
						<TabBar current={selectForm} />
						<Content current={selectForm}/>
						<Footer current={selectForm} onSet={val=>this.onSet(val)}/>
					</div>
				</div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    loading: state.data.loading
});

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ 
        fetchFormFields, 
        fetchFormStoryTools, 
        fetchRewardTypes, 
        fetchRewardFields,
        fetchTeamFields,
    }, dispatch);
}

export default connect( mapStateToProps, mapDispatchToProps )(App);