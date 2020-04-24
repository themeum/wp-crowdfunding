const gulp = require('gulp');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const minifyCSS = require('gulp-minify-css');
const autoprefixer = require('gulp-autoprefixer');


gulp.task('style_build', function() {
  return gulp.src(['./src/blocks/**/style.scss'])
    .pipe(sass())
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
    }))
    .pipe(minifyCSS())
    .pipe(concat('blocks.style.build.css'))
    .pipe(gulp.dest('../assets/css'))
})


gulp.task('editor_build', function() {
  return gulp.src(['./src/editor.scss'])
    .pipe(sass())
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
    }))
    .pipe(minifyCSS())
    .pipe(concat('blocks.editor.build.css'))
    .pipe(gulp.dest('../assets/css'))
})

 
gulp.task('watch', function(){
  gulp.watch('src/blocks/**/*.scss', gulp.series('style_build'));
  gulp.watch('src/editor.scss', gulp.series('editor_build'));
})