/**
 * gruntfile.js
 *
 * 参考サイト：
 * http://d.hatena.ne.jp/koba04/20130203/1359898395
 * http://blog.kjirou.net/p/2605
 * https://github.com/kjirou/hitorion/blob/master/Gruntfile.js
 * http://kojika17.com/2013/03/grunt.js-memo.html
 *
 */

module.exports = function(grunt) {

	'use strict';

	// ------------------------------------------------------------------------------
	// 0. Gruntインストールディレクトリから見たサイトのルート位置
	// ------------------------------------------------------------------------------
		var SITE_ROOT = '../';

	// ------------------------------------------------------------------------------
	// 1. 処理したい JS　FILE を記述
	// ------------------------------------------------------------------------------
		var JS_PATH = SITE_ROOT + 'lib/js/';
		var JS_FILE = [
			//'./*.js',//全部まとめて読み込む場合（順番が制御できないので未使用）
			JS_PATH+'function/meta.js',
			JS_PATH+'vendor/jquery-2.0.3.min.js',
			JS_PATH+'vendor/jquery-easing.js',
			JS_PATH+'vendor/jquery-mousescroll.js',
			JS_PATH+'vendor/jquery.powertip.min.js',

			// rjs
			JS_PATH+'function/getHashData.js',
			JS_PATH+'function/getNowDate.js',
			JS_PATH+'function/setHeightLineGroup.js',

			// Database
			JS_PATH+'function/Database.js',

			// start
			JS_PATH+'function/intro.js',

				// 配列削除
				JS_PATH+'function/deleteArray.js',
				// パラメータ取得
				JS_PATH+'function/getAllUrlParm.js',

				// MainView DownloadBtn
				JS_PATH+'function/setMainViewDownloadBtn.js',

				// MainView RenameBtn
				JS_PATH+'function/setMainViewRenameBtn.js',

				// ImgView DownloadBtn
				JS_PATH+'function/setImgViewDownloadBtn.js',

				// ImgView DownloadBtn
				JS_PATH+'function/setMainViewImgListItem.js',

				// Other
				JS_PATH+'function/function.js',

				// document.radery で全ての関数を実行
				JS_PATH+'function/init.js',

			// end
			JS_PATH+'function/outro.js',

			// addStar
			JS_PATH+'function/addStar.js'

		];

	// ------------------------------------------------------------------------------
	// 2. 処理したい CSS　FILE を記述
	// ------------------------------------------------------------------------------
		var CSS_PATH = SITE_ROOT + 'lib/sass/';


	// ------------------------------------------------------------------------------
	// 3. Grunt 実行処理を記述
	// ------------------------------------------------------------------------------
		grunt.initConfig({

			// ------------------------------------------------------------------------------
			// concat：ファイル結合のみ
			// ------------------------------------------------------------------------------
			concat: {
				my_target: {
					files: {
						// JS_FILEで指定したファイルを結合して「../lib/js/app.js」に出力
						'../lib/js/app.min.js': JS_FILE
					}
				}
			},

			// ------------------------------------------------------------------------------
			// uglify：ファイルを結合し、最小化
			// ------------------------------------------------------------------------------
			uglify: {
				my_target: {
					files: {
						// JS_FILEで指定したファイルを結合＆圧縮して「../lib/js/app.min.js」に出力
						'../lib/js/app.min.js': '../lib/js/app.min.js'
					}
				}
			},

			// ------------------------------------------------------------------------------
			// compass: compassによるSassファイルのコンパイル
			// ------------------------------------------------------------------------------
			compass: {

				// 開発
				dev: {
					options: {
						// 設定ファイル
						config: SITE_ROOT+"lib/config.rb"
					}
				},

				// 本番
				release: {
					options: {
						config: SITE_ROOT+"lib/config_release.rb"
					}
				}

			},


			// ---------------------------------------------------
			// styleguide: KSS スタイルガイドを作成
			// ---------------------------------------------------
			styleguide: {
				kss: {
					options: {
						framework: {
							name: 'kss'
						},
						name: 'Style Guide',
						template: {
							src: 'kss'
						}
					},
					files: {
						'../_CODING_GUIDELINE': CSS_PATH+'style.v01.scss'
					}
				}
			},

			// ------------------------------------------------------------------------------
			// watch：対象ディレクトリを監視してタスクを実行
			// ------------------------------------------------------------------------------
			watch: {
				// JSファイルの監視
				js:{
					files: JS_FILE,
					tasks: ['uglify']
				},
				// Sass(.scss)ファイルの監視
				sass:{
					files: [
						CSS_PATH+'**/*.scss'
					],
					tasks: ['compass:dev']
				},
				// KSS STYLE GUIDE の作成
				kss:{
					files:[
						CSS_PATH+'*.scss',
						CSS_PATH+'*.md'
					],
					tasks:['styleguide']
				}
			}


		});



	// ------------------------------------------------------------------------------
	// 4. 外部ファイルに記載したタスクをロードする
	// ------------------------------------------------------------------------------

		// ----------------------------------------------------------
		//  4-1. ./tasks/のstyleguide.js
		// ----------------------------------------------------------
			grunt.loadTasks('_KSS-STYLE-GUIDE');



	// ------------------------------------------------------------------------------
	// 5. インストールしたモジュールをロードする
	// ------------------------------------------------------------------------------

		// ------------------------------------------------------------
		// 5-1. package.json に記載しているモジュールをまとめてロードする場合
		// ------------------------------------------------------------
			// var pkg = grunt.file.readJSON('package.json');
			// var taskName;
			// for(taskName in pkg.devDependencies) {
			// 	if(taskName.substring(0, 6) == 'grunt-') {
			// 		grunt.loadNpmTasks(taskName);
			// 	}
			// }

		// ------------------------------------------------------------
		// 5-2. 個別に指定してモジュールをロードする場合
		// ------------------------------------------------------------
			//ファイル結合
			grunt.loadNpmTasks('grunt-contrib-concat');
			//ファイル監視
			grunt.loadNpmTasks('grunt-contrib-watch');
			//js最小化
			grunt.loadNpmTasks('grunt-contrib-uglify');
			//Comass
			grunt.loadNpmTasks("grunt-contrib-compass");

	// ------------------------------------------------------------------------------
	// 6. タスクのグールプ化設定:
	//    タスクをグループ化することで、コマンドラインから grunt を実行する際に、
	//    まとめて処理を行わせることができるようになります。
	// ------------------------------------------------------------------------------

		// ------------------------------------------------------------------------------
		// 6.1【開発用】 コマンドラインで grunt を実行時に 最終的に行う処理を記述
		// ------------------------------------------------------------------------------
			grunt.registerTask('default', [
				'watch'  // 監視 Ctrl + C で監視を停止
			]);

		// ------------------------------------------------------------------------------
		// 6.2【本番用(release)】 コマンドラインで grunt r を実行時に 最終的に行う処理を記述
		// ------------------------------------------------------------------------------
			grunt.registerTask('r', [
				'uglify',
				'compass:release'
			]);


};