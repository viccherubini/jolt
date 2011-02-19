<?php

declare(encoding='UTF-8');
namespace jolt;

class uploader {


	private $upload_data = array();

	private $filename = NULL;
	private $destination = NULL;

	private $unique = true;
	private $overwrite = false;

	public function __construct() {

	}

	public function __destruct() {

	}

	public function upload() {
		if (0 == count($this->upload_data)) {
			throw new \jolt\exception('The uploaded data can not be empty.');
		}

		if (!is_writable($this->destination)) {
			throw new \jolt\exception('The destination directory is not writable.');
		}

		$file_path = $this->destination . $this->filename;
		if (is_file($file_path) && !$this->overwrite) {
			throw new \jolt\exception('The file being uploaded already exists.');
		}

		if (!move_uploaded_file($this->upload_data['tmp_name'], $file_path)) {
			throw new \jolt\exception('The file could not be moved from the temporary directory to the destination.');
		}
		return true;
	}

	public function set_destination($destination) {
		$destination_length = strlen(trim($destination))-1;
		if ($destination_length >= 0 && $destination[$destination_length] != DIRECTORY_SEPARATOR) {
			$destination .= DIRECTORY_SEPARATOR;
		}
		$this->destination = $destination;
		return $this;
	}

	public function set_overwrite($overwrite) {
		$this->overwrite = $overwrite;
		return $this;
	}

	public function set_unique($unique) {
		if (!is_bool($unique)) {
			$unique = true;
		}
		$this->unique = $unique;
		return $this;
	}

	public function set_upload_data($upload_data) {
		if (!is_array($upload_data) || 0 == count($upload_data)) {
			throw new \jolt\exception('The data attached to be uploaded can not be empty.');
		}

		$error = NULL;
		switch ($upload_data['error']) {
			case UPLOAD_ERR_INI_SIZE: {
				$filesize = filesize($upload_data['tmp_name']);
				$ini_size = ini_get('upload_max_filesize');

				$ini_size_last_char_index = strlen($ini_size)-1;
				$ini_size_last_char = strtoupper($ini_size[$ini_size_last_char_index]);

				$byte_power = 1;
				if ('M' === $ini_size_last_char) {
					$byte_power = 2;
				} elseif ('G' === $ini_size_last_char) {
					$byte_power = 3;
				}

				$ini_byte_size = $ini_size * pow(1024, $byte_power);
				$error = sprintf('The file being uploaded is %d bytes larger than allowed in php.ini.', $ini_byte_size);
				break;
			}

			case UPLOAD_ERR_FORM_SIZE: {
				$error = 'The file is larger than allowed from the submitted form.';
				break;
			}

			case UPLOAD_ERR_PARTIAL: {
				$error = 'Only partial data was uploaded.';
				break;
			}

			case UPLOAD_ERR_NO_FILE: {
				$error = 'No file was present.';
				break;
			}

			case UPLOAD_ERR_NO_TMP_DIR: {
				$tmp_directory = ini_get('upload_tmp_dir');
				$error = sprintf('The temporary directory %s does not exist.', $tmp_directory);
				break;
			}

			case UPLOAD_ERR_CANT_WRITE: {
				$error = 'The temporary directory is not writable.';
				break;
			}

			case UPLOAD_ERR_EXTENSION: {
				$error = 'This type of file is not allowed.';
				break;
			}
		}

		if (!empty($error)) {
			throw new \jolt\exception($error);
		}

		$this->upload_data = $upload_data;
		$this->filename = $upload_data['name'];
		if ($this->unique) {
			$this->filename = uniqid() . '-' . $this->filename;
		}
		$this->filename = str_replace(' ', '-', $this->filename);
		return $this;
	}

	public function get_destination() {
		return $this->destination;
	}

	public function get_filename() {
		return $this->filename;
	}

	public function get_file_path() {
		return $this->destination . $this->filename;
	}
}