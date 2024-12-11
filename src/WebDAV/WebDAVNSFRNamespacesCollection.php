<?php

namespace BlueSpice\NSFileRepoConnector\WebDAV;

use BlueSpice\NSFileRepoConnector\Util\NSFileRepoConnectorHelper;
use WebDAVPagesCollection;

class WebDAVNSFRNamespacesCollection extends WebDAVPagesCollection {
	/**
	 *
	 * @return array
	 */
	public function getChildren() {
		$children = [];
		$aNamespaces = NSFileRepoConnectorHelper::getPossibleNamespaces();

		foreach ( $aNamespaces as $iNsId => $sNsName ) {
			$children[] = new WebDAVNSFRFilesCollection( $this, $sNsName, $iNsId );
		}

		return $children;
	}
}
