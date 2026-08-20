(function (blocks, element, blockEditor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { __, sprintf } = i18n;

	const BASE = 'wp-block-zvg-fse-token-flow';

	registerBlockType('zvg-fse/token-flow', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const source = attributes.source || {};
			const outputs = attributes.outputs || [];
			const blockProps = useBlockProps();

			const updateOutput = (index, key, value) => {
				setAttributes({
					outputs: outputs.map((item, i) =>
						i === index ? Object.assign({}, item, { [key]: value }) : item
					),
				});
			};

			const pair = (label, value, onChange) =>
				el(TextControl, { label: label, value: value || '', onChange: onChange });

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Source', 'zvg-fse'), initialOpen: true },
						pair(__('Source: name', 'zvg-fse'), source.name, (value) =>
							setAttributes({ source: Object.assign({}, source, { name: value }) })
						),
						pair(__('Source: detail', 'zvg-fse'), source.meta, (value) =>
							setAttributes({ source: Object.assign({}, source, { meta: value }) })
						)
					),
					el(
						PanelBody,
						{ title: __('Outputs', 'zvg-fse'), initialOpen: true },
						outputs.map((output, index) =>
							el(
								'div',
								{ key: index },
								pair(
									/* translators: %d: position of the output in the diagram. */
									sprintf(__('Output %d: name', 'zvg-fse'), index + 1),
									output.name,
									(value) => updateOutput(index, 'name', value)
								),
								pair(
									/* translators: %d: position of the output in the diagram. */
									sprintf(__('Output %d: detail', 'zvg-fse'), index + 1),
									output.meta,
									(value) => updateOutput(index, 'meta', value)
								)
							)
						)
					)
				),
				el(
					'div',
					blockProps,
					el(
						'p',
						{ className: BASE + '__source' },
						el('span', { className: BASE + '__name' }, source.name),
						el('span', { className: BASE + '__meta' }, source.meta)
					),
					el('div', { className: BASE + '__trunk' }),
					el(
						'div',
						{ className: BASE + '__elbows' },
						el('div', { className: BASE + '__elbow ' + BASE + '__elbow--left' }),
						el('div', { className: BASE + '__elbow ' + BASE + '__elbow--right' }),
						el('div', { className: BASE + '__mid' })
					),
					el(
						'ul',
						{ className: BASE + '__outputs' },
						outputs.map((output, index) =>
							el(
								'li',
								{ key: index, className: BASE + '__output' },
								el('span', { className: BASE + '__name' }, output.name),
								el('span', { className: BASE + '__meta' }, output.meta)
							)
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
