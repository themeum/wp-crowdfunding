import React from 'react';
import { Field } from 'redux-form';
import { required, notRequred  } from '../../Helper';
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