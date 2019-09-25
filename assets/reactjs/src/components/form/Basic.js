import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { Field, FieldArray, reduxForm, change as changeFieldValue, formValueSelector } from 'redux-form';
import { RenderField, renderVideoLinks } from './RenderField';
import { fetchSubCategories, fetchStates } from '../../actions';

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

    _uploadFile(field, sFiles) {
        const prevFiles = sFiles ? [...sFiles] : [];
        const mediaLibrary = wp.media({
            multiple: true,
            library: {
                type: field
            }
        });
        //Callback function for set prev selected files
        mediaLibrary.on('open', () => {
            const selectionAPI = mediaLibrary.state().get('selection');
            prevFiles.forEach( item => {
                const attachment = wp.media.attachment( item.id );
                selectionAPI.add( attachment ? [ attachment ] : []);
            });
        });
        //Callback function on select files
        mediaLibrary.on('select', () => {
            const length = mediaLibrary.state().get('selection').length;
            const files = mediaLibrary.state().get('selection').models;
            let selectedFiles = [];
            for(let i = 0; i < length; i++) {
                selectedFiles.push({
                    id: files[i].id,
                    name: files[i].changed.filename,
                    url: files[i].changed.url,
                });
            }
            //Dispatch for update field value
            this.props.changeFieldValue('formBasic', field, selectedFiles);
        });
        mediaLibrary.open();
    }

    _removeArrValue(index, field, values) {
        values = [...values];
        values.splice(index, 1);
        this.props.changeFieldValue('formBasic', field, values);
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
    initialValues: { goal: 1, amount_range: {min: 1, max: 5000000}, video_link: [{url:''}] }
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