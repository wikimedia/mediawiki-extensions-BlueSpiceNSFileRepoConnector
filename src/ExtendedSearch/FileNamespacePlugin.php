<?php

namespace BlueSpice\NSFileRepoConnector\ExtendedSearch;

use BS\ExtendedSearch\ILookupModifierProvider;
use BS\ExtendedSearch\ISearchDocumentProvider;
use BS\ExtendedSearch\ISearchSource;
use BS\ExtendedSearch\Lookup;
use BS\ExtendedSearch\Plugin\IDocumentDataModifier;
use BS\ExtendedSearch\Plugin\IMappingModifier;
use BS\ExtendedSearch\Plugin\ISearchPlugin;
use BS\ExtendedSearch\Source\DocumentProvider\RepoFile as RepoFileProvider;
use BS\ExtendedSearch\Source\RepoFiles;
use MediaWiki\Context\IContextSource;
use MediaWiki\Title\TitleFactory;
use MWStake\MediaWiki\Component\Utils\UtilityFactory;

class FileNamespacePlugin implements ISearchPlugin, ILookupModifierProvider, IDocumentDataModifier, IMappingModifier {

	/**
	 * @param TitleFactory $titleFactory
	 * @param UtilityFactory $utilityFactory
	 */
	public function __construct(
		private readonly TitleFactory $titleFactory,
		private readonly UtilityFactory $utilityFactory
	) {
	}

	/**
	 * @inheritDoc
	 */
	public function getLookupModifiers( Lookup $lookup, IContextSource $context ): array {
		return [
			new SubNamespaceReadRestrictionsModifier( $lookup, $context, $this->utilityFactory ),
		];
	}

	/**
	 * @inheritDoc
	 */
	public function modifyMapping( ISearchSource $source, array &$indexSettings, array &$propertyMapping ): void {
		if ( !( $source instanceof RepoFiles ) ) {
			return;
		}
		$propertyMapping['properties']['sub-namespace'] = [
			'type' => 'integer',
		];
	}

	/**
	 * @inheritDoc
	 */
	public function modifyDocumentData(
		ISearchDocumentProvider $documentProvider, array &$data, $uri, $documentProviderSource
	): void {
		if ( !( $documentProvider instanceof RepoFileProvider ) ) {
			return;
		}
		if ( !is_array( $documentProviderSource ) || !isset( $documentProviderSource['title'] ) ) {
			return;
		}

		$title = $this->titleFactory->newFromText( $documentProviderSource['title'] );
		$data['sub-namespace'] = $title->getNamespace();
	}
}
