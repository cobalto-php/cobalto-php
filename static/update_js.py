# coding: utf-8
import os, shutil, sys, datetime
import time
import urllib2

PROJECT_ROOT_PATH = os.path.dirname(os.path.abspath(__file__))

themes_jqueryui = ['blitzer', 'cupertino', 'flick', 'hot-sneaks', 'redmond', 'smoothness', 'ui-lightness', 'humanity']

urls_files_js = [
                    ["http://code.jquery.com/jquery-1.8.3.js", "jquery.js"],
                    ["http://code.jquery.com/ui/1.10.1/jquery-ui.js", "jquery-ui.js"],
                    ["http://cdn.jsdelivr.net/jqplot/1.0.6/jquery.jqplot.js", "jquery.jqplot.js"],
                    ["https://raw.github.com/digitalBush/jquery.maskedinput/1.3.1/dist/jquery.maskedinput.min.js", "jquery.maskedinput.min.js"],
                    ["http://malsup.github.com/jquery.form.js", "jquery.form.js"],
                    ["http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js", "jquery.easing.min.js"],
                    ["http://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.3.1/jquery.cookie.min.js", "jquery.cookie.min.js"]
                ]

url_jqueryui_css = "http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css"

for url_file_js in urls_files_js:
    f = urllib2.urlopen(url_file_js[0])
    data = f.read()
    with open(url_file_js[1], "wb") as code:
        code.write(data)        
        shutil.move(os.path.join(PROJECT_ROOT_PATH, url_file_js[1]), os.path.join(PROJECT_ROOT_PATH+"/_js/", url_file_js[1]))
        print "download complete of "+url_file_js[1]

for theme_jqueryui in themes_jqueryui:    
    f = urllib2.urlopen(url_jqueryui_css.replace("base", theme_jqueryui))
    data = f.read()
    with open(theme_jqueryui+".css", "wb") as code:
        code.write(data)
        shutil.move(os.path.join(PROJECT_ROOT_PATH, theme_jqueryui+".css"), os.path.join(PROJECT_ROOT_PATH+"/_css/"+theme_jqueryui+"/", 'jquery-ui.css'))
        print "download complete of "+theme_jqueryui

print "finish update archives"