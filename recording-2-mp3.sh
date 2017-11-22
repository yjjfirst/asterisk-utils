#!/bin/bash

FILE=$1
PERM=$2

ENCODER=/usr/bin/lame

${ENCODER} -V 8 --silent "${FILE}.wav" "${FILE}.mp3"
if [ -f "${FILE}.mp3" ]; then
    rm -f "${FILE}.wav"
fi

FILENAME=`basename "$FILE"`
DIRNAME=`dirname "$FILE"`
if [ -f "$DIRNAME/permanent/$FILENAME.mp3" ];
then
    mv -f "$FILE.mp3" "$DIRNAME/permanent/$FILENAME.mp3"
fi

