var gulp = require('gulp');
var sass = require('gulp-sass');
var prefix = require('gulp-autoprefixer');
var browserSync = require('browser-sync').create();
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');

var paths = {

    styles: {
        src: './assets/sass',
        files: './assets/sass/**/*.scss',
        dest: './public/css'
    },

    fonts: {
        src: './assets/fonts',
        files: [],
        dest: './public/fonts'
    },

    javascripts: {
        src: './assets/scripts',
        files: [
            './node_modules/jquery/dist/jquery.min.js',
            './node_modules/bootstrap/dist/js/bootstrap.js',
            './assets/scripts/**/*.js'
        ],
        dest: './public/js'
    },

    html: {
        src: './public',
        files: [
            './templates/**/*.html.twig',
            './src/**/*.php',
        ]
    }
};

var displayError = function(error) {
    var errorString = '[' + error.plugin + ']';
    errorString += ' ' + error.message.replace("\n",'');
    if(error.fileName)
        errorString += ' in ' + error.fileName;

    if(error.lineNumber)
        errorString += ' on line ' + error.lineNumber;
    console.error(errorString);
};

gulp.task('sass', function (){
    gulp.src(paths.styles.files)
        .pipe(sass({
            outputStyle: 'compressed',
            sourceComments: 'map',
            includePaths : [paths.styles.src]
        }))
        .on('error', function(err){
            displayError(err);
        })
        .pipe(prefix(
            'last 2 version'
        ))
        .pipe(gulp.dest(paths.styles.dest))
        .pipe(browserSync.reload({ stream:true }));
});

gulp.task('fonts', function (){
    gulp.src(paths.fonts.files)
        .pipe(gulp.dest(paths.fonts.dest))
        .pipe(browserSync.reload({ stream:true }));
});

gulp.task('js', function (){
    gulp.src(paths.javascripts.files)
        .pipe(sourcemaps.init())
        .pipe(concat('all.js'))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(paths.javascripts.dest))
        .pipe(browserSync.reload({ stream:true }));
});

gulp.task('html', function(){
    gulp.src(paths.html.files)
        .pipe(browserSync.reload());
});

gulp.task('browserSync', function() {
    browserSync.init({
        files: ['public/**/*.js', 'public/**/*.css', 'public/*.php'],
        server: {
            baseDir: 'public',
            index: "/index.php"
        }
    })
});

gulp.task('default', ['sass', 'fonts', 'js', 'browserSync'], function() {
    gulp.watch(paths.styles.files, ['sass']);
    gulp.watch(paths.javascripts.files, ['js']);
    gulp.watch(paths.html.files, ['html']);
});
