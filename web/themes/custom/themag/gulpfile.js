const { src, dest, task, series, watch } = require("gulp");

// Import Gulp plugins
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');
const sourcemaps = require('gulp-sourcemaps');
const babel = require('gulp-babel');
const plumber = require('gulp-plumber');
const uglify = require('gulp-uglify');
const run = require('gulp-run');

// Transpile JavaScripts
const javascript = () => {
    return src(['./_src/themag/js/**/*.js', '!./_src/themag/js/**/*.min.js'])
        .pipe(sourcemaps.init())
        .pipe(plumber())
        .pipe(babel({
            presets: [
                ['@babel/env', {
                    modules: false
                }]
            ]
        }))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(dest('./assets/js'));
};

// Compile SCSS
const scss = () => {
    return src('./_src/themag/scss/**/*.scss')
        .pipe(sourcemaps.init())
        .pipe(plumber()) // Add plumber to prevent watch crashes
        .pipe(sass({
            outputStyle: 'expanded',
            includePaths: ['./node_modules']
        }).on('error', sass.logError))
        .pipe(autoprefixer({
            overrideBrowserslist: ['last 2 versions', 'ie 11'],
            cascade: false
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(dest('./assets/css'));
};

// Define tasks
task('scss', scss);
task('javascript', javascript);

// Default task (Watch for changes)
exports.default = () => {
    watch(['./_src/themag/scss/**/*.scss'], scss);
    watch(['./_src/themag/js/**/*.js'], javascript);
};
// const { src, dest, task, series, watch } = require("gulp");
//
// // Import Gulp plugins.
// const sass = require('gulp-sass')(require('sass'));
// const autoprefixer = require('gulp-autoprefixer');
// const sourcemaps = require('gulp-sourcemaps');
// const babel = require('gulp-babel');
// const plumber = require('gulp-plumber');
// const uglify = require('gulp-uglify');
// const run = require('gulp-run');
//
// // Transpile JavaScripts
// const javascript = () => {
//     return src(['./_src/themag/js/**/*.js', '!./_src/themag/js/**/*.min.js']) // Ignore min.js files
//         .pipe(sourcemaps.init())
//         .pipe(plumber())
//         .pipe(babel({
//             presets: [
//                 ['@babel/env', {
//                     modules: false
//                 }]
//             ]
//         }))
//         .pipe(uglify())
//         .pipe(sourcemaps.write('.')) // Inline source maps
//         .pipe(dest('./assets/js'));
// };
//
// // Compile SCSS
// const scss = () => {
//     return src('./_src/themag/scss/**/*.scss')
//         .pipe(sourcemaps.init())
//         .pipe(sass({
//             outputStyle: 'expanded',
//             includePaths: ['./node_modules']
//         }).on('error', sass.logError))
//         .pipe(autoprefixer({
//             overrideBrowserslist: ['last 2 versions', 'ie 11'],
//             cascade: false
//         }))
//         .pipe(sourcemaps.write('.'))
//         .pipe(dest('./assets/css'));
// };
//
// // Define tasks
// task('scss', scss);
// task('javascript', javascript);
//
// // Default task (Watch for changes)
// exports.default = () => {
//     watch(['./_src/themag/scss/**/*.scss'], scss);
//     watch(['./_src/themag/js/**/*.js'], javascript);
// };
