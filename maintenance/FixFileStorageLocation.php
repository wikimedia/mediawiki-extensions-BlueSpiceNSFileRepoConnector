<?php

require_once dirname( dirname( dirname( __DIR__ ) ) ) . '/maintenance/Maintenance.php';

use MediaWiki\MediaWikiServices;
use NSFileRepo\NamespaceList;

class FixFileStorageLocation extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->addDescription(
			"Checks and moves files to the correct location. Use --execute to actually execute"
		);
		$this->addOption( 'execute', 'Actually exectue the script' );
		$this->requireExtension( 'BlueSpiceNSFileRepoConnector' );
	}

	public function execute() {
		$this->output( "Retrive avaiable Namespaces...\n" );
		$execute = $this->getOption( 'execute', false );
		$nsList = new NamespaceList(
			$this->getUser(),
			$this->getConfig(),
			$this->getLanguage()
		);
		foreach ( $nsList->getReadable() as $ns ) {
			$this->output( "* {$ns->getCanonicalName()}\n" );
		}
		$this->output( "Checking files in Namespaces...\n" );
		$res = $this->getDB( DB_REPLICA )->select(
			'page',
			[ 'page_title' ],
			[ 'page_namespace' => NS_FILE ],
			__METHOD__
		);
		$files = [];
		foreach ( $res as $row ) {
			foreach ( $nsList->getReadable() as $ns ) {
				if ( empty( $ns->getCanonicalName() ) ) {
					continue;
				}
				if ( strpos( $row->page_title, "{$ns->getCanonicalName()}:" ) !== 0 ) {
					continue;
				}
				$files[$row->page_title] = $ns;
				break;
			}
		}
		$this->output( "..." . count( $files ) . " Files collected\n" );
		$this->output( "...check and fix locations\n" );
		foreach ( $files as $fileName => $ns ) {
			$this->output( "* $fileName ... " );
			$title = Title::makeTitle( NS_FILE, $fileName );
			$file = $this->getServices()->getRepoGroup()->findFile( $title );
			if ( !$file instanceof File ) {
				$this->output( "ERROR: invalid file" );
			}
			$path = $this->getOldImagePath( $fileName );
			if ( file_exists( $path ) ) {
				$newPath = $this->getNewImagePath( $fileName, $file );
				$this->output( "\n" );
				$this->output( "  $path => $newPath ..." );
				$fileMoved = true;
				if ( $execute ) {
					$dir = explode( "/", $newPath );
					array_pop( $dir );
					$dirName = implode( "/", $dir );
					mkdir( $dirName, 0755, true );
					$fileMoved = rename( $path, $newPath );
				}
				$this->output( $fileMoved ? "OK" : "ERROR: Renaming File" );
			} else {
				$this->output( "OK" );
			}
			$this->output( "\n" );
		}
		if ( !$execute ) {
			$this->output( "Use --execute to really execute the script\n" );
		}
		$this->output( "Done, GG\n" );
	}

	/**
	 * @return User
	 */
	private function getUser() {
		return $this->getServices()->getService( 'BSUtilityFactory' )
			->getMaintenanceUser()->getUser();
	}

	/**
	 *
	 * @return MediaWikiServices
	 */
	private function getServices() {
		return MediaWikiServices::getInstance();
	}

	/**
	 *
	 * @return Language
	 */
	public function getLanguage() {
		return $this->getServices()->getLanguageFactory()->getLanguage(
			$this->getConfig()->get( 'LanguageCode' )
		);
	}

	/**
	 * @param string $name
	 * @param int $levels
	 * @return string
	 */
	protected function getHashPathForLevel( $name, $levels ) {
		if ( $levels == 0 ) {
			return '';
		} else {
			$hash = md5( $name );
			$path = '';
			for ( $i = 1; $i <= $levels; $i++ ) {
				$path .= substr( $hash, 0, $i ) . '/';
			}

			return $path;
		}
	}

	/**
	 *
	 * @param string $fileName
	 * @return string
	 */
	private function getOldImagePath( $fileName ) {
		$path = $this->getHashPathForLevel(
			$fileName,
			$this->getServices()->getRepoGroup()->getLocalRepo()->getHashLevels()
		);

		return "{$GLOBALS['IP']}/images/$path$fileName";
	}

	/**
	 *
	 * @param string $fileName
	 * @param File $file
	 * @return string
	 */
	private function getNewImagePath( $fileName, $file ) {
		return "{$GLOBALS['IP']}/images/{$file->getRel()}";
	}

}

$maintClass = FixFileStorageLocation::class;
require_once RUN_MAINTENANCE_IF_MAIN;
