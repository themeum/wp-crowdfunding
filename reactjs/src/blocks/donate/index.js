import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/donate', {
	title: __('Donation'),
	icon: 'megaphone',
    category: 'wp-crowdfunding',
    keywords: [__('Campaign Donation'), __('WPCF Donation')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  