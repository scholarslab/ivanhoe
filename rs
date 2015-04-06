#! /bin/bash

rm screenshot_2015*
rm capybara-*.html
# rspec "$*"
rspec
ls screenshot_2015*.png && open screenshot_2015*.png
