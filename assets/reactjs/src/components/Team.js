import { __ } from '@wordpress/i18n';
import React, { Component } from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { isEmail, removeArrValue  } from '../Helper';
import { reduxForm,  getFormValues, change as changeFieldValue } from 'redux-form';
import { fetchUser } from '../actions';
import PageControl from './Control';

const formName = "campaignForm";
const sectionName = "team";
class Team extends Component {
	constructor(props) {
		super(props);
		this.state = {
			edit: false,
			searchingUser:false,
			member: { id:'', image:'', email:'', name:'', manage_campaign:false, edit_campaign:false}
		}
        this._onChange = this._onChange.bind(this);
        this._addMember = this._addMember.bind(this);
        this._deleteMember = this._deleteMember.bind(this);
	}

	_onChange(name, value) {
		let { member } = this.state;
		member = Object.assign({}, member, {[name]: value});
		if(name=='email') {
			if(isEmail(email) === undefined) {
				this.props.fetchUser.then( response =>  {
					
				})
				.catch( error => console.log(error));
			}
		}
		this.setState({member});
	}

	_addMember() {
		/* const { selectedItem } = this.state;
		const { formValues } = this.props;
		const team = [ ...formValues.team ];

		this.props.changeFieldValue(formName, sectionName, team);
		this.setState({selectedItem: team.length-1}); */
	}
	
	_deleteMember(index) {
		/* const { formValues: {team} } = this.props;
		const selectedItem = (index==0) ? 0 : index-1;
		const values = removeArrValue(team, index);
		this.setState({selectedItem});
		this.props.changeFieldValue(formName, sectionName, values); */
	}

	render() {
		const { member: {email, name, manage_campaign, edit_campaign} } = this.state;
		const { formValues: {team}, handleSubmit, current, prevStep, lastStep } = this.props;
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
										<div className='wpcf-form-field'>
											<div className='wpcf-field-title'>{ __('Email', 'wp-crowdfunding') }</div>
											<div className="">
												<input type="email" value={email} onChange={(e) => this._onChange('email', e.target.value)}/>
												<span>{isEmail(email)}</span>
											</div>
										</div>

										<div className='wpcf-form-field'>
											<div className='wpcf-field-title'>{ __('Collaborator Name', 'wp-crowdfunding') }</div>
											<div className="">
												<input type="text" value={name} readOnly/>
											</div>
										</div>

										<div className='wpcf-form-field'>
											<div className='wpcf-field-title'>{ __('If you Want to Show Contributor List', 'wp-crowdfunding') }</div>
											<div className="">
												<label className="checkbox-inline">
													<input type="checkbox" checked={manage_campaign} onChange={(e) => this._onChange('manage_campaign', !manage_campaign)}/>
													{ __('Give Permission to Manage Campaign', 'wp-crowdfunding') }
												</label>
											</div>
										</div>

										<div className='wpcf-form-field'>
											<div className='wpcf-field-title'>{ __('If you Want to Show Contributor List', 'wp-crowdfunding') }</div>
											<div className="">
												<label className="checkbox-inline">
													<input type="checkbox" checked={edit_campaign} onChange={(e) => this._onChange('edit_campaign', !edit_campaign)}/>
													{ __('Give Permission to Edit Campaign', 'wp-crowdfunding') }
												</label>
											</div>
										</div>
											
										<button type="button" onClick={() => this._addMember()}><span className="fa fa-plus"/>{ __('Add Member', 'wp-crowdfunding') }</button>
									</form>
								</div>
							</div>
						</div>
					
						<div className="wpcf-team-members">
							<h3>{ __('Team Members', 'wp-crowdfunding') }</h3>
							{team && team.map((item, index) =>
								<div key={index} className={`wpcf-reward-item ${(selectedItem == index) ? 'active':''}`}>
									<p>{item.name}</p>
									<p>{item.email}</p>
									<span className="fa fa-trash" onClick={ () => this._deleteMember(index)}/>
									<span className="fa fa-pencil" onClick={ () => this.setState({selectedItem:index})}/>
								</div>
							)}
						</div>
						
						<PageControl
                            current={current}
                            prevStep={prevStep}
                            lastStep={lastStep}/>
					</form>
				</div>
				<div className='col-md-5'>
                    <div className='wpcf-form-sidebar'>
                        <div className="preview-title">{ __('Preview', 'wp-crowdfunding') }</div>
						<p>{ __('Nothing to See here', 'wp-crowdfunding') }</p>
                    </div>
                </div>
			</div>
		)
	}
}

const mapStateToProps = state => ({
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
