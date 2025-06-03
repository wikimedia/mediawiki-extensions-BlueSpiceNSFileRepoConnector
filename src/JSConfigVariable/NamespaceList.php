<?php

namespace BlueSpice\NSFileRepoConnector\JSConfigVariable;

use BlueSpice\JSConfigVariable;
use MediaWiki\Config\GlobalVarConfig;

class NamespaceList extends JSConfigVariable {

	/**
	 * @inheritDoc
	 */
	public function getValue() {
		$oNamespaceList = new \MediaWiki\Extension\NSFileRepo\NamespaceList(
			$this->context->getUser(),
			new GlobalVarConfig( 'egNSFileRepo' ),
			$this->context->getLanguage()
		);

		return [
			'read' => array_keys( $oNamespaceList->getReadable() ),
			'edit' => array_keys( $oNamespaceList->getEditable() )
		];
	}
}
