import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __, _x, sprintf } from '@wordpress/i18n';

const BASE = 'wp-block-zvg-fse-compare-table';
const BLANK = '—';

/**
 * Editor form of the comparison table: columns and rows in the sidebar, the table on the canvas.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Table preview and its settings panels.
 */
export default function Edit( { attributes, setAttributes } ) {
	const columns = attributes.columns || [];
	const rows = attributes.rows || [];

	const setColumn = ( index, value ) =>
		setAttributes( {
			columns: columns.map( ( c, i ) => ( i === index ? value : c ) ),
		} );

	const setRow = ( index, key, value ) =>
		setAttributes( {
			rows: rows.map( ( row, i ) =>
				i === index ? { ...row, [ key ]: value } : row
			),
		} );

	const setValue = ( rowIndex, colIndex, value ) =>
		setRow(
			rowIndex,
			'values',
			( rows[ rowIndex ].values || [] ).map( ( v, i ) =>
				i === colIndex ? value : v
			)
		);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Table', 'zvg-fse' ) } initialOpen>
					<TextControl
						label={ __( 'Caption for screen readers', 'zvg-fse' ) }
						value={ attributes.caption || '' }
						onChange={ ( value ) =>
							setAttributes( { caption: value } )
						}
					/>
					{ columns.map( ( column, index ) => (
						<TextControl
							key={ 'col-' + index }
							label={ sprintf(
								/* translators: %d: position of the column in the table. */
								__( 'Column %d', 'zvg-fse' ),
								index + 1
							) }
							value={ column }
							onChange={ ( value ) => setColumn( index, value ) }
						/>
					) ) }
				</PanelBody>
				{ rows.map( ( row, rowIndex ) => {
					const labelled = sprintf(
						/* translators: 1: position of the row in the table, 2: row label. */
						_x( 'Row %1$d: %2$s', 'Row panel title', 'zvg-fse' ),
						rowIndex + 1,
						row.label
					);
					const numbered = sprintf(
						/* translators: %d: position of the row in the table. */
						__( 'Row %d', 'zvg-fse' ),
						rowIndex + 1
					);

					return (
						<PanelBody
							key={ 'row-' + rowIndex }
							title={ row.label ? labelled : numbered }
							initialOpen={ false }
						>
							<TextControl
								label={ sprintf(
									/* translators: %d: position of the row in the table. */
									__( 'Row %d: label', 'zvg-fse' ),
									rowIndex + 1
								) }
								value={ row.label || '' }
								onChange={ ( value ) =>
									setRow( rowIndex, 'label', value )
								}
							/>
							{ columns.map( ( column, colIndex ) => (
								<TextControl
									key={ 'val-' + colIndex }
									label={ sprintf(
										/* translators: 1: position of the row in the table, 2: column name. */
										_x(
											'Row %1$d: %2$s',
											'Row value field label',
											'zvg-fse'
										),
										rowIndex + 1,
										column
									) }
									value={
										( row.values || [] )[ colIndex ] || ''
									}
									placeholder={ BLANK }
									onChange={ ( value ) =>
										setValue( rowIndex, colIndex, value )
									}
								/>
							) ) }
						</PanelBody>
					);
				} ) }
			</InspectorControls>
			<div { ...useBlockProps() }>
				<table className={ BASE + '__table' }>
					<thead>
						<tr>
							<td />
							{ columns.map( ( column, index ) => (
								<th key={ index } scope="col">
									{ column }
								</th>
							) ) }
						</tr>
					</thead>
					<tbody>
						{ rows.map( ( row, rowIndex ) => (
							<tr key={ rowIndex }>
								<th scope="row">{ row.label }</th>
								{ columns.map( ( column, colIndex ) => (
									<td key={ colIndex }>
										{ ( row.values || [] )[ colIndex ] ||
											BLANK }
									</td>
								) ) }
							</tr>
						) ) }
					</tbody>
				</table>
			</div>
		</>
	);
}
