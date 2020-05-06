import Edit from './Edit';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('wp-crowdfunding/dashboard', {
	title: __('Dashboard'),
	icon: 'admin-site',
    category: 'wp-crowdfunding',
    keywords: [__('Dashboard'), __('WPCF Dashboard')],
	edit: Edit,
	save: function( props ) {
		return null;
	}
});  