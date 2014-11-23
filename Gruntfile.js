module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON("package.json"),
		// concat: {
		// 	php: {
		// 		files: {
		// 			"functions.php": ["admin/php/**/*.php"]
		// 		}
		// 	}
		// },
		coffee: {
			compileJoined: {
				options: {
					join: true
				},
				files: {
					"admin/js/<%= pkg.permalink %>.js": ["admin/coffeescript/*.coffee"],
					"public/js/<%= pkg.permalink %>.js": ["public/coffeescript/*.coffee"]
				}
	    }
	  },
		sass: {
	    dist: {
	      files: {
					"admin/css/<%= pkg.permalink %>.css": ["admin/scss/main.scss"],
					"public/css/<%= pkg.permalink %>.css": ["public/scss/main.scss"],
	      }
	    }
	  },
    uglify: {
      js: {
				options: {
					banner: "/*! <%= pkg.name %> <%= pkg.version %> compiled on <%= grunt.template.today('yyyy-mm-dd') %> */\n(function($){",
					footer: "})(jQuery);",
					bare: true
				},
				files: {
					"admin/js/<%= pkg.permalink %>.min.js": ["admin/js/<%= pkg.permalink %>.js"],
					"public/js/<%= pkg.permalink %>.min.js": ["public/js/<%= pkg.permalink %>.js"]
				}
      }
    },
		cssmin: {
		  css: {
				options: {
					banner: "/*! <%= pkg.name %> <%= pkg.version %> compiled on <%= grunt.template.today('yyyy-mm-dd') %> */\n",
				},
				files: {
					"admin/css/<%= pkg.permalink %>.min.css": ["admin/css/vendor/*.css", "admin/css/<%= pkg.permalink %>.css"],
					"public/css/<%= pkg.permalink %>.min.css": ["public/css/<%= pkg.permalink %>.css"]
				}
		  }
		},
		watch: {
		  js: {
		    files: ["admin/coffeescript/*.coffee", "public/coffeescript/*.coffee"],
		    tasks: ["coffee", "uglify"]
		  },
		  css: {
		    files: ["admin/scss/*.scss", "public/scss/*.scss", "admin/css/vendor/*.css"],
		    tasks: ["sass", "cssmin"]
		  },
		  // php: {
		  //   files: ["admin/php/**/*.php"],
		  //   tasks: ["concat"]
		  // }
		}
  });

  grunt.loadNpmTasks("grunt-contrib-uglify");
	grunt.loadNpmTasks("grunt-contrib-coffee");
	grunt.loadNpmTasks("grunt-contrib-watch");
	grunt.loadNpmTasks("grunt-contrib-sass");
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-concat');
	
	grunt.registerTask("default", ["coffee", "sass", "uglify"]);

};