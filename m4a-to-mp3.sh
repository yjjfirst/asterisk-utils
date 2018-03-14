#!/bin/bash

files=$(find . -name "*.m4a")

delimiter='./'
s=$files$delimiter

array=()
while [[ $s ]]; do
    array+=( "${s%%"$delimiter"*}" );
    s=${s#*"$delimiter"};
done

declare -a array

for name in "${array[@]}"
do
    name="${name%"${name##*[![:space:]]}"}"
    ffmpeg -i "$name" -acodec libmp3lame -ab 256k "${name%.*}.mp3"
done       
