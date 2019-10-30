<?php
class WebDAVNSFRFileFile extends WebDAVFileFile {
	protected $sName = '';
	protected $sPrefix = '';

	public function __construct($oFile) {
		parent::__construct($oFile);
		$this->sName = $oFile->getTitle()->getText(); //'File:Project:someFile.pdf' --> 'Project:someFile.pdf'
		$aFileTitleParts = explode( ':', $this->sName, 2 );
		if( count( $aFileTitleParts ) > 1 ) {
			$this->sPrefix = str_replace(' ', '_', $aFileTitleParts[0] ).':';
			$this->sName = $aFileTitleParts[1];
		}

		$this->sName = str_replace( ' ', '_', $this->sName );
	}

	public function getName() {
		return $this->sName;
	}

	public function setName($name) {
		$name = $this->sPrefix.$name;
		parent::setName($name);
	}
}