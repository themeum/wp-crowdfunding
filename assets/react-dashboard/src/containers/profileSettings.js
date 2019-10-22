import React, { Component } from 'react';
import { connect } from 'react-redux';
import { fetchUser, saveUserData, fetchCountries } from '../actions/userAction';
import ProfileEditForm from '../components/profileEditForm';
import SocialForm from '../components/socialForm';

class ProfileSettings extends Component {
	constructor (props) {
        super(props);
        this.state = { profileEdit: false };
        this.toggleEdit = this.toggleEdit.bind(this);
        this.onClickSaveData = this.onClickSaveData.bind(this);
    }

    componentDidMount() {
        const { user, countries } = this.props;
        if( !user.loaded ) {
            this.props.fetchUser();
        }
        if( !countries.loaded ) {
            this.props.fetchCountries();
        }
    }

    componentDidUpdate(prevProps, prevState) {
        const { saveReq, error } = this.props.user;
        if ( saveReq !== prevProps.user.saveReq ) {
            if( saveReq == 'complete' ) {
                alert( 'data saved' );
            }
            if( saveReq == 'error' ) {
                alert( error );
            }
        }
    }

    toggleEdit() {
        this.setState({
            profileEdit: !this.state.profileEdit
        });
    }

    onClickSaveData( data ) {
        this.props.saveUserData( data );
    }

	render() {
        const { profileEdit } = this.state;
        const { loading, data } = this.props.user;
        
        if( loading ) { 
            return (
                <div>
                    Loading...
                </div>
            );
        };

        if( profileEdit ) {
            return(
                <ProfileEditForm 
                    data={ data }
                    countries={ this.props.countries.data }
                    toggleEdit={ this.toggleEdit } 
                    onClickSaveData={ this.onClickSaveData }/>
            );
        }

        return (
            <div>
                <h3>Profile</h3>
                <div className="wpcf-dashboard-content-inner">
                    <div className="wpcf-dashboard-profile">
                        <div>
                            <img className="profile-form-img" src={ data.profile_image } alt="Profile Image" />
                        </div>
                        <div>
                            <p>{data.first_name+' '+data.last_name}</p>
                            <p>{data.profile_email1}</p>
                        </div>
                        <div>
                            <p> Country </p>
                            <p>{data.country_name}</p>
                        </div>
                        <div>
                            <span onClick={ this.toggleEdit }>Edit</span>
                        </div>
                    </div>
                    <div className="wpcf-dashboard-profile">
                        <div>
                            <p>Deactivated your Account</p>
                            <p>{data.country_name}</p>
                        </div>
                        <div>
                            <button>Yes</button>
                        </div>
                    </div>
                    <div className="wpcf-dashboard-profile">
                        <h2>Connected Social Account</h2>
                        <SocialForm data={ data } onClickSaveData={ this.onClickSaveData }/>
                    </div>
                </div>
            </div>
        )
	}
}


const mapStateToProps = state => ({
    user: state.user,
    countries: state.countries
})

export default connect( mapStateToProps, { fetchUser, saveUserData, fetchCountries } )(ProfileSettings);