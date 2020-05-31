import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/submitform', {
	title: __('Campaign Submission'),
	icon: 'buddicons-groups',
    category: 'wp-crowdfunding',
    keywords: [__('Campaign Submit Form'), __('WPCF Submit Form')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  