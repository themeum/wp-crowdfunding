import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchUser } from '../actions/userAction';
import SocialForm from '../components/socialForm';

class ProfileSettings extends Component {
	constructor (props) {
        super(props);
    }

    componentDidMount() {
        const { loaded } = this.props.user;
        if( !loaded ) {
            this.props.fetchUser();
        }
    }

	render() {
        const { loading, data, data:{ profile, social } } = this.props.user;
        if( loading ) { 
            return (
                <div>
                    Loading...
                </div>
            ) 
        };
        return (
            <div className="wpcf-dashboard-content">
                <h3>Profile</h3>
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-dashboard-profile">
                        <div>
                            <img className="profile-form-img" src={ profile.image } alt="Profile Image" />
                        </div>
                        <div>
                            <p>{data.first_name+' '+data.last_name}</p>
                            <p>{data.profile_email1}</p>
                        </div>
                        <div>
                            <p> Country </p>
                            <p>{profile.country}</p>
                        </div>
                    </div>
                    <div className="wpcf-dashboard-profile">
                        <div>
                            <p>Deactivated your Account</p>
                            <p>{profile.country}</p>
                        </div>
                        <div>
                            <button>Yes</button>
                        </div>
                    </div>
                    <div className="wpcf-dashboard-profile">
                        <h2>Connected Social Account</h2>
                        <SocialForm data={ social }/>
                    </div>
                </div>
            </div>
        )
	}
}


const mapStateToProps = state => ({
    user: state.user
})

export default connect( mapStateToProps, { fetchUser } )(ProfileSettings);