( function ( mw, $, bs ) {
	bs.util.registerNamespace( 'bs.nsfrc.ui.plugin' );

	bs.nsfrc.ui.plugin.MWMediaDialog = function BsNsfrcUiPluginMWMediaDialog( component, options ) {
		bs.nsfrc.ui.plugin.MWMediaDialog.super.apply( this, [ component ] );

		options = options || {};
		options = Object.assign( {
			currentNamespace: 0,
			formattedNamespaces: []
		},
		options
		);

		this.currentNamespace = options.currentNamespace;
		this.formattedNamespaces = options.formattedNamespaces;
	};

	OO.inheritClass( bs.nsfrc.ui.plugin.MWMediaDialog, bs.vec.ui.plugin.MWMediaDialog );

	bs.nsfrc.ui.plugin.MWMediaDialog.prototype.setNewUploadBooklet = async function () {
		const invalidNamespaces = await bs.nsfrc.getInvalidFileNamespacesForEditing();

		this.namespaceSelector = new mw.widgets.NamespaceInputWidget( {
			value: this.currentNamespace,
			exclude: invalidNamespaces
		} );
		this.namespaceSelectorLayout = new OO.ui.FieldLayout( this.namespaceSelector, {
			label: mw.msg( 'bs-nsfrc-uploaddialog-selector-label' ),
			align: 'top'
		} );

		if ( this.component.mediaUploadBooklet instanceof bs.vec.ui.ForeignStructuredUpload.BookletLayoutOneClick ) {
			this.component.mediaUploadBooklet.uploadForm.items[ 0 ].addItems( [ this.namespaceSelectorLayout ], 0 );
		} else {
			// instanceof bs.vec.ui.ForeignStructuredUpload.BookletLayout or bs.vec.ui.ForeignStructuredUpload.BookletLayoutSimple
			this.component.mediaUploadBooklet.infoForm.items[ 1 ].addItems( [ this.namespaceSelectorLayout ], 0 );
		}

		this.component.mediaUploadBooklet.on( 'getfilename', this.onMediaUploadBookletGetFilename, [], this );
	};

	bs.nsfrc.ui.plugin.MWMediaDialog.prototype.onMediaUploadBookletGetFilename = function ( booklet, data ) {
		const selectedNamespace = +this.namespaceSelector.getValue();
		let prefix = '';
		if ( this.formattedNamespaces[ selectedNamespace ] ) {
			prefix = this.formattedNamespaces[ selectedNamespace ];
		}
		if ( prefix !== '' ) {
			prefix = prefix + ':';
		}
		data.filename = prefix + data.filename;
	};
}( mediaWiki, jQuery, blueSpice ) );
