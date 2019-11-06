import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { required, getYotubeVideoID, uploadFiles, removeArrValue } from '../Helper';
import { FormSection, Field, FieldArray, reduxForm, getFormValues, change as changeFieldValue } from 'redux-form';
import { fetchSubCategories, fieldShowHide } from '../actions';
import RenderField from './fields/Single';
import RenderRepeatableFields from './fields/Repeatable';
import PreviewBasic from './preview/Basic';
import PageControl from './Control';

const formName = "campaignForm";
const sectionName = "basic";
class Basic extends Component {
    constructor(props) {
        super(props);
        this.state = { sectionActive: 0 };
        this._onBlurVideoLink = this._onBlurVideoLink.bind(this);
        this._removeArrValue = this._removeArrValue.bind(this);
        this._uploadFile = this._uploadFile.bind(this);
        this._addTag = this._addTag.bind(this);
    }

    componentDidUpdate(prevProps) {
        const { formValues: {basic: curVal} } =  this.props;
        const { formValues: {basic: prevVal} } = prevProps;
        if( curVal.category && curVal.category !== prevVal.category) {
            this.props.fetchSubCategories(curVal.category);
            this.props.changeFieldValue(formName, `${sectionName}.sub_category`, null);
        }
        if( curVal.goal_type && curVal.goal_type !== prevVal.goal_type) {
            const field = 'media.if_target_date';
            const show = (curVal.goal_type=='target_date') ? true : false;
            this.props.fieldShowHide(field, show);
        }
    }

    _onBlurVideoLink() {
        const files = [];
        const type = 'video_link';
        const { formValues: {basic: {video_link} } } =  this.props;
        video_link.map( i => {
            const id = getYotubeVideoID(i.src);
            if(id) {
                const item = {
                    id: id,
                    type: type,
                    src: i.src,
                    thumb: `https://img.youtube.com/vi/${id}/default.jpg`,
                };
                files.push(item);
            }
        });
        setTimeout(() => {
            this.addMediaFile(files);
            this.removeMediaFile(type, files);
        }, 300)
    }

    _removeArrValue(type, index, field, values, is_media) {
        values = removeArrValue(values, index);
        this.props.changeFieldValue(formName, field, values);
        if( is_media ) {
            this.removeMediaFile(type, values);
        }
    }

    _uploadFile(type, field, sFiles, multiple, is_media) {
        uploadFiles(type, sFiles, multiple).then( (files) => {
            this.props.changeFieldValue(formName, field, files);
            if( is_media ) {
                this.addMediaFile(files);
                this.removeMediaFile(type, files);
            }
        });
    }

    _addTag(tag, field, selectedTags) {
        selectedTags = [...selectedTags];
        if( selectedTags.findIndex( item => item.value == tag.value) === -1 ) {
            selectedTags.push(tag);
            this.props.changeFieldValue(formName, field, [...selectedTags]);
        }
    }

    addMediaFile(files) {
        const { formValues: { basic }, changeFieldValue } =  this.props;
        let media = [ ...basic.media ];
        files.map((item) => {
            const index = media.findIndex(i => i.id === item.id);
            if(index === -1) {
                media.push(item); //add files in media store which is not added before
            }
        });
        changeFieldValue(formName, `${sectionName}.media`, media);
    }

    removeMediaFile(type, files) {
        const { formValues: { basic }, changeFieldValue } =  this.props;
        let media = [ ...basic.media ];
        media.filter((item) => item.type === type).map((item, i) => {
            const index = files.findIndex(i => i.id === item.id);
            if(index === -1) {
                const mIndex = media.findIndex(i => i.id === item.id);
                media.splice(mIndex, 1); //remove files from media store which is unselected
            }
        });
        changeFieldValue(formName, `${sectionName}.media`, media);
    }

    render() {
        const { sectionActive } = this.state;
        const { fields, formValues, handleSubmit, current, prevStep } =  this.props;
        const basicValues = (formValues && formValues.hasOwnProperty(sectionName)) ? formValues[sectionName] : {};
        return (
            <div className="row">
                <div className='col-md-7'>
                    <form onSubmit={handleSubmit}>
                        <FormSection name={sectionName}>
                            <div className='wpcf-accordion-wrapper'>
                            {Object.keys(fields).map( (section, index) =>
                                    <div key={section} className='wpcf-accordion'>
                                        <div className={`wpcf-accordion-title ${index == sectionActive ? 'active' : ''}`} onClick={ () => this.setState({sectionActive:index}) }>
                                            { section.replace('_', ' ') }
                                        </div>
                                        <div className='wpcf-accordion-details' style={{ display: (index==sectionActive) ? 'block' : 'none' }}>
                                            {Object.keys(fields[section]).map( key => {
                                                const field = fields[section][key];
                                                const validate = field.required ? [required] : [];
                                                if(field.show) {
                                                    return (
                                                        <div key={key} className='wpcf-form-group'>
                                                            <label className='wpcf-field-title'>{field.title}</label>
                                                            <p className='wpcf-field-desc'>{field.desc}</p>

                                                            { field.type == 'form_group' ?
                                                                <div className="form-group">
                                                                    {Object.keys(field.fields).map( key => {
                                                                        const gField = field.fields[key];
                                                                        const gValidate = gField.required ? [required] : [];
                                                                        return (
                                                                            <Field
                                                                                key={key}
                                                                                name={key}
                                                                                item={gField}
                                                                                fieldValue={basicValues[key] || ''}
                                                                                validate={gValidate}
                                                                                component={RenderField}/>
                                                                            )
                                                                    })}
                                                                </div>

                                                            : field.type == 'repeatable' ?
                                                                <FieldArray
                                                                    name={key}
                                                                    item={field}
                                                                    onBlurVideoLink={this._onBlurVideoLink}
                                                                    component={RenderRepeatableFields}/>

                                                            :   <Field
                                                                    name={key}
                                                                    item={field}
                                                                    addTag={this._addTag}
                                                                    onChangeSelect={this._onChangeSelect}
                                                                    onChangeGoalType={this._onChangeGoalType}
                                                                    uploadFile={this._uploadFile}
                                                                    removeArrValue={this._removeArrValue}
                                                                    fieldValue={basicValues[key] || ''}
                                                                    validate={validate}
                                                                    component={RenderField}/>
                                                            }
                                                        </div>
                                                    )
                                                }
                                            })}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </FormSection>

                        <PageControl
                            current={current}
                            prevStep={prevStep}/>
                    </form>
                </div>
				<div className='col-md-5'>
                    <div className='wpcf-form-sidebar'>
                        <div className="preview-title">Preview</div>
                        { sectionActive==2 ?
                            <PreviewBasic data={basicValues}/>
                        :   <div>
                                Nothing to See here
                            </div>
                        }
                    </div>
                </div>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    fields: state.data.formFields,
    formValues: getFormValues(formName)(state)
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({
        getFormValues,
        changeFieldValue,
        fetchSubCategories,
        fieldShowHide
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: formName,
    destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Basic));
