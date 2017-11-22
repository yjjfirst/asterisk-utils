#!/bin/bash

files=$(find . -name "*.mp3")

for name in $files
do
 mpg123 -w ${name%.*}.wav $name
 sox ${name%.*}.wav -t raw -r 8000  -c 1 ${name%.*}.sln
done       
