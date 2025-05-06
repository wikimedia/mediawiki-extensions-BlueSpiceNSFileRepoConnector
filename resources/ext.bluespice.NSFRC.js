function _getInvalidFileNamespacesForReading() { // eslint-disable-line no-underscore-dangle
	const unreadableNS = [];
	const allNS = mw.config.get( 'wgFormattedNamespaces' );
	const bsgNSBasePermissions = mw.config.get( 'bsgNSBasePermissions' );
	for ( const nsIdx in allNS ) {
		if ( $.inArray( +nsIdx, bsgNSBasePermissions.read ) === -1 ) { // eslint-disable-line no-jquery/no-in-array
			unreadableNS.push( parseInt( nsIdx ) );
		}
	}

	return bs.ns.filter.NO_TALK
		.concat( bs.ns.filter.ONLY_CONTENT_NS )
		.concat( unreadableNS )
		.concat( [ bs.ns.NS_FILE ] );
}

function _getInvalidFileNamespacesForEditing() { // eslint-disable-line no-underscore-dangle
	const invalidIds = _getInvalidFileNamespacesForReading();

	const bsgNSBasePermissions = mw.config.get( 'bsgNSBasePermissions' );
	for ( const nsIdx in bsgNSBasePermissions.edit ) {
		if ( $.inArray( +nsIdx, invalidIds ) === -1 ) { // eslint-disable-line no-jquery/no-in-array
			invalidIds.push( nsIdx );
		}
	}

	return invalidIds;
}

bs.util.registerNamespace( 'bs.nsfrc' );
bs.nsfrc.getInvalidFileNamespacesForReading = _getInvalidFileNamespacesForReading;
bs.nsfrc.getInvalidFileNamespacesForEditing = _getInvalidFileNamespacesForEditing;
