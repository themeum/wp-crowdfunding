import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchFormFields } from '../actions';
import TabBar from '../components/TabBar';
import MainForm from '../components/MainForm';
import Sidebar from '../components/Sidebar';
import Footer from '../components/Footer';

class App extends Component {
	constructor (props) {
		super(props)
		this.state = { selectForm: 'basic', percent: 0 }
    }
    
    componentDidMount() {
        this.props.fetchFormFields();
    }

	onSet(val) {
		this.setState({selectForm:val})
	}

	render() {
		const { selectForm } = this.state
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
						<MainForm current={selectForm} />
						<Sidebar />
						<Footer current={selectForm} onSet={val=>this.onSet(val)}/>
					</div>
				</div>
			</div>
		)
	}
}

export default connect( '', { fetchFormFields } )(App);