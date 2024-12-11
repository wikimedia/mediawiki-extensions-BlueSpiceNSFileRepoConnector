<?php

namespace BlueSpice\NSFileRepoConnector\Hook;

use MediaWiki\Hook\BeforePageDisplayHook;

class AddResources implements BeforePageDisplayHook {

	/**
	 * @inheritDoc
	 */
	public function onBeforePageDisplay( $out, $skin ): void {
		$out->addModules( 'ext.bluespice.NSFRC' );
		$out->addModules( 'mediawiki.Title.newFromImg' );
	}
}
