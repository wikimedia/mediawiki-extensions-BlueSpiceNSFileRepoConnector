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
		$this->path = utf8_encode( $this->path );
		$this->name = utf8_encode( $this->name );
		$this->filename = utf8_encode( $this->filename );
	}

}
