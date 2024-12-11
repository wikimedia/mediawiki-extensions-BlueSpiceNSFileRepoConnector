<?php

namespace BlueSpice\NSFileRepoConnector\Hook;

use MediaWiki\Config\GlobalVarConfig;
use MediaWiki\Extension\NSFileRepo\NamespaceList;
use MediaWiki\Output\OutputPage;

class MakeGlobalVariables {
	/**
	 * Exposes basic permissions of this user to the client side.
	 * This should not be used for _all_ permissions!
	 * TODO: Maybe move to BSF
	 * @param array &$vars
	 * @param OutputPage $out
	 * @return bool
	 */
	public static function onMakeGlobalVariablesScript( &$vars, $out ) {
		$oNamespaceList = new NamespaceList(
			$out->getUser(),
			new GlobalVarConfig( 'egNSFileRepo' ),
			$out->getLanguage()
		);

		$vars['bsgNSBasePermissions'] = [
			'read' => array_keys( $oNamespaceList->getReadable() ),
			'edit' => array_keys( $oNamespaceList->getEditable() )
		];

		return true;
	}
}
