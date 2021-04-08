import React, { Component } from 'react';

class ProfileEditForm extends Component {
	state = {
		...this.props.data,
		new_pass: '',
		retype_pass: '',
		error: '',
	};

	changeImage = () => {
		wp.media.editor.send.attachment = (props, attachment) => {
			this.setState({
				profile_image: attachment.url,
				profile_image_id: attachment.id,
			});
		};
		wp.media.editor.open();
	};

	onChangeValue = (e) => {
		this.setState({ [e.target.name]: e.target.value });
	};

	onSubmit = (e) => {
		e.preventDefault();
		const { new_pass, retype_pass } = this.state;
		let postData = this.state;
		delete postData.new_pass;
		delete postData.retype_pass;
		delete postData.error;

		if (new_pass) {
			if (new_pass !== retype_pass) {
				this.setState({
					error: "Password doesn't match",
				});
				return false;
			}
			postData['password'] = new_pass;
		}
		this.props.onClickSaveData(postData);
		this.setState({ error: '' });
		this.props.toggleEdit();
	};

	render() {
		const { countries } = this.props;
		const {
			profile_image,
			username,
			first_name,
			last_name,
			profile_email1,
			profile_country,
			profile_city,
			profile_address,
			profile_post_code,
			new_pass,
			retype_pass,
			error,
		} = this.state;
		return (
			<form onSubmit={this.onSubmit}>
				{error && <div className='alert alert-danger'>{error}</div>}
				<div className='wpcf-form-group'>
					<label>Profile Picture</label>
					<div className='wpcf-profile-photo-edit'>
						<img
							className=''
							src={profile_image}
							style={{ maxWidth: '200px' }}
						/>
						<button aria-label='Edit' onClick={this.changeImage}>
							<span className='fas fa-pen'></span>
						</button>
					</div>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_username'>Username</label>
					<input
						type='text'
						id='wpcfp_username'
						name='username'
						value={username}
						disabled
					/>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_first_name'>First Name</label>
					<input
						type='text'
						id='wpcfp_first_name'
						name='first_name'
						value={first_name}
						onChange={this.onChangeValue}
					/>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_last_name'>Last Name</label>
					<input
						type='text'
						id='wpcfp_last_name'
						name='last_name'
						value={last_name}
						onChange={this.onChangeValue}
					/>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_email'>Email</label>
					<input
						type='text'
						id='wpcfp_email'
						name='profile_email1'
						value={profile_email1}
						onChange={this.onChangeValue}
					/>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_email'>Country</label>
					<select
						name='profile_country'
						value={profile_country}
						onChange={this.onChangeValue}
					>
						<option value=''>Select Country</option>
						{Object.keys(countries).map((key) => (
							<option key={key} value={key}>
								{countries[key]}
							</option>
						))}
						<option></option>
					</select>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_city'>City</label>
					<input
						type='text'
						id='wpcfp_city'
						name='profile_city'
						value={profile_city}
						onChange={this.onChangeValue}
					/>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_address'>Address</label>
					<input
						type='text'
						id='wpcfp_address'
						name='profile_address'
						value={profile_address}
						onChange={this.onChangeValue}
					/>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_post_code'>Postal Code</label>
					<input
						type='text'
						id='wpcfp_post_code'
						name='profile_post_code'
						value={profile_post_code}
						onChange={this.onChangeValue}
					/>
				</div>
				<h3 className='wpcf-form-title'>Password</h3>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_new_pass'>New Password</label>
					<input
						type='password'
						id='wpcfp_new_pass'
						name='new_pass'
						value={new_pass || ''}
						onChange={this.onChangeValue}
					/>
				</div>
				<div className='wpcf-form-group'>
					<label htmlFor='wpcfp_retype_pass'>Retype Password</label>
					<input
						type='password'
						id='wpcfp_retype_pass'
						name='retype_pass'
						value={retype_pass || ''}
						onChange={this.onChangeValue}
					/>
				</div>
				<div className='wpcf-profile-submit wpcf-btn-group'>
					<button
						type='button'
						className='wpcf-btn wpcf-btn-round wpcf-btn-outline'
						onClick={this.props.toggleEdit}
					>
						Cancel
					</button>
					<button type='submit' className='wpcf-btn wpcf-btn-round'>
						<i className='fas fa-save icon-left'></i> Save
					</button>
				</div>
			</form>
		);
	}
}

export default ProfileEditForm;
