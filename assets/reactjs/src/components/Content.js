import React, { Component } from 'react';
import Basic from './form/Basic';
import Story from './form/Story';
import Reward from './form/Reward';
import Team from './form/Team';
import Sidebar from './Sidebar';

class MainForm extends Component {
	constructor(props) {
		super(props);
	}
	
	render () {
		const { current } = this.props;
		return (
			<div className="row">
				<div className='col-md-7'>
					{ current == 'basic' && <Basic/> }
					{ current == 'story' && <Story /> }
					{ current == 'reward' && <Reward /> }
					{ current == 'team' && <Team /> }
				</div>
				<div className='col-md-5'>
					<Sidebar current={current}/>
				</div>
			</div>
				
		)
	}
}

export default MainForm;