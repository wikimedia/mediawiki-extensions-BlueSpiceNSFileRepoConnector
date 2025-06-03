let namespaceList = null;

function _getNamespaceList() { // eslint-disable-line no-underscore-dangle
	const dfd = $.Deferred();
	if ( namespaceList ) {
		return dfd.resolve( namespaceList ).promise();
	}
	bs.config.getDeferred( 'NSFRNamespaceList', true ).done( ( value ) => {
		namespaceList = value;
		dfd.resolve( namespaceList );
	} ).fail( () => {
		namespaceList = { read: [], edit: [] };
		dfd.resolve( namespaceList );
	} );

	return dfd.promise();
}

async function _getInvalidFileNamespacesForReading() { // eslint-disable-line no-underscore-dangle
	const nsList = await _getNamespaceList();
	const unreadableNS = [];
	const allNS = mw.config.get( 'wgFormattedNamespaces' );
	for ( const nsIdx in allNS ) {
		if ( !nsList.read.includes( +nsIdx ) ) {
			unreadableNS.push( parseInt( nsIdx ) );
		}
	}

	return bs.ns.filter.NO_TALK
		.concat( bs.ns.filter.ONLY_CONTENT_NS )
		.concat( unreadableNS )
		.concat( [ bs.ns.NS_FILE ] );
}

async function _getInvalidFileNamespacesForEditing() { // eslint-disable-line no-underscore-dangle
	const invalidIds = await _getInvalidFileNamespacesForReading();
	const nsList = await _getNamespaceList();
	for ( const nsIdx in nsList.edit ) {
		if ( $.inArray( +nsIdx, invalidIds ) === -1 ) { // eslint-disable-line no-jquery/no-in-array
			invalidIds.push( nsIdx );
		}
	}

	return invalidIds;
}

bs.util.registerNamespace( 'bs.nsfrc' );
bs.nsfrc.getInvalidFileNamespacesForReading = _getInvalidFileNamespacesForReading;
bs.nsfrc.getInvalidFileNamespacesForEditing = _getInvalidFileNamespacesForEditing;
