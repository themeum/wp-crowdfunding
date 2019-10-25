import React from 'react';
import { Field } from 'redux-form';
import { required, isYoutubeUrl  } from '../../Helper';
import RenderField from './Single';

export default (props) => {
    const { fields, item } = props;
    if(fields.length == 0 && item.open_first_item) {
        fields.push({});
    }
    return (
        <div className="">
            {fields.map((field, index) => (
                <div key={index}>
                    {Object.keys(item.fields).map( key => {
                        const name = `${field}.${key}`;
                        const sItem = item.fields[key];
                        const fieldName = field.split('[');
                        const validate = item.required ? [required] : [];
                        if(fieldName[0] == 'video_link') {
                            validate.push(isYoutubeUrl);
                        }
                        return (
                            <Field
                                key={key}
                                name={name}
                                item={sItem}
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