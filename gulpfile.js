const fs = require('fs');
const path = require('path');
const { src, dest, watch, series, parallel } = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const sourcemaps = require('gulp-sourcemaps');
const browserSync = require('browser-sync').create();

/**
 * BrowserSync proxy URL for this WordPress install.
 *
 * Order:
 * 1. GULP_SITE_URL env (override)
 * 2. WP_HOME / WP_SITEURL in wp-config.php
 * 3. MAMP subdirectory: http://localhost:8888/{wp-root-folder}
 */
function getSiteUrl() {
  if (process.env.GULP_SITE_URL) {
    return process.env.GULP_SITE_URL.replace(/\/$/, '');
  }

  const wpRoot = path.resolve(__dirname, '../../..');
  const wpConfigPath = path.join(wpRoot, 'wp-config.php');

  if (fs.existsSync(wpConfigPath)) {
    const config = fs.readFileSync(wpConfigPath, 'utf8');
    const match = config.match(
      /define\s*\(\s*['"]WP_(?:HOME|SITEURL)['"]\s*,\s*['"]([^'"]+)['"]/i
    );
    if (match) {
      return match[1].replace(/\/$/, '');
    }
  }

  return `http://localhost:8888/${path.basename(wpRoot)}`;
}

const sassOptions = {
  outputStyle: 'expanded',
  silenceDeprecations: ['legacy-js-api'],
};

function compileFrontendSass() {
  return src('./assets/scss/custom.scss')
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(sourcemaps.write('.'))
    .pipe(dest('./assets/css/'))
    .pipe(browserSync.stream({ match: '**/*.css' }));
}

function compileAdminSass() {
  return src('./assets/scss/admin/custom-admin.scss', { allowEmpty: true })
    .pipe(sourcemaps.init())
    .pipe(sass(sassOptions).on('error', sass.logError))
    .pipe(sourcemaps.write('.'))
    .pipe(dest('./assets/css/'))
    .pipe(browserSync.stream({ match: '**/*.css' }));
}

function serve(done) {
  browserSync.init({
    proxy: getSiteUrl(),
    open: false,
    notify: true,
    ghostMode: false,
  });
  done();
}

function reload(done) {
  browserSync.reload();
  done();
}

function watcher() {
  watch('./assets/scss/**/*.scss', parallel(compileFrontendSass, compileAdminSass));
  watch(['./**/*.php', '!./node_modules/**'], reload);
}

const compileAllSass = parallel(compileFrontendSass, compileAdminSass);

exports.build = compileAllSass;
exports.sass = compileFrontendSass;
exports.adminSass = compileAdminSass;
exports.default = series(compileAllSass, serve, watcher);
