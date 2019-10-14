import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { uploadFiles, removeArrValue  } from '../../Helper';
import { reduxForm, getFormValues, change as changeFieldValue } from 'redux-form';
import RenderStoryItem from './RenderStoryItem';
import PreviewStory from './preview/Story';

class Story extends Component {
	constructor(props) {
		super(props);
		this._addItem = this._addItem.bind(this);
        this._editItem = this._editItem.bind(this);
        this._moveItem = this._moveItem.bind(this);
        this._swapItem = this._swapItem.bind(this);
        this._removeItem = this._removeItem.bind(this);
        this._upload = this._upload.bind(this);
	}

	_addItem(type) {
		const { formValues: {story} } = this.props;
		let newItem = {type, value:''};
		if(type == 'text_image') {
			newItem = [{type:'text', value:''}, {type:'image', value:''}];
		} else if(type == 'image_image') {
			newItem = [{type:'image', value:''}, {type:'image', value:''}];
		} else if(type == 'text_text') {
			newItem = [{type:'text', value:''}, {type:'text', value:''}];
		} else if(type == 'text_video') {
			newItem = [{type:'text', value:''}, {type:'video', value:''}];
		}
		this.props.changeFieldValue('campaignForm', 'story', [...story, newItem]);
	}

	_editItem(name, value) {
		this.props.changeFieldValue('campaignForm', name, value);
	}

	_moveItem( index, moveTo ) {
		let { formValues: {story} } = this.props;
		story = [ ...story ];
        const moveIndex = ( moveTo == 'top' ) ? index-1 : index+1;
        const movableItem = story[ index ];
        story[ index ] = story[ moveIndex ];
		story[ moveIndex ] = movableItem;
		this.props.changeFieldValue('campaignForm', 'story', story);
	}
	
	_swapItem( index ) {
		let { formValues: {story} } = this.props;
		story = [ ...story ];
		const item = story[ index ];
		story[index] = [ item[1], item[0] ];
		this.props.changeFieldValue('campaignForm', 'story', story);
    }
	
	_removeItem(index) {
		const { formValues: {story} } = this.props;
		const values = removeArrValue(story, index);
		this.props.changeFieldValue('campaignForm', 'story', values);
	}

	_upload(name, type, sFiles) {
        uploadFiles(type, sFiles, false).then( (files) => {
			this.props.changeFieldValue('campaignForm', name, files);
        });
    }

	render() {
		const { tools, formValues: {story} } = this.props;
		return (
			<div className="row">
                <div className='col-md-7'>
					<div className="wpcf-accordion-wrapper">
						<div className="wpcf-accordion">
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
									{ story.map((data, index) => 
										<div key={index} className="wpcf-story-item">
											{ data && data.length > 0 ?
												<div className="story-item-multiple">
													{ data.map((item, i) =>
														<RenderStoryItem
															data={item}
															key={index+i}
															edit={this._editItem}
															upload={this._upload}
															name={`story[${index}][${i}].value`}
														/>
													)}
													<div className="story-item-swap-control">
														<span onClick={ () => this._swapItem( index ) }>
															<i className="fa fa-long-arrow-right"/>
															<i className="fa fa-long-arrow-left"/>
														</span>
													</div>
												</div>
											: 	<RenderStoryItem
													data={data}
													edit={this._editItem}
													upload={this._upload}
													name={`story[${index}].value`}
												/>
											}

											<div className="story-item-control">
												<span onClick={ () => this._moveItem( index, 'top' ) } className={ ( index == 0 ) && 'item-move-disable' }><i className="fa fa-long-arrow-up"/></span>
												<span onClick={ () => this._moveItem( index, 'bottom' ) } className={ ( index == story.length-1 ) && 'item-move-disable' }><i className="fa fa-long-arrow-down"/></span>
												<span onClick={ () => this._removeItem( index ) }><i className="fa fa-trash"/></span>
											</div>
										</div>
									)}
								</div>
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
