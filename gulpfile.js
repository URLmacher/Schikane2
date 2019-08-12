const { src, dest, series, parallel } = require('gulp');
const cleanCSS = require('gulp-clean-css');
const concat = require('gulp-concat');

function jsmain() {
	return src([
		'assets/js/dashboard.js',
		'assets/js/messages.js',
		'assets/js/friends.js',
		'assets/js/stats.js',
		'assets/js/own-profile.js',
		'assets/js/other-profile.js',
		'assets/js/jquery-3.4.1.js',
		'assets/js/popper.min.js',
		'assets/js/bootstrap.min.js',
	])
		.pipe(concat('main.js'))
		.pipe(dest('assets/dist/js/'));
}

function jsgame() {
	return src([
		'assets/js/dashboard.js',
		'assets/js/messages.js',
		'assets/js/game-info.js',
		'assets/js/game-leave.js',
		'assets/js/game-restart.js',
		'assets/js/friends.js',
		'assets/js/stats.js',
		'assets/js/own-profile.js',
		'assets/js/other-profile.js',
	])
		.pipe(concat('schikane.js'))
		.pipe(dest('assets/dist/js/'));
}

function cssgame() {
	return src(['assets/css/dashboard.css', 'assets/css/game.css', 'assets/css/utility.css'])
		.pipe(concat('schikane.css'))
		.pipe(cleanCSS())
		.pipe(dest('assets/dist/css/'));
}

function cssmain() {
	return src([
		'assets/css/bootstrap.css',
		'assets/css/dashboard.css',
		'assets/css/main.css',
		'assets/css/utility.css',
	])
		.pipe(concat('mainest.css'))
		.pipe(cleanCSS())
		.pipe(dest('assets/dist/css/'));
}

exports.default = series(parallel(jsgame, jsmain, cssgame, cssmain));
