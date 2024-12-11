<?php

namespace BlueSpice\NSFileRepoConnector\Util;

use MediaWiki\Context\RequestContext;
use MediaWiki\Extension\NSFileRepo\NamespaceList;
use User;

class NSFileRepoConnectorHelper {
	/**
	 * Returns an Array of Namespaces, that can be used for NSFileRepo
	 * @param bool $bFilterByPermissions
	 * @param User|null $oUser
	 * @return array (NsIdx => NsLocalizedName)
	 */
	public static function getPossibleNamespaces( $bFilterByPermissions = true, $oUser = null ) {
		$oNamespaceList = new NamespaceList(
			RequestContext::getMain()->getUser(),
			new \MediaWiki\Extension\NSFileRepo\Config(),
			RequestContext::getMain()->getLanguage()
		);

		$aNamespaces = [];
		foreach ( $oNamespaceList->getReadable() as $iNsId => $oNamespace ) {
			$sName = RequestContext::getMain()->getLanguage()->getNsText( $iNsId );
			if ( $iNsId === NS_MAIN ) {
				$sName = wfMessage( 'nsfilerepo-nsmain' )->inContentLanguage()->plain();
			}
			$aNamespaces[$iNsId] = $sName;
		}

		return $aNamespaces;
	}
}
