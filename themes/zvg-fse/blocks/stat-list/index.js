(function (blocks, element, blockEditor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, TextControl, Button } = components;
	const { __, sprintf } = i18n;

	const PLACEHOLDER = '—';
	const WITH_UNIT = /^([0-9][0-9\s.,]*)\s*([A-Za-z%]{1,4})$/;

	/**
	 * Render a value the way the front end does, with a trailing unit in smaller type.
	 *
	 * @param {string} value Value as typed in the panel.
	 *
	 * @return {string|Array} Value, or the number followed by its unit.
	 */
	function renderValue(value) {
		const parts = WITH_UNIT.exec(value);

		if (!parts) {
			return value;
		}

		return [
			parts[1].trim(),
			el('span', { key: 'unit', className: 'wp-block-zvg-fse-stat-list__unit' }, parts[2]),
		];
	}

	registerBlockType('zvg-fse/stat-list', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const items = attributes.items || [];
			const blockProps = useBlockProps();

			const update = (index, key, value) => {
				const next = items.map((item, i) =>
					i === index ? Object.assign({}, item, { [key]: value }) : item
				);
				setAttributes({ items: next });
			};

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Stats', 'zvg-fse'), initialOpen: true },
						items.map((item, index) =>
							el(
								'div',
								{ key: index },
								el(TextControl, {
									/* translators: %d: position of the stat in the list. */
									label: sprintf(__('Stat %d: label', 'zvg-fse'), index + 1),
									value: item.label || '',
									onChange: (value) => update(index, 'label', value),
								}),
								el(TextControl, {
									/* translators: %d: position of the stat in the list. */
									label: sprintf(__('Stat %d: value', 'zvg-fse'), index + 1),
									value: item.value || '',
									placeholder: PLACEHOLDER,
									onChange: (value) => update(index, 'value', value),
								}),
								el(
									Button,
									{
										variant: 'link',
										isDestructive: true,
										onClick: () =>
											setAttributes({ items: items.filter((_, i) => i !== index) }),
									},
									/* translators: %d: position of the stat in the list. */
									sprintf(__('Remove stat %d', 'zvg-fse'), index + 1)
								)
							)
						),
						el(
							Button,
							{
								variant: 'secondary',
								onClick: () =>
									setAttributes({ items: items.concat([{ label: '', value: '' }]) }),
							},
							__('Add stat', 'zvg-fse')
						)
					)
				),
				el(
					'dl',
					blockProps,
					items.map((item, index) =>
						el(
							'div',
							{ key: index, className: 'wp-block-zvg-fse-stat-list__item' },
							el(
								'dt',
								{ className: 'wp-block-zvg-fse-stat-list__label' },
								item.label || __('Label', 'zvg-fse')
							),
							el(
								'dd',
								{ className: 'wp-block-zvg-fse-stat-list__value' },
								item.value ? renderValue(item.value) : PLACEHOLDER
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
