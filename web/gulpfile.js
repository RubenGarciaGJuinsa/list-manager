'use strict';

let gulp = require('gulp'),
    sass = require('gulp-sass'),
    prefix = require('gulp-autoprefixer'),
    cleanCSS = require('gulp-clean-css'),
    rename = require('gulp-rename')
;

sass.compiler = require('node-sass');

gulp.task('styles', function () {
    return gulp.src('./scss/**/*.scss')
        .pipe(sass())
        .pipe(prefix())
        .pipe(gulp.dest('./css'))
        .pipe(cleanCSS())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('css'));
});

gulp.task('styles:watch', function () {
    gulp.watch('./scss/**/*.scss', ['styles']);
});