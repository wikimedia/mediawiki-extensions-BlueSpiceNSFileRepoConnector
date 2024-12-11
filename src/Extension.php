<?php

namespace BlueSpice\NSFileRepoConnector;

use BlueSpice\NSFileRepoConnector\WebDAV\WebDAVNSFRNamespacesCollection;

class Extension extends \BlueSpice\Extension {

	/**
	 * @return void
	 */
	public static function setup() {
		if ( isset( $GLOBALS['wgWebDAVNamespaceCollections'] ) ) {
			$GLOBALS['wgWebDAVNamespaceCollections'][NS_MEDIA]
				= WebDAVNSFRNamespacesCollection::class;
		}
	}
}
