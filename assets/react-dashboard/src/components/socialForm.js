import React, { Component } from 'react';

class SocialForm extends Component {
	constructor (props) {
        super(props);
        this.state = { ...this.props.data };
        this.onChangeInput = this.onChangeInput.bind(this);
        console.log( this.state );
    }
    onChangeInput(e) {
        this.setState( { [e.target.name]: e.target.value } );
    }

	render() {
        const { facebook, twitter, instagram, youtube, linkedin, pinterest } = this.state;
        return (
            <form>
                <div className="wpcf-form-group">
                    <label htmlFor="wpcf_profile_facebook">Facebook</label>
                    <input type="text" id="wpcf_pro_facebook" name="facebook" value={ facebook } placeholder="www.facebook.com/wp-crowdfunding" onChange={ this.onChangeInput }/>
                </div>
                <div className="wpcf-form-group">
                    <label htmlFor="wpcf_profile_twitter">Twitter</label>
                    <input type="text" id="wpcf_pro_twitter" name="twitter" value={ twitter } placeholder="www.twitter.com/wp-crowdfunding" onChange={ this.onChangeInput }/>
                </div>
                <div className="wpcf-form-group">
                    <label htmlFor="wpcf_pro_instagram">Instagram</label>
                    <input type="text" id="wpcf_pro_instagram" name="instagram" value={ instagram } placeholder="www.instagram.com/wp-crowdfunding" onChange={ this.onChangeInput }/>
                </div>
                <div className="wpcf-form-group">
                    <label htmlFor="wpcf_pro_youtube">Youtube</label>
                    <input type="text" id="wpcf_pro_youtube" name="youtube" value={ youtube } placeholder="www.youtube.com/wp-crowdfunding" onChange={ this.onChangeInput }/>
                </div>
                <div className="wpcf-form-group">
                    <label htmlFor="wpcf_pro_linkedin">Linkedin</label>
                    <input type="text" id="wpcf_pro_linkedin" name="linkedin" value={ linkedin } placeholder="www.linkedin.com/wp-crowdfunding" onChange={ this.onChangeInput }/>
                </div>
                <div className="wpcf-form-group">
                    <label htmlFor="wpcf_pro_pinterest">Pinterest</label>
                    <input type="text" id="wpcf_pro_pinterest" name="pinterest" value={ pinterest } placeholder="www.pinterest.com/wp-crowdfunding" onChange={ this.onChangeInput }/>
                </div>
            </form>
        )
	}
}

export default SocialForm;