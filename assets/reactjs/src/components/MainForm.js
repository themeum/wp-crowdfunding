import React, { Component } from 'react';
import Basic from './form/Basic';
import Story from './form/Story';
import Reward from './form/Reward';
import Team from './form/Team';

class MainForm extends Component {
	render () {
		const { current } = this.props;
		return (
			<div className='wpcf-form-main'>
				{ current == 'basic' && <Basic/> }
				{ current == 'story' && <Story /> }
				{ current == 'reward' && <Reward /> }
				{ current == 'team' && <Team /> }
			</div>
		)
	}
}

export default MainForm;