import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchUser } from '../actions/userAction';
import Header from '../components/contentHeader'

class Profile extends Component {
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
        const { loading, data } = this.props.user;

        console.log(this.props)

        if( loading ) {
            return (
                <div>
                    Loading...
                </div>
            )
        };

        return (
            <div>
                <Header title="My Profile" />
                <div className="wpcf-dashboard-content-inner">

                    <div className="wpcf-myprofile-primary-info">
                        <div className="wpcf-myprofile-avatar">
                            <img className="profile-form-img" src={ data.profile_image } alt="Profile Image" />
                        </div>
                        <div className="wpcf-myprofile-name-email">
                            <h4>{ data.display_name }</h4>
                            <span>{ data.user_name }</span>
                        </div>
                        <div className="wpcf-myprofile-country">
                            <h4>Country</h4>
                            <span>{ data.profile_country }</span>
                        </div>
                    </div>

                    <div className="wpcf-myprofile-additional-info">
                        <ul>
                            <li><strong>Username: </strong> { data.username }</li>
                            <li><strong>Address: </strong> { data.profile_address }</li>
                            <li><strong>City: </strong> { data.profile_city }</li>
                            <li><strong>Postal Code: </strong> { data.profile_post_code }</li>
                        </ul>
                    </div>
                </div>
            </div>
        )
	}
}


const mapStateToProps = state => ({
    user: state.user
})

export default connect( mapStateToProps, { fetchUser } )(Profile);
