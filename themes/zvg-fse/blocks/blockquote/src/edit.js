import { RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

/**
 * Editor form of the quote card.
 *
 * @param {Object}   props               Block edit props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Attribute setter.
 *
 * @return {Element} Quote card with editable text.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { text, authorName, authorRole } = attributes;

	return (
		<figure { ...useBlockProps() }>
			<blockquote className="wp-block-zvg-fse-blockquote__quote">
				<RichText
					tagName="p"
					className="wp-block-zvg-fse-blockquote__text"
					value={ text }
					onChange={ ( value ) => setAttributes( { text: value } ) }
					placeholder={ __( 'Write the quote…', 'zvg-fse' ) }
				/>
			</blockquote>
			<figcaption className="wp-block-zvg-fse-blockquote__author">
				<RichText
					tagName="cite"
					className="wp-block-zvg-fse-blockquote__name"
					value={ authorName }
					onChange={ ( value ) =>
						setAttributes( { authorName: value } )
					}
					placeholder={ __( 'Author name', 'zvg-fse' ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="span"
					className="wp-block-zvg-fse-blockquote__role"
					value={ authorRole }
					onChange={ ( value ) =>
						setAttributes( { authorRole: value } )
					}
					placeholder={ __( 'Role, company', 'zvg-fse' ) }
					allowedFormats={ [] }
				/>
			</figcaption>
		</figure>
	);
}
