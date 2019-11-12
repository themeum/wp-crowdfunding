import React from 'react';
import { Field } from 'redux-form';
import { required, isYoutubeUrl  } from '../../Helper';
import RenderField from './Single';

export default (props) => {
    const { fields, item } = props;
    const onBlurVideoLink = ( typeof props.onBlurVideoLink !== 'undefined') ? props.onBlurVideoLink : ()=>{};
    if(fields.length == 0 && item.open_first_item) {
        fields.push({});
    }
    return (
        <div className="">
            {fields.map((field, index) => (
                <div key={index} className="wpcf-repeatable-parent">
                    {Object.keys(item.fields).map( key => {
                        const name = `${field}.${key}`;
                        const rItem = item.fields[key];
                        const validate = item.required ? [required] : [];
                        const fieldName = field.split('[');
                        if(fieldName[0] == 'video_link') {
                            validate.push(isYoutubeUrl);
                        }
                        return (
                            <Field
                                key={key}
                                name={name}
                                item={rItem}
                                onBlurVideoLink={onBlurVideoLink}
                                validate={validate}
                                component={RenderField}/>
                        )
                    })}
                    { index !== 0 &&
                        <span  className="wpcf-repeatable-close fa fa-times" onClick={() => {fields.remove(index); setTimeout(() => { onBlurVideoLink() }, 300);}}/>
                    }
                </div>
            ))}
            <button className="wpcf-btn wpcf-btn-round wpcf-primary-light-btn" type="button" dangerouslySetInnerHTML={{__html: item.button}} onClick={() => fields.push({})}/>
        </div>
    )
}
