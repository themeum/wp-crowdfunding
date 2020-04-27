import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/donate', {
	title: __('Donate'),
	icon: 'megaphone',
    category: 'wp-crowdfunding',
    keywords: [__('Campaign Donate'), __('WPCF Donate')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  