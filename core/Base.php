<?php
defined( 'ABSPATH' ) || exit;

if (! class_exists('WPCF_Core_Base')) {

    class WPCF_Core_Base{

        protected static $_instance = null;
        public static function instance(){
            if (is_null(self::$_instance)){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct(){
			add_action( 'init', array( $this, 'blocks_init' ));
			add_action( 'enqueue_block_editor_assets', array( $this, 'post_editor_assets' ) );
			add_action( 'enqueue_block_assets', array( $this, 'post_block_assets' ) );
			add_filter( 'block_categories', array( $this, 'block_categories'), 1 , 2 );
		}

		/**
		 * Blocks Init
		 */
		public function blocks_init(){
			require_once WPCF_DIR_PATH . 'core/blocks/wpcf_search/wpcf_search.php';
        }
        
		/**
		 * Only for the Gutenberg Editor(Backend Only)
		 */
		public function post_editor_assets(){
			
			wp_enqueue_style(
				'crowdfunding-editor-editor-css',
				WPCF_DIR_URL . 'assets/css/blocks.editor.build.css',
				array( 'wp-edit-blocks' ),
				false
			);

			// Scripts.
			wp_enqueue_script(
				'crowdfunding-block-script-js',
				WPCF_DIR_URL . 'assets/js/blocks.script.build.min.js', 
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
				false,
				true
			);

			wp_localize_script( 'crowdfunding-block-script-js', 
			'thm_option', array(
                'plugin' => WPCF_DIR_URL,
				'name' => 'crowdfunding'
			) );
		}

		/**
		 * All Block Assets (Frontend & Backend)
		 */
		public function post_block_assets(){
			// Styles.
			wp_enqueue_style(
				'wpcf-core-global-style-css',
				WPCF_DIR_URL . 'assets/css/blocks.style.build.css', 
				array( 'wp-editor' ),
				false
			);
		}

		/**
		 * Block Category Add
		 */
		public function block_categories( $categories, $post ){
			return array_merge(
				array(
					array(
						'slug' 	=> 'wp-crowdfunding',
						'title' => __( 'WP Crowdfunding', 'wp-crowdfunding' ),
					)
				),
				$categories
			);
		}


    }
}
WPCF_Core_Base::instance();

