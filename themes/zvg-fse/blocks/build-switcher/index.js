(function (blocks, element, blockEditor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, ToggleControl } = components;
	const { __ } = i18n;

	const LABELS = window.zvgFseBuildLabels || [];

	registerBlockType('zvg-fse/build-switcher', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const shown = attributes.showSwitcher;

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Build switcher', 'zvg-fse'), initialOpen: true },
						el(ToggleControl, {
							label: __('Show the build switcher', 'zvg-fse'),
							help: shown
								? __('The links to the other two builds sit at the end of the menu.', 'zvg-fse')
								: __('Hidden on the front end. The block stays in the menu, so it can be switched back on here.', 'zvg-fse'),
							checked: !!shown,
							onChange: (value) => setAttributes({ showSwitcher: value }),
						})
					)
				),
				el(
					'div',
					useBlockProps({
						role: 'group',
						style: shown ? undefined : { opacity: 0.4 },
					}),
					LABELS.map((label, index) =>
						el(
							'span',
							{
								key: label,
								className: 'wp-block-zvg-fse-build-switcher__link',
								'aria-current': index === 0 ? 'page' : undefined,
							},
							label
						)
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
