<?php

namespace BlueSpice\NSFileRepoConnector\Hook;

use BSImportFiles;
use BSMaintenance;
use File;
use FileRepo;
use MediaWiki\Context\RequestContext;
use MediaWiki\Title\Title;

class HandleImportExport {
	/**
	 * Integrates NSFileRepo into
	 * BlueSpiceMaintenance/maintenance/BSImportFiles.php maintenance script
	 *
	 * @param BSImportFiles $oMaintenanceScript
	 * @param Title &$oTargetTitle
	 * @param FileRepo &$oRepo
	 * @param array $aParts
	 * @param string $sRoot
	 * @return bool
	 */
	public function onBSImportFilesMakeTitle(
		$oMaintenanceScript, &$oTargetTitle, &$oRepo, $aParts, $sRoot
	) {
		$iNsId = RequestContext::getMain()->getLanguage()->getNsIndex( $sRoot );
		if ( $iNsId === false || $iNsId === NS_MAIN ) {
			return true;
		}
		$sQuotedRoot = preg_quote( $sRoot );
		$sUnsuffixedFileName = preg_replace( "#^{$sQuotedRoot}_#", '', $oTargetTitle->getDBkey() );
		$oTargetTitle = Title::makeTitle( NS_FILE, $sRoot . ':' . $sUnsuffixedFileName );
		return true;
	}

	/**
	 * Integrates NSFileRepo into
	 * BlueSpiceMaintenance/maintenance/BSExportFiles.php maintenance script
	 *
	 * @param BSMaintenance $oMaintenanceScript
	 * @param File &$oFile
	 * @param string &$sDestPath
	 * @param string &$sSourcePath
	 * @return bool
	 */
	public function onBSExportFilesBeforeSave(
		$oMaintenanceScript, &$oFile, &$sDestPath, &$sSourcePath
	) {
		$sDestPath = str_replace( ':', '/', $sDestPath );
		wfMkdirParents( dirname( $sDestPath ) );
		return true;
	}
}
