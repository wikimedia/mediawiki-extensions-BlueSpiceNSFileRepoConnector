<?php
class NSFileRepoConnectorNamespaceHelper {

	/**
	 * Returns an Array of Namespaces, that can be used for NSFileRepo
	 * @param bool $bFilterByPermissions
	 * @param User|null $oUser
	 * @return array (NsIdx => NsLocalizedName)
	 */
	public static function getPossibleNamespaces( $bFilterByPermissions = true, $oUser = null ) {
		$oNamespaceList = new NSFileRepo\NamespaceList(
			RequestContext::getMain()->getUser(),
			new NSFileRepo\Config(),
			RequestContext::getMain()->getLanguage()
		);

		$aNamespaces = [];
		foreach ( $oNamespaceList->getReadable() as $iNsId => $oNamespace ) {
			$context = RequestContext::getMain();
			$sName = $context->getLanguage()->getNsText( $iNsId );
			if ( $iNsId === NS_MAIN ) {
				$sName = $context->msg( 'nsfilerepo-nsmain' )->plain();
			}
			$aNamespaces[$iNsId] = $sName;
		}

		return $aNamespaces;
	}
}
