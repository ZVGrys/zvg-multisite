import { useBlockProps } from '@wordpress/block-editor';
import { _x } from '@wordpress/i18n';

/**
 * Editor preview of the member bio.
 *
 * @return {Element} Placeholder paragraph.
 */
export default function Edit() {
	return (
		<p { ...useBlockProps() }>
			{ _x( 'Member bio', 'Editor placeholder', 'zvg-fse' ) }
		</p>
	);
}
