import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { Field, FieldArray, reduxForm, change as changeFieldValue, formValueSelector } from 'redux-form';
import { RenderField, renderVideoLinks } from './RenderField';
import { fetchSubCategories, fetchStates } from '../../actions';
import validate from './Validate'

const required = value => value ? undefined : 'Required';
const notRequred = value => '';
class Basic extends Component {
    constructor(props) {
        super(props);
        this.state = { activeSection: Object.keys(this.props.fields)[0] };
        this._onChangeSelect = this._onChangeSelect.bind(this);
        this._addTag = this._addTag.bind(this);
        this._uploadFile = this._uploadFile.bind(this);
        this._removeArrValue = this._removeArrValue.bind(this);
    }

    onSubmit(values) {
        console.log( values );
    }

    sectionName(name) {
        return name.replace('_', ' ');
    }

    _onChangeSection(section) {
        this.setState({
            activeSection: section
        });
    }

    _onChangeSelect(e) {
        const { name, value } = e.target;
        if(name == 'category') {
            this.props.fetchSubCategories(value);
            this.props.changeFieldValue('formBasic', 'sub_category', '');
        } else if(name == 'country') {
            this.props.fetchStates(value);
            this.props.changeFieldValue('formBasic', 'state', '');
        }
    }

    _addTag(tag, field, selectedTags) {
        selectedTags = [...selectedTags];
        if( selectedTags.findIndex( item => item.value == tag.value) === -1 ) {
            selectedTags.push(tag);
            this.props.changeFieldValue('formBasic', field, [...selectedTags]);
        }
    }

    _uploadFile(field) {
        let files = [];
        wp.media.editor.open({library: {type: field}});
        wp.media.editor.send.attachment = (props, attachment) => {
            files.push({
                id: attachment.id,
                name: attachment.filename,
                url: attachment.url,
            });
            this.props.changeFieldValue('formBasic', field, [...files]);
        };
    }

    _removeArrValue(index, field, values) {
        values = [...values];
        values.splice(index, 1);
        this.props.changeFieldValue('formBasic', field, values);
    }

    render() {
        const { activeSection } = this.state;
        const { fields, handleSubmit } =  this.props;
        return (
            <div className='wpcf-accordion-wrapper'>
                <form onSubmit={handleSubmit(this.onSubmit.bind(this))}>
                    {Object.keys(fields).map( section =>
                        <div key={section} className='wpcf-accordion'>
                            <div className={`wpcf-accordion-title ${section == activeSection ? 'active' : ''}`} onClick={ () => this._onChangeSection(section) }>{this.sectionName(section)}</div>
                            <div className='wpcf-accordion-details' style={ section == activeSection ? { display: 'block' } : { display: 'none' } } >
                                {Object.keys(fields[section]).map( field =>
                                    <div key={field} className='wpcf-form-field'>
                                        <div className='wpcf-field-title'>{fields[section][field].title}</div>
                                        <div className='wpcf-field-desc'>{fields[section][field].desc}</div>

                                        { fields[section][field].type == 'video_link' ?
                                            <FieldArray 
                                                name={field} 
                                                item={fields[section][field]}
                                                component={renderVideoLinks} />
                                            :
                                            <Field
                                                name={field}
                                                item={fields[section][field]}
                                                addTag={this._addTag}
                                                onChangeSelect={this._onChangeSelect}
                                                uploadFile={this._uploadFile}
                                                removeArrValue={this._removeArrValue}
                                                component={RenderField}
                                                validate={[ fields[section][field].required ? required : notRequred ]}/>
                                        }  
                                    </div>
                                )}
                            </div>
                        </div>
                    )}
                    <button type="submit">Show form data</button>
                </form>
            </div>
        )
    }
}

const mapStateToProps = state => ({
    fields: state.data.formFields,
    initialValues: { goal: 1, amount_range: {min: 1, max: 5000000}, video_link: [{url:''}] },
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
    form: 'formBasic'
})(Basic));