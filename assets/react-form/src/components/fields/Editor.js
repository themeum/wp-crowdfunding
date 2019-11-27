
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import MediumEditor from 'medium-editor';
import 'medium-editor/dist/css/medium-editor.min.css';
import 'medium-editor/dist/css/themes/beagle.min.css';

class Editor extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
        const dom = ReactDOM.findDOMNode(this);
        const { toolbar, onChange } = this.props;
        this.medium = new MediumEditor(dom, {toolbar});
        this.medium.subscribe('editableInput', e => {
            onChange(dom.innerHTML);
        });
    }

    componentDidUpdate() {
        this.medium.restoreSelection();
    }

    componentWillUnmount() {
        this.medium.destroy();
    }

    render() {
        const { tag, value, contentEditable, dangerouslySetInnerHTML, ...props } = this.props;
        props.dangerouslySetInnerHTML = { __html: value };
        if (this.medium) {
            this.medium.saveSelection();
        }
        return React.createElement(tag, props);
    }
}

Editor.defaultProps = {
    tag: 'div',
    toolbar: {
        buttons: [
            'bold', 
            'italic', 
            'underline', 
            {
                name: 'anchor',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-link']
            },
            'h2',
            'h3',
            {
                name: 'justifyLeft',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-align-left']
            },
            {
                name: 'justifyCenter',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-align-center']
            },
            {
                name: 'justifyRight',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-align-right']
            },
            {
                name: 'justifyFull',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-align-justify']
            },
            {
                name: 'indent',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-indent']
            },
            {
                name: 'outdent',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-outdent']
            },
            {
                name: 'orderedlist',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-list-ol']
            },
            {
                name: 'unorderedlist',
                contentDefault: '<i></i>',
                classList: ['fa', 'fa-list-ul']
            },
        ]
    }
};

export default Editor;
