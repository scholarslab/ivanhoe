#! /bin/bash

rm screenshot_2014*
rm capybara-*.html
rspec $*
ls screenshot_2014*.png && open screenshot_2014*.png
