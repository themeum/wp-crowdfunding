var gulp = require("gulp"),
	sass = require("gulp-sass"),
	rename = require("gulp-rename"),
	prefix = require("gulp-autoprefixer"),
	plumber = require("gulp-plumber"),
	notify = require("gulp-notify"),
	sourcemaps = require("gulp-sourcemaps"),
	wpPot = require('gulp-wp-pot'),
	clean = require("gulp-clean"),
	zip = require("gulp-zip"),
	concat = require('gulp-concat'),
	minify = require('gulp-minify'),
	cleanCSS = require('gulp-clean-css');

var onError = function (err) {
	notify.onError({
		title: "Gulp",
		subtitle: "Failure!",
		message: "Error: <%= error.message %>",
		sound: "Basso",
	})(err);
	this.emit("end");
};

gulp.task('makepot', function () {
	return gulp
		.src('**/*.php')
		.pipe(plumber({
			errorHandler: onError
		}))
		.pipe(wpPot({
			domain: 'wp-crowdfunding',
			package: 'WP Crowdfunding'
		}))
		.pipe(gulp.dest('languages/wp-crowdfunding.pot'));
});

gulp.task('pack-js', function () {
	return gulp.src(['assets/js/crowdfunding-front.js', 'assets/js/crowdfunding.js', 'assets/js/mce-button.js'])
		.pipe(minify({ ext: '.min.js' }))
		.pipe(gulp.dest('assets/js/dist'));
});

gulp.task('minify-css', () => {
	return gulp.src(['assets/css/crowdfunding.css', 'assets/css/crowdfunding-front.css'])
		.pipe(cleanCSS())
		.pipe(gulp.dest('assets/css/dist'));
});

/**
 * Build
 */
gulp.task("clean-zip", function () {
	return gulp.src("./wp-crowdfunding.2.1.11.zip", {
		read: false,
		allowEmpty: true
	}).pipe(clean());
});

gulp.task("clean-build", function () {
	return gulp.src("./build", {
		read: false,
		allowEmpty: true
	}).pipe(clean());
});

gulp.task("copy", function () {
	return gulp
		.src([
			"./**/*.*",
			"!./build/**",
			"!./assets/**/*.map",
			"!./assets/scss/**",
			"!./assets/.sass-cache",
			"!./node_modules/**",
			"!./**/*.zip",
			"!.github",
			"!./gulpfile.js",
			"!./webpack.config.js",
			"!./webpack.production.config.js",
			"!./reactjs/**",
			"!./readme.md",
			"!./README.md",
			"!.DS_Store",
			"!./**/.DS_Store",
			"!./LICENSE.txt",
			"!./package.json",
			"!./package-lock.json",
		])
		.pipe(gulp.dest("build/wp-crowdfunding/"));
});

gulp.task("make-zip", function () {
	return gulp.src("./build/**/*.*").pipe(zip("wp-crowdfunding.zip")).pipe(gulp.dest("./"));
});

// Watch task
gulp.task('watch', function () {
	gulp.watch(['assets/js/*.js', '!assets/js/dist/*.js'], gulp.series('pack-js'));
	gulp.watch(['assets/css/*.css', '!assets/css/dist/*.css'], gulp.series('minify-css'));
});

/**
 * Export tasks
 */
exports.build = gulp.series(
	"clean-zip",
	"clean-build",
	"makepot",
	"pack-js",
	"minify-css",
	"copy",
	"make-zip",
	"clean-build"
);

/**
 * Default task
 */
gulp.task('default', gulp.series('minify-css', 'pack-js', 'watch'));