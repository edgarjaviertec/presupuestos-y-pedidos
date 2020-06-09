const gulp = require("gulp");
const del = require('del');
const babel = require("gulp-babel");
const uglify = require('gulp-uglify');
const concat = require('gulp-concat');
const merge = require('merge-stream');
const rename = require("gulp-rename");
const sass = require("gulp-sass");
const imagemin = require('gulp-imagemin');

const configuration = {
	paths: {
		src: {
			fonts: './assets-src/webfonts/*.+(eot|svg|ttf|woff|woff2)',
			img: './assets-src/img/*.+(png|jpg|gif)',
			scss: "./assets-src/scss/**/*.scss",
			js: "./assets-src/js/*.js",

		},
		dist: {
			scss: "./assets/css",
			js: "./assets/js",
			img: "./assets/img",
			fonts: "./assets/webfonts",

		},
	},
	files_to_concatenate: [
		{
			bundle_name: 'customer.vendor.min.js',
			src: [
				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/select2/select2.min.js",
				"./assets-src/js/vendor/cleave/cleave.min.js",
				"./assets-src/js/vendor/cleave/cleave-phone.mx.js",
				"./assets-src/js/vendor/jquery-validation/jquery.validate.min.js",
				"./assets-src/js/vendor/jquery-input-filter/jquery-input-filter.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'customers.vendor.min.js',
			src: [
				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/datatables/jquery.dataTables.min.js",
				"./assets-src/js/vendor/datatables/dataTables.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/dataTables.responsive.min.js",
				"./assets-src/js/vendor/datatables/responsive.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/Spanish.js",
				"./assets-src/js/vendor/sweetalert2/sweetalert2.all.min.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'document.vendor.min.js',
			src: [
				"./assets-src/js/vendor/handlebars/handlebars.min.js",
				"./assets-src/js/vendor/pikaday/custom-pikaday.js",
				"./assets-src/js/vendor/pikaday/spanish.js",
				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/typeahead/typeahead.bundle.min.js",
				"./assets-src/js/vendor/select2/select2.min.js",
				"./assets-src/js/vendor/autosize/autosize.min.js",
				"./assets-src/js/vendor/jquery-validation/jquery.validate.min.js",
				"./assets-src/js/vendor/jquery-input-filter/jquery-input-filter.js",
				"./assets-src/js/vendor/sweetalert2/sweetalert2.all.min.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'empty.vendor.min.js',
			src: [
				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'estimates.vendor.min.js',
			src: [

				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/datatables/jquery.dataTables.min.js",
				"./assets-src/js/vendor/datatables/dataTables.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/dataTables.responsive.min.js",
				"./assets-src/js/vendor/datatables/responsive.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/Spanish.js",
				"./assets-src/js/vendor/sweetalert2/sweetalert2.all.min.js",
				"./assets-src/js/common/super-fresco.js",

			]
		},
		{
			bundle_name: 'login.vendor.min.js',
			src: [
				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/jquery-validation/jquery.validate.min.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'orders.vendor.min.js',
			src: [
				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/datatables/jquery.dataTables.min.js",
				"./assets-src/js/vendor/datatables/dataTables.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/dataTables.responsive.min.js",
				"./assets-src/js/vendor/datatables/responsive.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/Spanish.js",
				"./assets-src/js/vendor/sweetalert2/sweetalert2.all.min.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'product.vendor.min.js',
			src: [
				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/cleave/cleave.min.js",
				"./assets-src/js/vendor/jquery-validation/jquery.validate.min.js",
				"./assets-src/js/vendor/jquery-input-filter/jquery-input-filter.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'products.vendor.min.js',
			src: [

				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/datatables/jquery.dataTables.min.js",
				"./assets-src/js/vendor/datatables/dataTables.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/dataTables.responsive.min.js",
				"./assets-src/js/vendor/datatables/responsive.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/Spanish.js",
				"./assets-src/js/vendor/sweetalert2/sweetalert2.all.min.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'user.vendor.min.js',
			src: [

				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/cleave/cleave.min.js",
				"./assets-src/js/vendor/jquery-validation/jquery.validate.min.js",
				"./assets-src/js/vendor/jquery-input-filter/jquery-input-filter.js",
				"./assets-src/js/common/super-fresco.js",
			]
		},
		{
			bundle_name: 'users.vendor.min.js',
			src: [
				"./assets-src/js/vendor/jquery/jquery.min.js",
				"./assets-src/js/vendor/bootstrap/bootstrap.bundle.min.js",
				"./assets-src/js/vendor/datatables/jquery.dataTables.min.js",
				"./assets-src/js/vendor/datatables/dataTables.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/dataTables.responsive.min.js",
				"./assets-src/js/vendor/datatables/responsive.bootstrap4.min.js",
				"./assets-src/js/vendor/datatables/Spanish.js",
				"./assets-src/js/vendor/sweetalert2/sweetalert2.all.min.js",
				"./assets-src/js/common/super-fresco.js",
			]
		}
	]
};

function clean() {
	return del('assets/', {force: true});
}

function fonts() {
	return gulp
		.src(configuration.paths.src.fonts)
		.pipe(gulp.dest(configuration.paths.dist.fonts))
}

function bundles() {
	return merge(
		configuration.files_to_concatenate.map(function (currentValue, index) {
			return gulp.src(currentValue.src)
				.pipe(concat(currentValue.bundle_name))
				.pipe(uglify())
				.pipe(gulp.dest(configuration.paths.dist.js))
		})
	);
}

function dev_img() {
	return gulp
		.src(configuration.paths.src.img)
		.pipe(gulp.dest(configuration.paths.dist.img))
}

function prod_img() {
	return gulp.src(configuration.paths.src.img)
		.pipe(imagemin())
		.pipe(gulp.dest(configuration.paths.dist.img))
}

function dev_js() {
	return gulp
		.src(configuration.paths.src.js)
		.pipe(
			babel({
				presets: ["@babel/env"],
			})
		)
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(configuration.paths.dist.js))
}

function prod_js() {
	return gulp
		.src(configuration.paths.src.js)
		.pipe(
			babel({
				presets: ["@babel/env"],
			})
		)
		.pipe(uglify())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(configuration.paths.dist.js))
}

function dev_scss() {
	return gulp
		.src(configuration.paths.src.scss)
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest(configuration.paths.dist.scss))
}

function prod_scss() {
	return gulp
		.src(configuration.paths.src.scss)
		.pipe(sass({
			outputStyle: "compressed"
		}).on('error', sass.logError))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(configuration.paths.dist.scss));
}

function watch() {
	gulp.watch(configuration.paths.src.js, dev_js);
	gulp.watch(configuration.paths.src.scss, dev_scss);
	gulp.watch(configuration.paths.src.img, dev_img);
}

exports.bundles = bundles;

exports.prod = gulp.series(clean, fonts, prod_img, prod_scss, prod_js, bundles);
exports.default = gulp.series(fonts, dev_img, dev_scss, dev_js, watch);
