module.exports = function(grunt){

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		concat:{
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n',
				separator: ';'
			},
			dist: {
				src:[
					'javascript/libs/unmin/all-plugins.min.js',
					/*'javascript/libs/unmin/jquery3-3-1.min.js',
					'javascript/libs/unmin/jquery-migrate-v3-0-1.min.js',
					'javascript/libs/unmin/jquery-easing-1-3.min.js',
					'javascript/libs/unmin/jquery-mobile-1-3-0.min.js',
					'javascript/libs/unmin/jquery-owl-carousel.min.js',
					'javascript/libs/unmin/jquery-camera.min.js',
					'javascript/libs/unmin/jquery-parallax.min.js',
					'javascript/libs/unmin/isotope-3-0-4.min.js',
					'javascript/libs/unmin/fancybox-3-2-11.min.js',
					'javascript/libs/unmin/bootstrap.min.js',
					'javascript/libs/unmin/placeholdem.min.js',
					'javascript/libs/unmin/popper.min.js'*/
				],
				dest: 'javascript/libs/min/vendor.js'
			}
		},
		uglify:{
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
			},
			build: {
				files: [{
		            src: 'javascript/libs/min/vendor.js',
		            dest: 'javascript/libs/min/vendor.js'
		        }, {
		            src: 'javascript/custom.js',
		            dest: 'javascript/libs/min/custom.js'
		        }]
			}
		},
		sass: {
			dist: {
				files: {
					'css/_style_xl.css' : 'scss/style.scss'
				}
			}
		},
		cssmin: {
			options: {
				shorthandCompacting: false,
				roundingPrecision: -1
			},
			target: {
				files: {
					'css/style.css': 'css/_style_xl.css'
				}
			}
		},
		watch: {
			scripts:{
				files: ['javascript/libs/unmin/*.js', 'javascript/custom.js', 'Gruntfile.js'],
				tasks: ['concat', 'uglify'],
	      	}, 
	      	css: {
				files: 'scss/*.scss',
				tasks: ['sass', 'cssmin']
			}
	    }

	});

	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-watch');
	

	// Default task(s).
	grunt.registerTask('default', ['watch']);

};