bs.vec.registerComponentPlugin(
	bs.vec.components.MEDIA_DIALOG,
	( component ) => {
		const options = {
			currentNamespace: mw.config.get( 'wgNamespaceNumber' ),
			formattedNamespaces: mw.config.get( 'wgFormattedNamespaces' )
		};

		return new bs.nsfrc.ui.plugin.MWMediaDialog( component, options );
	}
);
