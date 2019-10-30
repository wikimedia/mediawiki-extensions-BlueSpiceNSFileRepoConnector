<?php
class WebDAVNSFRFilesCollection extends WebDAVFilesCollection {
	/**
	 *
	 * @global string $bsgWebDAVInvalidFileNameCharsRegEx
	 * @global Language $wgContLang
	 * @return array of Nodes
	 */
	public function getChildren() {
		$config = \BlueSpice\Services::getInstance()->getConfigFactory()->makeConfig( 'webdav' );
		$sPrefix = $this->getPrefix();

		$dbr = wfGetDB( DB_REPLICA );
		$pattern = array(
			$sPrefix, $dbr->anyString()
		);
		$res = $dbr->select(
			'image',
			'*',
			'img_name '.$dbr->buildLike( $pattern )
		);

		$children = array();
		foreach( $res as $row ) {
			$sTrimmedTitle = substr( $row->img_name, strlen( $sPrefix ) );

			if( preg_match( $config->get( 'WebDAVInvalidFileNameCharsRegEx') , $sTrimmedTitle ) !== 0 ) {
				continue;
			}

			$oFile = RepoGroup::singleton()->getLocalRepo()->newFileFromRow( $row );
			$children[] = new WebDAVNSFRFileFile( $oFile );
		}

		return array_values( $children );
	}

	/**
	 * This would not be needed if base class did not override this method...
	 * @param string $name
	 * @return Node
	 */
	public function getChild($name) {
		$sPrefix = $this->getPrefix();
		$sPrefixedName = $sPrefix.$name;

		$oWebDAVFileFile = parent::getChild( $sPrefixedName );
		return new WebDAVNSFRFileFile( $oWebDAVFileFile->getFileObj() );
	}

	public function createFile($name, $data = null) {
		$sPrefix = $this->getPrefix();
		$sPrefixedName = $sPrefix.$name;
		parent::createFile($sPrefixedName, $data);
	}

	public function getPrefix() {
		$sPrefix = $this->sName.':';
		if( $this->iNSId === NS_MAIN ) {
			$sPrefix = '';
		}
		return str_replace( ' ', '_',  $sPrefix );
	}

}
