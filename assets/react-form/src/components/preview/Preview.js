import React from "react"
import PreviewLink from "./Link";
import Icon from "../Icon";

const Preview = (props) => {
    const {postId=0} = props
    return (
        <div className="wpcf-form-sidebar">
            <div className="preview-title">
                <Icon name="eye"/> Preview
            </div>
            {
                props.children && props.children
            }
            <PreviewLink postId={postId}/>
        </div>
    )
}

export default Preview
