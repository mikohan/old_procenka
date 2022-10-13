#!/bin/bash

shopt -s nullglob
cd $HOME/photoparts/ || exit 1

for file in *.jpg
do
     mv -b -- "$file" "man-${file}"
done

find . -type f -iname "*.jpg" | xargs -l -i composite -dissolve 30 -tile -gravity center wm_angara77.png {} {} ;
