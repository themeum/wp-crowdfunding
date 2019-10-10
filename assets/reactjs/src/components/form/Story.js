import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { uploadFiles, removeArrValue  } from '../../Helper';
import { reduxForm, getFormValues, change as changeFieldValue } from 'redux-form';
import RenderStoryEditor from './RenderStoryEditor';
import PreviewStory from './preview/Story';

class Story extends Component {
	constructor(props) {
		super(props);
		this._addItem = this._addItem.bind(this);
        this._editItem = this._editItem.bind(this);
        this._deleteItem = this._deleteItem.bind(this);
        this._moveItem = this._moveItem.bind(this);
        this._upload = this._upload.bind(this);
	}

	_addItem(type) {
		const { formValues: {story} } = this.props;
		this.props.changeFieldValue('campaignForm', 'story', [...story, {type, value:{}}]);
	}

	_editItem(index, value) {
		this.props.changeFieldValue('campaignForm', `story[${index}].value`, value);
	}
	
	_deleteItem(index) {
		const { formValues: {story} } = this.props;
		const values = removeArrValue(story, index);
		this.props.changeFieldValue('campaignForm', 'story', values);
	}

	_moveItem( index, moveTo ) {
		let { formValues: {story} } = this.props;
		story = [ ...story ];
        const moveIndex = ( moveTo == 'left' ) ? index-1 : index+1;
        const movableItem = story[ index ];
        story[ index ] = story[ moveIndex ];
		story[ moveIndex ] = movableItem;
		this.props.changeFieldValue('campaignForm', 'story', story);
    }

	_upload(index, type, sFiles) {
        uploadFiles(type, sFiles, false).then( (files) => {
			this.props.changeFieldValue('campaignForm', `story[${index}].value`, files);
        });
    }

	render() {
		const { tools, formValues: {story} } = this.props;
		return (
			<div className="row">
                <div className='col-md-7'>
					<div className="wpcf-accordion-wrapper">
						<div className="wpcf-accordion-title active">
							Add Brief Story
						</div>
						<div className='wpcf-accordion-details'>
							<p>Write a Clear, Brief Title that Helps People Quickly Understand the Gist of your Project.</p>
							<div className="wpcf-story-tools">
								{ Object.keys(tools).map( key => 
									<div key={key} className="story-tool-item">
										<img src={tools[key].name}/>
										<p>{tools[key].name}</p>
										<i className="fa fa-plus" onClick={() => this._addItem(key)} />
									</div>
								)}
							</div>
							<div className="wpcf-story-values">
								{ story.map( (item, index) => 
									<RenderStoryEditor 
										key={index} 
										data={item}
										index={index}
										edit={this._editItem}
										deleteItem={this._deleteItem}
										move={this._moveItem}
										upload={this._upload}/>
								)}
							</div>
						</div>
					</div>
				</div>
				<div className='col-md-5'>
                    <div className='wpcf-form-sidebar'>
                        <div className="preview-title">Preview</div>
                        <PreviewStory data={story}/>
                    </div>
                </div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
    tools: state.data.storyTools,
	formValues: getFormValues('campaignForm')(state)
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({ 
        getFormValues, 
        changeFieldValue
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
	form: 'campaignForm',
	destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Story));
