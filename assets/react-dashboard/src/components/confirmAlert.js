import React, { Component } from 'react'
import { render, unmountComponentAtNode } from 'react-dom'

export default class ConfirmDialogue extends Component {
    static defaultProps = {
        buttons: [
            {
                label: 'Cancel',
                onClick: () => null
            },
            {
                label: 'Confirm',
                onClick: () => null
            }
        ],
        childrenElement: () => null,
        closeOnClickOutside: true,
        closeOnEscape: true,
        willUnmount: () => null,
        onClickOutside: () => null,
        onKeypressEscape: () => null
    }

    handleClickButton = button => {
        if (button.onClick) button.onClick();
        this.close();
    }

    handleClickOverlay = e => {
        const { closeOnClickOutside, onClickOutside } = this.props
        const isClickOutside = e.target === this.overlay

        if (closeOnClickOutside && isClickOutside) {
            onClickOutside();
            this.close();
        }
    }

    close = () => {
        removeBodyClass();
        removeElementReconfirm();
    }

    keyboardClose = event => {
        const { closeOnEscape, onKeypressEscape } = this.props
        const isKeyCodeEscape = event.keyCode === 27

        if (closeOnEscape && isKeyCodeEscape) {
            onKeypressEscape(event)
            this.close()
        }
    }

    componentDidMount = () => {
        document.addEventListener('keydown', this.keyboardClose, false)
    }

    componentWillUnmount = () => {
        document.removeEventListener('keydown', this.keyboardClose, false)
        this.props.willUnmount()
    }

    render() {
        const { title, message, buttons, childrenElement } = this.props

        return (
            <div
                className='wpcf-confirm-alert-overlay'
                ref={dom => (this.overlay = dom)}
                onClick={this.handleClickOverlay}
            >
                <div className='wpcf-confirm-alert'>
                    <div className='wpcf-confirm-alert-body'>
                        {title && <h1>{title}</h1>}
                        {message}
                        {childrenElement()}
                        <div className='wpcf-confirm-alert-button-group'>
                            {buttons.map((button, i) => (
                                <button key={i} onClick={() => this.handleClickButton(button)}>
                                    {button.label}
                                </button>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

function createElementReconfirm(properties) {
    let divTarget = document.getElementById('wpcf-confirm-alert');
    if (divTarget) {
        // Rerender - the mounted ConfirmDialogue
        render(<ConfirmDialogue {...properties} />, divTarget)
    } else {
        // Mount the ConfirmDialogue component
        divTarget = document.createElement('div');
        divTarget.id = 'wpcf-confirm-alert';
        document.body.appendChild(divTarget);
        render(<ConfirmDialogue {...properties} />, divTarget)
    }
}

function removeElementReconfirm() {
    const target = document.getElementById('wpcf-confirm-alert')
    unmountComponentAtNode(target)
    target.parentNode.removeChild(target)
}

function addBodyClass() {
    document.body.classList.add('wpcf-confirm-alert-body-element')
}

function removeBodyClass() {
    document.body.classList.remove('wpcf-confirm-alert-body-element')
}

export function confirmAlert(properties) {
    addBodyClass();
    createElementReconfirm(properties);
}