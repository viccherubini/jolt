<?php

declare(encoding='UTF-8');
namespace Jolt;

class Uploader {

	private $uploadData = array();

	private $fileName = NULL;
	private $destination = NULL;

	private $unique = true;
	private $overwrite = false;

	public function __construct() {

	}

	public function __destruct() {

	}

	public function upload() {
		if ( 0 == count($this->uploadData) ) {
			throw new \Jolt\Exception($lang['error_no_upload_data']);
		}

		if ( !is_writable($this->destination) ) {
			throw new \Jolt\Exception('The destination directory is not writable');
		}

		$filePath = $this->destination . $this->fileName;
		if ( is_file($filePath) && !$this->overwrite ) {
			throw new \Jolt\Exception('The file being uploaded already exists');
		}

		if ( !move_uploaded_file($this->uploadData['tmp_name'], $filePath) ) {
			throw new \Jolt\Exception('The file could not be moved from the temporary directory to the destination');
		}

		return true;
	}

	public function setDestination($destination) {
		$destinationLength = strlen(trim($destination)) - 1;
		if ( $destinationLength >= 0 && $destination[$destinationLength] != DIRECTORY_SEPARATOR ) {
			$destination .= DIRECTORY_SEPARATOR;
		}
		$this->destination = $destination;
		return $this;
	}

	public function setOverwrite($overwrite) {
		$this->overwrite = $overwrite;
		return $this;
	}

	public function setUnique($unique) {
		if ( !is_bool($unique) ) {
			$unique = true;
		}
		$this->unique = $unique;
		return $this;
	}

	public function setUploadData($uploadData) {
		if ( !is_array($uploadData) || 0 == count($uploadData) ) {
			throw new \Jolt\Exception('The data attached to be uploaded is empty');
		}

		$error = NULL;
		switch ( $uploadData['error'] ) {
			case UPLOAD_ERR_INI_SIZE: {
				$error = 'The file is larger than allowed in php.ini';
				break;
			}

			case UPLOAD_ERR_FORM_SIZE: {
				$error = 'The file is larger than allowed from the submitted form';
				break;
			}

			case UPLOAD_ERR_PARTIAL: {
				$error = 'Only partial data was uploaded';
				break;
			}

			case UPLOAD_ERR_NO_FILE: {
				$error = 'No file was present';
				break;
			}

			case UPLOAD_ERR_NO_TMP_DIR: {
				$error = 'The temporary directory does not exist';
				break;
			}

			case UPLOAD_ERR_CANT_WRITE: {
				$error = 'The temporary directory is not writable';
				break;
			}

			case UPLOAD_ERR_EXTENSION: {
				$error = 'This type of file is not allowed';
				break;
			}
		}

		if ( !empty($error) ) {
			throw new \Jolt\Exception($error);
		}

		$this->uploadData = $uploadData;

		$this->fileName = $uploadData['name'];
		if ( $this->unique ) {
			$this->fileName = uniqid('', true) . '-' . $this->fileName;
		}
		$this->fileName = str_replace(' ', '-', $this->fileName);

		return $this;
	}

	public function getDestination() {
		return $this->destination;
	}

	public function getFileName() {
		return $this->fileName;
	}

	public function getFilePath() {
		return $this->destination . $this->fileName;
	}

}