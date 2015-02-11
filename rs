#! /bin/bash

rm screenshot_2015*
rm capybara-*.html
rm -rf db-dump-*
rspec $*
ls screenshot_2015*.png && open screenshot_2015*.png
