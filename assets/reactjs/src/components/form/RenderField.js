import React from 'react';
import { Field } from 'redux-form';
import InputRange from 'react-input-range';
import 'react-input-range/lib/css/index.css';

export const RenderField = (props) => {

    const { input, meta: { touched, error }, item , uploadFile, removeFile} = props;

    switch (item.type) {
        case 'text':
            return (
                <input {...input} type={item.type} placeholder={item.placeholder} />
            );
        case 'select':
            return (
                <div className="">
                    <select {...input}>
                        <option value="">{item.placeholder}</option>
                        {item.options.map((option, index) =>
                            <option key={index} value={option.value}>{option.label}</option>
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
                </div>
            );
        case 'tags':
            return (
                <div className="">
                    <input type='text'/>
                    <div className=''>
                        {item.options.map((option, index) =>
                            <span key={index}>+ {option.label}</span>
                        )}
                    </div>
                </div>
            );
        case 'file':
            return (
                <div className="">
                    <div className="wpcf-form-attachments">
                        {input.value && input.value.map( (item, index) =>
                            <div key={index}>{item.name} <span onClick={() => removeFile(index, input.name, input.value)} className="fa fa-times"/></div>
                        )}
                    </div>
                    <button dangerouslySetInnerHTML={{ __html: item.button }} onClick={() => uploadFile(input.name)}/>
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
        case 'recommended_amount':
            return (
                <div className="">
                    <div>
                        <span>{}</span>
                        <span>{}</span>
                        <span>{}</span>
                        <span>{}</span>
                    </div>
                    <InputRange
                        step={2}
                        minValue={0}
                        maxValue={20}
                        value={input.value}
                        onChange={input.onChange} />
                    <div className="">{}</div>
                </div>
            );
        default:
            return '';
    }
}

export const renderVideoLinks = ({ fields, meta: { error, submitFailed }, item }) => (
    <div className="">
        {fields.map((field, index) => (
            <div key={index}>
                <Field
                    name={`${field}.url`}
                    component="input"
                    type="url"
                    placeholder=""/>
                { index !== 0 &&
                    <button
                        type="button" 
                        title="Remove"
                        onClick={() => fields.remove(index)}>
                        Remove
                    </button>
                }
            </div>
        ))}
        <button dangerouslySetInnerHTML={{__html: item.button}} onClick={() => fields.push({})}/>
        {submitFailed && error && <span>{error}</span>}
    </div>
)
