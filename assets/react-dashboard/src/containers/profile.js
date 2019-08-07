import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { fetchUser } from '../actions';


class Profile extends Component {
	constructor (props) {
        super(props);
    }

    componentDidMount() {
        if( this.emptyProps() ) {
            this.props.fetchUser();
        }
    }

    emptyProps() {
        return (Object.keys(this.props.user).length === 0) ? true : false;
    }
    
	render() {
        const { user } = this.props;
        if( this.emptyProps() ) { 
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
                    <div>
                        { ( user.img_src ) &&
                            <img className="profile-form-img" src={ user.img_src } alt="Profile Image" />
                        }
                        { ( user.profile_email1 ) &&
                            <p>{ user.profile_email1[0] } </p>
                        }
                        { ( user.profile_country ) &&
                            <p>{ user.profile_country[0] } </p>
                        }
                    </div>
                    <div className="wpcf-dashboard-profile">
                        <div className="wpcf-dashboard-profile-item">
                            <div className="heading">
                                <span>Username</span>
                            </div>
                            <div className="content">
                                { ( user.display_name ) &&
                                    <p>{ user.display_name }</p>
                                }
                            </div>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <div className="heading">
                                <span>Address</span>
                            </div>
                            <div className="content">
                                { ( user.profile_address ) &&
                                    <p>{ user.profile_address[0] }</p>
                                }
                            </div>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <div className="heading">
                                <span>City</span>
                            </div>
                            <div className="content">
                                { ( user.profile_city ) &&
                                    <p>{ user.profile_city[0] }</p>
                                }
                            </div>
                        </div>
                        <div className="wpcf-dashboard-profile-item">
                            <div className="heading">
                                <span>Postal Code</span>
                            </div>
                            <div className="content">
                                { ( user.profile_postal_code ) &&
                                    <p>{ user.profile_postal_code[0] }</p>
                                }
                            </div>
                        </div>
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