<?php

namespace BlueSpice\NSFileRepoConnector\Hook\ImgAuthBeforeCheckFileExists;

use BlueSpice\NSFileRepoConnector\Hook\ImgAuthBeforeCheckFileExists;

class FixFileNameEncodingForIIS extends ImgAuthBeforeCheckFileExists {

	/**
	 *
	 * @return bool
	 */
	protected function skipProcessing() {
		return !wfIsWindows();
	}

	protected function doProcess() {
		$this->path = mb_convert_encoding( $this->path, 'UTF-8', 'ISO-8859-1' );
		$this->name = mb_convert_encoding( $this->name, 'UTF-8', 'ISO-8859-1' );
		$this->filename = mb_convert_encoding( $this->filename, 'UTF-8', 'ISO-8859-1' );
	}

}
