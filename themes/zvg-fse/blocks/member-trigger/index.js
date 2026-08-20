(function (blocks, element, blockEditor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { __, _x } = i18n;

	registerBlockType('zvg-fse/member-trigger', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const defaultLabel = _x('Read profile', 'Team member button', 'zvg-fse');

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Card button', 'zvg-fse'), initialOpen: true },
						el(TextControl, {
							label: __('Button text', 'zvg-fse'),
							value: attributes.toggleLabel,
							placeholder: defaultLabel,
							onChange: (value) => setAttributes({ toggleLabel: value }),
						})
					)
				),
				el(
					'div',
					useBlockProps(),
					el(
						'button',
						{ className: 'zvg-fse-member__toggle', type: 'button', disabled: true },
						attributes.toggleLabel || defaultLabel
					)
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
