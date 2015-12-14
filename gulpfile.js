var gulp      = require('gulp');
var gutil     = require('gulp-util');
var sass      = require('gulp-sass');
var coffee    = require('gulp-coffee');
var react     = require('gulp-react');


gulp.task('sass', function () {
  gulp.src('./resources/assets/sass/*.scss')
    .pipe(sass().on('error', gutil.log))
    .pipe(gulp.dest('./public/css'));
});

gulp.task('coffee', function() {
  gulp.src('./resources/assets/coffee/*.coffee')
    .pipe(coffee({bare: true}).on('error', gutil.log))
    .pipe(react())
    .pipe(gulp.dest('./public/js'))
});

gulp.task('watch', function () {
  gulp.watch('./resources/assets/sass/*.scss', ['sass']);
  gulp.watch('./resources/assets/coffee/*.coffee', ['coffee']);
});

gulp.task('default', ['sass', 'coffee', 'watch']);
