#!/bin/bash

files=$(find . -name "*.mp3")

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
    mpg123 -w "${name%.*}.wav" "$name"
    sox "${name%.*}.wav" -t raw -r 8000  -c 1 "${name%.*}.sln"
done       
