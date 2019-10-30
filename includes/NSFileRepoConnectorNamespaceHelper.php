<?php
class NSFileRepoConnectorNamespaceHelper {

	/**
	 * Returns an Array of Namespaces, that can be used for NSFileRepo
	 * @param Boolean $bFilterByPermissions
	 * @param User $oUser
	 * @return Array (NsIdx => NsLocalizedName)
	 */
	public static function getPossibleNamespaces( $bFilterByPermissions = true, $oUser = null ) {
		$oNamespaceList = new NSFileRepo\NamespaceList(
			RequestContext::getMain()->getUser(),
			new NSFileRepo\Config(),
			RequestContext::getMain()->getLanguage()
		);

		$aNamespaces = [];
		foreach( $oNamespaceList->getReadable() as $iNsId => $oNamespace ) {
			$sName = $oNamespace->getCanonicalName();
			if( $iNsId === NS_MAIN ) {
				$sName = wfMessage( 'nsfilerepo-nsmain' )->inContentLanguage()->plain();
			}
			$aNamespaces[$iNsId] = $sName;
		}

		return $aNamespaces;
	}
}