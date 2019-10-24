import React from 'react';
import { Field } from 'redux-form';
import { required, isYoutubeUrl  } from '../../Helper';
import RenderField from './Single';

export default (props) => {
    const { fields, item } = props;
    const onChangeVideoLink = props.onChangeVideoLink ? onChangeVideoLink : ()=>{};
    if(fields.length == 0 && item.open_first_item) {
        fields.push({});
    }
    return (
        <div className="">
            {fields.map((field, index) => (
                <div key={index}>
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
                                onChangeVideoLink={onChangeVideoLink}
                                validate={validate}
                                component={RenderField}/>
                        )
                    })}
                    { index !== 0 &&
                        <span onClick={() => fields.remove(index)} className="fa fa-times"/>
                    }
                </div>
            ))}
            <button type="button" dangerouslySetInnerHTML={{__html: item.button}} onClick={() => fields.push({})}/>
        </div>
    )
}