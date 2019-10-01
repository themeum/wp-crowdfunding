import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { required, notRequred, uploadFiles, removeArrValue  } from '../../Helper';
import { FormSection, Field, FieldArray, reduxForm, change as changeFieldValue, formValueSelector } from 'redux-form';
import { RenderField, renderRepeatableFields } from './RenderField';
import { fetchSubCategories, fetchStates } from '../../actions';

class Basic extends Component {
    constructor(props) {
        super(props);
        this.state = { index: 0 };
        this._onChangeSelect = this._onChangeSelect.bind(this);
        this._addTag = this._addTag.bind(this);
        this._uploadFile = this._uploadFile.bind(this);
        this._removeArrValue = this._removeArrValue.bind(this);
    }

    sectionName(name) {
        return name.replace('_', ' ');
    }

    _onChangeSelect(e) {
        const { name, value } = e.target;
        if(name == 'category') {
            this.props.fetchSubCategories(value);
            this.props.changeFieldValue('campaignForm', 'sub_category', '');
        } else if(name == 'country') {
            this.props.fetchStates(value);
            this.props.changeFieldValue('campaignForm', 'state', '');
        }
    }

    _addTag(tag, field, selectedTags) {
        selectedTags = [...selectedTags];
        if( selectedTags.findIndex( item => item.value == tag.value) === -1 ) {
            selectedTags.push(tag);
            this.props.changeFieldValue('campaignForm', field, [...selectedTags]);
        }
    }

    _uploadFile(type, field, sFiles, multiple) {
        uploadFiles(type, sFiles, multiple).then( (files) => {
            this.props.changeFieldValue('campaignForm', field, files);
        });
    }

    _removeArrValue(index, field, values) {
        values = removeArrValue(values, index);
        this.props.changeFieldValue('campaignForm', field, values);
    }

    _onSubmit(values) {
        console.log( values );
    }

    render() {
        const { activeSection } = this.state;
        const { fields, handleSubmit } =  this.props;
        return (
            <div className='wpcf-accordion-wrapper'>
                <form onSubmit={handleSubmit(this._onSubmit.bind(this))}>
                    <FormSection name="basic">
                        {Object.keys(fields).map( (section, index) =>
                            <div key={section} className='wpcf-accordion'>
                                <div className={`wpcf-accordion-title ${index == this.state.index ? 'active' : ''}`} onClick={ () => this.setState({index}) }>
                                    {this.sectionName(section)}
                                </div>
                                <div className='wpcf-accordion-details' style={ index == this.state.index ? { display: 'block' } : { display: 'none' } } >
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
                                                    component={renderRepeatableFields}/>
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
                    </FormSection>
                    <button type="submit">Show form data</button>
                </form>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    fields: state.data.formFields,
    initialValues: { basic: {goal: 1, amount_range: {min: 1, max: 5000000}, video_link: [{src:''}]}, rewards:[] }
});

function mapDispatchToProps(dispatch) {
    return bindActionCreators({ 
        changeFieldValue, 
        formValueSelector, 
        fetchSubCategories,
        fetchStates,
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: 'campaignForm',
    destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Basic));