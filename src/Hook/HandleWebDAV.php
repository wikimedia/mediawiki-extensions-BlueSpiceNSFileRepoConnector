<?php

namespace BlueSpice\NSFileRepoConnector\Hook;

use MediaWiki\Title\Title;

class HandleWebDAV {

	/**
	 * @param string &$filename
	 * @param string $url
	 * @return bool
	 */
	public function onWebDAVGetFilenameFromUrl( &$filename, $url ) {
		$urlBits = explode( '/', $url );
		$file = array_pop( $urlBits );
		$namespace = array_pop( $urlBits );

		if ( $namespace === wfMessage( 'nsfilerepo-nsmain' )->plain() ) {
			$filename = $file;
		} else {
			$filename = $namespace . ':' . $file;
		}

		return true;
	}

	/**
	 * Insert correct namespace path if needed
	 * @param string &$sPath
	 * @param string &$sFilename
	 * @param Title $oTitle
	 * @return bool Always true to keep hook running
	 */
	public function onWebDAVUrlProviderGetUrl( &$sPath, &$sFilename, $oTitle ) {
		$aFileParts = explode( ':', $oTitle->getDBKey(), 2 );
		if ( count( $aFileParts ) === 1 ) {
			// NS_MAIN --> prepend '(Pages)'
			array_unshift(
				$aFileParts,
				wfMessage( 'nsfilerepo-nsmain' )->plain()
			);
		}

		$sFilename = implode( '/', $aFileParts );

		return true;
	}
}
