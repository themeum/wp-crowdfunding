import React from "react"
import PreviewLink from "./Link";

const Preview = (props) => {
    const {postId=0} = props
    return (
        <div className="wpcf-form-sidebar">
            <div className="preview-title"><span className="fas fa-eye"></span> Preview</div>
            {props.children && props.children}
            <PreviewLink postId={postId}/>
        </div>
    )
}

export default Preview
