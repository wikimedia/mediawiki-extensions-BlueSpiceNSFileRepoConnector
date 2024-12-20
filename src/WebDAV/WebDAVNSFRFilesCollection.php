<?php

namespace BlueSpice\NSFileRepoConnector\WebDAV;

use MediaWiki\Extension\NSFileRepo\File\NamespaceLocalFile;
use Node;
use WebDAVFilesCollection;

class WebDAVNSFRFilesCollection extends WebDAVFilesCollection {
	/**
	 *
	 * @return array of Nodes
	 */
	public function getChildren() {
		$config = $this->services->getConfigFactory()->makeConfig( 'webdav' );
		$sPrefix = $this->getPrefix();

		$dbr = $this->services->getDBLoadBalancer()->getConnection( DB_REPLICA );
		$pattern = [
			$sPrefix, $dbr->anyString()
		];

		$fileQuery = NamespaceLocalFile::getQueryInfo();
		$res = $dbr->select(
			$fileQuery['tables'],
			$fileQuery['fields'],
			'img_name ' . $dbr->buildLike( $pattern ),
			__METHOD__,
			[],
			$fileQuery['joins']
		);

		$localRepo = $this->services->getRepoGroup()->getLocalRepo();
		$children = [];
		foreach ( $res as $row ) {
			$sTrimmedTitle = substr( $row->img_name, strlen( $sPrefix ) );

			if ( preg_match( $config->get( 'WebDAVInvalidFileNameCharsRegEx' ), $sTrimmedTitle ) !== 0 ) {
				continue;
			}

			$oFile = $localRepo->newFileFromRow( $row );
			$children[] = new WebDAVNSFRFileFile( $oFile );
		}

		return array_values( $children );
	}

	/**
	 * This would not be needed if base class did not override this method...
	 * @param string $name
	 * @return Node
	 */
	public function getChild( $name ) {
		$sPrefix = $this->getPrefix();
		$sPrefixedName = $sPrefix . $name;

		$oWebDAVFileFile = parent::getChild( $sPrefixedName );
		return new WebDAVNSFRFileFile( $oWebDAVFileFile->getFileObj() );
	}

	/**
	 *
	 * @param string $name
	 * @param resource|null $data
	 */
	public function createFile( $name, $data = null ) {
		$sPrefix = $this->getPrefix();
		$sPrefixedName = $sPrefix . $name;
		parent::createFile( $sPrefixedName, $data );
	}

	/**
	 *
	 * @return string
	 */
	public function getPrefix() {
		$sPrefix = $this->sName . ':';
		if ( $this->iNSId === NS_MAIN ) {
			$sPrefix = '';
		}
		return str_replace( ' ', '_', $sPrefix );
	}

}
