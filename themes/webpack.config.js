/**
 * Block script build for the zvg-fse blocks.
 *
 * Each blocks/<slug>/src/index.js compiles to build/index.js (editorScript) and each
 * src/view.js to build/view.js (viewScriptModule), with their .asset.php files.
 * Output lands inside the theme, so cleaning the output path is off.
 */
const { globSync } = require( 'node:fs' );
const path = require( 'node:path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

if ( ! Array.isArray( defaultConfig ) ) {
	throw new Error( 'Run wp-scripts with --experimental-modules.' );
}

const [ scriptConfig, moduleConfig ] = defaultConfig;
const blocks = path.resolve( __dirname, 'zvg-fse/blocks' );

const entriesFor = ( name ) =>
	Object.fromEntries(
		globSync( `*/src/${ name }.js`, { cwd: blocks } ).map( ( file ) => [
			`${ path.dirname( path.dirname( file ) ) }/build/${ name }`,
			path.join( blocks, file ),
		] )
	);

const intoTheme = ( config, entry ) => ( {
	...config,
	entry,
	output: {
		...config.output,
		path: blocks,
		filename: '[name].js',
		clean: false,
	},
	plugins: config.plugins.filter(
		( plugin ) =>
			! [
				'CopyPlugin',
				'PhpFilePathsPlugin',
				'BlockJsonDependenciesPlugin',
			].includes( plugin.constructor.name )
	),
} );

module.exports = [
	intoTheme( scriptConfig, entriesFor( 'index' ) ),
	intoTheme( moduleConfig, entriesFor( 'view' ) ),
];
