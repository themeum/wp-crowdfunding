import React from 'react';
import { Field } from 'redux-form';
import InputRange from 'react-input-range';
import { required, notRequred  } from '../../Helper';
import 'react-input-range/lib/css/index.css';

export const RenderField = (props) => {
    const { input, meta: { touched, error }, item, uploadFile, removeArrValue} = props;
    const addTag = (props.addTag) ? props.addTag : null;
    const onChangeSelect = (props.onChangeSelect) ? props.onChangeSelect : null;
    switch (item.type) {
        case 'text':
        case 'number':
            return (
                <div>
                    <input {...input} type={item.type} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'textarea':
            return (
                <div>
                    <textarea {...input} placeholder={item.placeholder} />
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'select':
            return (
                <div className="">
                    <select {...input} onChange={(e) => { input.onChange(e); onChangeSelect(e); }}>
                        <option value="">{item.placeholder}</option>
                        {item.options.map((option, index) =>
                            <option key={index} value={option.value} dangerouslySetInnerHTML={{ __html: option.label }} />
                        )}
                    </select>
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'radio':
            return (
                <div className="">
                    {item.options.map((option, index) =>
                        <label key={index} className="radio-inline">
                            <input {...input} type={item.type} /> {option.label} <span>{option.desc}</span>
                        </label>
                    )}
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'checkbox':
            return (
                <div className="">
                    {item.options.map((option, index) =>
                        <label key={index} className="checkbox-inline">
                            <input {...input} type={item.type} /> {option.label}
                        </label>
                    )}
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'tags':
            return (
                <div className="">
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
                    <div className=''>
                        {item.options.map((tag, index) =>
                            <span key={index} onClick={() => addTag(tag, input.name, input.value)}>+ {tag.label}</span>
                        )}
                    </div>
                </div>
            );
        case 'file':
            return (
                <div className="">
                    <div className="wpcf-form-attachments">
                        {input.value && input.value.map( (item, index) =>
                            <div key={index}>{item.name} <span onClick={() => removeArrValue(index, input.name, input.value)} className="fa fa-times"/></div>
                        )}
                    </div>
                    <button type="button" dangerouslySetInnerHTML={{ __html: item.button }} onClick={() => uploadFile(input.name, input.value, item.multiple)}/>
                    {touched && error && <span>{error}</span>}
                </div>
            );
        case 'range':
            return (
                <div className="">
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
