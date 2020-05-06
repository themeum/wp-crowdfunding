import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/registration', {
	title: __('Registration'),
	icon: 'art',
    category: 'wp-crowdfunding',
    keywords: [__('Registration'), __('WPCF Registration')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  