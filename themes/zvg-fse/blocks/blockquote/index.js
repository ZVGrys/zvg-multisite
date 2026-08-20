(function (blocks, element, blockEditor, i18n) {
	const { registerBlockType } = blocks;
	const { createElement: el, Fragment } = element;
	const { RichText, useBlockProps } = blockEditor;
	const { __ } = i18n;

	registerBlockType('zvg-fse/blockquote', {
		edit: function Edit(props) {
			const { attributes, setAttributes } = props;
			const { text, authorName, authorRole } = attributes;
			const blockProps = useBlockProps();

			return el(
				'figure',
				blockProps,
				el(
					'blockquote',
					{ className: 'wp-block-zvg-fse-blockquote__quote' },
					el(RichText, {
						tagName: 'p',
						className: 'wp-block-zvg-fse-blockquote__text',
						value: text,
						onChange: (value) => setAttributes({ text: value }),
						placeholder: __('Write the quote…', 'zvg-fse'),
					})
				),
				el(
					'figcaption',
					{ className: 'wp-block-zvg-fse-blockquote__author' },
					el(RichText, {
						tagName: 'cite',
						className: 'wp-block-zvg-fse-blockquote__name',
						value: authorName,
						onChange: (value) => setAttributes({ authorName: value }),
						placeholder: __('Author name', 'zvg-fse'),
						allowedFormats: [],
					}),
					el(RichText, {
						tagName: 'span',
						className: 'wp-block-zvg-fse-blockquote__role',
						value: authorRole,
						onChange: (value) => setAttributes({ authorRole: value }),
						placeholder: __('Role, company', 'zvg-fse'),
						allowedFormats: [],
					})
				)
			);
		},

		save: function () {
			return null;
		},
	});
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.i18n);
