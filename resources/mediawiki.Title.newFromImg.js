(function( mw, $ ) {
	var oldTitleNewFromImg = mw.Title.newFromImg;

	mw.Title.newFromImg = function ( img ) {
		var title = oldTitleNewFromImg( img );
		var newTitle = null;
		var dummyNSFRTitle = null;
		var src = img.jquery ? img[0].src : img.src;
		src = decodeURI( src );

		matches = src.match( /\/([^\s]*\/{1})([^\s]*\/{1})([0-9]+?)\/[a-f0-9]\/[a-f0-9]{2}\/([^\s]+)$/ );
		// If file is e.g. "20210121121500!Some_file.png/100px-Some_file.png", we need to remove the timestamp
		if ( matches !== null && matches[2] === 'archive/' ) {
			// Is it an archived thumbnail?
			var fileNameParts = matches[4].match( /([0-9]{14})!([^\s]+)\/([^\s]+)$/ );

			if ( fileNameParts === null ) {
				// Is it an archived image?
				fileNameParts = matches[4].match( /([0-9]{14})!([^\s]+)$/ );
			}

			if ( fileNameParts !== null ) {
				dummyNSFRTitle = mw.Title.newFromText( 'dummy', matches[3] );
				newTitle = mw.Title.newFromText(
					'File:' + dummyNSFRTitle.getNamespacePrefix() + fileNameParts[2]
				);

				// Hint: The timestamp prefix of the filename is the upload date of the newer file.
				newTitle.timestamp = fileNameParts[1];

				return newTitle;
			}
		} else {
			// Is it an archived thumb?
			matches = src.match( /\/([^\s]*\/{1})([0-9]+?)\/[a-f0-9]\/[a-f0-9]{2}\/([^\s]+)\/([^\s]+)/ );

			if ( matches === null) {
				// Is it an archived image?
				matches = src.match( /\/([^\s]*\/{1})([0-9]+?)\/[a-f0-9]\/[a-f0-9]{2}\/([^\s]+)/ );
			}

			if ( matches !== null) {
				dummyNSFRTitle = mw.Title.newFromText( 'dummy', matches[2] );
				newTitle = mw.Title.newFromText(
					'File:' + dummyNSFRTitle.getNamespacePrefix() + matches[3]
				);

				return newTitle;
			}
		}

		return title;
	}
})( mediaWiki, jQuery );