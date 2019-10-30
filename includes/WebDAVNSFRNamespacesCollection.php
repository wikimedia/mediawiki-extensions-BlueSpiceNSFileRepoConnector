<?php
class WebDAVNSFRNamespacesCollection extends WebDAVPagesCollection {
	public function getChildren() {
		$children = array();
		$aNamespaces = NSFileRepoConnectorNamespaceHelper::getPossibleNamespaces();

		foreach ( $aNamespaces as $iNsId => $sNsName ) {
			$children[] = new WebDAVNSFRFilesCollection( $this, $sNsName, $iNsId );
		}

		return $children;
	}
}