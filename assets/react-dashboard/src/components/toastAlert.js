import React, { Component } from 'react';
import { render, unmountComponentAtNode } from 'react-dom';

class ToastBox extends Component {
    static defaultProps = {
        type: 'info',
        message: 'Toaster',
        position: 'right bottom',
        timeout: 1000,
    }

    componentDidMount() {
        const { timeout } = this.props;
        setTimeout( () =>  removeElement(), timeout);
    }

    close = () => {
        removeElement();
    }

    render() {
        const { icon, message, position } = this.props;
        return (
            <div className={position}>
                {icon}
                <span>{message}</span>
                <button onClick={this.close}>X</button>
            </div>
        )
    }
}

const createElement = (properties) => {
    let divTarget = document.getElementById('wpcf-toast-alert');
    if (divTarget) {
        render(<ToastBox {...properties} />, divTarget)
    } else {
        divTarget = document.createElement('div');
        divTarget.id = 'wpcf-toast-alert';
        document.body.appendChild(divTarget);
        render(<ToastBox {...properties} />, divTarget)
    }
}

const removeElement = () => {
    const target = document.getElementById('wpcf-toast-alert')
    unmountComponentAtNode(target)
    target.parentNode.removeChild(target)
}

export default (properties) => {
    createElement(properties);
}