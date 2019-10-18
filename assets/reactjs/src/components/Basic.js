import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { required, notRequred, uploadFiles, removeArrValue } from '../Helper';
import { FormSection, Field, FieldArray, reduxForm, getFormValues, change as changeFieldValue } from 'redux-form';
import { RenderField, RenderRepeatableFields } from './renderItems/Field';
import { fetchSubCategories, fetchStates } from '../actions';
import PreviewBasic from './preview/Basic';
import PageControl from './Control';

const formName = "campaignForm";
const sectionName = "basic";
class Basic extends Component {
    constructor(props) {
        super(props);
        this.state = { sectionActive: 0 };
        this._onChangeSelect = this._onChangeSelect.bind(this);
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

    _addTag(tag, field, selectedTags) {
        selectedTags = [...selectedTags];
        if( selectedTags.findIndex( item => item.value == tag.value) === -1 ) {
            selectedTags.push(tag);
            this.props.changeFieldValue(formName, field, [...selectedTags]);
        }
    }

    _uploadFile(type, field, sFiles, multiple) {
        uploadFiles(type, sFiles, multiple).then( (files) => {
            this.props.changeFieldValue(formName, field, files);
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
                                            {section.replace('_', ' ')}
                                        </div>
                                        <div className='wpcf-accordion-details' style={ index == sectionActive ? { display: 'block' } : { display: 'none' } } >
                                            {Object.keys(fields[section]).map( field =>
                                                <div key={field} className='wpcf-form-field'>
                                                    <div className='wpcf-field-title'>{fields[section][field].title}</div>
                                                    <div className='wpcf-field-desc'>{fields[section][field].desc}</div>

                                                    { fields[section][field].type == 'repeatable' ?
                                                        <FieldArray
                                                            name={field}
                                                            item={fields[section][field]}
                                                            uploadFile={this._uploadFile}
                                                            removeArrValue={this._removeArrValue}
                                                            component={RenderRepeatableFields}/>
                                                        :
                                                        <Field
                                                            name={field}
                                                            item={fields[section][field]}
                                                            addTag={this._addTag}
                                                            onChangeSelect={this._onChangeSelect}
                                                            uploadFile={this._uploadFile}
                                                            removeArrValue={this._removeArrValue}
                                                            component={RenderField}
                                                            validate={[fields[section][field].required ? required : notRequred]}/>
                                                    }
                                                </div>
                                            )}
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
                        <PreviewBasic data={basicValues}/>
                    </div>
                </div>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    fields: state.data.formFields,
    formValues: getFormValues(formName)(state),
    initialValues: { basic: {goal: 1, amount_range: {min: 1, max: 5000000}}, story:[], rewards:[], team:[] }
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({
        getFormValues,
        changeFieldValue,
        fetchSubCategories,
        fetchStates,
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: formName,
    destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Basic));