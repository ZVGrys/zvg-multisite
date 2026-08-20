(function (blocks, element, blockEditor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { __, _x } = i18n;

	registerBlockType('zvg-fse/member-dialog', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const defaultCloseLabel = _x('Close', 'Team dialog button', 'zvg-fse');
			const defaultLinkText = _x('Get in touch', 'Team dialog link', 'zvg-fse');

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Dialog text', 'zvg-fse'), initialOpen: true },
						el(TextControl, {
							label: __('Close button', 'zvg-fse'),
							value: attributes.closeLabel,
							placeholder: defaultCloseLabel,
							onChange: (value) => setAttributes({ closeLabel: value }),
						}),
						el(TextControl, {
							label: __('Link text', 'zvg-fse'),
							value: attributes.linkText,
							placeholder: defaultLinkText,
							onChange: (value) => setAttributes({ linkText: value }),
						})
					)
				),
				el(
					'p',
					useBlockProps({ className: 'zvg-fse-dialog__placeholder' }),
					_x('Team member dialog', 'Editor placeholder', 'zvg-fse')
				)
			);
		},

		save: function () {
			return null;
		},
	});
})(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n
);
