<?php/*Plugin Name: Mobile Browser Tab ColorPlugin URI: http://matthewbacklas.comDescription: Changes color of tab UI in supported mobile browsers. Option is under 'Settings' -> General.Version: 1.0Author: Matthew BacklasAuthor URI: http://matthewbacklas.comLicense: GNU General Public License v3.0*/if ( ! defined( 'ABSPATH' ) ) exit;class MB_chrome_browser_color{		private $color;		public function __construct()	{		if (!get_option( 'mb_chrome_color' )){			$this->color = '#FFFFFF';		}else{			$this->color = get_option( 'mb_chrome_color' );		}				if( is_admin() ) { 			add_action( 'admin_enqueue_scripts', array($this,'mb_chrome_enqueue_color_picker') );			add_action( 'admin_init', array( $this, 'mb_chrome_add_settings' ) );			add_action( 'admin_head' , array($this, 'mb_chrome_output_colorpicker_script') );			if (!$this->verify_hex_color($this->color)){				add_action( 'admin_notices', array( $this, 'mb_color__error') );			}		}else{			add_action( 'wp_head', array( $this, 'mb_chrome_frontend_output' ), 99);		}	}		public function mb_color__error()	{		?>			<div class="notice notice-error is-dismissible">				<p><?php _e( 'Please use a valid hex color provided by the color picker.', 'mb_chrome-text-domain' ); ?></p>			</div>		<?php	}	public function mb_chrome_enqueue_color_picker()	{		wp_enqueue_style( 'wp-color-picker' );		wp_enqueue_script( 'mb_chrome_script', plugins_url('', __FILE__ ), array( 'wp-color-picker' ), false, true );	}		private function verify_hex_color($color)	{		$verify = false;		if(preg_match('/^#[a-f0-9]{6}$/i', $color)){			$verify = true;		}else if(preg_match('/^[a-f0-9]{6}$/i', $color)){			$verify = true;		}				return $verify;	}		public function mb_chrome_add_settings()	{			register_setting(				'general',				'mb_chrome_color',				array($this, 'mb_chrome_color_validate_options')			);				add_settings_field(				'mb_chrome_bc',				'Chrome Browser Color',				array($this, 'mb_chrome_bc_input'),				'general',				'default'			);	}		private function mb_chrome_color_validate_options($input)	{		if (!$this->verify_hex_color($input)){			$input = $this->color;		}				return $input;	}		public function mb_chrome_output_colorpicker_script()	{		?>			<script>			jQuery(document).ready(function(){					jQuery('#mb_chrome_bc').wpColorPicker();			});			</script>		<?php	}		public function mb_chrome_bc_input()	{		?>		<input id='mb_chrome_bc' name='mb_chrome_color' type='text' value='<?php echo $this->color; ?>' />		<?php	}		public function mb_chrome_frontend_output()	{		if ($this->verify_hex_color($this->color)){			?>			<meta name="theme-color" content="<?php if ($this->color[0] === '#'){ echo $this->color; }else{ echo '#' . $this->color; }?>">			<?php		}	}}$mb_chrome_browser_color = new MB_chrome_browser_color();