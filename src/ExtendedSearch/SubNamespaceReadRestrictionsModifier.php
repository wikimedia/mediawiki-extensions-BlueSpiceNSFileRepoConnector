<?php

namespace BlueSpice\NSFileRepoConnector\ExtendedSearch;

use BS\ExtendedSearch\Source\LookupModifier\WikiPageSecurityTrimming;

class SubNamespaceReadRestrictionsModifier extends WikiPageSecurityTrimming {

	/**
	 * @return void
	 */
	public function apply() {
		$readableNamespaces = $this->utilityFactory->getReadableNamespacesHelper();

		$this->namespaceIdBlacklist = $readableNamespaces->getRestrictedNamespaces( $this->context->getUser() );
		if ( !empty( $this->namespaceIdBlacklist ) ) {
			$this->lookup->addBoolMustNotTerms( 'sub-namespace', $this->namespaceIdBlacklist );
		}
	}
}
