import { __ } from '@wordpress/i18n';
import React, {Component, Fragment} from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { isEmail, removeArrValue  } from '../Helper';
import { reduxForm,  getFormValues, change as changeFieldValue } from 'redux-form';
import { fetchUser } from '../actions';
import PageControl from './Control';
import Preview from "./preview/Preview";
import PreviewBasic from "./preview/Basic";
import PreviewEmpty from "./preview/Empty";
import Icon from "./Icon"

const formName = "campaignForm";
const sectionName = "team";
const memberFields = { id:'', name:'', email:'', image:'', manage:false, edit:false}
class Team extends Component {
	constructor(props) {
		super(props);
		this.state = {
			editMember: -1,
			emailInputMsg: false,
			member: memberFields
		}
        this._onChange = this._onChange.bind(this);
        this._addMember = this._addMember.bind(this);
        this._editMember = this._editMember.bind(this);
        this._updateMember = this._updateMember.bind(this);
        this._deleteMember = this._deleteMember.bind(this);
	}

	_onChange(name, value) {
		const { formValues } = this.props;
		const team = [ ...formValues.team ];
		let { member, emailInputMsg, editMember } = this.state;
		member = Object.assign({}, member, {[name]: value});
		if(name=='email') {
			const validateEmail = isEmail(value);
			if(value && validateEmail == undefined) {
				const index = team.findIndex(item => item.email === value);
            	if(index === -1) {
					emailInputMsg = __('Searching...', 'wp-crowdfunding');
					this.props.fetchUser(value).then( response => {
						this.setState({
							member: response.user,
							emailInputMsg: response.message
						});
					})
					.catch( error => console.log(error));
				} else {
					emailInputMsg = __('Member already added', 'wp-crowdfunding');
				}
				this.setState({emailInputMsg});
			} else {
				emailInputMsg = validateEmail;
			}
			if(editMember !== -1) {
				this.setState({editMember:-1});
			}
		}
		this.setState({member, emailInputMsg});
	}

	_addMember() {
		const { formValues } = this.props;
		const team = [ ...formValues.team ];
		team.push(this.state.member);
		this.props.changeFieldValue(formName, sectionName, team);
		this.setState({member: memberFields});
	}

	_editMember(index) {
		const { team } = this.props.formValues;
		this.setState({member: team[index], editMember: index, emailInputMsg:''});
	}

	_updateMember() {
		const { editMember, member } = this.state;
		const { formValues } = this.props;
		const team = [ ...formValues.team ];
		team[editMember] = member;
		this.props.changeFieldValue(formName, sectionName, team);
		this.setState({member: memberFields, editMember: -1});
	}

	_deleteMember(index) {
		const { formValues: {team} } = this.props;
		const values = removeArrValue(team, index);
		this.props.changeFieldValue(formName, sectionName, values);
	}

	render() {
		const { formValues: {team}, handleSubmit, current, prevStep, lastStep, postId } = this.props;
		const { member: {id, name, email, image, manage, edit}, emailInputMsg, editMember } = this.state;
		return (
			<div className="row">
                <div className='col-md-7'>
					<form onSubmit={handleSubmit}>
						<div className="wpcf-accordion-wrapper">
							<div className="wpcf-accordion">
								<div className="wpcf-accordion-title active">
								{ __('Team Settings', 'wp-crowdfunding') }
								</div>
								<div className='wpcf-accordion-details'>
									<form>
										<div className='wpcf-form-group'>
											<label className='wpcf-field-title'>{ __('Email', 'wp-crowdfunding') }</label>
											<div>
												<input type="email" value={email} onChange={(e) => this._onChange('email', e.target.value)}/>
												<p className="wpcf-field-info">{emailInputMsg || "Enter Collaborator email address"}</p>
											</div>
										</div>

										{
											id !== "" && <Fragment>
												<div className='wpcf-form-group'>
													<label className='wpcf-field-title'>{ __('Collaborator Name', 'wp-crowdfunding') }</label>
													<div>
														<input type="text" value={name} readOnly/>
													</div>
												</div>

												<div className='wpcf-form-group'>
													<div>
														<img className="profile-form-img" src={(image) ? image : ''} alt="Profile Image" />
													</div>
												</div>

												<div className='wpcf-form-group'>
													<p className='wpcf-field-desc'>{ __('If you Want to Show Contributor List', 'wp-crowdfunding') }</p>
													<label htmlFor="wpcf_manage_camp" className="wpcf-team-label">
														<input id="wpcf_manage_camp" type="checkbox" checked={manage} onChange={(e) => this._onChange('manage', !manage)}/>
														{ __('Give Permission to Manage Campaign', 'wp-crowdfunding') }
													</label>
												</div>

												<div className='wpcf-form-group'>
													<p className='wpcf-field-desc'>{ __('If you Want to Show Contributor List', 'wp-crowdfunding') }</p>
													<label htmlFor="wpcf_edit_camp" className="wpcf-team-label">
														<input id="wpcf_edit_camp" type="checkbox" checked={edit} onChange={(e) => this._onChange('edit', !edit)}/>
														{ __('Give Permission to Edit Campaign', 'wp-crowdfunding') }
													</label>
												</div>

											</Fragment>
										}
										{ editMember !== -1 ?
											<button type="button" className="wpcf-btn wpcf-btn-round" onClick={() => this._updateMember()}>{ __('Update Member', 'wp-crowdfunding') }</button> :
											<button type="button" className="wpcf-btn wpcf-btn-round" onClick={() => this._addMember()} disabled={!id}><Icon name="plus" className="wpcf-icon"/>{ __('Add Member', 'wp-crowdfunding') }</button>
										}
									</form>
								</div>
							</div>
						</div>

						{team && <div className="wpcf-team-members">
							<h3>{ __('Team Members', 'wp-crowdfunding') }</h3>
							<div className="wpcf-team-member-items">
								{ team.map((item, index) =>
									<div key={index} className={`wpcf-team-member ${(editMember == index) ? 'active':''}`}>
										<div className="wpcf-team-avatar">
											<img className="profile-form-img" src={item.image} alt="Profile Image" />
										</div>
										<div className="wpcf-team-content">
											<h4>{item.name}</h4>
											<h6>Campaigner</h6>
											<p>{item.email}</p>
										</div>
										<div className="wpcf-team-overlay">
											<button type="button" className="fa fa-trash" onClick={ () => this._deleteMember(index)}/>
											<button type="button" className="fa fa-pencil" onClick={ () => this._editMember(index)}/>
										</div>
									</div>
								)}
							</div>
						</div> }

						<PageControl
                            current={current}
                            prevStep={prevStep}
                            lastStep={lastStep}/>
					</form>
				</div>
				<div className='col-md-5'>


					<Preview postId={postId} title="Preview">
						<PreviewEmpty/>
					</Preview>
                </div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
	postId: state.data.postId,
    formValues: getFormValues(formName)(state)
});

const mapDispatchToProps = dispatch => {
    return bindActionCreators({
		fetchUser,
        getFormValues,
        changeFieldValue
    }, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(reduxForm({
    form: formName,
	destroyOnUnmount: false, //preserve form data
  	forceUnregisterOnUnmount: true, //unregister fields on unmount
})(Team));
