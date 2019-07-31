import React, { Component } from 'react'
import ReactDOM from 'react-dom'
import MainForm from './MainForm'
import Sidebar from './Sidebar'
import TopBar from './TopBar'
import Footer from './Footer'

class Wrapper extends Component {
	constructor (props) {
			super(props)
			this.state = { selectForm: 'basic', percent: 0 }
	}

	onSet(val) {
		this.setState({selectForm:val})
	}

	render () {
		const { selectForm } = this.state
		return (
			<div className='wpcf-form-wrapper'>
				<TopBar current={selectForm} onSet={val=>this.onSet(val)} />
				<MainForm current={selectForm} />
				<Sidebar />
				<Footer current={selectForm} onSet={val=>this.onSet(val)}/>
			</div>
		)
	}
}

ReactDOM.render(<Wrapper />, document.getElementById('wpcf-live-form'))