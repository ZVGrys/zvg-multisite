/**
 * Multi-theme Gulp build for this install's wp-content/themes/.
 *
 * One node_modules for every theme. Each theme is compiled "in place" — the
 * .css / .min.js land next to their source, same as the single-theme Gigi
 * gulpfile this is derived from.
 *
 * Add a new theme = add its slug to THEMES.
 */
const fs = require('fs');
const gulp = require('gulp');
const { src, dest, watch, series, parallel } = gulp;

const babel = require('gulp-babel');
const sourcemaps = require('gulp-sourcemaps');

const gulpSass = require('gulp-sass')(require('sass'));

const gulpAutoprefixer = require('gulp-autoprefixer');
const autoprefixer = gulpAutoprefixer.default || gulpAutoprefixer;
const notify = require('gulp-notify');

const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const cleanCSS = require('gulp-clean-css');
const plumber = require('gulp-plumber');

/* ─── The only place themes are listed ──────────────────────────────────── */
const THEMES = ['zvg-fse', 'zvg-acf', 'zvg-elementor'];

/* ─── Globs for one theme ───────────────────────────────────────────────── */
const globsFor = (t) => ({
    scss: [
        `./${t}/**/*.scss`,
        `!./${t}/cmb2/**/*.scss`, // vendored CMB2 — never compile
        `!./${t}/node_modules/**`,
    ],
    js: [`./${t}/**/js/*.js`, `!./${t}/**/js/*.min.js`, `!./${t}/cmb2/**`],
    jsAdmin: [`./${t}/**/js/admin/*.js`, `!./${t}/**/js/admin/*.min.js`, `!./${t}/cmb2/**`],
    jsWidgets: [`./${t}/widgets/**/*.js`, `!./${t}/widgets/**/*.min.js`],
});

/**
 * `allowEmpty` covers a glob that matches nothing inside an existing folder,
 * but node still throws ENOENT when the folder itself is missing — which is the
 * normal case here (a block theme has no widgets/, a theme may not exist yet).
 * Gate every task on its root folder instead.
 */
const dirExists = (p) => fs.existsSync(p) && fs.statSync(p).isDirectory();

/** Resolves immediately, so a skipped task still completes cleanly. */
const noop = () => Promise.resolve();

/* ─── Pipelines ─────────────────────────────────────────────────────────── */
function jsPipeline(glob, out) {
    return src(glob, { allowEmpty: true })
        .pipe(sourcemaps.init())
        .pipe(
            babel({
                presets: ['@babel/env'],
            })
        )
        .pipe(uglify())
        .pipe(rename({ suffix: '.min' }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(out));
}

function scssPipeline(glob, out) {
    return src(glob, { allowEmpty: true })
        .pipe(sourcemaps.init())
        .pipe(plumber())
        .pipe(
            gulpSass({
                style: 'expanded',
                loadPaths: ['node_modules'],
            }).on('error', function (err) {
                this.emit('end');
                return notify().write(err);
            })
        )
        .pipe(
            autoprefixer({
                overrideBrowserslist: ['> 1%', 'last 5 versions'],
                cascade: true,
            })
        )
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest(out));
}

/* ─── Generate one task set per theme ───────────────────────────────────── */
const perTheme = {};

THEMES.forEach((t) => {
    const g = globsFor(t);

    const hasTheme = () => dirExists(`./${t}`);
    const hasWidgets = () => dirExists(`./${t}/widgets`);

    const sassTask = () => (hasTheme() ? scssPipeline(g.scss, `./${t}/`) : noop());
    const scripts = () => (hasTheme() ? jsPipeline(g.js, `./${t}/`) : noop());
    const scriptsAdmin = () => (hasTheme() ? jsPipeline(g.jsAdmin, `./${t}/`) : noop());
    const widgetsScripts = () =>
        hasWidgets() ? jsPipeline(g.jsWidgets, `./${t}/widgets/`) : noop();

    sassTask.displayName = `sass:${t}`;
    scripts.displayName = `scripts:${t}`;
    scriptsAdmin.displayName = `scriptsAdmin:${t}`;
    widgetsScripts.displayName = `widgetsScripts:${t}`;

    const buildTheme = parallel(sassTask, scripts, scriptsAdmin, widgetsScripts);
    buildTheme.displayName = `build:${t}`;

    const watchTheme = () => {
        if (!hasTheme()) {
            return;
        }
        watch(g.scss, sassTask);
        watch(g.js, scripts);
        watch(g.jsAdmin, scriptsAdmin);
        if (hasWidgets()) {
            watch(g.jsWidgets, widgetsScripts);
        }
    };
    watchTheme.displayName = `watch:${t}`;

    perTheme[t] = { buildTheme, watchTheme };

    // Granular entry points: npx gulp build:zvg-fse, npx gulp watch:zvg-acf …
    exports[`sass:${t}`] = sassTask;
    exports[`scripts:${t}`] = scripts;
    exports[`scriptsAdmin:${t}`] = scriptsAdmin;
    exports[`widgetsScripts:${t}`] = widgetsScripts;
    exports[`build:${t}`] = buildTheme;
    exports[`watch:${t}`] = watchTheme;
});

/* ─── Aggregates ────────────────────────────────────────────────────────── */
const buildAll = parallel(...THEMES.map((t) => perTheme[t].buildTheme));
buildAll.displayName = 'build';

const watchAll = parallel(...THEMES.map((t) => perTheme[t].watchTheme));
watchAll.displayName = 'watch';

exports.build = buildAll;
exports.watch = watchAll;
exports.default = series(buildAll, watchAll);
