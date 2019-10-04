import React, { useState } from 'react';
import { Field, FieldArray } from 'redux-form';
import InputRange from 'react-input-range';
import { required, notRequred  } from '../../Helper';
import 'react-input-range/lib/css/index.css';

export const RenderField = (props) => {
    const { input, meta: { touched, error }, item, uploadFile, removeArrValue} = props;
    const addTag = (typeof props.addTag == 'function') ? props.addTag : () => {};
    const onChangeSelect = (typeof props.onChangeSelect == 'function') ? props.onChangeSelect : () => {};
    const className = (props.className) ? props.className : null;
    switch (item.type) {
        case 'text':
        case 'number':
            return (
                <div className={className}>
                    <input {...input} type={item.type} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'textarea':
            return (
                <div className={className}>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'select':
            return (
                <div className={className}>
                    <select {...input} onChange={(e) => { input.onChange(e); onChangeSelect(e); }}>
                        <option value="">{item.placeholder}</option>
                        {item.options.map((option, index) =>
                            <option key={index} value={option.value} dangerouslySetInnerHTML={{ __html: option.label }}/>
                        )}
                    </select>
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'radio':
            return (
                <div className={className}>
                    {item.options.map((option, index) =>
                        <label key={index} className="radio-inline">
                            <input {...input} type={item.type} value={option.value}/> {option.label} <span>{option.desc}</span>
                        </label>
                    )}
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'checkbox':
            return (
                <div className={className}>
                    {item.options.map((option, index) =>
                        <label key={index} className="checkbox-inline">
                            <input {...input} type={item.type} value={option.value}/> {option.label}
                        </label>
                    )}
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'tags':
            return (
                <div className={className}>
                    {input.value && input.value.map( (item, index) =>
                        <div key={index} onClick={() => removeArrValue(index, input.name, input.value)}>{item.label}</div>
                    )}
                    <input 
                        type='text' 
                        onKeyDown={(e) => {
                            if (e.keyCode === 13) {
                                e.preventDefault();
                                const value = e.target.value.toLowerCase();
                                const tag = { value, label: e.target.value };
                                addTag(tag, input.name, input.value);
                                e.target.value = '';
                            }
                        }} />
                    <div className={className}>
                        {item.options.map((tag, index) =>
                            <span key={index} onClick={() => addTag(tag, input.name, input.value)}>+ {tag.label}</span>
                        )}
                    </div>
                </div>
            );
        case 'image':
        case 'video':
            return (
                <div className={className}>
                    <div className="wpcf-form-attachments">
                        {input.value && input.value.map( (item, index) =>
                            <div key={index}>{item.name} <span onClick={() => removeArrValue(index, input.name, input.value)} className="fa fa-times"/></div>
                        )}
                    </div>
                    <button type="button" dangerouslySetInnerHTML={{ __html: item.button }} onClick={() => uploadFile(item.type, input.name, input.value, item.multiple)}/>
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'range':
            return (
                <div className={className}>
                    <InputRange
                        minValue={item.minVal}
                        maxValue={item.maxVal}
                        value={input.value}
                        onChange={input.onChange} />
                    <div className="">{}</div>
                </div>
            );
        default:
            return '';
    }
}


export const renderRepeatableFields = (props) => {
    const { fields, item } = props;
    if(fields.length == 0 && item.open_first_item) {
        fields.push({});
    }
    return (
        <div className="">
            {fields.map((field, index) => (
                <div key={index}>
                    {Object.keys(item.fields).map( key =>
                        <Field
                            key={key}
                            name={`${field}.${key}`}
                            item={item.fields[key]}
                            uploadFile={props.uploadFile}
                            removeArrValue={props.removeArrValue}
                            component={RenderField}
                            validate={[item.fields[key].required ? required : notRequred]}/>
                    )}
                    { index !== 0 &&
                        <span onClick={() => fields.remove(index)} className="fa fa-times"/>
                    }
                </div>
            ))}
            <button type="button" dangerouslySetInnerHTML={{__html: item.button}} onClick={() => fields.push({})}/>
        </div>
    )
}


export const renderRewardFields = (props) => {
    const { rewards, rewardTypes, onChangeType, selectedItem, rewardFields, uploadFile, removeArrValue, fields:{name} } = props;
    const [changeType, setChangeType] = useState(false);

    return (
        <div className="">
            <div className='wpcf-form-field'>
                <div className='wpcf-field-title'>
                    {rewardTypes[rewards[selectedItem].type].title}
                    <span onClick={() => setChangeType(true)}>Change</span>
                </div>
                { changeType && rewardTypes.map((item, index) =>
                    <label key={index} className="radio-inline">
                        <input type='radio' name="type" value={index} onClick={(e) => { onChangeType(e); setChangeType(false) }}/> {item.title}
                    </label>
                )}
            </div>
            {Object.keys(rewardFields).map( key =>
                <div key={key} className='wpcf-form-field'>
                    <div className='wpcf-field-title'>{rewardFields[key].title}</div>
                    <div className='wpcf-field-desc'>{rewardFields[key].desc}</div>
                    { rewardFields[key].type == 'form_group' ?
                        <div className="form-group">
                            {Object.keys(rewardFields[key].fields).map( field =>
                                <Field
                                    key={field}
                                    name={`${name}[${selectedItem}].${field}`}
                                    item={rewardFields[key].fields[field]}
                                    className={rewardFields[key].fields[field].class}
                                    uploadFile={props.uploadFile}
                                    removeArrValue={props.removeArrValue}
                                    component={RenderField}
                                    validate={[rewardFields[key].fields[field].required ? required : notRequred]}/>
                            )}
                        </div>

                    : rewardFields[key].type == 'repeatable' ?
                        <FieldArray
                            name={`${name}[${selectedItem}].${key}`}
                            item={rewardFields[key]}
                            uploadFile={uploadFile}
                            removeArrValue={removeArrValue}
                            component={renderRepeatableFields}/>
                    :
                        <Field
                            name={`${name}[${selectedItem}].${key}`}
                            item={rewardFields[key]}
                            uploadFile={uploadFile}
                            removeArrValue={removeArrValue}
                            component={RenderField}
                            validate={[rewardFields[key].required ? required : notRequred]}/>
                    }
                </div>
            )}
        </div>
    )
}

