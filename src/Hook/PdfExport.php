<?php

namespace BlueSpice\NSFileRepoConnector\Hook;

use BlueSpice\UEModulePDF\Hook\BSUEModulePDFFindFiles;

class PdfExport extends BSUEModulePDFFindFiles {

	/**
	 * @inheritDoc
	 */
	protected function doProcess() {
		$filename = str_replace( ':', '_', $this->fileName );
		$this->imageEl->setAttribute( 'src', 'images/' . urlencode( $filename ) );

		return true;
	}
}
