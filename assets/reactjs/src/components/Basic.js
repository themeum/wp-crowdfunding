import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { required, uploadFiles, removeArrValue } from '../Helper';
import { FormSection, Field, FieldArray, reduxForm, getFormValues, change as changeFieldValue } from 'redux-form';
import { fetchSubCategories, fetchStates, fieldShowHide } from '../actions';
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
        this._onChangeSelect = this._onChangeSelect.bind(this);
        this._onChangeGoalType = this._onChangeGoalType.bind(this);
        this._addTag = this._addTag.bind(this);
        this._uploadFile = this._uploadFile.bind(this);
        this._removeArrValue = this._removeArrValue.bind(this);
    }

    _onChangeSelect(e) {
        const { name, value } = e.target;
        if(name == `${sectionName}.category`) {
            this.props.fetchSubCategories(value);
            this.props.changeFieldValue(formName, `${sectionName}.sub_category`, null);
        } else if(name == `${sectionName}.country`) {
            this.props.fetchStates(value);
            this.props.changeFieldValue(formName, `${sectionName}.state`, null);
        }
    }

    _onChangeGoalType(e) {
        const { value } = e.target;
        const field = 'media.if_target_date';
        const show = (value=='target_date') ? true : false;
        this.props.fieldShowHide(field, show);
    }

    _addTag(tag, field, selectedTags) {
        selectedTags = [...selectedTags];
        if( selectedTags.findIndex( item => item.value == tag.value) === -1 ) {
            selectedTags.push(tag);
            this.props.changeFieldValue(formName, field, [...selectedTags]);
        }
    }

    _uploadFile(type, field, sFiles, multiple) {
        const { formValues: { basic } } =  this.props;
        let media = [ ...basic.media ];
        uploadFiles(type, sFiles, multiple).then( (files) => {
            this.props.changeFieldValue(formName, field, files);

            media.push(files);
            this.props.changeFieldValue(formName, `${sectionName}.media`, media);
        });
    }

    _removeArrValue(index, field, values) {
        values = removeArrValue(values, index);
        this.props.changeFieldValue(formName, field, values);
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
                                                        <div key={key} className='wpcf-form-field'>
                                                            <div className='wpcf-field-title'>{field.title}</div>
                                                            <div className='wpcf-field-desc'>{field.desc}</div>

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
                                                                                fieldValue={basicValues[key]? basicValues[key] : ''}
                                                                                validate={gValidate}
                                                                                component={RenderField}/>
                                                                            )
                                                                    })}
                                                                </div>

                                                            : field.type == 'repeatable' ?
                                                                <FieldArray
                                                                    name={key}
                                                                    item={field}
                                                                    component={RenderRepeatableFields}/>

                                                            :   <Field
                                                                    name={key}
                                                                    item={field}
                                                                    addTag={this._addTag}
                                                                    onChangeSelect={this._onChangeSelect}
                                                                    onChangeGoalType={this._onChangeGoalType}
                                                                    uploadFile={this._uploadFile}
                                                                    removeArrValue={this._removeArrValue}
                                                                    fieldValue={basicValues[key] ? basicValues[key] : ''}
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
    formValues: getFormValues(formName)(state),
    initialValues: { basic: {media:[], goal: 1, amount_range: {min: 1, max: 5000000}}, story:[], rewards:[], team:[] }
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({
        getFormValues,
        changeFieldValue,
        fetchSubCategories,
        fetchStates,
        fieldShowHide
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: formName,
    destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Basic));