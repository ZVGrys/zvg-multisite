(function (blocks, element, blockEditor, components, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { useBlockProps, InspectorControls } = blockEditor;
	const { PanelBody, TextControl } = components;
	const { __, _x, sprintf } = i18n;

	const BASE = 'wp-block-zvg-fse-compare-table';
	const BLANK = '—';

	registerBlockType('zvg-fse/compare-table', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const columns = attributes.columns || [];
			const rows = attributes.rows || [];
			const blockProps = useBlockProps();

			const setColumn = (index, value) =>
				setAttributes({ columns: columns.map((c, i) => (i === index ? value : c)) });

			const setRow = (index, key, value) =>
				setAttributes({
					rows: rows.map((row, i) => (i === index ? Object.assign({}, row, { [key]: value }) : row)),
				});

			const setValue = (rowIndex, colIndex, value) =>
				setRow(
					rowIndex,
					'values',
					(rows[rowIndex].values || []).map((v, i) => (i === colIndex ? value : v))
				);

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					el(
						PanelBody,
						{ title: __('Table', 'zvg-fse'), initialOpen: true },
						el(TextControl, {
							label: __('Caption for screen readers', 'zvg-fse'),
							value: attributes.caption || '',
							onChange: (value) => setAttributes({ caption: value }),
						}),
						columns.map((column, index) =>
							el(TextControl, {
								key: 'col-' + index,
								/* translators: %d: position of the column in the table. */
								label: sprintf(__('Column %d', 'zvg-fse'), index + 1),
								value: column,
								onChange: (value) => setColumn(index, value),
							})
						)
					),
						rows.map((row, rowIndex) => {
							const labelled = sprintf(
								/* translators: 1: position of the row in the table, 2: row label. */
								_x('Row %1$d: %2$s', 'Row panel title', 'zvg-fse'),
								rowIndex + 1,
								row.label
							);
							/* translators: %d: position of the row in the table. */
							const numbered = sprintf(__('Row %d', 'zvg-fse'), rowIndex + 1);

							return el(
								PanelBody,
								{
									key: 'row-' + rowIndex,
									title: row.label ? labelled : numbered,
									initialOpen: false,
								},
								el(TextControl, {
									/* translators: %d: position of the row in the table. */
									label: sprintf(__('Row %d: label', 'zvg-fse'), rowIndex + 1),
									value: row.label || '',
									onChange: (value) => setRow(rowIndex, 'label', value),
								}),
								columns.map((column, colIndex) =>
									el(TextControl, {
										key: 'val-' + colIndex,
										label: sprintf(
											/* translators: 1: position of the row in the table, 2: column name. */
											_x('Row %1$d: %2$s', 'Row value field label', 'zvg-fse'),
											rowIndex + 1,
											column
										),
										value: (row.values || [])[colIndex] || '',
										placeholder: BLANK,
										onChange: (value) => setValue(rowIndex, colIndex, value),
									})
								)
							);
						})
				),
				el(
					'div',
					blockProps,
					el(
						'table',
						{ className: BASE + '__table' },
						el(
							'thead',
							{},
							el(
								'tr',
								{},
								el('td', {}),
								columns.map((column, index) => el('th', { key: index, scope: 'col' }, column))
							)
						),
						el(
							'tbody',
							{},
							rows.map((row, rowIndex) =>
								el(
									'tr',
									{ key: rowIndex },
									el('th', { scope: 'row' }, row.label),
									columns.map((column, colIndex) =>
										el('td', { key: colIndex }, (row.values || [])[colIndex] || BLANK)
									)
								)
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
