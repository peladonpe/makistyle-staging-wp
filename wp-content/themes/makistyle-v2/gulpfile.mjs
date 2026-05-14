import gulp, { parallel } from 'gulp';
const { src, dest, watch, series } = gulp;
import browserSyncPre from 'browser-sync';
const browserSync = browserSyncPre.create();

// DEPENDENCIAS CSS y SASS
import * as dartSass from 'sass';
import gulpSass from 'gulp-sass';
const sass = gulpSass(dartSass);

function frontendCss(done) {
	src('./scss/styles.scss', { sourcemaps: true })
		.pipe(sass().on('error', sass.logError))
		.pipe(dest('css', { sourcemaps: '.' }))
		.pipe(browserSync.stream());
	done();
}
function adminCss(done) {
	src('./scss/admin-styles.scss', { sourcemaps: true })
		.pipe(sass().on('error', sass.logError))
		.pipe(dest('css', { sourcemaps: '.' }))
		.pipe(browserSync.stream());
	done();
}

export const css = parallel(frontendCss, adminCss);

// BrowserSync task
export function browserSyncInit() {
	browserSync.init({
		proxy: 'http://localhost/makistyle-staging/', // Replace with your local WordPress site URL
		open: false, // Set to true if you want the browser to open automatically
	});
}

export function watchCss() {
	watch('./scss/**/*.scss', css);
	watch(['*.php', '**/*.php']).on('change', browserSync.reload);
	watch(['./js/**/*.js']).on('change', browserSync.reload);
}

// export function cssmin(done) {
//   src("./scss/styles.scss", { sourcemaps: true })
//     .pipe(sass().on("error", sass.logError))
//     .pipe(postcss([autoprefixer(), cssnano()]))
//     .pipe(
//       rename({
//         suffix: ".min",
//       })
//     )
//     .pipe(dest("css", { sourcemaps: "." }));
//   done();
// }

// export default series(css, watchCss);
export default parallel(browserSyncInit, watchCss);
