// include gulp
var gulp = require('gulp');

// include plug-ins
var jshint     = require('gulp-jshint');
var changed    = require('gulp-changed');
var imagemin   = require('gulp-imagemin');
var concat     = require('gulp-concat');
var stripDebug = require('gulp-strip-debug');
var uglify     = require('gulp-uglify');
var autoprefix = require('gulp-autoprefixer');
var minifyCSS  = require('gulp-minify-css');
var less       = require('gulp-less');
var path       = require('path');
var debug      = require('gulp-debug');


gulp.task('less', function () {

  // geneerate bootstrap core
  gulp.src('./bootstrap.less')
    // .pipe(debug({title: 'unicorn:'}))
    .pipe(less({
        strictMath: true,
        strictUnits: true,
    }))
    .pipe(concat('bootstrap.css'))
    .pipe(autoprefix('last 2 versions'))
    // .pipe(minifyCSS({keepSpecialComments: 1}))
    .pipe(gulp.dest('./'));
});

// default gulp task
gulp.task('default', ['less'], function() {
  // watch for JS changes
  gulp.watch('./bootstrap.less', function(event) {
    console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
    // gulp.run('jshint', 'scripts');
    gulp.run('less');
  });


});