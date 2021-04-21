<?php
class NSFileRepoConnectorNamespaceHelper {

	/**
	 * Returns an Array of Namespaces, that can be used for NSFileRepo
	 * @param Boolean $bFilterByPermissions
	 * @param User|null $oUser
	 * @return Array (NsIdx => NsLocalizedName)
	 */
	public static function getPossibleNamespaces( $bFilterByPermissions = true, $oUser = null ) {
		$oNamespaceList = new NSFileRepo\NamespaceList(
			RequestContext::getMain()->getUser(),
			new NSFileRepo\Config(),
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
