(function (blocks, element, blockEditor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, SelectControl } = components;
	const { __ } = i18n;

	const LABELS = ['FSE', 'Elementor', 'ACF'];

	registerBlockType('zvg-fse/build-switcher', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const blockProps = useBlockProps({
				className: 'is-variant-' + attributes.variant,
			});

			return el(
				'nav',
				blockProps,
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Layout', 'zvg-fse'), initialOpen: true },
						el(SelectControl, {
							label: __('Variant', 'zvg-fse'),
							value: attributes.variant,
							options: [
								{ label: __('Segmented', 'zvg-fse'), value: 'segmented' },
								{ label: __('List', 'zvg-fse'), value: 'list' },
							],
							onChange: (value) => setAttributes({ variant: value }),
						})
					)
				),
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
